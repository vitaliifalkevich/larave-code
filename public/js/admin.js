jQuery(document).ready(function($) {
    /*Функция для добавления новых категорий, цветов, размеров*/
    function addTags(HTMLElement) {
        var activeClass = HTMLElement.currentTarget.className.substring(4);
        if(activeClass == "colors"||activeClass == "sizes") {
            $(".add_"+activeClass).hide();
        }
        else {
            $(".add_"+activeClass+",.list_"+activeClass).hide();
        }
            $(".add_"+activeClass+"_input,.remove-"+activeClass).show();
       /* console.log(activeClass);*/
    }
    /*Функция для удаления новых категорий, цветов, размеров*/
    function removeTags(HTMLElement) {
        var removeClass = HTMLElement.currentTarget.className.substring(20);
        $(".add_"+removeClass+",.list_"+removeClass).show();
        $(".add_"+removeClass+"_input,.remove-"+removeClass).hide();
        /*console.log($("input[name='add_"+removeClass+"']").val(null));*/

        /*console.log(removeClass);*/
    }
/*Вызыв функции для добавления новых категорий, цветов, размеров*/
    $(".add_category").on('click', function (e) {
        addTags(e);
    });
    $(".add_colors").on('click', function (e) {
        addTags(e);
    });
    $(".add_sizes").on('click', function (e) {
        addTags(e);
    });
    /*Вызов функции для удаления возможности ввести новые категории, цвета, размеры*/
    $(".remove-category").on('click', function (e) {
        removeTags(e);
    });
    $(".remove-colors").on('click', function (e) {
        removeTags(e);
    });
    $(".remove-sizes").on('click', function (e) {
        removeTags(e);
    });

    /*$(".add_category").on('click', function (e) {
        console.log(e);
        $(".add_category,.list_categories").hide();
        $(".add_category_input,.remove-category").show();
    });
    $(".remove-category").on('click', function (e) {
        $(".remove-category, .add_category_input").hide();
        $(".list_categories, .add_category").show();
    });*/
    $(document).on('change', '#changeSelect', function () {
        $('#saveStatus').css('display','inline-block');
    });
});