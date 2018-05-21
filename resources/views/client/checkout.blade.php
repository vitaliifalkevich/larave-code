@extends('client.layouts.index')
@section('content')<!--header-->
<div class="container">
    <div class="check">
        <h1>@if(isset($cookies['count']))Мои товары ({{$cookies['count']}})
            @else Корзина пустая
            @endif</h1>
        <form action="" name="orderForm" id="orderForm" method="post">
            <input type="hidden" name="checkout" value="true">
            <div class="col-md-9 cart-items">
                <script>
                    $(document).ready(function (c) {
                        function get_cookie(cookie_name) {
                            var results = document.cookie.match('(^|;) ?' + cookie_name + '=([^;]*)(;|$)');

                            if (results)
                                return (unescape(results[2]));
                            else
                                return null;
                        }

                        $('.close1').on('click', function (event) {
                            var tovarToDelete = $(this).parent().children().val();
                            var tovars = JSON.parse(get_cookie('tovars'));
                            tovars.splice(tovarToDelete, 1);
                            $(this).parent().remove();
                            /*удаление элементов из куки*/
                            document.cookie = 'cost=' + (+get_cookie('cost') - (+$(this).parent().find('.delete_tovar_price').val())) + '; path=/; expires=-1';
                            document.cookie = 'count=' + (+get_cookie('count') - 1) + '; path=/;';
                            document.cookie = 'tovars=' + JSON.stringify(tovars) + '; path=/;';
                        });
                    });
                </script>
                @if(isset($productArr))
                    <?php $i = 0 ?>
                    @foreach($productArr as $i=>$product)
                        @foreach($product->getCategory as $category)
                        @endforeach
                        <div class="cart-header">
                            <input class="delete_tovar" type="hidden" value="{{$i}}">
                            <input class="delete_tovar_price" type="hidden" value="{{$product->price}}">
                            <a href="" class="close1"></a>
                            <div class="cart-sec simpleCart_shelfItem">
                                <div class="cart-item cyc">
                                    <img src={{asset("assets/images/".$category->categoryAlias."/".$arrImages[$i][0]->image)}} class="img-responsive"
                                         alt=""/>
                                </div>
                                <div class="cart-item-info">
                                    <h3><a href="/tovar/{{$product->alias}}">{{$product->productName}}</a><span>Артикул: UA00{{$product->id}}</span>
                                    </h3>
                                    <ul class="qty">
                                        @foreach($tovars as $i=>$tovar)
                                            <li class="orderSize"><p>Размер : {{$tovar[2]}} </p></li>
                                            <li class="orderColor"><p>Цвет : {{$tovar[1]}}</p></li>
                                            <?php unset($tovars[$i]) ?>
                                            @break
                                        @endforeach
                                    </ul>
                                    <div class="delivery">
                                        <span>Доставка 2-3 рабочих дня</span>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <?php $i++ ?>
                    @endforeach
                @endif

            </div>
            <div class="col-md-3 cart-total">
                {{--<a class="continue" href="#">Продолжить покупки</a>--}}
                @if(isset($cookies['cost'])&&isset($cookies['count']))
                    <div class="price-details">
                        <h3>Детали стоимости</h3>
                        <span>Всего</span>
                        <span class="total1">₴{{$cookies['cost']}}</span>
                        <span>Скидка</span>
                        <span class="total1">---</span>
                        <span>Стоимость доставки</span>
                        <span class="total1">₴40</span>
                        <div class="clearfix"></div>
                    </div>
                    <div class="available2">
                        <ul>
                            <li>Способ оплаты: <br/>
                                <select name="paying" class="pay_select">
                                    @foreach($ways_pay as $way_pay)
                                        <option value="{{$way_pay->id}}">{{$way_pay->payName}}</option>
                                    @endforeach
                                </select>
                            </li>
                            <li>Способ доставки: <br/>
                                <select name="delivery" class="delivery_select">
                                    @foreach($ways_delivery as $way_delivery)
                                        <option value="{{$way_delivery->id}}">{{$way_delivery->deliveryName}}</option>
                                    @endforeach
                                </select>
                            </li>
                        </ul>
                    </div>

                    <ul class="total_price">
                        <li class="last_price"><h4>ИТОГО</h4></li>
                        <li class="last_price"><span>₴{{$cookies['cost']+40}}</span></li>
                        <div class="clearfix"></div>
                    </ul>
                    <div class="clearfix"></div>
                    <button class="order" type="submit">Оформить заказ</button>
                @endif
            </div>
            {{ csrf_field() }}
        </form>

        <div class="clearfix"></div>
    </div>
</div>
@endsection