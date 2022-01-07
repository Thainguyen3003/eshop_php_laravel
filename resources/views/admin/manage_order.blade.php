@extends('admin_layout') 
@section('admin_content')

<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      LIỆT KÊ THƯƠNG HIỆU SẢN PHẨM
    </div>
    <div class="row w3-res-tb">

    </div>
    <div class="table-responsive">
    <?php
      $message = Session::get('message');
      if ($message) {
        echo $message;
        Session::get('message', null);
      }
    ?>
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Thứ tự</th>
            <th>Mã đơn hàng</th>
            <th>Tình trạng đơn hàng</th>
          </tr>
        </thead>
        <tbody>
          @foreach($list_order as $key => $order)
          <tr>
            <td><i>{{$key}}</i></td>
            <td>{{ $order->order_code }}</td>

            <td>
                @if ($order->order_status == 1)
                    Đơn hàng mới
                @else
                    Đơn hàng đã xử lí
                @endif
            </td>
            
            <td>
              <a href="{{ URL::to('/view-order/' .$order->order_code) }}" class="active styling-edit" ui-toggle-class="">
                <i class="fa fa-eye text-success text-active"></i></a>
              <a onclick="return confirm('Bạn có chắc là muốn xóa danh mục này không ?')" href="{{ URL::to('/delete-order/' .$order->order_code) }}" class="active styling-edit" ui-toggle-class="">
                <i class="fa fa-times text-danger text"></i>
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
