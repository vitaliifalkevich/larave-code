@extends('client.layouts.index')
@section('content')<!--header-->
<div class="container">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="check">
        @if(isset($productArr))
            <h1>Оформить заказ</h1>
        @else
            @if (isset($status))
                <div class="text-center">
                    <h2>Заказ успешно оформлен</h2>
                    <br/>
                    <h4 style="margin-bottom: 200px;">Наши менеджеры уже работают над ним и вскоре свяжутся с Вами</h4>
                </div>
            @endif

        @endif
        @if(isset($productArr))
            <form method="post">
                <input type="hidden" name="order" value="true">
                <div class="table-responsive">
                    <table class="table">
                        <tr style="border-top: 2px solid #ddd;">
                            <th>Наименование</th>
                            <th>Стоимость</th>
                            <th>Цвет</th>
                            <th>Размер</th>
                        </tr>
                        @foreach($productArr as $i=>$product)
                            <tr>
                                <input type="hidden" name="tovar_id{{$i}}" value="{{$product->id}}">
                                <td>{{$product->productName}}</td>
                                <td>{{$product->price}}</td>
                                @foreach($tovars as $i=>$tovar)
                                    <td>{{$tovar[1]}}</td>
                                    <td>{{$tovar[2]}}</td>
                                    <?php unset($tovars[$i]) ?>
                                    @break
                                @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <th>Доставка</th>
                            @foreach($delivery_way as $delivery_item)
                                <td colspan="3">{{$delivery_item->deliveryName}}</td>
                                <input type="hidden" name="delivery" value="{{$delivery_item->id}}">
                            @endforeach
                        </tr>
                        <tr style="border-bottom: 2px solid #ddd;">
                            <th>Оплата</th>
                            @foreach($pay_way as $pay_item)
                                <td colspan="3">{{$pay_item->payName}}</td>
                                <input type="hidden" name="paying" value="{{$pay_item->id}}">
                            @endforeach
                        </tr>
                    </table>

                </div>
                <div style="margin-top: 30px;margin-bottom: 50px;" class="col-md-6 col-md-offset-3 contact-grid">
                    <h4>Заполните контактную информацию</h4>
                    <input type="text" name="firstName"
                           value="@if(isset($clientData['firstName'])){{$clientData['firstName']}}@endif"
                           placeholder="Ваше имя">
                    <input type="text" name="lastName"
                           value="@if(isset($clientData['lastName'])){{$clientData['lastName']}}@endif"
                           placeholder="Ваша фамилия">
                    <input type="text" name="email"
                           value="@if(isset($clientData['email'])){{$clientData['email']}}@endif"
                           placeholder="email@mail.ru">
                    <input type="text" name="phone"
                           value="@if(isset($clientData['phone'])){{$clientData['phone']}}@endif"
                           placeholder="+38(067)495-34-54">
                    <input type="text" name="adress"
                           value="@if(isset($clientData['adress'])){{$clientData['adress']}}@endif"
                           placeholder="адрес доставки">
                    <div class="send">
                        <input style="width: 105px;" type="submit" value="Отправить">
                    </div>
                </div>
                {{ csrf_field() }}
            </form>
        @endif
    </div>
    <div class="clearfix"></div>
</div>
@endsection