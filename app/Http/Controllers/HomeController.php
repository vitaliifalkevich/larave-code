<?php

namespace App\Http\Controllers;

use App\Helpers\DeleteImages;
use Illuminate\Http\Request;
use App\Product;
use App\Image;
use App\Client;
use App\Order;
use App\OrdersLists;
use DB;
use App\Category;
use ValidationForm;
use App\ProductColor;
use App\ProductSize;
use App\Color;
use App\Size;
use App\Review;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product, Request $request)
    {
        $query = Product::select('productName', 'alias', 'id', 'category_id')->get();

        /*ниже идет привязка товара к изображениям для последующего запроса*/
        $arrImages = [];
        foreach ($query as $item) {
            $images = Image::where('product_id', '=', $item->id)->where('index_image_id', '=', '1')->get();
            array_push($arrImages, $images->all());
        }
        /*!привязка товара к изображениям для последующего запроса*/

        /*Вывод списка заказов*/
        $orders = DB::table('orders')
            ->leftJoin('clients', 'orders.client_id', '=', 'clients.id')
            ->leftJoin('status', 'orders.status_id', '=', 'status.id')
            ->select(['orders.id', 'clients.firstName', 'status.statusName', 'status.className', 'orders.price', 'orders.created_at'])->get();

        if ($request->isMethod('delete')) {
            $input = $request->except('_token');
            /*проверка на удаление товара, а не заказа*/
            if (!empty($input['id'])) {
                /*Валидация введенных значений*/
                $validate = ValidationForm::deleteProduct($input['id']);
                if ($validate[0]) {
                    $errorMsg[0] = "Товар не может быть удален, поскольку его ожидают следующие клиенты: ";
                    $count = 1;
                    foreach ($validate[1] as $item) {
                        $errorMsg[] = "$count " . $item->firstName;
                        $count++;

                    }
                    return view('admin.admin')->with(['query' => $query, 'arrImages' => $arrImages, 'orders' => $orders])->withErrors([$errorMsg]);
                    /*Здесь код не может быть выполнен*/
                } else {
                    /*Начинаем транзакцию*/
                    DB::beginTransaction();
                    try {
                        /*попытка удалить товар*/

                        /*вспомогательные переменные*/
                        $sizeExist = true;

                        /*Получаем записи о цветах и товарах соответственно*/
                        $colors = ProductColor::where('products_id', '=', $input['id'])->get();
                        /*Удаляем записи о товарах и цветах*/
                        ProductColor::where('products_id', '=', $input['id'])->delete();
                        /*обход коллекции и проверка, что цвет используется в товарах*/
                        foreach ($colors as $color) {
                            $isColorExists = ProductColor::where('colors_id', '=', $color->colors_id)->exists();
                            if (!$isColorExists) {
                                /*Если цвет нигде не используется, он удаляется*/
                                Color::where('id', '=', $color->colors_id)->delete();
                            }
                        }
                        /*Получаем записи о размерах и товарах соответственно*/
                        $sizes = ProductSize::where('products_id', '=', $input['id'])->get();
                        /*Удаляем записи о товарах и размерах*/
                        ProductSize::where('products_id', '=', $input['id'])->delete();
                        /*обход коллекции и проверка, что размер используется в товарах*/
                        foreach ($sizes as $size) {
                            $isSizeExists = ProductSize::where('sizes_id', '=', $size->sizes_id)->exists();
                            if (!$isSizeExists) {
                                /*Если размер нигде не используется, он удаляется*/
                                Size::where('id', '=', $size->sizes_id)->delete();
                            }
                        }
                        /*делаем проверку на наличие отзывов, если есть, то удаляем отзывы о товаре*/
                        $checkReview = Review::where('tovar_id', '=', $input['id'])->exists();
                        if ($checkReview) {
                            $reviews = Review::where('tovar_id', '=', $input['id'])->delete();
                        }


                        /*получаем удаляемый товар для дальнейших действий*/
                        $productToDelete = Product::where('id', '=', $input['id'])->get();
                        /*Получаем id_категории товара через получение товара*/
                        $category_id = $productToDelete->all()[0]->category_id;
                        /*Выполняем удаление картинок, передаем параметр id категории и товара*/
                        DeleteImages::removeDeletedImagesTovar($category_id, $input['id']);
                        /*удаляем товар*/
                        Product::where('id', '=', $input['id'])->delete();
                        /*Проверяем на сущестоввание товаров для  категории удаляемого товара*/
                        $checkProductsInCategories = Product::where('category_id', '=', $category_id)->exists();
                        if (!$checkProductsInCategories) {
                            $categoryAlias = Category::where('id', '=', $category_id)->select('categoryAlias')->get();
                            $categoryAlias = $categoryAlias->all()[0]->categoryAlias;
                            Category::where('id', '=', $category_id)->delete();
                            DeleteImages::removeUnUsedDirectory($categoryAlias);
                        }
                    } catch (QueryException $e) {
                        DB::rollBack();
                    }
                    DB::commit();
                    return redirect()->route('administrator')->with('status', 'Товар успешно удален');
                }
            }

            /*Удаление заказа из базы:*/
            if (!empty($input['order_id'])) {
                /*Начинаем транзакцию*/
                DB::beginTransaction();
                try {
                    /*попытка удалить Заказ*/
                    /*Удаляем записи о заказе и его состовляющих товарах*/
                    OrdersLists::where('order_id', '=', $input['order_id'])->delete();

                    /*Получаем информацию о заказе*/
                    $recordsInOrders = Order::where('id', '=', $input['order_id'])->get();
                    /*Находим id клиента в заказе*/
                    $client_id = $recordsInOrders->all()[0]->client_id;
                    /*Удаляем заказ из таблицы Orders*/
                    Order::where('id', '=', $input['order_id'])->delete();
                    /*Проверка на использование клиента еще где-нибудь*/
                    $checkClientIsUsed = Order::where('client_id', '=', $client_id)->exists();

                    if (!$checkClientIsUsed) {
                        Client::where('id', '=', $client_id)->delete();
                    }
                } catch (QueryException $e) {
                    DB::rollBack();
                }
                DB::commit();
                return redirect()->route('administrator')->with('status', 'Заказ успешно удален');
            }

        }
        return view('admin.admin')->with(['query' => $query, 'arrImages' => $arrImages, 'orders' => $orders]);
    }
}
