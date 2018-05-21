<?php

namespace App\Helpers;

use Validator;
use App\Category;
use App\Size;
use App\Color;
use App\Product;
use App\Image;
use App\OrdersLists;

class ValidationForm
{

    static public function productValidate($typeValidation)
    {
        $request = $_POST;
        $errMessage = [];
        $error = false;
        /*Проверка на уникальность вводимых значений, таких как категория, цвет, размер*/
        $categoryToUniq = Category::select("categoryName")->get();
        $colorToUniq = Color::select("color")->get();
        $sizeToUniq = Size::select("size")->get();
        $productToUniq = Product::select("productName")->get();

        /*Проверяем тип валидации путем передачи строки в параметр метода при вызове метода*/
        if ($typeValidation == "add") {
            foreach ($productToUniq as $item) {
                if ($item->productName == $_POST['productName']) {
                    $error = true;
                    $errMessage[] = "Товар с таким названием существует";
                    break;
                }
            }
        }
        if ($typeValidation == "edit") {
            $originName = $_POST['originName'];
            $productName = $_POST['productName'];
            if ($originName != $productName) {
                foreach ($productToUniq as $item) {
                    if ($item->productName == $_POST['productName']) {
                        $error = true;
                        $errMessage[] = "Название товара уже занято!";
                        break;
                    }
                }
            }
        }
        foreach ($categoryToUniq as $item) {
            if ($item->categoryName == $_POST['add_category']) {
                $error = true;
                $errMessage[] = "Такая категория уже существует";
                break;
            }
        }
        foreach ($colorToUniq as $item) {
            if ($item->color == $_POST['add_colors']) {
                $error = true;
                $errMessage[] = "Такой цвет уже существует";
                break;
            }
        }
        foreach ($sizeToUniq as $item) {
            if ($item->size == $_POST['add_sizes']) {
                $error = true;
                $errMessage[] = "Такой размер уже существует";
                break;
            }
        }
        if (empty($request['productName'])) {
            $error = true;
            $errMessage[] = "Вы не ввели название товара";

        }
        if (empty($request['description'])) {
            $error = true;
            $errMessage[] = "Вы не ввели описание товара";
        }
        if (empty($request['price'])) {
            $error = true;
            $errMessage[] = "Вы не указали стоимость товара";

        }
        if ($typeValidation == "add") {
            if (empty($request['add_category']) && empty($request['category'])) {
                $error = true;
                $errMessage[] = "Выберите категорию или добавьте новую";
            }
        }
        if ($typeValidation == "add") {
            if (empty($_FILES['images'])) {
                $error = true;
                $errMessage[] = "Необходимо выбрать изображения товара";
            }
        }
        if (empty($request['add_colors']) && empty($request['color'])) {
            $error = true;
            $errMessage[] = "Необходимо выбрать цвет или добавить новый";
        }
        if (empty($request['add_sizes']) && empty($request['size'])) {
            $error = true;
            $errMessage[] = "Необходимо выбрать размер или добавить новый";
        }
        return [$error, $errMessage];
    }

    static public function imageValidate($request, $tovarId)
    {
        $error = false;
        $errMessage = [];
        $method = 0;
        /*Метод, который проверяет наличе титулки в удаляемых картинках*/
        function checkTitulImage($request)
        {
            /*Получаем массив id картинок*/
            $imagesToDelete = explode(',', $request['imgToDeleteFromDatabase']);
            $countToDelete = count($imagesToDelete);
            foreach ($imagesToDelete as $imageToDelete) {
                $imageToDelete = Image::where('id', '=', $imageToDelete)->get();
                if ($imageToDelete->all()[0]->index_image_id == "1") {
                    $titul_id = $imageToDelete->all()[0]->id;
                    break;
                } else {
                    $titul_id = 0;
                }
            }
            return [$titul_id, $countToDelete];
        }

        /*Метод, который считает все картинки товара в базе*/
        function checkCountOfProductImages($tovarId)
        {
            $query = Product::where('alias', '=', $tovarId)->get();
            $images = Image::where('product_id', '=', $query->all()[0]->id)->get();
            $countImages = count($images->all());

            return $countImages;

        }

        if (!empty($request['imgToDeleteFromDatabase'])) {//Если картинки удаляются
            /*Вызываем проверку титулки в удаляемых картинках*/
            $checkTitul = checkTitulImage($request);
            if (!empty($_FILES)) {
                if ($checkTitul[0] != 0) {
                   /* echo "Картинки удаляются и добавляются, индекс найден";*/
                    //Здесь можно просто удалить картинки и записать новые
                    return [$error, $errMessage, "indexDeleteAdd"];
                } else {
                   /* echo "Картинки удаляются и добавляются, индекс не найден";*/
                    //картинки добавляются и индекс добавленной картинки должен
                    //сменить индекс существующей картинки
                    return [$error, $errMessage, "noindexDeleteAdd"];
                }
            } else {/*картинки удаляются и не добавляются*/
                if ($checkTitul[0] != 0) {
                    /*echo "Картинки удаляются без добавления, присутствует индекс";*/
                    /*Вызываем метод для сравнения колличества удаляемых изображений и
                    присутствующих в базе данных*/
                    $countOfProductImages = checkCountOfProductImages($tovarId);
                    if ($countOfProductImages > $checkTitul[1]) {//Колличество картинок больше,
                        //чем колличество удаляемых картинок
                       /* echo "Количество удаляемых картинок меньше всех картинок товара";*/
                        return [$error, $errMessage, "indexRemove"];
                    } else if ($countOfProductImages == $checkTitul[1]) {//Колличество картинок равно удаляемым


                        $errMessage[] = "Недопустимо удалять все картинки товара";
                        $error = true;
                        return [$error, $errMessage, $method];
                    }

                } else {
                   /* echo "Картинки удаляются без добавления, без индекса";*/
                    //Здесь можно просто удалить картинки
                    return [$error, $errMessage, "noindexRemove"];
                }
            }
        } else if (!empty($_FILES)) { //Если картинки не удаляются, но добавляются
           /* echo "Картинки не удаляются, но добавляются";*/
            //Здесь нужно сменить индекс существующей картинки в базе на новый
            //для добавленной картинки
            return [$error, $errMessage, "noindexAdd"];
        }

        return [$error, $errMessage, $method];
    }

    static public function deleteProduct($deletedProduct)
    {
        $clientName = false;
        $checkOrdersLists = OrdersLists::where('product_id', '=', $deletedProduct)->exists();
        if ($checkOrdersLists) {
            $clientName = OrdersLists::where('product_id', '=', $deletedProduct)
                ->leftJoin('orders', 'orders_lists.order_id', '=', 'orders.id')
                ->leftJoin('clients', 'orders.client_id', '=', 'clients.id')
                ->select('clients.firstName')->get();
        }
        return [$checkOrdersLists, $clientName];
    }
}