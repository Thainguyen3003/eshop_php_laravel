<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Order;
use App\OrderDetails;
use App\Shipping;
use App\Customer;
use App\Coupon;
use PDF;
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
        $total = 0;

        foreach($order_details as $key => $detail) {
            $product_coupon = $detail->product_coupon;
            $product_feeship = $detail->product_feeship;
            $subtotal = $detail->product_price * $detail->product_sales_quantity;
            $total += $subtotal;
        }
        $coupon = Coupon::where('coupon_code', $product_coupon)->first();
        $total_final = 0;

        if ($product_coupon != 'no') {
            if ($coupon->coupon_feat == 1) {
                $total_coupon = ($total*$coupon->coupon_money)/100;
                $total_final = $total-$total_coupon + $product_feeship;
            } else {
                $total_coupon = $total - $coupon->coupon_money;
                $total_final = $total_coupon + $product_feeship;
            }
        } else {
            $total_final = $total + $product_feeship;
        }
        
        $order_details = OrderDetails::with('product')->where('order_code', $order_code)->get();

        return view('admin.order.view_order')->with(compact('order_details', 'customer', 'shipping', 'coupon', 'total_final', 'product_feeship', 'order_code'));

    }

    public function manage_order() {
        $this->AuthLogin();

        $list_order = Order::orderby('created_at', 'desc')->get();
        return view('admin.manage_order')->with(compact('list_order'));
    }

    public function print_order($checkout_code) {
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->print_order_convert($checkout_code));
        return $pdf->stream();
    }

    public function print_order_convert($checkout_code) {
        return $checkout_code;
    }
}
