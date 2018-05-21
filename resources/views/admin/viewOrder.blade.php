@extends('layouts.app')
@section('content')
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <h4 style="float: left;">Подробнее о заказе:</h4>
                <a style="float: right; margin-top: 12px;" href="{{route('administrator')}}">Вернуться на главную</a>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-8 col-md-offset-4">
                        <div class="row">
                            <div class="col-md-5" style="padding-top: 7px;">
                                <label for="orderStatus">Изменить статус заказа:</label></div>
                            <div class="col-md-7">
                                <form action="" name="changeStatus" id="changeStatus" method="post">
                                    <select id="changeSelect" style="width:140px; float: left;"
                                            class="form-control list_category" id="orderStatus"
                                            name="orderStatus">
                                        @foreach($statuses as $status)
                                            <option @if($getOrder->statusName ==$status->statusName) selected
                                                    @endif value="{{$status->id}}">{{$status->statusName}}</option>
                                        @endforeach
                                    </select>
                                    <input id="saveStatus" style="float: right; display: none" class="btn btn-primary"
                                           type="submit"
                                           value="сохранить">
                                    {{csrf_field()}}
                                </form>
                            </div>

                        </div>
                        <p></p>
                    </div>
                </div>
                <table class="list_of_goods table table-striped">
                    <tr>
                        <th colspan="2"><span class="alert alert-{{$getOrder->className}}"
                                              role="alert">{{$getOrder->statusName}}</span> Заказ №
                            0000{{$getOrder->id}} <span
                                    style="float: right">от {{date('d.m.Y', strtotime($getOrder->created_at))}}</span>
                        </th>
                    </tr>
                    <tr>
                        <th>Данные клиента:</th>
                        <td>
                            <table style="width: 100%;">
                                <tr>
                                    <td style="width: 162px;">Имя:</td>
                                    <td>{{$getOrder->firstName}}</td>
                                </tr>
                                <tr>
                                    <td>Фамилия:</td>
                                    <td>{{$getOrder->lastName}}</td>
                                </tr>
                                <tr>
                                    <td>Телефон:</td>
                                    <td>{{$getOrder->phone}}</td>
                                </tr>
                                <tr>
                                    <td>Адрес доставки:</td>
                                    <td>{{$getOrder->adress}}</td>
                                </tr>
                                <tr>
                                    <td>E-mail:</td>
                                    <td>{{$getOrder->email}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Общая информация о заказе:
                        </th>
                        <td>
                            <table>
                                <tr>
                                    <td style="width: 162px;">Способ оплаты:</td>
                                    <td>{{$getOrder->payName}}</td>
                                </tr>
                                <tr>
                                    <td>Способ доставки:</td>
                                    <td>{{$getOrder->deliveryName}}</td>
                                </tr>
                                <tr>
                                    <td>Стоимость с доставкой:</td>
                                    <td>{{$getOrder->price}} грн:</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table class="list_of_goods table table-striped">
                    <tr>
                        <th colspan="5">Состав заказа:</th>
                    </tr>
                    @foreach($getProducts as $product)
                        <tr>
                            <td><img class="img-thumbnail"
                                     src="{{asset('assets/images/'.$product->categoryAlias.'/'.$product->image)}}"
                                     alt=""></td>
                            <td>{{$product->productName}}</td>
                            <td>Цвет: {{$product->color}}</td>
                            <td>Размер: {{$product->size}}</td>
                            <td>Стоимость: {{$product->price}} грн</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection