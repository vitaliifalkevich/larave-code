<?php

namespace App\Helpers;

use App\Category;
use App\Image;

class DeleteImages
{
    static public function deleteChoosedImages($category_id)
    {
        /*Удаление картинок*/
        if (!empty($_POST['imgToDeleteFromDatabase'])) {
            $current_category = Category::where('id', '=', $category_id)->get();
            $current_category = $current_category->all()[0]->categoryAlias;
            $imagesToDelete = explode(',', $_POST['imgToDeleteFromDatabase']);
            foreach ($imagesToDelete as $imgToDelete) {
                $getImageToDelete = Image::where('id', '=', $imgToDelete)->get();
                $imageToDelete = Image::where('id', '=', $imgToDelete)->delete();
                $pathToDeleteImage = public_path() . '\assets\images\\' . $current_category . '\\' . $getImageToDelete->all()[0]->image;
                unlink($pathToDeleteImage);

            }
        }

    }

    static public function removeDeletedImagesTovar($category_id, $tovar_id)
    {
        $current_category = Category::where('id', '=', $category_id)->get();
        $current_category = $current_category->all()[0]->categoryAlias;

        $images = Image::where('product_id', '=', $tovar_id)->get();

        foreach ($images as $image) {
            Image::where('id', '=', $image->id)->delete();
            $pathToDeleteImage = public_path() . '\assets\images\\' . $current_category . '\\' . $image->image;
            unlink($pathToDeleteImage);

        }
    }

    static public function removeUnUsedDirectory($categoryAlias)
    {
        rmdir(public_path() . '\assets\images\\' . $categoryAlias);


    }

}