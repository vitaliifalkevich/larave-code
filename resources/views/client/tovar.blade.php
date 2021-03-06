@extends('client.layouts.index')
@section('content')
    <!--content-->
    <!---->
    <script src={{asset('assets/js/jstarbox.js')}}></script>
    <link rel="stylesheet" href={{asset('assets/css/jstarbox.css')}} type="text/css" media="screen" charset="utf-8"/>
    <!--[if lt IE 8]>
    <link rel="stylesheet" media="screen" href={{asset('assets/css/ie7.css')}}/><![endif]-->
    <script type="text/javascript">

        jQuery(function () {
            jQuery('.starbox').each(function () {
                var starbox = jQuery(this);
                starbox.starbox({
                    ghosting: starbox.hasClass('ghosting'),
                    autoUpdateAverage: starbox.hasClass('autoupdate'),
                    buttons: starbox.hasClass('smooth') ? false : starbox.attr('data-button-count') || 5,
                    stars: starbox.attr('data-star-count') || 5
                }).bind('starbox-value-changed', function (event, value) {
                    $('#mark').attr('value', value * 5);
                })
            });
            $(function () {
                $('.add-re').click(function () {
                    $('#myform').addClass('formActive');
                });
            });
        });
    </script>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="product">
        <div class="container">
            @include('client.layouts.left-menu')
            <div class="col-md-9 product-price1">
                <div class="col-md-5 single-top">
                    <div class="flexslider">
                        <ul class="slides">
                            @foreach($images as $image)
                                <li data-thumb={{asset("assets/images/".$tovarItem->categoryAlias."/".$image->image)}}>
                                    <img src={{asset("assets/images/".$tovarItem->categoryAlias."/".$image->image)}} />
                                </li>
                            @endforeach
                        </ul>

                    </div>
                    <!-- FlexSlider -->
                    <script defer src={{asset("assets/js/jquery.flexslider.js")}}></script>
                    <link rel="stylesheet" href={{asset("assets/css/flexslider.css")}}"" type="text/css"
                          media="screen"/>

                    <script>
                        // Can also be used with $(document).ready()
                        $(window).load(function () {
                            $('.flexslider').flexslider({
                                animation: "slide",
                                controlNav: "thumbnails"
                            });
                        });
                    </script>
                </div>
                <div class="col-md-7 single-top-in simpleCart_shelfItem">
                    <div class="single-para ">
                        <input type="hidden" id="hidden_type" value="{{$tovarItem->id}}">
                        <h4>{{$tovarItem->productName}}</h4>
                        <div class="star-on">
                        <span class="rating_star2">
                            @for($i=0;$i < round($query_review_avg);$i++)
                                <div class="star"></div>
                            @endfor
                        </span>
                            <span class="review">
                            {{count($query_review)}} {{Lang::choice('frontSite.review',count($query_review))}}
                        </span>
                            <div class="clearfix"></div>
                        </div>
                        <h5 class="item_price">{{$tovarItem->price}} грн</h5>
                        {!! $tovarItem->description !!}
                        <div class="available">
                            <ul>
                                <li>Цвет
                                    <select class="color_select">
                                        @foreach($query_colors  as $color)
                                            <option>{{$color->color}}</option>
                                        @endforeach
                                    </select></li>
                                <li class="size-in">Размер
                                    <select class="size_select">
                                        @foreach($query_sizes  as $size)
                                            <option>{{$size->size}}</option>
                                        @endforeach
                                    </select></li>
                                <div class="clearfix"></div>
                            </ul>
                        </div>
                        <ul class="tag-men">
                            <li><span>Категория</span>
                                <span class="women1">: {{$tovarItem->categoryName}}</span></li>
                            <li><span>артикул</span>
                                <span class="women1">: UA00{{$tovarItem->id}}</span></li>
                        </ul>
                        <a href="#" class="add-cart item_add">В КОРЗИНУ</a>
                    </div>
                </div>
                <div class="clearfix"></div>
                <!---->
                <div class="cd-tabs">
                    <nav>
                        <ul class="cd-tabs-navigation">
                            <li><a data-content="fashion" href="#0">Описание </a></li>
                            <li><a data-content="cinema" href="#0">Дополнительно</a></li>
                            <li><a data-content="television" href="#0" class="selected ">Отзывы
                                    ({{count($query_review)}})</a></li>

                        </ul>
                    </nav>
                    <ul class="cd-tabs-content">
                        <li data-content="fashion">
                            <div class="facts">
                                {!! $tovarItem->description !!}
                            </div>

                        </li>
                        <li data-content="cinema">
                            <div class="facts1">
                                <div class="color"><p>Цвет</p>
                                    <span>
                                    @foreach($query_colors  as $color)
                                            {{$color->color}},
                                        @endforeach
                                </span>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="color">
                                    <p>Размер</p>
                                    <span>
                                        @foreach($query_sizes  as $size)
                                            {{$size->size}},
                                        @endforeach
                                </span>
                                    <div class="clearfix"></div>
                                </div>

                            </div>

                        </li>
                        <li data-content="television" class="selected">
                            <div class="comments-top-top">
                                @foreach($query_review as $review)
                                    <div style="margin-top:10px;margin-bottom:10px;">
                                        <div class="top-comment-left">
                                            <img class="img-responsive" src={{asset("assets/images/co.png")}} alt="">
                                        </div>
                                        <div class="top-comment-right">
                                            <h6><a href="#">{{$review->userName}}</a>
                                                - {{substr($review->created_at, 0, 10)}}</h6>
                                            <div class="rating_star">
                                                @for($i=0;$i < $review->mark;$i++)
                                                    <div class="star"></div>
                                                @endfor
                                            </div>

                                            <p>{{$review->review}}</p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                @endforeach
                                <div class="add-re">ДОБАВИТЬ ОТЗЫВ</div>
                                <form action="" name="myform" id="myform" method="post">
                                    <input type="text" name="userName" value="{{ old('userName') }}" placeholder="Имя"/>
                                    <br/>
                                    <textarea name="review">{{ old('review') }}</textarea>
                                    <br/>
                                    <input id="email-form" type="text" value="{{ old('mail') }}" name="mail"
                                           placeholder="E-mail"/>
                                    <div class="block">
                                        <div class="starbox ghosting autoupdate"></div>
                                        <input type="hidden" id="mark" name="mark" value="">
                                    </div>
                                    <br/>
                                    <input id="submit" onclick="sendform();" type="submit" id="submit"
                                           value="ОТПРАВИТЬ"/>
                                    {{csrf_field()}}
                                </form>
                            </div>
                        </li>

                        <div class="clearfix"></div>
                    </ul>
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
    <!--//content-->
@endsection