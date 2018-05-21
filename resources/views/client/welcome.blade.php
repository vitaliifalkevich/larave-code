<!--header-->
@extends('client.layouts.index')
@section('content')
    <div class="banner">
        <div class="container">
            <script src={{asset("assets/js/responsiveslides.min.js")}}></script>
            <script>
                $(function () {
                    $("#slider").responsiveSlides({
                        auto: true,
                        nav: true,
                        speed: 500,
                        namespace: "callbacks",
                        pager: true,
                    });
                });
            </script>
            <div id="top" class="callbacks_container">
                <div class="banner-text">
                    <h3>Подари добро! <br>Вырученные средства идут на помощь детям</h3>
                    <p></p>
                </div>
            </div>

        </div>
    </div>
    <!--content-->
    <div class="content">
        <div class="container">
            <div class="content-top">
                <h1>НОВЫЕ ТОВАРЫ</h1>
                <?php $i = 0 ?>
                @foreach($query as $last_item)
                    @foreach($last_item->getCategory as $category)
                    @endforeach
                    <div class="col-md-4 grid-top main_photo">
                        <a href="tovar/{{$last_item->alias}}" class="b-link-stripe b-animate-go  thickbox"><img
                                    class="img-responsive"


                                    src={{asset("assets/images/".$category->categoryAlias."/".$arrImages[$i][0]->image)}} alt="">

                            <div class="b-wrapper">
                                <h3 class="b-animate b-from-left    b-delay03 ">
                                    <span>{{$last_item->productName}}</span>
                                </h3>
                            </div>
                        </a>
                    </div>
                    <?php $i++ ?>
                @endforeach
            </div>
            <!----->
        </div>
        <!---->
        {{--<div class="content-bottom">
            <ul>
                <li><a href="#"><img class="img-responsive" src={{asset("assets/images/lo.png")}} alt=""></a></li>
                <li><a href="#"><img class="img-responsive" src={{asset("assets/images/lo1.png")}} alt=""></a></li>
                <li><a href="#"><img class="img-responsive" src={{asset("assets/images/lo2.png")}} alt=""></a></li>
                <li><a href="#"><img class="img-responsive" src={{asset("assets/images/lo3.png")}} alt=""></a></li>
                <li><a href="#"><img class="img-responsive" src={{asset("assets/images/lo4.png")}} alt=""></a></li>
                <li><a href="#"><img class="img-responsive" src={{asset("assets/images/lo5.png")}} alt=""></a></li>
                <div class="clearfix"></div>
            </ul>
        </div>--}}
    </div>
@endsection
