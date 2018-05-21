<div class="header">
    <div class="header-top">
        <div class="container">
            <div class="search">
                <input id="search-text" type="text" value="Поиск " onfocus="this.value = '';"
                       onblur="if (this.value == '') {this.value = 'Search';}">
                {{--<input type="submit" value="Искать">--}}
            </div>
            <a id="search-link"
               style="text-decoration:none;display: inline-block;background: #EF5F21;color: #fff;padding: 5.2px;margin-top: 10.5px;"
               href="">Искать</a>
            <div class="header-left">

                <div class="cart box_1">
                    <a href="/checkout">
                        <h3>
                            <div class="total">
                                ₴<span class="simpleCart_total">0</span>.00 (<span id="simpleCart_quantity"
                                                                                   class="simpleCart_quantity">0</span>
                                товаров)
                            </div>
                            <img src="/assets/images/cart.png" alt=""/></h3>
                    </a>
                    <p>
                        <a onclick="document.cookie ='cost=; path=/; expires=-1';document.cookie ='count=; path=/; expires=-1'; document.cookie = 'tovars='+JSON.stringify('')+'; path=/; expires=-1'"
                           href="" class="simpleCart_empty">Очистить корзину</a></p>

                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="container">
        <div class="head-top">
            <div class="logo">
                <a href={{route('index')}}><img style="width: 210px;" src="/assets/images/logo.png" alt=""></a>
            </div>
            <div class=" h_menu4">
                <ul class="memenu skyblue">
                    <li @if(Request::url() == route('index'))  class="active grid" @endif ><a class="color2"
                                                                                              href={{route('index')}}>Главная</a>
                    </li>
                    @if(isset($categories))

                        @foreach($categories as $category)
                            <li @if(Request::url() == url("category/".$category->categoryAlias))  class="active grid" @endif >
                                <a class="color1"
                                   href={{url("category/".$category->categoryAlias)}}>{{$category->categoryName}}</a>
                            </li>
                        @endforeach
                    @endif
                    <li @if(Request::url() == route('contacts'))  class="active grid" @endif ><a class="color6"
                                                                                                 href={{route('contacts')}}>Контакты</a>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>