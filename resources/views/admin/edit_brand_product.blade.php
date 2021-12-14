@extends('admin_layout')

@section('admin_content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Cập nhật thương hiệu sản phẩm
                </header>
                <div class="panel-body">
                    <?php
                    $message = Session::get('message');
                    if ($message) {
                        echo $message;
                        Session::get('message', null);
                    }
                    
                    ?>
                    @foreach ($edit_brand_product as $key => $edit_value)
                        <div class="position-center">
                            <form role="form"
                                action="{{ URL::to('/update-brand-product/' . $edit_value->brand_id) }}"
                                method="post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên thương hiệu</label>
                                    <input type="text" value="{{ $edit_value->brand_name }}"
                                        name="brand_product_name" class="form-control" id="exampleInputEmail1"
                                        placeholder="Tên thương hiệu" />
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mô tả thương hiệu</label>
                                    <textarea style="resize: none;" rows="5" type="password" name="brand_product_desc"
                                        class="form-control" id="exampleInputPassword1"
                                        placeholder="Mô tả thương hiệu">{{ $edit_value->brand_desc }}</textarea>
                                </div>
                                <button type="submit" name="update_brand_product" class="btn btn-info">Cập nhật danh
                                    mục</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

    </div>
@endsection
