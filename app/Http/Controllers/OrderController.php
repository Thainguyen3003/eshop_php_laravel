<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Order;
use App\OrderDetails;
use App\Shipping;
use App\Customer;
use App\Coupon;
use App\Product;
use PDF;
use Session;

session_start();

class OrderController extends Controller
{
    public function AuthLogin()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }

    public function view_order($order_code)
    {
        $this->AuthLogin();

        $order_details = OrderDetails::with('product')->where('order_code', $order_code)->get();
        $order = Order::where('order_code', $order_code)->first();
        $customer_id = $order->customer_id;
        $shipping_id = $order->shipping_id;
        $customer = Customer::where('customer_id', $customer_id)->first();
        $shipping = Shipping::where('shipping_id', $shipping_id)->first();
        $total = 0;

        foreach ($order_details as $key => $detail) {
            $product_coupon = $detail->product_coupon;
            $product_feeship = $detail->product_feeship;
            $subtotal = $detail->product_price * $detail->product_sales_quantity;
            $total += $subtotal;
        }
        $coupon = Coupon::where('coupon_code', $product_coupon)->first();
        $total_final = 0;

        if ($product_coupon != 'no') {
            if ($coupon->coupon_feat == 1) {
                $total_coupon = ($total * $coupon->coupon_money) / 100;
                $total_final = $total - $total_coupon + $product_feeship;
            } else {
                $total_coupon = $total - $coupon->coupon_money;
                $total_final = $total_coupon + $product_feeship;
            }
        } else {
            $total_final = $total + $product_feeship;
        }

        return view('admin.order.view_order')->with(compact('order_details', 'customer', 'shipping', 'coupon', 'total_final', 'product_feeship', 'order_code', 'order'));
    }

    public function manage_order()
    {
        $this->AuthLogin();

        $list_order = Order::orderby('created_at', 'desc')->get();
        return view('admin.manage_order')->with(compact('list_order'));
    }

    public function print_order($order_code)
    {
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->print_order_convert($order_code));
        return $pdf->stream();
    }

    public function print_order_convert($order_code)
    {
        $order_details = OrderDetails::where('order_code', $order_code)->get();
        $order = Order::where('order_code', $order_code)->first();
        $customer_id = $order->customer_id;
        $shipping_id = $order->shipping_id;
        $customer = Customer::where('customer_id', $customer_id)->first();
        $shipping = Shipping::where('shipping_id', $shipping_id)->first();
        $total = 0;
        foreach ($order_details as $key => $detail) {
            $product_coupon = $detail->product_coupon;
            $product_feeship = $detail->product_feeship;
            $subtotal = $detail->product_price * $detail->product_sales_quantity;
            $total += $subtotal;
        }

        $order_details_products = OrderDetails::with('product')->where('order_code', $order_code)->get();

        $coupon = Coupon::where('coupon_code', $product_coupon)->first();
        $total_final = 0;

        if ($product_coupon != 'no') {
            if ($coupon->coupon_feat == 1) {
                $total_coupon = ($total * $coupon->coupon_money) / 100;
                $total_final = $total - $total_coupon + $product_feeship;
            } else {
                $total_coupon = $total - $coupon->coupon_money;
                $total_final = $total_coupon + $product_feeship;
            }
        } else {
            $total_final = $total + $product_feeship;
        }


        $output = '';
        $output .= '
        <style>
            body {
                font-family:DejaVu Sans;
            }
            table ,tr ,td {
                border:1px solid black;
            }
        </style>
        <h1><center>C??ng ty TNHH m???t m??nh tao</center></h1>
        <h4><center>Xu???t h??a ????n</center></h4>
        <p>Ng?????i ?????t h??ng</p>
        <table class="table-styling" style="width:100%">
            <thead>
                <tr>
                    <th>T??n kh??ch ?????t h??ng</th>
                    <th>Email</th>
                    <th>S??? ??i???n tho???i</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>'. $customer->customer_name .'</td>
                    <td>'. $customer->customer_email .'</td>
                    <td>'. $customer->customer_phone .'</td>
                </tr>
            </tbody>
        </table>
        <p>?????a ch??? giao h??ng</p>
        <table class="table-styling" style="width:100%">
            <thead>
                <tr>
                    <th>T??n ng?????i nh???n</th>
                    <th>S??? ??i???n tho???i</th>
                    <th>Email</th>
                    <th>?????a ch???</th>
                    <th>Ghi ch??</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>'. $shipping->shipping_name .'</td>
                    <td>'. $shipping->shipping_phone .'</td>
                    <td>'. $shipping->shipping_email .'</td>
                    <td>'. $shipping->shipping_address .'</td>
                    <td>'. $shipping->shipping_notes .'</td>
                </tr>
            </tbody>
        </table>
        <p>Danh s??ch ????n h??ng</p>
        <table class="table-styling" style="width:100%">
            <thead>
                <tr>
                    <th>Th??? t???</th>
                    <th>T??n s???n ph???m</th>
                    <th>S??? l?????ng</th>
                    <th>Gi?? s???n ph???m</th>
                    <th>T???ng ti???n</th>
                </tr>
            </thead>
            <tbody>';
            
                foreach ($order_details_products as $key => $product) {
                    $output .='
                        <tr>
                            <td>'. $key + 1 .'</td>
                            <td>'. $product->product_name .'</td>
                            <td>'. $product->product_sales_quantity .'</td>
                            <td>'. number_format($product->product_price, 0, ',', '.') . ' VN??' .'</td>
                            <td>'. number_format($product->product_price * $product->product_sales_quantity, 0, ',', '.') . ' VN??' .'</td>
                        </tr>
                    ';
                }
                $output .='
                <tr>
                    <td>M?? gi???m gi?? ???????c gi???m</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>'. number_format($coupon->coupon_money, 0, ',', '.') . ' VN??' .'</td>
                </tr>
                <tr>
                    <td>Ph?? ship</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>'. number_format($product_feeship, 0, ',', '.') . ' VN??' .'</td>
                </tr>
                <tr>
                    <td>T???ng thanh to??n</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>'. number_format($total_final, 0, ',', '.') . ' VN??' .'</td>
                </tr>
            </tbody>
        </table>
        ';

        return $output;
    }

    public function update_order_status(Request $request) {
        // update order status
        $order = Order::find($request->order_id);
        $order->order_status = $request->order_status;
        $order->save();

        //
        if($order->order_status == 2) {
            foreach ($request->order_product_id as $key1 => $product_id) {
                $product = Product::find($product_id);
                $product_quantity = $product->product_quantity;
                $product_sold = $product->product_sold;
                foreach ($request->product_sales_quantity as $key2 => $qty) {

                    if($key1 == $key2) {
                        $pro_remain = $product_quantity - $qty;
                        $product->product_quantity = $pro_remain;
                        $product->product_sold = $product_sold + $qty;
                        $product->save();
                    }
                }
            }
        } elseif($order->order_status != 2 && $order->order_status != 3  ) {
            foreach ($request->order_product_id as $key1 => $product_id) {
                $product = Product::find($product_id);
                $product_quantity = $product->product_quantity;
                $product_sold = $product->product_sold;
                foreach ($request->product_sales_quantity as $key2 => $qty) {

                    if($key1 == $key2) {
                        $pro_remain = $product_quantity + $qty;
                        $product->product_quantity = $pro_remain;
                        $product->product_sold = $product_sold - $qty;
                        $product->save();
                    }
                }
            }
        }
    }

    public function update_qty_order(Request $request) {
        $order_details = OrderDetails::where([
            ['product_id', $request->order_product_id],
            ['order_code', $request->order_code]
        ])->first();
        $order_details->product_sales_quantity = $request->product_sales_quantity;
        $order_details->save();
    }
}
