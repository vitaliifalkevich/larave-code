@extends('client.layouts.index')
@section('content')<!--header-->
<div class="product">
    <div class="container">
        @include('client.layouts.left-menu')
        <div class="col-md-9 product1">
            <h1 style="text-transform: uppercase;margin-bottom: 30px;margin-left: 40px;">Результаты поиска</h1>
            <div class=" bottom-product">
                <?php $i = 0 ?>
                @foreach($searchResults as $result)
                    <div class="col-md-4 bottom-cd simpleCart_shelfItem">
                        <div class="product-at ">
                            <a href={{url('tovar/'.$result->alias)}}><img class="img-responsive"
                                                                          src={{asset("assets/images/".$result->categoryAlias."/".$arrImages[$i][0]->image)}} alt="">
                            </a>
                        </div>
                        <p class="tun">{{$result->productName}}</p>
                        <a href={{url('tovar/'.$result->alias)}} class="item_add"><p class="number item_price">
                                <i> </i>{{$result->price}} грн</p></a>
                    </div>
                    <?php $i++ ?>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection