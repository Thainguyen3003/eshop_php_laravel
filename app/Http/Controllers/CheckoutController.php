<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use Cart;
use App\City;
use App\District;
use App\Ward;
use App\Feeship;
use App\Order;
use App\OrderDetails;
use App\Shipping;

session_start();

class CheckoutController extends Controller
{
    public function AuthLogin() {
        $admin_id = Session::get('admin_id');
        if($admin_id) {
            return Redirect::to('dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }

    public function login_checkout() {

        $cate_product = DB::table('tbl_category_product')
        ->where('category_status', '0')
        ->orderBy('category_id', 'desc')->get();

        $brand_product = DB::table('tbl_brand')
        ->where('brand_status', '0')
        ->orderBy('brand_id', 'desc')->get();

        return view('pages.checkout.login_checkout')->with('categories', $cate_product)->with('brands', $brand_product);
    }

    public function add_customer(Request $request) {
        $data = array();
        $data['customer_name'] = $request->customer_name;
        $data['customer_email'] = $request->customer_email;
        $data['customer_password'] = md5($request->customer_password);
        $data['customer_phone'] = $request->customer_phone;

        $customer_id = DB::table('tbl_customers')->insertGetId($data);

        Session::put('customer_id', $customer_id);
        Session::put('customer_name', $request->customer_name);

        return redirect('/checkout');
    }

    public function checkout() {
        $cate_product = DB::table('tbl_category_product')
        ->where('category_status', '0')
        ->orderBy('category_id', 'desc')->get();

        $brand_product = DB::table('tbl_brand')
        ->where('brand_status', '0')
        ->orderBy('brand_id', 'desc')->get();

        $list_cities = City::orderby('matp', 'asc')->get();

        return view('pages.checkout.show_checkout')
        ->with('categories', $cate_product)
        ->with('brands', $brand_product)
        ->with('list_cities', $list_cities);
    }

    public function save_checkout_customer(Request $request) {
        $data = array();
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_address'] = $request->shipping_address;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['shipping_notes'] = $request->shipping_notes;
        $shipping_id = DB::table('tbl_shipping')->insertGetId($data);

        Session::put('shipping_id', $shipping_id);

        return redirect('/payment');
    }

    public function payment() {
        $cate_product = DB::table('tbl_category_product')
        ->where('category_status', '0')
        ->orderBy('category_id', 'desc')->get();

        $brand_product = DB::table('tbl_brand')
        ->where('brand_status', '0')
        ->orderBy('brand_id', 'desc')->get();

        return view('pages.checkout.payment')->with('categories', $cate_product)->with('brands', $brand_product);

    }

    public function logout_checkout() {
        Session::flush();
        return redirect('/login-checkout');
    }

    public function login_customer(Request $request) {
        $email = $request->email_account;
        $password = md5($request->password_account);
        $result = DB::table('tbl_customers')->where('customer_email', $email)->where('customer_password', $password)->first();
        if($result) {
            Session::put('customer_id', $result->customer_id);
            return redirect('/checkout');
        } else {
            return redirect('/login-checkout');
        }
    }

    public function order_place(Request $request) {
        $cate_product = DB::table('tbl_category_product')
        ->where('category_status', '0')
        ->orderBy('category_id', 'desc')->get();

        $brand_product = DB::table('tbl_brand')
        ->where('brand_status', '0')
        ->orderBy('brand_id', 'desc')->get();

        // insert payment method
        $data = array();
        $data['payment_method'] = $request->payment_option;
        $data['payment_status'] = 'Đang chờ xử lý';

        $payment_id = DB::table('tbl_payment')->insertGetId($data);

        // insert order
        $order_data = array();
        $order_data['customer_id'] = Session::get('customer_id');
        $order_data['shipping_id'] = Session::get('shipping_id');
        $order_data['payment_id'] = $payment_id;
        $order_data['order_total'] = Cart::total();
        $order_data['order_status'] = 'Đang chờ xử lý';

        $order_id = DB::table('tbl_order')->insertGetId($order_data);

        // insert order_details
        $order_details_data = array();
        $content = Cart::content();

        foreach($content as $v_content) {
            $order_details_data['order_id'] = $order_id;
            $order_details_data['product_id'] = $v_content->id;
            $order_details_data['product_name'] = $v_content->name;
            $order_details_data['product_price'] = $v_content->price;
            $order_details_data['product_sales_quantity'] = $v_content->qty;
            DB::table('tbl_order_details')->insert($order_details_data);
        }
        
        if($data['payment_method'] == 1) {
            dd('thanh toán thẻ ATM');
        } else {
            Cart::destroy();
            return view('pages.checkout.handcast')->with('categories', $cate_product)->with('brands', $brand_product);
        }
        
        //return redirect('/payment');
    }

    public function manage_order() {
        $this->AuthLogin();

        $all_order = DB::table('tbl_order')
        ->join('tbl_customers', 'tbl_order.customer_id', '=', 'tbl_customers.customer_id')
        ->select('tbl_order.*', 'tbl_customers.customer_name')
        ->orderBy('tbl_order.order_id', 'desc')->get();
        $manager_order = view('admin.manage_order')->with('all_order', $all_order);
        return view('admin_layout')->with('admin.manage_order', $manager_order);

    }

    public function edit_order($orderId) {
        $this->AuthLogin();

        $order_by_id = DB::table('tbl_order')
        ->join('tbl_customers', 'tbl_order.customer_id', '=', 'tbl_customers.customer_id')
        ->join('tbl_shipping', 'tbl_order.shipping_id', '=', 'tbl_shipping.shipping_id')
        ->join('tbl_order_details', 'tbl_order.order_id', '=', 'tbl_order_details.order_id')
        ->select('tbl_order.*', 'tbl_customers.*', 'tbl_shipping.*', 'tbl_order_details.*')->first();

        $manager_order_order_by_id = view('admin.edit_order')->with('order_by_id', $order_by_id);
        return view('admin_layout')->with('admin.edit_order', $manager_order_order_by_id);

    }

    public function select_delivery_checkout(Request $request) {
        $data = $request->all();
        $option = '';
        if($data['action']) {
            if($data['action'] == 'city') {
                $select_district = District::where('matp', $data['ma_id'])->orderby('maqh', 'asc')->get();
                $option .= '<option>---Chọn quận, huyện---</option>';
                foreach ($select_district as $key => $district) {
                    $option .= '<option value="'.$district->maqh.'">'.$district->name_quanhuyen.'</option>';
                }
            } else {
                $select_ward = Ward::where('maqh', $data['ma_id'])->orderby('xaid', 'asc')->get();
                $option .= '<option>---Chọn xã, phường, thị trấn---</option>';
                foreach ($select_ward as $key => $ward) {
                    $option .= '<option value="'.$ward->xaid.'">'.$ward->name_xaphuong.'</option>';
                }
            }
        }
        echo $option;
    }

    public function calculate_fee(Request $request) {
        $data = $request->all();
        if($data['matp']) {
            $feeship = Feeship::where('fee_matp', $data['matp'])->where('fee_maqh', $data['maqh'])->where('fee_xaid', $data['xaid'])->get();
            if ($feeship->count() > 0) {
                foreach ($feeship as $key => $fee) {
                    Session::put('fee', $fee->fee_ship);
                    Session::save();
                }
            } else {
                Session::put('fee', 30000);
                Session::save();
            }
        }
    }

    public function delete_fee() {
        Session::forget('fee');
        return redirect()->back();
    }

    public function confirm_order(Request $request) {
        $data = $request->all();
        $shipping = new Shipping();
        $shipping->shipping_name = $data['shipping_name'];
        $shipping->shipping_email = $data['shipping_email'];
        $shipping->shipping_address = $data['shipping_address'];
        $shipping->shipping_phone = $data['shipping_phone'];
        $shipping->shipping_notes = $data['shipping_notes'];
        $shipping->shipping_method = $data['shipping_method'];
        $shipping->save();

        $order_code = substr(md5(microtime()), rand(0, 26), 5);

        $shipping_id = $shipping->shipping_id;
        $order = new Order();
        $order->customer_id = Session::get('customer_id');
        $order->shipping_id = $shipping_id;
        $order->order_status = 1;
        $order->order_code = $order_code;
        $order->save();
    }
}
