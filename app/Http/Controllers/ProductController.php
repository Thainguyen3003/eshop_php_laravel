<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Product;
use Session;
session_start();

class ProductController extends Controller
{
    public function AuthLogin() {
        $admin_id = Session::get('admin_id');
        if($admin_id) {
            return Redirect::to('dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }

    public function add_product() {
        $this->AuthLogin();

        $cate_product = DB::table('tbl_category_product')->orderBy('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->orderBy('brand_id', 'desc')->get();

        return view('admin.add_product')->with('cate_product', $cate_product)->with('brand_product', $brand_product);
    }

    public function all_product() {
        $this->AuthLogin();

        $all_product = DB::table('tbl_product')
        ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'tbl_product.category_id')
        ->join('tbl_brand', 'tbl_brand.brand_id', '=', 'tbl_product.brand_id')
        ->orderBy('tbl_product.product_id', 'desc')->get();
        $manager_product = view('admin.all_product')->with('all_product', $all_product);
        return view('admin_layout')->with('admin.all_product', $manager_product);

    }

    public function save_product(Request $request) {
        $this->AuthLogin();

        $product = new Product();
        $product->product_name = $request->product_name;
        $product->product_quantity = $request->product_quantity;
        $product->product_price = $request->product_price;
        $product->product_desc = $request->product_desc;
        $product->product_content = $request->product_content;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->product_status = $request->product_status;

        $get_image = $request->file('product_image');

        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image.rand(0,99). '.' . $get_image->getClientOriginalExtension();
            $get_image->move('public/uploads/product', $new_image);
            $product->product_image = $new_image;

        } else {
            $product->product_image = '';
        }

        $product->save();
        Session::put('message', 'Th??m s???n ph???m th??nh c??ng');
        return Redirect::to('all-product');
    }

    public function unactive_product($product_id) {
        $this->AuthLogin();

        DB::table('tbl_product')->where('product_id', $product_id)->update(['product_status' => 1 ]);
        Session::put('message', 'Kh??ng k??ch ho???t s???n ph???m th??nh c??ng');
        return Redirect::to('all-product');
    }

    public function active_product($product_id) {
        $this->AuthLogin();

        DB::table('tbl_product')->where('product_id', $product_id)->update(['product_status' => 0 ]);
        Session::put('message', 'K??ch ho???t s???n ph???m th??nh c??ng');
        return Redirect::to('all-product');
    }

    public function edit_product($product_id) {
        $this->AuthLogin();

        $cate_product = DB::table('tbl_category_product')->orderBy('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->orderBy('brand_id', 'desc')->get();

        $edit_product = DB::table('tbl_product')->where('product_id', $product_id)->get();

        $manager_product = view('admin.edit_product')->with('edit_product', $edit_product)->with('cate_product', $cate_product)
        ->with('brand_product', $brand_product);
        return view('admin_layout')->with('admin.edit_product', $manager_product);
    }

    public function delete_product($product_id) {
        $this->AuthLogin();

        DB::table('tbl_product')->where('product_id', $product_id)->delete();
        Session::put('message', 'X??a s???n ph???m th??nh c??ng');

        return Redirect::to('all-product');

    }

    public function update_product(Request $request, $product_id) {
        $this->AuthLogin();
        
        $product = new Product();
        $product->product_name = $request->product_name;
        $product->product_quantity = $request->product_quantity;
        $product->product_price = $request->product_price;
        $product->product_desc = $request->product_desc;
        $product->product_content = $request->product_content;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->product_status = $request->product_status;
        $get_image = $request->file('product_image');

        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image.rand(0,99). '.' . $get_image->getClientOriginalExtension();
            $get_image->move('public/uploads/product', $new_image);
            $product->product_image = $new_image; 
        }

        $product->save();
        Session::put('message', 'C???p nh???t s???n ph???m th??nh c??ng');

        return Redirect::to('all-product');

    }

    // End function admin page

    public function show_detail_product($product_id) {
        $cate_product = DB::table('tbl_category_product')
        ->where('category_status', '0')
        ->orderBy('category_id', 'desc')->get();

        $brand_product = DB::table('tbl_brand')
        ->where('brand_status', '0')
        ->orderBy('brand_id', 'desc')->get();

        $detail_product = DB::table('tbl_product')
        ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'tbl_product.category_id')
        ->join('tbl_brand', 'tbl_brand.brand_id', '=', 'tbl_product.brand_id')
        ->where('tbl_product.product_id', $product_id)->get();

        foreach ($detail_product as $key => $value) {
            $category_id = $value->category_id;
        }

        $related_product = DB::table('tbl_product')
        ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'tbl_product.category_id')
        ->join('tbl_brand', 'tbl_brand.brand_id', '=', 'tbl_product.brand_id')
        ->where('tbl_category_product.category_id', $category_id)->whereNotIn('tbl_product.product_id', [$product_id])->get();

        return view('pages.product.show_detail')
        ->with('categories', $cate_product)
        ->with('brands', $brand_product)
        ->with('product_detail', $detail_product)
        ->with('related_products', $related_product);
    }
}
