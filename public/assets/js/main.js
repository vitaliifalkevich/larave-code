jQuery(document).ready(function($){
	/*функция для получения конкретной куки*/
    function get_cookie ( cookie_name )
    {
        var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );

        if ( results )
            return ( unescape ( results[2] ) );
        else
            return null;
    }
	var tabItems = $('.cd-tabs-navigation a'),
		tabContentWrapper = $('.cd-tabs-content');
	/*Переменные для корзины*/
	var costCheckout = $('.simpleCart_total');
	var countCheckout = $('#simpleCart_quantity');
	/*работа с Cookie*/
    var costCookie = get_cookie('cost');
    var countCookie = get_cookie('count');
    /*работа с Cookie*/
    if(costCookie) {
        costCheckout.text(costCookie);
	}
	if(countCookie) {
        countCheckout.text(countCookie);
	}
    /*!Переменные для корзины*/
    /*переменные для id товара*/
    var tovars = JSON.parse(get_cookie('tovars'));
    /*console.log(tovars);*/
    if(!tovars) {
        tovars = [];
	}
    var idTovar = {};
    idTovar.id = $('#hidden_type').val();
    /*!переменные для id товара*/

	tabItems.on('click', function(event){
		event.preventDefault();
		var selectedItem = $(this);
		if( !selectedItem.hasClass('selected') ) {
			var selectedTab = selectedItem.data('content'),
				selectedContent = tabContentWrapper.find('li[data-content="'+selectedTab+'"]'),
				slectedContentHeight = selectedContent.innerHeight();
			
			tabItems.removeClass('selected');
			selectedItem.addClass('selected');
			selectedContent.addClass('selected').siblings('li').removeClass('selected');
			//animate tabContentWrapper height when content changes 
			tabContentWrapper.animate({
				'height': slectedContentHeight
			}, 200);
		}
	});
	$('.add-cart').on('click', function () {
        var r, re,s;
        idTovar.color = $('.color_select').prop('selected', true).val();
        idTovar.size = $('.size_select').prop('selected', true).val();

            tovars.push([idTovar.id,idTovar.color, idTovar.size]);
        /*console.log(document.cookie = "tovars="+JSON.stringify(tovars)+"; path=/;");*/
            document.cookie = "tovars="+JSON.stringify(tovars)+"; path=/;";
            s = $('.item_price').text();
            re = /\D+/ig;
            r = +s.replace(re, '');
            costCookie = +costCheckout.text(+costCheckout.text() + r);
            countCookie = countCheckout.text(+countCheckout.text()+1);
            document.cookie = "cost="+(+costCheckout.text())+"; path=/;";
            document.cookie = "count="+(+countCookie.text())+"; path=/;";

        /*console.log(tovars);*/

    });
		//hide the .cd-tabs::after element when tabbed navigation has scrolled to the end (mobile version)
	checkScrolling($('.cd-tabs nav'));
	$(window).on('resize', function(){
		checkScrolling($('.cd-tabs nav'));
		tabContentWrapper.css('height', 'auto');
	});
	$('.cd-tabs nav').on('scroll', function(){ 
		checkScrolling($(this));
	});

	function checkScrolling(tabs){
		var totalTabWidth = parseInt(tabs.children('.cd-tabs-navigation').width()),
		 	tabsViewport = parseInt(tabs.width());
		if( tabs.scrollLeft() >= totalTabWidth - tabsViewport) {
			tabs.parent('.cd-tabs').addClass('is-ended');
		} else {
			tabs.parent('.cd-tabs').removeClass('is-ended');
		}
	}
	$('#search-link').on('click', function (e) {
		var contentSearch = $('#search-text').val();
		$(this).attr('href','/search/'+contentSearch);
    })

});