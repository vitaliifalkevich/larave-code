@extends('layouts.app')
@section('content')
    <style>
        .success {
            height: 42px;
            width: 345px;
            background-color: #cde8cd;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
        }

        .error {
            width: 345px;
            background-color: #fbdfe3;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
        }
    </style>
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('layouts.messenges')
                <h4 style="float: left;">Редактировать товар: {{$item->productName}}</h4>
                <a style="float: right; margin-top: 12px;" href="{{route('administrator')}}">Вернуться на главную</a>
                <div class="clearfix"></div>
                <br/>
                <form role="form" id="editTovar" action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group group_for_label">
                        <label>Название продукта
                            <input class="form-control" type="text" name="productName" placeholder="Название продукта"
                                   value="{{$item->productName}}"/></label>
                        <input name="originName" type="hidden" value="{{$item->productName}}">
                        <br/><br/>
                        <label>Описание продукта
                            <textarea style="height:200px;" class="form-control"
                                      name="description">{{$item->description}}</textarea></label>
                        <br/><br/>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Стоимость
                                    <input class="form-control" type="number" name="price" placeholder="Стоимость"
                                           value="{{$item->price}}"/></label>
                                <br/><br/>
                            </div>
                            <div class="col-md-6">
                                <label><i class="fas fa-exclamation-triangle"></i> Категория закреплена!
                                    <select disabled class="form-control list_category" name="category">
                                        @foreach($categories as $category)
                                            <option @if($selectedCategory->categoryName == $category->categoryName) selected
                                                    @endif value="{{$category->id}}">{{$category->categoryName}}</option>
                                        @endforeach
                                    </select>
                                    <div class="row add_category_input">
                                        <div class="col-xs-10">
                                            <input class="form-control" type="text" name="add_category"

                                                   placeholder="Введите новую категорию на латинском">
                                        </div>
                                        <div class="col-xs-1">
                                            <i class="fas fa-times remove-category"
                                               style="font-size: 20px;color: #cc0b0b;margin-top: 8px; cursor: pointer"></i>
                                        </div>
                                    </div>
                                </label>
                                {{--<button type="button" class="add_category"><i class="fas fa-plus"></i> Категория
                                </button>--}}
                                <br/><br/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <span style="font-weight: bold;">Изображения</span>
                                <div>(выберите изображения товара)</div>
                            </div>
                            <div class="col-md-4 img-block">
                                <img width="100" src="{{asset('/assets/images/photography.svg')}}" alt="">
                                <br/>
                                <small>Максимальный размер файла 5 МБ, формат .jpg, .jpeg, .png, .gif
                                    <br/>Указание контактной информации на фото не допускается.
                                </small><!--Тут содержимое подсказки для загрузки изображений. Графическая часть-->
                            </div>
                            <div class="col-md-8">
                                <div id="imagesFromDatabase">
                                    <ul>

                                        @foreach($images as $image)
                                            <li class="imageFromDatabase">
                                                <img width="80"
                                                     src="{{asset('/assets/images/'.$selectedCategory->categoryAlias.'/'.$image->image)}}"
                                                     alt="">
                                                <a data-img="{{$image->id}}" class="deleteImageFromDatabase">Удалить</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <input id="imgToDeleteFromDatabase" name="imgToDeleteFromDatabase" type="hidden"
                                           value="">
                                </div>
                                <input type="hidden" name="imagesValues[]" id="imagesValues">
                                <div class="col-sm-3 col-6 images-col">
                                    <input type="file" id="fileMulti" multiple="">
                                    <label class="label-file label-odd" for="fileMulti">
                                    </label>
                                </div>
                                <div class="col-sm-3 col-6 images-col">
                                    <label class="label-file" for="fileMulti">
                                    </label>
                                </div>
                                <div class="col-sm-3 col-6 images-col">
                                    <label class="label-file label-odd" for="fileMulti">
                                    </label>
                                </div>
                                <div class="col-sm-3 col-6 images-col">
                                    <label class="label-file" for="fileMulti">
                                    </label>
                                </div>
                                <div class="col-sm-3 col-6 images-col">
                                    <label class="label-file label-odd" for="fileMulti">
                                    </label>
                                </div>
                                <div class="col-sm-3 col-6 images-col">
                                    <label class="label-file" for="fileMulti">
                                    </label>
                                </div>
                                <div class="col-sm-3 col-6 images-col">
                                    <label class="label-file label-odd" for="fileMulti">
                                    </label>
                                </div>
                                <div class="col-sm-3 col-6 images-col">
                                    <label class="label-file" for="fileMulti">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <br/><br/>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Цвета
                                    <select class="form-control list_colors" name="color[]" multiple>
                                        @foreach($colors as $color)
                                            <option @for($i=0; $i < count($selectedColors->all()); $i++)
                                                    @if($selectedColors->all()[$i]->color == $color->color) selected
                                                    @endif
                                                    @endfor
                                                    value="{{$color->id}}">{{$color->color}}</option>
                                        @endforeach
                                    </select>
                                    <div class="row add_colors_input" style="margin-top: 5px;">
                                        <div class="col-xs-10">
                                            <input class="form-control" type="text" name="add_colors"
                                                   placeholder="Новый цвет">
                                        </div>
                                        <div class="col-xs-2">
                                            <i class="fas fa-times remove-colors"
                                               style="font-size: 20px;color: #cc0b0b;margin-top: 8px; cursor: pointer"></i>
                                        </div>
                                    </div>
                                </label>
                                <button type="button" class="add_colors"><i class="fas fa-plus"></i> Цвет</button>
                                <br/><br/>
                            </div>
                            <div class="col-md-6">
                                <label>Размер
                                    <select class="form-control list_sizes" name="size[]" multiple>
                                        @foreach($sizes as $size)
                                            <option
                                                    @for($i=0; $i < count($selectedSizes->all()); $i++)
                                                    @if($selectedSizes->all()[$i]->size == $size->size) selected
                                                    @endif
                                                    @endfor
                                                    value="{{$size->id}}">{{$size->size}}</option>
                                        @endforeach
                                    </select>
                                    <div class="row add_sizes_input" style="margin-top: 5px;">
                                        <div class="col-xs-10">
                                            <input class="form-control" type="text" name="add_sizes"
                                                   placeholder="Новый размер">
                                        </div>
                                        <div class="col-xs-2">
                                            <i class="fas fa-times remove-sizes"
                                               style="font-size: 20px;color: #cc0b0b;margin-top: 8px; cursor: pointer"></i>
                                        </div>
                                    </div>
                                </label>
                                <button type="button" class="add_sizes"><i class="fas fa-plus"></i> Размер</button>
                                <br/><br/>
                            </div>
                        </div>
                        <input type="submit" onclick="for (instance in CKEDITOR.instances)
            CKEDITOR.instances[instance].updateElement();" id="submit" class="btn btn-primary" value="ОТПРАВИТЬ"/>
                        <div id="error" style="float: right;font-weight: bold;">
                        </div>
                    </div>
                    {{csrf_field()}}
                </form>
            </div>
        </div>
    </div>
    <script>
        CKEDITOR.replace("description");
    </script>
@endsection
