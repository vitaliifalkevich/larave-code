<?php

namespace App\Helpers;

use App\Category;
use App\Image;

class SaveImages
{

    static public function saveImages($current_product_id, $category_id)
    {
        if (!empty($_FILES)) {
            /*Далее следует код, который сохраняет изображения на сервер
                        Изначально делается выборка категории, чтобы создать путь к изображению
                        */
            $categories = Category::where('id', '=', $category_id)->get();
            /*обход коллекции для того, чтобы вывести категорию в виде строки*/
            foreach ($categories as $category) {

            }
            /*Проверка на существование сгенерированной директории. Если директория существует, проверка, условие пропускается
            в противном случае создается новая директория*/
            if (!(file_exists(public_path() . '/assets/images/' . $category->categoryAlias . '/'))) {
                mkdir(public_path() . '/assets/images/' . $category->categoryAlias . '/');
            }
            /*присвоение пути, по которому будут сохранены файлы*/
            $path = public_path() . '/assets/images/' . $category->categoryAlias . '/'; // директория для загрузки
            /*Создание счетчика для дальнейшего выполнения цикла*/
            $counter = count($_FILES['images']['name']);
            /*получаем имя титульной картинки*/
            $nameTitul = $_POST['titul'];
            /*цикл для обхода массива с изображениями*/
            for ($i = 0; $i < $counter; $i++) {
                $isTitul = 0;
                if ($nameTitul == $_FILES['images']['name'][$i]) {
                    $isTitul = 1;
                }
                $ext = explode('.', $_FILES['images']['name'][$i]); // расширение
                $ext = array_pop($ext);
                /*Новое имя файла будет храниться в базе данных*/
                $new_name = time() . $i . '.' . $ext; // новое имя с расширением
                $full_path = $path . $new_name; // полный путь с новым именем и расширением
                move_uploaded_file($_FILES['images']['tmp_name'][$i], $full_path);
                /*$isTitul будет заноситься в базу данных, изначально присваеваем ей 0*/
                $add_image_to_base = new Image();
                $add_image_to_base->fill(
                    array(
                        'image' => $new_name,
                        'product_id' => $current_product_id,
                        'index_image_id' => $isTitul
                    )
                );
                $add_image_to_base->save();
            }
        }
    }
}