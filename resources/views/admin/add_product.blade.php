@extends('admin_layout') @section('admin_content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Thêm sản phẩm
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
                        <form role="form" action="{{ URL::to('/save-product') }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tên sản phẩm</label>
                                <input type="text" name="product_name" class="form-control" id="exampleInputEmail1"
                                    placeholder="Tên sản phẩm" />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Số lượng sản phẩm</label>
                                <input type="text" name="product_quantity" class="form-control" id="exampleInputEmail1"
                                    placeholder="Só lượng sản phẩm" />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Giá sản phẩm</label>
                                <input type="text" name="product_price" class="form-control" id="exampleInputEmail1"
                                    placeholder="Giá sản phẩm" />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Hình ảnh sản phẩm</label>
                                <input type="file" name="product_image" class="form-control" id="exampleInputEmail1" />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Mô tả sản phẩm</label>
                                <textarea style="resize: none;" rows="5" type="password" name="product_desc"
                                    class="form-control" id="ckeditor1"
                                    placeholder="Mô tả sản phẩm"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Nội dung sản phẩm</label>
                                <textarea style="resize: none;" rows="5" type="password" name="product_content"
                                    class="form-control" id="ckeditor2"
                                    placeholder="Nội dung sản phẩm"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Danh mục sản phẩm</label>
                                <select name="category_id" class="form-control input-sm m-bot15">
                                    @foreach ($cate_product as $key => $cate)
                                        <option value="{{ $cate->category_id }}">{{ $cate->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Thương hiệu sản phẩm</label>
                                <select name="brand_id" class="form-control input-sm m-bot15">
                                    @foreach ($brand_product as $key => $brand)
                                        <option value="{{ $brand->brand_id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Hiển thị</label>
                                <select name="product_status" class="form-control input-sm m-bot15">
                                    <option value="0">Ẩn</option>
                                    <option value="1">Hiển thị</option>
                                </select>
                            </div>
                            <button type="submit" name="add_product" class="btn btn-info">Thêm sản phẩm</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>

    </div>
@endsection
