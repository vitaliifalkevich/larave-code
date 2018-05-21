<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\Image;

class SearchController extends Controller
{
    public function results(Request $request, $tovarToFind)
    {
        $categories = Category::select('categoryName', 'categoryAlias')->get();

        $searchResults = Product::where('productName', '=', $tovarToFind)
            ->orWhere('productName', 'like', '%' . $tovarToFind . '%')
            ->orWhere('description', 'like', '%' . $tovarToFind . '%')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select(['products.id', 'products.productName', 'products.price', 'products.alias', 'categories.categoryName', 'categories.categoryAlias'])
            ->get();
        /*ниже идет привязка товара к изображениям для последующего запроса*/
        $arrImages = [];
        foreach ($searchResults as $item) {
            $images = Image::where('product_id', '=', $item->id)->where('index_image_id', '=', '1')->get();
            array_push($arrImages, $images->all());
        }
        /*!привязка товара к изображениям для последующего запроса*/

        return view('client.search')->with(['categories' => $categories, 'searchResults' => $searchResults, 'arrImages' => $arrImages]);

    }
}
