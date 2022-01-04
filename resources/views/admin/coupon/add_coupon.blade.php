@extends('admin_layout') @section('admin_content')

<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Thêm mã giảm giá
            </header>
            <div class="panel-body">
                <?php
                    $message = Session::get('message');
                    if ($message) {
                        echo $message;
                        Session::get('message', null);
                    }

                ?>
                <div class="position-center">
                    <form role="form" action="{{ URL::to('/them-ma-giam-gia') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="exampleInputEmail1">Tên mã giảm giá</label>
                            <input type="text" name="coupon_name" class="form-control" id="exampleInputEmail1"/>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Mã giảm giá</label>
                            <input type="text" name="coupon_code" class="form-control" id="exampleInputEmail1"/>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Mô tả mã giảm giá</label>
                            <textarea style="resize: none;" rows="5" type="password" name="coupon_desc" class="form-control" id="exampleInputPassword1" placeholder="Mô tả danh mục"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Số lượng mã giảm giá</label>
                            <input type="number" name="coupon_qty" class="form-control" id="exampleInputEmail1" min="1"/>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Tính năng mã</label>
                            <select name="coupon_feat" class="form-control input-sm m-bot15">
                                <option value="0">----Chọn----</option>
                                <option value="1">Giảm theo phần trăm</option>
                                <option value="2">Giảm theo tiền</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Nhập số % hoặc tiền giảm</label>
                            <input type="text" name="coupon_money" class="form-control" id="exampleInputEmail1" min="1"/>
                        </div>
                        
                        <button type="submit" name="add_coupon" class="btn btn-info">Thêm mã giảm giá</button>
                    </form>
                </div>
            </div>
        </section>
    </div>

</div>
@endsection
