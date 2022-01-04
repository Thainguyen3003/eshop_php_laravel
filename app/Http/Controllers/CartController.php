<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use Cart;
session_start();

class CartController extends Controller
{
    public function show_cart_ajax() {
        $cate_product = DB::table('tbl_category_product')->where('category_status', '0')->orderBy('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status', '0')->orderBy('brand_id', 'desc')->get();
        
        return view('pages.cart.cart_ajax')->with('categories', $cate_product)->with('brands', $brand_product);
    }

    public function add_cart_ajax(Request $request) {
        $data = $request->all();
        $session_id = substr(md5(microtime()), rand(0,26), 5);
        $cart = Session::get('cart');
        /* if($cart == true) {
            $is_available = 0;
            foreach ($cart as $key => $value) {
                if($value['product_id'] == $data['product_id']) {
                    $is_available++;
                }
            }
            if($is_available == 0) {
                $cart[] = array(
                    'session_id' => $session_id,
                    'product_id' => $data['cart_product_id'],
                    'product_name' => $data['cart_product_name'],
                    'product_image' => $data['cart_product_image'],
                    'product_qty' => $data['cart_product_qty'],
                    'product_price' => $data['cart_product_price'],
                );
                Session::put('cart', $cart);
            }
        } else { */
            $cart[] = array(
                'session_id' => $session_id,
                'product_id' => $data['cart_product_id'],
                'product_name' => $data['cart_product_name'],
                'product_image' => $data['cart_product_image'],
                'product_qty' => $data['cart_product_qty'],
                'product_price' => $data['cart_product_price'],
            );
        /* } */
        Session::put('cart', $cart);
        Session::save();

    }

    public function delete_product_ajax($session_id) {
        $cart = Session::get('cart');
        if($cart == true) {
            foreach ($cart as $key => $value) {
                if($value['session_id'] == $session_id) {
                    unset($cart[$key]);
                }
            }
            
            Session::put('cart', $cart);
            return redirect()->back()->with('message', 'Xóa sản phẩm thành công');
        } else {
            return redirect()->back()->with('message', 'Xóa sản phẩm thất bại');
        }
    }

    public function update_cart(Request $request) {
        $data = $request->all();
        $cart = Session::get('cart');
        if($cart == true) {
            foreach ($data['cart_qty'] as $key => $qty) {
                foreach ($cart as $session => $value) {
                    if($value['session_id'] == $key ) {
                        $cart[$session]['product_qty'] = $qty;
                    }
                }
            }
            Session::put('cart', $cart);
            return redirect()->back()->with('message', 'Cập nhật số lượng sản phẩm thành công');
        } else {
            return redirect()->back()->with('message', 'Cập nhật số lượng sản phẩm thất bại');
        }
    }

    public function delete_all_product_ajax() {
        $cart = Session::get('cart');
        if($cart == true) {
            /* Session::destroy(); */
            Session::forget('cart');
            return redirect()->back()->with('message', 'Xóa toàn bộ sản phẩm trong giỏ hàng thành công');
        }
    }

    public function save_cart(Request $request) {
        $productId = $request->productid_hidden;
        $quantity = $request->qty;

        $product_info = DB::table('tbl_product')->where('product_id', $productId)->first();
        //Cart::add('293ad', 'Product 1', 1, 9.99, 550);
        //Cart::destroy();
        $data['id'] = $productId;
        $data['qty'] = $quantity;
        $data['name'] = $product_info->product_name;
        $data['price'] = $product_info->product_price;
        $data['weight'] = '0';
        $data['options']['image'] = $product_info->product_image;
        Cart::add($data);
        Cart::setGlobalTax(10);
        /* Cart::destroy();   */


        return Redirect::to('/show-cart');
    }
    
    public function show_cart() {
        $cate_product = DB::table('tbl_category_product')->where('category_status', '0')->orderBy('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status', '0')->orderBy('brand_id', 'desc')->get();
        
        return view('pages.cart.show_cart')->with('categories', $cate_product)->with('brands', $brand_product);
    }

    public function delete_to_cart($rowId) {
        Cart::update($rowId, 0);
        return Redirect::to('/show-cart');
    }

    public function update_cart_quantity(Request $request) {
        $row_id = $request->rowId_cart;
        $qty = $request->cart_quantity;
        Cart::update($row_id, $qty);
        return Redirect::to('/show-cart');
    }
}
