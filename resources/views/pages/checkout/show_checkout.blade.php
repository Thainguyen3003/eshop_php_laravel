@extends('layout')
@section('content')

    <section id="cart_items">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="#">Trang chủ</a></li>
                    <li class="active">Giỏ hàng của bạn</li>
                </ol>
            </div>
            <!--/breadcrums-->

            <div class="register-req">
                <p>Làm ơn đăng ký hoặc đăng nhập để thanh toán giỏ hàng và xem lại lịch sử mua hàng</p>
            </div>
            <!--/register-req-->

            <div class="shopper-informations">
                <div class="row">
                    <div class="col-sm-12 clearfix">
                        <div class="bill-to">
                            <p>Điền thông tin gửi hàng</p>
                            <div class="form-one">
                                <form action="{{ URL::to('/save-checkout-customer') }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="text" name="shipping_name" class="shipping_name" placeholder="Họ và tên *">
                                    <input type="text" name="shipping_email" class="shipping_email" placeholder="Email*">
                                    <input type="text" name="shipping_address" class="shipping_address" placeholder="Địa chỉ">
                                    <input type="text" name="shipping_phone" class="shipping_phone" placeholder="Phone *">
                                    <textarea name="shipping_notes" class="shipping_notes" placeholder="Ghi chú đơn hàng của bạn"
                                        rows="5"></textarea>
                                    
                                    @if (Session::get('fee'))
                                        <input type="hidden" name="order_coupon" class="order_coupon" value="{{ Session::get('fee') }}">
                                    @else
                                        <input type="hidden" name="order_coupon" class="order_coupon" value="30000">
                                    @endif

                                    @if (Session::get('coupon'))
                                        @foreach (Session::get('coupon') as $key => $cou)
                                            @if ($cou['coupon_feat'] == 1)
                                                <input type="hidden" name="order_fee" class="order_fee" value="{{ $cou['coupon_code'] }}">
                                            @else
                                                <input type="hidden" name="order_fee" class="order_fee" value="{{ $cou['coupon_code'] }}">
                                            @endif
                                        @endforeach
                                    @else
                                        <input type="hidden" name="order_fee" class="order_fee" class="no">
                                    @endif

                                    <div class="payment-option">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Chọn hình thức thanh toán</label>
                                            <select name="shipping_method" id="city" class="form-control input-sm m-bot15 shipping_method">
                                                <option value="0">Chuyển khoản</option>
                                                <option value="1">Tiền mặt</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="button" value="Xác nhận đơn hàng" name="send_order"
                                        class="btn btn-primary btn-sm send_order">
                                </form>

                                <form>
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Chọn thành phố</label>
                                        <select name="city" id="city" class="form-control input-sm m-bot15 choose city">
                                            <option value="">--Chọn tỉnh, thành phố--</option>
                                            @foreach ($list_cities as $key => $city)
                                                <option value="{{ $city->matp }}">{{ $city->name_thanhpho }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Chọn quận huyện</label>
                                        <select name="district" id="district"
                                            class="form-control input-sm m-bot15 choose district">
                                            <option value="">--Chọn quận, huyện--</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Chọn xã, phường, thị trấn</label>
                                        <select name="ward" id="ward" class="form-control input-sm m-bot15 ward">
                                            <option value="">--Chọn xã phường thị trấn--</option>
                                        </select>
                                    </div>

                                    <input type="button" value="Tính phí vận chuyển" name="calculate_order"
                                        class="btn btn-primary btn-sm calculate-order">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 clearfix">
                        <div class="table-responsive cart_info">
                            <form action="{{ url('/cap-nhat-gio-hang') }}" method="post">
                                {{ csrf_field() }}
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
                                        @if (Session::get('cart') == true)


                                            @foreach (Session::get('cart') as $key => $cart)
                                                @php
                                                    $subtotal = $cart['product_price'] * $cart['product_qty'];
                                                    $total += $subtotal;
                                                @endphp
                                                <tr>
                                                    <td class="cart_product">
                                                        <a href=""><img
                                                                src="{{ asset('public/uploads/product/' . $cart['product_image']) }}"
                                                                width="100" alt="{{ $cart['product_name'] }}"></a>
                                                    </td>
                                                    <td class="cart_description">
                                                        <h4><a href=""></a></h4>
                                                        <p>{{ $cart['product_name'] }}</p>
                                                    </td>
                                                    <td class="cart_price">
                                                        <p>{{ number_format($cart['product_price'], 0, ',', '.') }} VNĐ
                                                        </p>
                                                    </td>
                                                    <td class="cart_quantity">
                                                        <div class="cart_quantity_button">

                                                            <input class="cart_quantity_input" type="number" min="1"
                                                                name="cart_qty[{{ $cart['session_id'] }}]"
                                                                value="{{ $cart['product_qty'] }}" autocomplete="off"
                                                                size="2">

                                                        </div>
                                                    </td>
                                                    <td class="cart_total">
                                                        <p class="cart_total_price">
                                                            {{ number_format($subtotal, 0, ',', '.') }} VNĐ
                                                        </p>
                                                    </td>
                                                    <td class="cart_delete">
                                                        <a class="cart_quantity_delete"
                                                            href="{{ url('/xoa-san-pham/' . $cart['session_id']) }}"><i
                                                                class="fa fa-times"></i></a>
                                                    </td>
                                                </tr>

                                            @endforeach
                                            <tr>
                                                <td>
                                                    <input type="submit" value="Cập nhật giỏ hàng" name="update_qty"
                                                        class="btn btn-default check_out">
                                                </td>
                                            </tr>

                                        @else
                                            <tr>
                                                <td colspan="5">
                                                    <center>
                                                        Chưa có sản phẩm nào trong giỏ hàng
                                                    </center>
                                                </td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </form>
                        </div>

                        <section id="do_action">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-6">
                                        @if (Session::get('cart'))
                                            <div class="total_area">
                                                <ul>
                                                    <li>Tổng <span>{{ number_format($total, 0, ',', '.') }} VNĐ</span>
                                                    </li>
                                                    @if (Session::get('coupon'))
                                                        <li>Mã giảm giá
                                                            <span>
                                                                @foreach (Session::get('coupon') as $key => $cou)
                                                                    @if ($cou['coupon_feat'] == 1)
                                                                        {{ $cou['coupon_code'] . ' - ' . $cou['coupon_money'] }}
                                                                        %
                                                                    @else
                                                                        {{ $cou['coupon_code'] . ' - ' . $cou['coupon_money'] }}
                                                                        VNĐ
                                                                    @endif
                                                                @endforeach
                                                            </span>
                                                        </li>
                                                        <li>
                                                            Mã được giảm
                                                            <span>
                                                                @php
                                                                    if ($cou['coupon_feat'] == 1) {
                                                                        $coupon_money = ($total * $cou['coupon_money']) / 100;
                                                                        echo number_format($coupon_money, 0, ',', '.') . ' VNĐ';
                                                                    } else {
                                                                        $coupon_money = $cou['coupon_money'];
                                                                        echo number_format($cou['coupon_money'], 0, ',', '.') . ' VNĐ';
                                                                    }
                                                                    
                                                                @endphp
                                                            </span>
                                                        </li>
                                                        <li>
                                                            Tổng tiền đã áp dụng mã giảm giá
                                                            <span>
                                                                @php
                                                                    $total_coupon = $total - $coupon_money;
                                                                    echo number_format($total_coupon, 0, ',', '.') . ' VNĐ';
                                                                @endphp
                                                            </span>
                                                        </li>
                                                    @else
                                                        <li>
                                                            Mã giảm giá
                                                            <span>
                                                                Hãy nhập mã giảm giá ở bên dưới
                                                            </span>
                                                        </li>

                                                    @endif
                                                    <li>Thuế <span></span></li>
                                                    <li>
                                                        Phí vận chuyển
                                                        @if (Session::get('fee'))
                                                            <span>
                                                                @php
                                                                    $fee = Session::get('fee');
                                                                    echo number_format($fee, 0, ',', '.') . ' VNĐ';
                                                                @endphp
                                                                <a class="cart_quantity_delete"
                                                                    href="{{ url('/delete-fee') }}"><i
                                                                        class="fa fa-times"></i></a>
                                                            </span>
                                                        @else
                                                            <span>Hãy chọn địa chỉ</span>
                                                        @endif
                                                    </li>
                                                    <li>
                                                        Thành tiền
                                                        <span>
                                                            @if (Session::get('fee'))
                                                                @php
                                                                    $total_final = $total_coupon + $fee;
                                                                    echo number_format($total_final, 0, ',', '.') . ' VNĐ';
                                                                @endphp
                                                            @endif
                                                        </span>
                                                    </li>
                                                    <form action="{{ url('/kiem-tra-ma-giam-gia') }}" method="post">
                                                        @csrf
                                                        <br />
                                                        <input type="text" class="form-control" name="coupon"
                                                            placeholder="Nhập mã giảm giá"><br />
                                                        <input type="submit" class="btn btn-warning check_coupon"
                                                            name="check_coupon" value="Tính mã giảm giá">
                                                        @if (Session::get('coupon'))
                                                            <a class="btn btn-default"
                                                                onclick="return confirm('Bạn có chắc là muốn bỏ sử dụng mã giảm giá ?')"
                                                                href="{{ url('/bo-ma-giam-gia') }}">Bỏ mã giảm giá</a>
                                                        @endif
                                                    </form>
                                                </ul>
                                                {{-- <a class="btn btn-default update" href="">Update</a> --}}

                                                <a class="btn btn-default check_out" href="">Thanh toán</a>
                                                <a class="btn btn-default check_out"
                                                    onclick="return confirm('Bạn có chắc là muốn tất cả sản phẩm trong giỏ này không ?')"
                                                    href="{{ url('/xoa-tat-ca-san-pham') }}">Xóa tất cả</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <div class="review-payment">
                <h2>Xem lại giỏ hàng</h2>
            </div>

            <div class="payment-options">
                <span>
                    <label><input type="checkbox"> Direct Bank Transfer</label>
                </span>
                <span>
                    <label><input type="checkbox"> Check Payment</label>
                </span>
                <span>
                    <label><input type="checkbox"> Paypal</label>
                </span>
            </div>
        </div>
    </section>
    <!--/#cart_items-->

@endsection
