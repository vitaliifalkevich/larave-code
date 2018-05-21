<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Category;

class ContactController extends Controller
{

    public function contact(Request $request) {
        $categories = Category::select('categoryName','categoryAlias')->get();
        if($request->isMethod('post')) {
            $input=$request->except('_token');
            $messages=[
                'required'=>'Поле :attribute обязательно к заполнению',
                'email'=>'Введен некорректный Email'
            ];
            $validator=Validator::make($input,[
                'name'=>'required|max:100',
                'email'=>'required|email',
                'topic'=>'required',
                'message'=>'required'
            ],$messages);

            if($validator->fails()){
                return redirect()->route('contacts')->with(['categories'=>$categories])->withErrors($validator);
            }
            else {
                /*Отправляем сообщение на нужный адрес*/
                $mail="admin@torgsystem.com"; // e-mail куда уйдет письмо
                $title="Обращение"; // заголовок(тема) письма
                //конвертируем
                $title=iconv("utf-8","windows-1251",$title);
                $title=convert_cyr_string($title, "w", "k");
                /*Содержание сообщения*/
                $message="<html><head></head><body><b>Имя:</b>".$input['name']."<br>";
                $message.="<b>Email:</b>".$input['email']."<br>";
                $message.="<b>Тема вопроса:</b>".$input['topic']."<br>";
                $message.="<b>Тема вопроса:</b>".$input['message']."<br>";
                $message.="</body></html>";

                //конвертируем
                $message=iconv("utf-8","windows-1251",$message);
                $message=convert_cyr_string($message, "w", "k");
                $headers="MIME-Version: 1.0\r\n";
                $headers.="Content-Type: text/html; charset=utf-8\r\n";
                $headers.="From: admin@les-dv.ru\r\n"; // откуда письмо
                mail($mail, $title, $message, $headers); // отправляем
                return redirect()->route('contacts')->with(['categories'=>$categories])->with('status','Сообщение успешно отправлено');
            }
        }
        return view('client.contact')->with(['categories'=>$categories]);

    }
}
