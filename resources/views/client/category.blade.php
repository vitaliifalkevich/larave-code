@extends('client.layouts.index')
@section('content')<!--header-->
<div class="product">
    <div class="container">
        @include('client.layouts.left-menu')
        <div class="col-md-9 product1">
            <h1 style="text-transform: uppercase;margin-bottom: 30px;margin-left: 40px;">{{$query->all()[0]->categoryName}} </h1>
            <div class=" bottom-product">
                <?php $i = 0 ?>
                @foreach($query as $query_item)
                    <div class="col-md-4 bottom-cd simpleCart_shelfItem">
                        <div class="product-at ">
                            <a href={{url('tovar/'.$query_item->alias)}}><img class="img-responsive"
                                                                              src={{asset("assets/images/".$query_item->categoryAlias."/".$arrImages[$i][0]->image)}} alt="">
                            </a>
                        </div>
                        <p class="tun">{{$query_item->productName}}</p>
                        <a href={{url('tovar/'.$query_item->alias)}} class="item_add"><p class="number item_price">
                                <i> </i>{{$query_item->price}} грн</p></a>
                    </div>
                    <?php $i++ ?>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection