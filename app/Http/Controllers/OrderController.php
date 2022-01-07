<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Order;
use App\OrderDetails;
use App\Shipping;
use App\Customer;
use Session;
session_start();

class OrderController extends Controller
{
    public function AuthLogin() {
        $admin_id = Session::get('admin_id');
        if($admin_id) {
            return Redirect::to('dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }

    public function view_order($order_code) {
        $this->AuthLogin();

        $order_details = OrderDetails::where('order_code', $order_code)->get();
        $order = Order::where('order_code', $order_code)->first();
        $customer_id = $order->customer_id;
        $shipping_id = $order->shipping_id;
        $customer = Customer::where('customer_id', $customer_id)->first();
        $shipping = Shipping::where('shipping_id', $shipping_id)->first();

        $order_details = OrderDetails::with('product')->where('order_code', $order_code)->get();
        return view('admin.order.view_order')->with(compact('order_details', 'customer', 'shipping', 'order_details'));

    }

    public function manage_order() {
        $this->AuthLogin();

        $list_order = Order::orderby('created_at', 'desc')->get();
        return view('admin.manage_order')->with(compact('list_order'));
    }
}
