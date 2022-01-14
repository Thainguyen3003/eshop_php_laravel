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
                            <th>Số lượng kho</th>
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
                                <td>{{ $detail->product->product_quantity }}</td>
                                <td>
                                    @if ($detail->product_coupon != 'no')
                                        {{ $detail->product_coupon }}
                                    @else
                                        Không có mã giảm giá
                                    @endif
                                </td>
                                <td>
                                    <input type="number" min="1" value="{{ $detail->product_sales_quantity }}" name="product_sales_quantity">
                                    <input type="hidden" name="order_product_id" class="order_product_id" value="{{ $detail->product_id }}">
                                    <button class="btn btn-default" name="update-quantity">Cập nhật</button>
                                </td>
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
                            <td></td>
                            <td>{{ number_format($coupon->coupon_money, 0, ',', '.') . ' VNĐ' }}</td>
                        </tr>
                        <tr>
                            <td>Phí ship</td>
                            <td></td>
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
                            <td></td>
                            <td>{{ number_format($total_final, 0, ',', '.') . ' VNĐ' }}</td>
                        </tr>
                        <tr>
                            <td colspan="7">
                                <form action="" method="POST">
                                    @csrf
                                    <select class="form-control order_status" name="order_status">
                                        <option value="">-----Chọn hình thức đơn hàng-----</option>
                                        <option id="{{ $order->order_id }}" value="1">Chưa xử lí</option>
                                        <option id="{{ $order->order_id }}" value="2">Đã xử lý - Đã giao hàng</option>
                                        <option id="{{ $order->order_id }}" value="3">Hủy đơn hàng - tạm giữ</option>
                                    </select>
                                </form>
                            </td>

                        </tr>
                    </tbody>
                </table>

                <a target="_blank" href="{{ url('/print-order/' . $order_code) }}">In đơn hàng</a>
            </div>
        </div>
    </div>
@endsection
