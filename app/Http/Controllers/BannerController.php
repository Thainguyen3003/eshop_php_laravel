<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Banner;
use Session;
session_start();

class BannerController extends Controller
{
    public function AuthLogin() {
        $admin_id = Session::get('admin_id');
        if($admin_id) {
            return Redirect::to('dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }

    public function manage_banner() {
        $this->AuthLogin();
        $list_banner = Banner::orderBy('banner_id', 'desc')->get();
        return view('admin.banner.list_banner')->with(compact('list_banner'));
    }

    public function add_banner() {
        $this->AuthLogin();
        return view('admin.banner.add_banner');
    }

    public function save_banner(Request $request) {
        $this->AuthLogin();
        
        $banner = new Banner();
        $banner->banner_name = $request->banner_name;
        $banner->banner_desc = $request->banner_desc;
        $banner->banner_status = $request->banner_status;
        $get_image = $request->file('banner_image');
        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image.rand(0,99). '.' . $get_image->getClientOriginalExtension();
            $get_image->move('public/uploads/banner', $new_image);
            $banner->banner_image = $new_image; 
            $banner->save();

            Session::put('message', 'Thêm banner thành công');
            return Redirect::to('add-banner');
        } else {
            $banner->banner_image = '';
            Session::put('message', 'Hãy thêm hình ảnh');
            return Redirect::to('add-banner');
        }
    }

    public function unactive_banner($banner_id) {
        $this->AuthLogin();

        Banner::where('banner_id', $banner_id)->update(['banner_status' => 1 ]);
        Session::put('message', 'Không kích hoạt banner thành công');
        return Redirect::to('manage-banner');
    }

    public function active_banner($banner_id) {
        $this->AuthLogin();

        Banner::where('banner_id', $banner_id)->update(['banner_status' => 0 ]);
        Session::put('message', 'Kích hoạt banner thành công');
        return Redirect::to('manage-banner');
    }
}
