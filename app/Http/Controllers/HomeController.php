<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Session;
session_start();
class HomeController extends Controller
{
    //
    public function index(Request $request) {
        // seo
        /* $meta_desc = "Shop bán quần áo chính hãng";
        $meta_keywords = "Quần áo nam, quần áo nữ, phụ kiện";
        $meta_title = "Trang chủ";
        $meta_url_canonical = $request->url(); */
        // end seo

        $cate_product = DB::table('tbl_category_product')->where('category_status', '0')->orderBy('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status', '0')->orderBy('brand_id', 'desc')->get();

        /* $all_product = DB::table('tbl_product')
        ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'tbl_product.category_id')
        ->join('tbl_brand', 'tbl_brand.brand_id', '=', 'tbl_product.brand_id')
        ->orderBy('tbl_product.product_id', 'desc')->get(); */

        $all_product = DB::table('tbl_product')->where('product_status', '0')->orderby('product_id', 'desc')->limit(4)->get();
        return view('pages.home')
        ->with('categories', $cate_product)
        ->with('brands', $brand_product)
        ->with('all_product', $all_product)
        /* ->with('meta_desc', $meta_desc) */
        /* ->with('meta_keywords', $meta_keywords) */
        /* ->with('meta_title', $meta_title) */
        /* ->with('meta_url_canonical', $meta_url_canonical) */;

    }

    public function search(Request $request) {
        $keywords = $request->keywords_submit;

        $cate_product = DB::table('tbl_category_product')->where('category_status', '0')->orderBy('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status', '0')->orderBy('brand_id', 'desc')->get();

        /* $all_product = DB::table('tbl_product')
        ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'tbl_product.category_id')
        ->join('tbl_brand', 'tbl_brand.brand_id', '=', 'tbl_product.brand_id')
        ->orderBy('tbl_product.product_id', 'desc')->get(); */

        $search_product = DB::table('tbl_product')->where('product_name', 'like', '%' .$keywords. '%')->get();
        
        return view('pages.product.search')
        ->with('categories', $cate_product)
        ->with('brands', $brand_product)
        ->with('search_product', $search_product);
    }
}
