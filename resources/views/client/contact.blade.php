@extends('client.layouts.index')
@section('content')
    <!--header-->
    <!--content-->
    <div class="contact">
        <div class="container">
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
            <h1 style="text-transform: uppercase;">Контакты</h1>
            <div class="contact-form">
                <div class="col-md-8 contact-grid">
                    <form action="" method="post">
                        <input type="text" name="name" placeholder="Имя">

                        <input type="text" name="email" placeholder="Email">
                        <input type="text" name="topic" placeholder="Тема сообщения">

                        <textarea cols="77" name="message" rows="6" placeholder="Сообщение"></textarea>
                        <div class="send">
                            <input type="submit" value="Отправить">
                        </div>
                        {{csrf_field()}}
                    </form>
                </div>
                <div class="col-md-4 contact-in">
                    <div class="address-more">
                        <h4>Адрес</h4>
                        <p>NEW store,</p>
                        <p>г. Запорожье,</p>
                        <p>ул. Ленина 24 </p>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <!--//content-->
@endsection