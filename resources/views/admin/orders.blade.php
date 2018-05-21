<h3>Заказы:</h3>
<div class="table-responsive">
    <table class="list_of_goods table table-striped">
        <tr>
            <th>№</th>
            <th>Имя</th>
            <th>Статус</th>
            <th>Стоимость</th>
            <th colspan="3">Дата создания</th>
        </tr>
        @if(isset($orders))
            @foreach($orders as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{$item->firstName}}</td>
                    <td><span class="alert alert-{{$item->className}}" role="alert">{{$item->statusName}}</span></td>
                    <td>{{$item->price}} грн</td>
                    <td>{{date('d.m.Y', strtotime($item->created_at))}}</td>
                    <td><a href="/administrator/view/order/{{$item->id}}">Просмотреть</a></td>
                    <td>
                        <form action="" method="POST">
                            <input name="_method" type="hidden" value="DELETE">
                            <input name="order_id" type="hidden" value="{{$item->id}}">
                            <input class="btn btn-danger" type="submit"
                                   onclick="return confirm('Вы уверены, что хотите удалить заказ?')"
                                   value="X">
                            {{csrf_field()}}
                        </form>
                    </td>
                </tr>
                {{--{{dump($item)}}--}}
            @endforeach
        @endif
    </table>
</div>