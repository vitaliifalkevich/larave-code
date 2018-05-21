<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Review;
use App\Image;
use App\Product;
use App\Category;
use Validator;

class TovarController extends Controller
{
    public function tovar($tovarLink, Request $request, Review $review)
    {
        $categories = Category::select('categoryName', 'categoryAlias')->get();
        /**
         *
         * Запрос на получение товара, категории.
         * */
        $query = Product::where('alias', '=', $tovarLink)
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select(['products.id', 'products.productName', 'products.description', 'products.price', 'products.alias', 'categories.categoryName', 'categoryAlias'])
            ->get();
        /*
         * Запрос на получение размеров товара.
         * */
        $query_sizes = Product::where('alias', '=', $tovarLink)
            ->leftJoin('products_sizes', 'products.id', '=', 'products_sizes.products_id')
            ->leftJoin('sizes', 'sizes.id', '=', 'products_sizes.sizes_id')
            ->select(['products.id', 'sizes.size'])
            ->get();
        /*
         * Запрос на получение цветов товара.
         * */
        $query_colors = Product::where('alias', '=', $tovarLink)
            ->leftJoin('products_colors', 'products.id', '=', 'products_colors.products_id')
            ->leftJoin('colors', 'colors.id', '=', 'products_colors.colors_id')
            ->select(['products.id', 'colors.color'])
            ->get();
        /*
         * Перебор коллекции и получение массива.
         * */
        foreach ($query as $tovarItem)
            /*
        * Запрос на получение картинок товара.
        * */
            $images = Image::where('product_id', '=', $tovarItem->id)->orderBy('index_image_id', 'desc')
                ->get();

        /*
       * Запрос на получение отзывов о товаре.
       * */
        $query_review = Review::where('tovar_id', '=', $tovarItem->id)
            ->orderBy('created_at', 'desc')->get();
        /*
       * Запрос на получение средней оценки товара.
       * */
        $query_review_avg = Review::where('tovar_id', '=', $tovarItem->id)->avg('mark');
        if ($request->isMethod('post')) {
            $input = $request->except('_token');
            /**
             * Валидация данных
             *
             */
            $messages = [
                'required' => 'Поле :attribute обязательно к заполнению',
                'unique' => 'Поле :attribute должно быть уникальным',
                'email' => 'Введен некорректны email адрес'
            ];
            $validator = Validator::make($input, [
                'userName' => 'required|max:100',
                'review' => 'required',
                'mark' => 'required',
                'mail' => 'required|email'
            ], $messages);

            $review = new Review();
            $review->tovar_id = $tovarItem->id;
            $review->fill($input);
            if ($validator->fails()) {
                return redirect()->route('tovar', ['tovar' => $tovarItem->alias, 'categories' => $categories])->withErrors($validator)->withInput();
            }
            if ($review->save()) {
                return redirect()->route('tovar', ['tovar' => $tovarItem->alias, 'categories' => $categories])->with('status', 'Отзыв успешно добавлен');
            }
        }
        return view('client/tovar')->with(['tovarItem' => $tovarItem, 'query_review' => $query_review, 'query_review_avg' => $query_review_avg, 'images' => $images, 'query_sizes' => $query_sizes, 'query_colors' => $query_colors, 'categories' => $categories]);
//
    }
}
