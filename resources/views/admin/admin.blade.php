@extends('layouts.app')
@section('content')
    <style>
        .alert-danger ul {
            list-style: none !important;
            padding-left: 20px !important;
        }
    </style>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('layouts.messenges')
                <div class="row">

                    <div class="col-md-6">
                        @include('admin.orders')
                    </div>
                    <div class="col-md-6">
                        <h3 style="float: left;">Список товаров:</h3>
                        <a style="float: right;" class="btn" href={{route('adminAddPage')}}><i class="fas fa-plus"></i>
                            Добавить новый товар</a>
                        <table class="list_of_goods">
                            <?php $i = 0 ?>
                            @foreach($query as $item)
                                @foreach($item->getCategory as $category)
                                @endforeach
                                <tr>
                                    <td>
                                        <img src={{asset(("assets/images/".$category->categoryAlias."/".$arrImages[$i][0]->image))}}>
                                    </td>
                                    <td>
                                        <span><a href={{url('tovar/'.$item->alias)}} target="_blank">{{$item->productName}}</a></span>
                                    </td>
                                    <td><a style="float: right;" href="{{url('administrator/edit/'.$item->alias)}}">Редактировать</a>
                                    </td>
                                    <td style="width: 50px; text-align: right;">
                                        <form action="" method="POST">
                                            <input name="_method" type="hidden" value="DELETE">
                                            <input name="id" type="hidden" value="{{$item->id}}">
                                            <input style="width:100px;margin-left: 20px;" class="btn btn-danger"
                                                   type="submit"
                                                   onclick="return confirm('Вы уверены, что хотите удалить товар?')"
                                                   value="Удалить">
                                            {{csrf_field()}}
                                        </form>
                                    </td>
                                </tr>
                                <?php $i++ ?>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
