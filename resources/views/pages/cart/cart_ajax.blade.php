@extends('layout')
@section('content')

    <section id="cart_items">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="{{ URL::to('/') }}">Trang chủ</a></li>
                    <li class="active">Giỏ hàng của bạn</li>
                </ol>
            </div>
            <div class="table-responsive cart_info">
                <table class="table table-condensed">
                    <thead>
                        <tr class="cart_menu">
                            <td class="image">Hình ảnh</td>
                            <td class="description">Tên sản phẩm</td>
                            <td class="price">Giá</td>
                            <td class="quantity">Số lượng</td>
                            <td class="total">Thành tiền</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @foreach (Session::get('cart') as $key => $cart)
                            @php
                                $subtotal = $cart['product_price'] * $cart['product_qty'];
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td class="cart_product">
                                    <a href=""><img
                                            src="{{asset('public/uploads/product/' .$cart['product_image'])}}"
                                            width="100" alt="{{ $cart['product_name'] }}"></a>
                                </td>
                                <td class="cart_description">
                                    <h4><a href=""></a></h4>
                                    <p>{{ $cart['product_name'] }}</p>
                                </td>
                                <td class="cart_price">
                                    <p>{{ number_format($cart['product_price'], 0, ',', '.') }} VNĐ</p>
                                </td>
                                <td class="cart_quantity">
                                    <div class="cart_quantity_button">
                                        <form action="" method="post">
                                            {{ csrf_field() }}
                                            <input class="cart_quantity_input" type="number" min="1" name="cart_quantity"
                                                value="{{$cart['product_qty']}}" autocomplete="off" size="2">
                                            <input type="submit" value="Cập nhật" name="update_qty"
                                                class="btn btn-default btn-sm">
                                        </form>
                                    </div>
                                </td>
                                <td class="cart_total">
                                    <p class="cart_total_price">
                                        {{ number_format($subtotal, 0, ',', '.') }} VNĐ
                                    </p>
                                </td>
                                <td class="cart_delete">
                                    <a class="cart_quantity_delete"
                                        href=""><i
                                            class="fa fa-times"></i></a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!--/#cart_items-->

    <section id="do_action">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="total_area">
                        <ul>
                            <li>Tổng <span>{{ number_format($total, 0, ',', '.') }} VNĐ</span></li>
                            <li>Thuế <span></span></li>
                            <li>Phí vận chuyển <span>Free</span></li>
                            <li>Thành tiền <span></span></li>
                        </ul>
                        {{-- <a class="btn btn-default update" href="">Update</a> --}}
                        <a class="btn btn-default check_out" href="">Thanh toán</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/#do_action-->

@endsection
