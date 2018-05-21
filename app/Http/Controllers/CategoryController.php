<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Image;
use App\Category;

class CategoryController extends Controller
{
    public function category($category)
    {
        $query = Product::where('categories.categoryAlias', '=', $category)
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select(['products.id', 'products.productName', 'products.price', 'products.alias', 'categories.categoryName', 'categories.categoryAlias'])
            ->get();
        /*ниже идет привязка товара к изображениям для последующего запроса*/
        $arrImages = [];

        $categories = Category::select('categoryName', 'categoryAlias')->get();

        foreach ($query as $item) {
            $images = Image::where('product_id', '=', $item->id)->where('index_image_id', '=', '1')->get();
            array_push($arrImages, $images->all());
        }
        /*!привязка товара к изображениям для последующего запроса*/

        return view('client/category')->with(['query' => $query, 'arrImages' => $arrImages, 'categories' => $categories]);
    }
}
