<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\OrdersLists;
use App\Status;

class ViewOrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $orderId)
    {
        if ($request->isMethod('post')) {
            $input = $request->except('_token');
            $changeOrder = Order::where('id', '=', $orderId)->first();
            $changeOrder->status_id = $input['orderStatus'];
            $changeOrder->save();
        }
        $getOrder = Order::where('orders.id', '=', $orderId)
            ->leftJoin('clients', 'orders.client_id', '=', 'clients.id')
            ->leftJoin('status', 'orders.status_id', '=', 'status.id')
            ->leftJoin('way_delivery', 'orders.delivery_id', '=', 'way_delivery.id')
            ->leftJoin('way_pay', 'orders.pay_id', '=', 'way_pay.id')
            ->select(['orders.id', 'orders.price', 'orders.created_at', 'clients.firstName', 'clients.lastName', 'clients.phone', 'clients.adress', 'clients.email', 'status.statusName', 'status.className', 'way_delivery.deliveryName', 'way_pay.payName'])
            ->get();
        $getOrder = $getOrder->all()[0];

        $getProducts = OrdersLists::where('orders_lists.order_id', '=', $orderId)
            ->leftJoin('products', 'orders_lists.product_id', '=', 'products.id')
            ->leftJoin('colors', 'orders_lists.color_id', '=', 'colors.id')
            ->leftJoin('sizes', 'orders_lists.size_id', '=', 'sizes.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('images', 'products.id', '=', 'images.product_id')
            ->where('images.index_image_id', '=', 1)
            ->select(['categories.categoryAlias', 'images.image', 'products.productName', 'colors.color', 'sizes.size', 'products.price'])
            ->get();

        $statuses = Status::select('id', 'statusName')->get();
        return view('admin/viewOrder')->with(['getOrder' => $getOrder, 'getProducts' => $getProducts, 'statuses' => $statuses]);
    }
}
