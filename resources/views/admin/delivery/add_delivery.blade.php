@extends('admin_layout') @section('admin_content')

<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Thêm vận chuyển
            </header>
            <div class="panel-body">
                <div class="position-center">
                    <form>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="exampleInputPassword1">Chọn thành phố</label>
                            <select name="city" id="city" class="form-control input-sm m-bot15 choose city">
                                <option>--Chọn tỉnh, thành phố--</option>
                                @foreach ($list_cities as $key => $city)
                                    <option value="{{ $city->matp }}">{{ $city->name_thanhpho }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Chọn quận huyện</label>
                            <select name="district" id="district" class="form-control input-sm m-bot15 choose district">
                                <option>--Chọn quận, huyện--</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Chọn xã, phường, thị trấn</label>
                            <select name="ward" id="ward" class="form-control input-sm m-bot15 ward">
                                <option>--Chọn xã phường thị trấn--</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Phí vận chuyển</label>
                            <input type="text" name="fee_ship" class="form-control fee_ship" id="exampleInputEmail1"/>
                        </div>
                        <button type="button" name="add_delivery" class="btn btn-info add_delivery">Thêm phí vận chuyển</button>
                    </form>
                </div>
            </div>
        </section>
    </div>

</div>
@endsection
