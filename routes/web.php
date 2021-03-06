<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Frontend
Route::get('/', 'HomeController@index');

Route::get('/trang-chu', 'HomeController@index');
Route::post('/tim-kiem', 'HomeController@search');

// Danh muc san pham
Route::get('/danh-muc-san-pham/{category_id}', 'CategoryProduct@show_category_home');
Route::get('/thuong-hieu-san-pham/{brand_id}', 'BrandProduct@show_brand_home');
Route::get('/chi-tiet-san-pham/{product_id}', 'ProductController@show_detail_product');


// Backend
Route::get('/admin', 'AdminController@index');
Route::get('/dashboard', 'AdminController@show_dashboard');
Route::get('logout', 'AdminController@log_out');
Route::post('/admin-dashboard', 'AdminController@dashboard');

// Category Product
Route::get('/add-category-product', 'CategoryProduct@add_category_product');
Route::get('/edit-category-product/{category_product_id}', 'CategoryProduct@edit_category_product');
Route::get('/delete-category-product/{category_product_id}', 'CategoryProduct@delete_category_product');
Route::get('/all-category-product', 'CategoryProduct@all_category_product');

Route::get('/unactive-category-product/{category_product_id}', 'CategoryProduct@unactive_category_product');
Route::get('/active-category-product/{category_product_id}', 'CategoryProduct@active_category_product');

Route::post('/save-category-product', 'CategoryProduct@save_category_product');
Route::post('/update-category-product/{category_product_id}', 'CategoryProduct@update_category_product');

Route::post('/import-csv', 'CategoryProduct@import_csv');
Route::post('/export-csv', 'CategoryProduct@export_csv');


// Brand Product
Route::get('/add-brand-product', 'BrandProduct@add_brand_product');
Route::get('/edit-brand-product/{brand_product_id}', 'BrandProduct@edit_brand_product');
Route::get('/delete-brand-product/{brand_product_id}', 'BrandProduct@delete_brand_product');
Route::get('/all-brand-product', 'BrandProduct@all_brand_product');

Route::get('/unactive-brand-product/{brand_product_id}', 'BrandProduct@unactive_brand_product');
Route::get('/active-brand-product/{brand_product_id}', 'BrandProduct@active_brand_product');

Route::post('/save-brand-product', 'BrandProduct@save_brand_product');
Route::post('/update-brand-product/{brand_product_id}', 'BrandProduct@update_brand_product');

// Product
Route::get('/add-product', 'ProductController@add_product');
Route::get('/edit-product/{product_id}', 'ProductController@edit_product');
Route::get('/delete-product/{product_id}', 'ProductController@delete_product');
Route::get('/all-product', 'ProductController@all_product');

Route::get('/unactive-product/{product_id}', 'ProductController@unactive_product');
Route::get('/active-product/{product_id}', 'ProductController@active_product');

Route::post('/save-product', 'ProductController@save_product');
Route::post('/update-product/{product_id}', 'ProductController@update_product');

// Cart
Route::post('/save-cart', 'CartController@save_cart');
Route::get('/show-cart', 'CartController@show_cart');
Route::get('/delete-to-cart/{rowId}', 'CartController@delete_to_cart');
Route::post('/update-cart-quantity', 'CartController@update_cart_quantity');

Route::post('/add-cart-ajax', 'CartController@add_cart_ajax');
Route::get('/gio-hang', 'CartController@show_cart_ajax');
Route::post('/cap-nhat-gio-hang', 'CartController@update_cart');
Route::get('/xoa-san-pham/{session_id}', 'CartController@delete_product_ajax');
Route::get('/xoa-tat-ca-san-pham', 'CartController@delete_all_product_ajax');

// Coupon
Route::post('/kiem-tra-ma-giam-gia', 'CartController@check_coupon');
Route::get('/bo-ma-giam-gia', 'CartController@unset_coupon');

// admin coupon
Route::get('/hien-thi-them-ma-giam-gia', 'CouponController@add_coupon');
Route::post('/them-ma-giam-gia', 'CouponController@add_coupon_code');
Route::get('/danh-sach-ma-giam-gia', 'CouponController@all_coupon');
Route::get('/xoa-ma-giam-gia/{coupon_id}', 'CouponController@delete_coupon');

// Checkout
Route::get('/login-checkout', 'CheckoutController@login_checkout');
Route::get('/logout-checkout', 'CheckoutController@logout_checkout');
Route::post('/add-customer', 'CheckoutController@add_customer');
Route::post('/login-customer', 'CheckoutController@login_customer');
Route::get('/checkout', 'CheckoutController@checkout');
Route::post('/save-checkout-customer', 'CheckoutController@save_checkout_customer');
Route::get('/payment', 'CheckoutController@payment');
Route::post('/order-place', 'CheckoutController@order_place');
Route::post('/select-delivery-checkout', 'CheckoutController@select_delivery_checkout');
Route::post('/calculate-fee', 'CheckoutController@calculate_fee');
Route::get('/delete-fee', 'CheckoutController@delete_fee');
Route::post('/confirm-order', 'CheckoutController@confirm_order');

// Order
Route::get('/manage-order', 'OrderController@manage_order');
Route::get('/view-order/{order_code}', 'OrderController@view_order');
Route::get('/print-order/{order_code}', 'OrderController@print_order');
Route::post('/update-order-status', 'OrderController@update_order_status');
Route::post('/update-qty-order', 'OrderController@update_qty_order');

/* Route::get('/manage-order', 'CheckoutController@manage_order');
Route::get('/edit-order/{orderId}', 'CheckoutController@edit_order'); */

// Send Mail
Route::get('/send-mail', 'MailController@send_mail');

// Login Facebook
Route::get('/login-facebook', 'AdminController@login_facebook');
Route::get('/admin/callback', 'AdminController@callback_facebook');

// Login Google
Route::get('/login-google', 'AdminController@login_google');
Route::get('/google/callback', 'AdminController@callback_google');

// Delivery
Route::get('/delivery', 'DeliveryController@delivery');
Route::post('/select-delivery', 'DeliveryController@select_delivery');
Route::post('/add-delivery', 'DeliveryController@add_delivery');
Route::post('/select-feeship', 'DeliveryController@select_feeship');
Route::post('/update-delivery', 'DeliveryController@update_delivery');

// banner
Route::get('/manage-banner', 'BannerController@manage_banner');
Route::get('/add-banner', 'BannerController@add_banner');
Route::post('/save-banner', 'BannerController@save_banner');
Route::get('/unactive-banner/{banner_id}', 'BannerController@unactive_banner');
Route::get('/active-banner/{banner_id}', 'BannerController@active_banner');
