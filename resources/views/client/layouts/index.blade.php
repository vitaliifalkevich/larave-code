<!DOCTYPE html>
<html>
<head>
    <title>Подари добро | баготворительный магазин</title>
    <link href={{asset("assets/css/bootstrap.css")}} rel="stylesheet" type="text/css" media="all"/>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src={{asset("assets/js/jquery.min.js")}}></script>
    <!-- Custom Theme files -->
    <!--theme-style-->
    <link href={{asset("assets/css/style.css")}} rel="stylesheet" type="text/css" media="all"/>
    <!--//theme-style-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords" content="New Store Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template,
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design"/>
    <script type="application/x-javascript"> addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        } </script>
    <!--webfonts-->
    <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900' rel='stylesheet' type='text/css'>
    <!--//webfonts-->
    <!-- start menu -->
    <link href={{asset("assets/css/memenu.css")}} rel="stylesheet" type="text/css" media="all"/>
    <script type="text/javascript" src={{asset("assets/js/memenu.js")}}></script>
    <script>$(document).ready(function () {
            $(".memenu").memenu();
        });</script>
    <script src={{asset("assets/js/simpleCart.js")}}></script>
    <script async src={{asset("assets/js/main.js")}}></script>
    <script src={{asset("assets/js/flexslider.min.js")}}></script>
    <link rel="stylesheet" href={{asset("assets/css/flexslider.css")}} type="text/css" media="screen"/>
</head>
<body>
@include('client.layouts.header')
@yield('content')
@include('client.layouts.footer')
</body>
</html>