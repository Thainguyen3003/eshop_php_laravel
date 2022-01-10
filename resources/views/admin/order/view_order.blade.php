@extends('admin_layout')
@section('admin_content')

    <div class="table-agile-info">
        <div class="panel panel-default">
            <div class="panel-heading">
                Thông tin đăng nhập
            </div>

            <div class="table-responsive">
       
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>Tên khách hàng</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $customer->customer_name }}</td>
                            <td>{{ $customer->customer_email }}</td>
                            <td>{{ $customer->customer_phone }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <br>

    <div class="table-agile-info">
        <div class="panel panel-default">
            <div class="panel-heading">
                Thông tin vận chuyển hàng
            </div>

            <div class="table-responsive">
    
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>Tên người vận chuyển</th>
                            <th>Số điện thoại</th>
                            <th>Địa chỉ</th>
                            <th>Ghi chú</th>
                            <th>Hình thức thanh toán</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $shipping->shipping_name }}</td>
                            <td>{{ $shipping->shipping_phone }}</td>
                            <td>{{ $shipping->shipping_address }}</td>
                            <td>{{ $shipping->shipping_notes }}</td>
                            <td>
                                @if ($shipping->shipping_method == 0)
                                    Chuyển khoản
                                @else
                                    Tiền mặt
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <br>
    <div class="table-agile-info">
        <div class="panel panel-default">
            <div class="panel-heading">
                LIỆT KÊ CHI TIẾT ĐƠN HÀNG
            </div>

            <div class="table-responsive">
    
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>Thứ tự</th>
                            <th>Tên sản phẩm</th>
                            <th>Mã giảm giá</th>
                            <th>Số lượng</th>
                            <th>Giá sản phẩm</th>
                            <th>Tổng tiền</th>
                            <th style="width:30px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @foreach ($order_details as $key => $detail)
                            @php
                                $subtotal = $detail->product_price * $detail->product_sales_quantity;
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $detail->product_name }}</td>
                                <td>
                                    @if ($detail->product_coupon != 'no')
                                        {{ $detail->product_coupon }}
                                    @else
                                        Không có mã giảm giá
                                    @endif
                                </td>
                                <td>{{ $detail->product_sales_quantity }}</td>
                                <td>{{ number_format($detail->product_price, 0, ',', '.') . ' VNĐ' }}</td>
                                <td>{{ number_format($subtotal, 0, ',', '.') . ' VNĐ' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>Mã giảm giá được giảm</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ number_format($coupon->coupon_money, 0, ',', '.') . ' VNĐ' }}</td>
                        </tr>
                        <tr>
                            <td>Phí ship</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ number_format($product_feeship, 0, ',', '.') . ' VNĐ' }}</td>
                        </tr>
                        <tr>
                            <td>Thanh toán</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ number_format($total_final, 0, ',', '.') . ' VNĐ' }}</td>
                        </tr>
                    </tbody>
                </table>
                <a href="{{ url('/print-order/' .$order_code) }}">In đơn hàng</a>
            </div>
        </div>
    </div>
@endsection
