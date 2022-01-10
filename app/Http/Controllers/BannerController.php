<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Banner;

class BannerController extends Controller
{
    public function manage_banner() {
        $list_banner = Banner::orderBy('banner_id', 'desc');
        return view('admin.banner.list_banner')->with(compact('list_banner'));
    }

    public function add_banner() {
        return view('admin.banner.add_banner');
    }

    public function save_banner(Request $request) {
        
    }
}
