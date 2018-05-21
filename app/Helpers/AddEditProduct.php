<?php

namespace App\Helpers;

use App\Category;
use App\Image;
use App\Product;
use App\Color;
use App\Size;
use App\ProductColor;
use App\ProductSize;

class AddEditProduct
{
    static public function addOrEdit($typeMethod, $tovarId = 0)
    {
        $arr_colors = [];
        $arr_sizes = [];
        $category_id = 0;

        if ($typeMethod == "add") {
            /*Проверка на факт добавления новой категории*/

            if ($_POST['add_category'] == null) {
                $category_id = $_POST['category'];
            } else {
                $category = $_POST['add_category'];

                /*Тут проходит преобразование названия категории на русском в
                транслитерацию и запись в базу данных*/

                $add_category = new Category();
                $add_category->fill(
                    array(
                        'categoryName' => $category,
                        'categoryAlias' => TranslitName::rus2translit($category)
                    )
                );
                $add_category->save();
                $category_id = $add_category->id;
            }
            $add_product = new Product();
            $add_product->fill(
                array(
                    'productName' => $_POST['productName'],
                    'description' => $_POST['description'],
                    'price' => $_POST['price'],
                    'category_id' => $category_id,
                    /*'index_image_id' => $_POST['titul'],*/
                    'alias' => TranslitName::rus2translit($_POST['productName'])
                )
            );
            $add_product->save();
            /*Возвращаем id вновь добавленного товара для дальнейшей работы*/
            $current_product_id = $add_product->id;
        }
        /*Удаление существующих записей цвета и размера в таблицах
        products_colors, products_sizes
        */
        if ($typeMethod == "edit") {
            $edit_product = Product::where('alias', '=', $tovarId)->get();
            $current_product_id = $edit_product->all()[0]->id;
            $current_colors_delete = ProductColor::where('products_id', '=', $current_product_id)->delete();
            $current_sizes_delete = ProductSize::where('products_id', '=', $current_product_id)->delete();
        }
        /*Проверка на выбор существующего цвета*/
        if (isset($_POST['color'])) {
            $arr_colors = $_POST['color'];
        }
        /*Проверка на выбор существующего размера*/
        if (isset($_POST['size'])) {
            $arr_sizes = $_POST['size'];
        }

        /*Проверка на факт добавления нового цвета*/
        if ($_POST['add_colors'] != "") {
            $add_color_id = $_POST['add_colors'];
            $add_color = new Color();
            $add_color->fill(
                array(
                    'color' => $add_color_id
                )
            );
            $add_color->save();
            $add_color_id = $add_color->id;
            array_push($arr_colors, $add_color_id);
        }
        /*Проверка на факт добавления нового размера*/
        if ($_POST['add_sizes'] != "") {
            $add_size_id = $_POST['add_sizes'];
            $add_size = new Size();
            $add_size->fill(
                array(
                    'size' => $add_size_id
                )
            );
            $add_size->save();
            $add_size_id = $add_size->id;
            array_push($arr_sizes, $add_size_id);
        }
        /*Заполняем таблицу products_colors*/
        foreach ($arr_colors as $color_id) {
            $add_new_color = new ProductColor();
            $add_new_color->fill(
                array(
                    'colors_id' => $color_id,
                    'products_id' => $current_product_id
                )
            );
            $add_new_color->save();
        }
        /*Заполняем таблицу products_sizes*/
        foreach ($arr_sizes as $size_id) {
            $add_new_size = new ProductSize();
            $add_new_size->fill(
                array(
                    'sizes_id' => $size_id,
                    'products_id' => $current_product_id
                )
            );
            $add_new_size->save();
        }
        if ($typeMethod == "edit") {
            $new_alias = TranslitName::rus2translit($_POST['productName']);
            Product::where('alias', '=', $tovarId)->update([
                'productName' => $_POST['productName'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'alias' => $new_alias
            ]);
        }
        if ($typeMethod == "add") {
            return [$current_product_id, $category_id];
        }
        if ($typeMethod == "edit") {
            return $new_alias;
        }
    }
}