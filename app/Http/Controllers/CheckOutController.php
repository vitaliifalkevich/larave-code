<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Image;
use App\Client;
use App\WayPay;
use App\WayDelivery;
use App\OrdersLists;
use App\Order;
use App\Color;
use App\Size;
use App\Category;
use DB;
use Validator;

class CheckOutController extends Controller
{
    public function checkout(Request $request)
    {
        $categories = Category::select('categoryName', 'categoryAlias')->get();
        if (isset($_COOKIE['tovars'])) {
            $cookies_tovar_cod = iconv(mb_detect_encoding($_COOKIE['tovars'], mb_detect_order(), true), "UTF-8", $_COOKIE['tovars']);
        }
        if (isset($cookies_tovar_cod) && ($cookies_tovar_cod) != '[]' && ($cookies_tovar_cod) != '""') {
            $cookies = $_COOKIE;
            $tovarsFromCookie = json_decode($cookies_tovar_cod);

            $arrImages = [];
            foreach ($tovarsFromCookie as $tovarFromCookie) {
                $products = Product::where('id', '=', $tovarFromCookie[0])->get();

                foreach ($products as $product) {
                    $productArr[] = $product;
                    $images = Image::where('product_id', '=', $product->id)->where('index_image_id', '=', '1')->get();
                    $arrImages[] = $images->all();
                }
            }

            $ways_pay = WayPay::select('id', 'payName')->get();
            $ways_delivery = WayDelivery::select('id', 'deliveryName')->get();
            if ($request->isMethod('post')) {
                /*обрабатываем запрос post*/
                $input = $request->except('_token');
                $pay_way = WayPay::where('id', '=', $input['paying'])->select('id', 'payName')->get();
                $delivery_way = WayDelivery::where('id', '=', $input['delivery'])->select('id', 'deliveryName')->get();
                if ($request['order']) {
                    $clientData = $request->only('firstName', 'lastName', 'phone', 'adress', 'email');
                    /*
                   * Валидация данных
                   * */
                    $messages = [
                        'required' => 'Поле :attribute обязательно к заполнению',
                        'unique' => 'Пользователь с такой почтой уже зарегистрирован',
                        'integer' => 'Телефон введен некорректно',
                        'email' => 'Введен некорректный e-mail'
                    ];
                    $validator = Validator::make($clientData, [
                        'firstName' => 'required|max:100',
                        'lastName' => 'required',
                        'phone' => 'required',
                        'adress' => 'required',
                        'email' => 'required|email'
                    ], $messages);


                    /*
                     * Проверка на прохождение валидации
                     * */
                    if ($validator->fails()) {
                        return view('client/order')->with(['tovars' => $tovarsFromCookie, 'cookies' => $cookies, 'productArr' => $productArr, 'arrImages' => $arrImages, 'pay_way' => $pay_way, 'delivery_way' => $delivery_way, 'clientData' => $clientData, 'categories' => $categories])->withErrors($validator);
                    } else {
                        /*
                         * Начинаем транзакцию
                         * */
                        DB::beginTransaction();
                        try {
                            /*создаем нового клиента*/
                            $client = new Client();
                            $client->fill($clientData);
                            $client->save();

                            /*создаем новый заказ*/
                            $order = new Order();
                            /*создадим пустой массив для данных о заказе*/
                            $orderData = [];
                            /*создаем пустой массив для хранения информации о стоимости заказа*/
                            $orderData['price'] = [];
                            foreach ($pay_way as $pay_id) {
                                $orderData['pay_id'] = $pay_id->id;
                            }
                            foreach ($delivery_way as $delivery_id) {
                                $orderData['delivery_id'] = $delivery_id->id;
                            }
                            $orderData['client_id'] = (int)$client->id;

                            $orderData['price'] = $cookies['cost'] + 40;
                            $order->fill($orderData);
                            $order->save();

                            $i = 0;
                            /*заполняем таблицу orders_lists*/
                            foreach ($tovarsFromCookie as $item) {
                                $orderData = [];
                                $orders_lists = new OrdersLists();
                                $orderData['order_id'] = (int)$order->id;
                                $orderData['product_id'] = $tovarsFromCookie[$i][0];
                                $sizeCollection = Size::where('size', $tovarsFromCookie[$i][2])->select('id')->get();
                                foreach ($sizeCollection as $size_id) {
                                    $orderData['size_id'] = (int)$size_id->id;
                                    break;
                                }
                                $colorCollection = Color::where('color', $tovarsFromCookie[$i][1])->select('id')->get();
                                foreach ($colorCollection as $color_id) {
                                    $orderData['color_id'] = (int)$color_id->id;
                                    break;
                                }
                                $orders_lists->fill($orderData);
                                $orders_lists->save();
                                $i++;
                            }

                            setcookie('tovars', '', time() - 30);
                            setcookie('cost', '', time() - 30);
                            setcookie('count', '', time() - 30);
                        } catch (QueryException $e) {
                            DB::rollBack();
                        }
                        DB::commit();
                        return view('client/order')->with(['status' => 'true', 'categories' => $categories]);
                    }
                }
                if ($request['checkout']) {
                    return view('client/order')->with(['tovars' => $tovarsFromCookie, 'cookies' => $cookies, 'productArr' => $productArr, 'arrImages' => $arrImages, 'pay_way' => $pay_way, 'delivery_way' => $delivery_way, 'categories' => $categories]);
                }
            }
            return view('client/checkout')->with(['tovars' => $tovarsFromCookie, 'cookies' => $cookies, 'productArr' => $productArr, 'arrImages' => $arrImages, 'ways_pay' => $ways_pay, 'ways_delivery' => $ways_delivery, 'categories' => $categories]);
        } else {
            return view('client/checkout')->with(['categories' => $categories]);
        }
    }
}
