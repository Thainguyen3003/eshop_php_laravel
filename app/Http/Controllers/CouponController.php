<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;
use Illuminate\Support\Facades\Redirect;
use Session;
session_start();

class CouponController extends Controller
{
    public function add_coupon() {
        return view('admin.coupon.add_coupon');
    }

    public function add_coupon_code(Request $request) {
        $data = $request->all();
        $coupon = new Coupon();

        $coupon->coupon_name = $data['coupon_name'];
        $coupon->coupon_code = $data['coupon_code'];
        $coupon->coupon_desc = $data['coupon_desc'];
        $coupon->coupon_qty = $data['coupon_qty'];
        $coupon->coupon_feat = $data['coupon_feat'];
        $coupon->coupon_money = $data['coupon_money'];
        $coupon->save();
        
        Session::put('message', 'Thêm mã giảm giá thành công');
        return Redirect::to('/hien-thi-them-ma-giam-gia');
    }

    public function all_coupon() {
        $all_coupon = Coupon::orderby('coupon_id', 'desc')->get();
        return view('admin.coupon.all_coupon')->with(compact('all_coupon'));
    }

    public function delete_coupon($coupon_id) {
        $coupon = Coupon::find($coupon_id);
        $coupon->delete();
        Session::put('message', 'Xóa mã giảm giá thành công');
        return Redirect::to('/danh-sach-ma-giam-gia');
    }
}
