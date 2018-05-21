<?php

namespace App\Http\Controllers;

use App\Helpers\DeleteImages;
use App\ProductColor;
use App\ProductSize;
use App\Size;
use App\Image;
use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Color;
use Validator;
use TranslitName;
use ValidationForm;
use DB;
use SaveImages;
use DeleteImage;
use AddEditProduct;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $categories = Category::select('id', 'categoryName', 'categoryAlias')->get();
        $sizes = Size::select('id', 'size')->get();
        $colors = Color::select('id', 'color')->get();
        /* dump($categories);*/
        return view('admin.add')->with(['categories' => $categories, 'sizes' => $sizes, 'colors' => $colors]);
    }

    /*
     * Метод обработки роута добавления новых товаров
     */
    public function add(Request $request)
    {
        if ($request->ajax()) {

            /*Валидация введенных значений*/
            $validate = ValidationForm::productValidate("add");
            /*Проверка валидации*/
            if ($validate[0] == false) {//Если ошибка равно false, тогда валидация прошла успешно
                /*
                * Далее идет код, отвечающий за
                * обработку информации из формы
                * и сохранение в базу данных
                * */
                /*
                * Начинаем транзакцию
                * */
                DB::beginTransaction();
                try {
                    /*Обращаемся к статическому методу, который добавит
                    в базу данных новый товар*/
                    $add_product = AddEditProduct::addOrEdit('add');

                    /*добавление картинок*/
                    $saveImage = SaveImages::saveImages($add_product[0], $add_product[1]);

                    echo "<span style='color:green'>Товар успешно добавлен</span><br/>";
                    echo "<script>$('#error').addClass('success').removeClass('error');setTimeout(function(){
    window.location.href='/administrator/add/'
},1000);</script>";

                } catch (QueryException $e) {
                    DB::rollBack();
                }
                DB::commit();
            } else {
                echo "<script>$('#error').addClass('error').removeClass('success')</script>";
                foreach ($validate[1] as $item) {
                    echo "<span style='color:red'>" . $item . "</span><br/>";
                }
            }
        }
    }
    /*
     * Методы обработки роута редактирования товаров
     */
    /*GET*/
    public function indexEdit($tovarId)
    {
        $categories = Category::select('id', 'categoryName', 'categoryAlias')->get();
        $sizes = Size::select('id', 'size')->get();
        $colors = Color::select('id', 'color')->get();
        $query = Product::where('alias', '=', $tovarId)->get();

        /*Отображение выбранной категории по умолчанию*/
        $selectedCategory = $query->all()[0]->getCategory->all()[0];

        /*Отображение выбранных цветов по умолчанию*/
        $selectedColors = $query->all()[0]->getColorsForProduct;
        /*Отображение выбранных размеров по умолчанию*/
        $selectedSizes = $query->all()[0]->getSizesForProduct;

        $images = Image::where('product_id', '=', $query->all()[0]->id)->get();


        foreach ($query as $item)
            return view('admin.edit')->with(['categories' => $categories, 'sizes' => $sizes, 'colors' => $colors, 'item' => $item, 'selectedCategory' => $selectedCategory, 'selectedColors' => $selectedColors, 'selectedSizes' => $selectedSizes, 'images' => $images]);
    }

    /*POST*/
    public function edit(Request $request, $tovarId)
    {
        if ($request->ajax()) {

            /*Валидация введенных значений*/
            $validate = ValidationForm::productValidate("edit");
            $imageValidate = ValidationForm::imageValidate($request, $tovarId);
            $query = Product::where('alias', '=', $tovarId)->get();
            $current_product_id = $query->all()[0]->id;
            $category_id = $query->all()[0]->category_id;
            /*dump($query->all()[0]->id);*/

            /*Проверка валидации*/
            if ($validate[0] == false && $imageValidate[0] == false) {//Если ошибка равно false, тогда валидация прошла успешно
                DB::beginTransaction();
                try {
                    /*Методы, которые реализуют логику уделения/добавления картинок
                    и смены титульной картинки*/
                    function indexDeleteAdd($current_product_id, $category_id)
                    {
                        /*Удаление выбранных картинок*/
                        $deleteImage = DeleteImages::deleteChoosedImages($category_id);

                        /*добавление картинок*/
                        $saveImage = SaveImages::saveImages($current_product_id, $category_id);
                    }

                    function noindexDeleteAdd($current_product_id, $category_id)
                    {
                        /*Ниже расположенная конструкция обнуляет титульную картинку*/
                        $images = Image::where('product_id', '=', $current_product_id)->update([
                            'index_image_id' => 0
                        ]);
                        /*Удаление выбранных картинок*/
                        $deleteImage = DeleteImages::deleteChoosedImages($category_id);
                        /*добавление картинок*/
                        $saveImage = SaveImages::saveImages($current_product_id, $category_id);
                    }

                    function noindexAdd($current_product_id, $category_id)
                    {
                        /*Ниже расположенная конструкция обнуляет титульную картинку*/
                        $images = Image::where('product_id', '=', $current_product_id)->update([
                            'index_image_id' => 0
                        ]);
                        /*добавление картинок*/
                        $saveImage = SaveImages::saveImages($current_product_id, $category_id);
                    }

                    function indexRemove($current_product_id, $category_id)
                    {
                        /*Удаление выбранных картинок*/
                        $deleteImage = DeleteImages::deleteChoosedImages($category_id);
                        /*Ниже расположенная конструкция добавляет титульную картинку первому элементу*/
                        $images = Image::where('product_id', '=', $current_product_id)->first()->update([
                            'index_image_id' => 1
                        ]);
                    }

                    function noindexRemove($category_id)
                    {
                        /*Удаление выбранных картинок*/
                        $deleteImage = DeleteImages::deleteChoosedImages($category_id);
                    }

                    /*Выбор метода - реализатора логики добавления/удаления картинки
                    и смены титульной картинки*/
                    switch ($imageValidate[2]) {
                        case "indexDeleteAdd":
                            indexDeleteAdd($current_product_id, $category_id);
                            break;
                        case "noindexDeleteAdd":
                            noindexDeleteAdd($current_product_id, $category_id);
                            break;
                        case "noindexAdd":
                            noindexAdd($current_product_id, $category_id);
                            break;
                        case "indexRemove":
                            indexRemove($current_product_id, $category_id);
                            break;
                        case "noindexRemove":
                            noindexRemove($category_id);
                            break;
                    }
                    //Обновление информации о товаре
                    $edit_product = AddEditProduct::addOrEdit('edit', $tovarId);
                    echo "<span style='color:green'>Товар успешно обновлен.</span><br/>";
                    echo "<script>$('#error').addClass('success').removeClass('error');setTimeout(function(){
    window.location.href='/administrator/edit/" . $edit_product . "'
},2000);</script>";
                } catch (QueryException $e) {
                    DB::rollBack();
                }
                DB::commit();
            } else {
                echo "<script>$('#error').addClass('error').removeClass('success')</script>";
                if (isset($validate[1])) {
                    foreach ($validate[1] as $item) {
                        echo "<span style='color:red'>" . $item . "</span><br/>";
                    }
                }
                if (isset($imageValidate[1])) {
                    foreach ($imageValidate[1] as $item) {
                        echo "<span style='color:red'>" . $item . "</span><br/>";
                    }
                }
            }
        }

    }

}
