<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Imports\ExcelImports;
use App\Exports\ExcelExports;
use Excel;
use App\Category;
use Session;
session_start();

class CategoryProduct extends Controller
{
    public function AuthLogin() {
        $admin_id = Session::get('admin_id');
        if($admin_id) {
            return Redirect::to('dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }

    public function add_category_product() {
        $this->AuthLogin();

        return view('admin.add_category_product');
    }

    public function all_category_product() {
        $this->AuthLogin();

        $all_category_product = DB::table('tbl_category_product')->get();
        $manager_category = view('admin.all_category_product')->with('all_category_product', $all_category_product);
        return view('admin_layout')->with('admin.all_category_product', $manager_category);

    }

    public function save_category_product(Request $request) {
        $this->AuthLogin();

        $data = array();
        $data['category_name'] = $request->category_product_name;
        $data['category_desc'] = $request->category_product_desc;
        /* $data['meta_keywords'] = $request->meta_keywords; */
        $data['category_status'] = $request->category_product_status;

        DB::table('tbl_category_product')->insert($data);
        Session::put('message', 'Thêm danh mục sản phẩm thành công');
        return Redirect::to('add-category-product');
    }

    public function unactive_category_product($category_product_id) {
        $this->AuthLogin();

        DB::table('tbl_category_product')->where('category_id', $category_product_id)->update(['category_status' => 1 ]);
        Session::put('message', 'Không kích hoạt danh mục sản phẩm thành công');
        return Redirect::to('all-category-product');
    }

    public function active_category_product($category_product_id) {
        $this->AuthLogin();

        DB::table('tbl_category_product')->where('category_id', $category_product_id)->update(['category_status' => 0 ]);
        Session::put('message', 'Kích hoạt danh mục sản phẩm thành công');
        return Redirect::to('all-category-product');
    }

    public function edit_category_product($category_product_id) {
        $this->AuthLogin();

        $edit_category_product = DB::table('tbl_category_product')->where('category_id', $category_product_id)->get();
        $manager_category = view('admin.edit_category_product')->with('edit_category_product', $edit_category_product);
        return view('admin_layout')->with('admin.edit_category_product', $manager_category);
    }

    public function delete_category_product($category_product_id) {
        $this->AuthLogin();

        DB::table('tbl_category_product')->where('category_id', $category_product_id)->delete();
        Session::put('message', 'Xóa danh mục sản phẩm thành công');

        return Redirect::to('all-category-product');

    }

    public function update_category_product(Request $request, $category_product_id) {
        $this->AuthLogin();
        
        $data = array();
        $data['category_name'] = $request->category_product_name;
        $data['category_desc'] = $request->category_product_desc;
        /* $data['meta_keywords'] = $request->meta_keywords; */
        
        DB::table('tbl_category_product')->where('category_id', $category_product_id)->update($data);
        Session::put('message', 'Cập nhật danh mục sản phẩm thành công');

        return Redirect::to('all-category-product');

    }

    // End function admin page
    
    public function show_category_home(Request $request, $category_id) {

        $cate_product = DB::table('tbl_category_product')
        ->where('category_status', '0')
        ->orderBy('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')
        ->where('brand_status', '0')
        ->orderBy('brand_id', 'desc')->get();

        $category_by_id = DB::table('tbl_product')
        ->join('tbl_category_product', 'tbl_product.category_id', '=', 'tbl_category_product.category_id')
        ->where('tbl_product.category_id', $category_id)->get();
        /* if ($category_by_id) {
            foreach ($category_by_id as $key => $value) {
                // seo
                $meta_desc = $value->category_desc;
                $meta_keywords = $value->meta_keywords;
                $meta_title = "Trang chủ";
                $meta_url_canonical = $request->url();
                // end seo
            }
        } */
        

        $category_name = DB::table('tbl_category_product')->where('tbl_category_product.category_id', $category_id)->get();

        return view('pages.category.show_category')
        ->with('categories', $cate_product)
        ->with('brands', $brand_product)
        ->with('category_by_id', $category_by_id)
        ->with('category_name', $category_name)
        /* ->with('meta_keywords', $meta_keywords)
        ->with('meta_desc', $meta_desc)
        ->with('meta_title', $meta_title)
        ->with('meta_url_canonical', $meta_url_canonical)*/;
    }

    public function import_csv(Request $request) {
        $path = $request->file('file')->getRealPath();
        Excel::import(new ExcelImports, $path);
        return back();
    }

    public function export_csv() {
        return Excel::download(new ExcelExports, 'category.xlsx');
    }
}
