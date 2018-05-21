<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Image;
use App\Category;

class IndexController extends Controller
{
    public function showIndex()
    {
        $query = Product::orderBy('created_at', 'desc')->get()->take(12);
        $categories = Category::select('categoryName', 'categoryAlias')->get();
        $arrImages = [];
        foreach ($query as $item) {
            $images = Image::where('product_id', '=', $item->id)->where('index_image_id', '=', '1')->get();
            array_push($arrImages, $images->all());
        }
        return view('client/welcome')->with(['query' => $query, 'arrImages' => $arrImages, 'categories' => $categories]);
    }
}
