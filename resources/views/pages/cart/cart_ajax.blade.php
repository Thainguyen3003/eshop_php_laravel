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

            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @elseif (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif


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
                                            <p>{{ number_format($cart['product_price'], 0, ',', '.') }} VNĐ</p>
                                        </td>
                                        <td class="cart_quantity">
                                            <div class="cart_quantity_button">

                                                <input class="cart_quantity_input" type="number" min="1"
                                                    name="cart_qty[{{ $cart['session_id'] }}]"
                                                    value="{{ $cart['product_qty'] }}" autocomplete="off" size="2">

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
        </div>
    </section>
    <!--/#cart_items-->

    <section id="do_action">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    @if (Session::get('cart'))
                        <div class="total_area">
                            <ul>
                                <li>Tổng <span>{{ number_format($total, 0, ',', '.') }} VNĐ</span></li>
                                @if (Session::get('coupon'))
                                    <li>Mã giảm giá
                                        <span>
                                                @foreach (Session::get('coupon') as $key => $cou)
                                                    @if($cou['coupon_feat'] == 1)
                                                        {{ $cou['coupon_code'] .' - '. $cou['coupon_money'] }} %  
                                                    @else
                                                        {{ $cou['coupon_code'] .' - '. $cou['coupon_money'] }} VNĐ
                                                    @endif
                                                @endforeach                                            
                                        </span>
                                    </li>
                                    <li>
                                        Mã được giảm
                                        <span>
                                            @php
                                                if ($cou['coupon_feat'] == 1) {
                                                    $coupon_money = ($total * $cou['coupon_money'])/100;
                                                    echo number_format($coupon_money, 0, ',', '.') .' VNĐ';
                                                } else {
                                                    $coupon_money = $cou['coupon_money'];
                                                    echo number_format($cou['coupon_money'], 0, ',', '.') .' VNĐ';
                                                }
                                                
                                            @endphp
                                        </span>
                                    </li>
                                    <li>
                                        Tổng tiền đã áp dụng mã giảm giá
                                        <span>
                                            @php
                                                $total_coupon = $total - $coupon_money;
                                                echo number_format($total_coupon, 0, ',', '.') . ' VNĐ' 
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
                                <li>Phí vận chuyển <span>Free</span></li>
                                <li>Thành tiền <span></span></li>
                                <form action="{{ url('/kiem-tra-ma-giam-gia') }}" method="post">
                                    @csrf
                                    <br/>
                                    <input type="text" class="form-control" name="coupon" placeholder="Nhập mã giảm giá"><br/>
                                    <input type="submit" class="btn btn-warning check_coupon" name="check_coupon" value="Tính mã giảm giá">
                                    @if (Session::get('coupon'))
                                        <a class="btn btn-default" onclick="return confirm('Bạn có chắc là muốn bỏ sử dụng mã giảm giá ?')" href="{{ url('/bo-ma-giam-gia') }}">Bỏ mã giảm giá</a>
                                    @endif
                                </form>
                            </ul>
                            {{-- <a class="btn btn-default update" href="">Update</a> --}}
                            
                            <a class="btn btn-default check_out" href="">Thanh toán</a>
                            <a class="btn btn-default check_out" onclick="return confirm('Bạn có chắc là muốn tất cả sản phẩm trong giỏ này không ?')" href="{{ url('/xoa-tat-ca-san-pham') }}">Xóa tất cả</a>
                        </div> 
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!--/#do_action-->

@endsection
