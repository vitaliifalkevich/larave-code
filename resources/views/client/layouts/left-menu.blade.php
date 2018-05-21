<div class="col-md-3 product-price">
    <div class=" rsidebar span_1_of_left">
        <div class="of-left">
            <h3 class="cate">Категории</h3>
        </div>
        <ul class="menu">
            @if(isset($categories))
                @foreach($categories as $category)
                    <li class="item1"><a
                                href={{url("category/".$category->categoryAlias)}}>{{$category->categoryName}}</a>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>