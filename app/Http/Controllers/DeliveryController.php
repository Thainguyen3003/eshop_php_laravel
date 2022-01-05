<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;
use App\District;
use App\Ward;
use App\Feeship;

class DeliveryController extends Controller
{
    public function update_delivery(Request $request) {
        $data = $request->all();
        $fee_ship = Feeship::find($data['feeship_id']);
        $fee_ship_value = rtrim($data['fee_ship'], '.');
        $fee_ship->fee_ship = $fee_ship_value;
        $fee_ship->save();
    }

    public function select_feeship() {
        $feeship = Feeship::orderby('fee_id', 'desc')->get();
        $output = '';
        $output .= '<div class="table-responsive">
            <table class="table table-bordered">
                <thread>
                    <tr>
                        <th>Tên thành phố</th>
                        <th>Tên quận huyện</th>
                        <th>Tên xã, phường, thị trấn</th>
                        <th>Phí ship (VNĐ)</th>
                    </tr>
                </thread>
                <tbody>
                ';
                foreach ($feeship as $key => $fee) {
                    $output .='
                        <tr>
                            <td>'.$fee->city->name_thanhpho.'</td>
                            <td>'.$fee->district->name_quanhuyen.'</td>
                            <td>'.$fee->ward->name_xaphuong.'</td>
                            <td contenteditable data-feeship_id="'.$fee->fee_id.'" class="fee_ship_edit">'.number_format($fee->fee_ship, 0, ',', '.').'</td>
                        </tr>
                    ';
                }
                    
                $output .='
                </tbody>
            </table>
        </div>';
        
        echo $output;
    }

    public function delivery(Request $request) {
        $list_cities = City::orderby('matp', 'desc')->get();
        return view('admin.delivery.add_delivery', compact('list_cities'));
    }

    public function select_delivery(Request $request) {
        $data = $request->all();
        $option = '';
        if($data['action']) {
            if($data['action'] == 'city') {
                $select_district = District::where('matp', $data['ma_id'])->orderby('maqh', 'asc')->get();
                $option .= '<option>---Chọn quận, huyện---</option>';
                foreach ($select_district as $key => $district) {
                    $option .= '<option value="'.$district->maqh.'">'.$district->name_quanhuyen.'</option>';
                }
            } else {
                $select_ward = Ward::where('maqh', $data['ma_id'])->orderby('xaid', 'asc')->get();
                $option .= '<option>---Chọn xã, phường, thị trấn---</option>';
                foreach ($select_ward as $key => $ward) {
                    $option .= '<option value="'.$ward->xaid.'">'.$ward->name_xaphuong.'</option>';
                }
            }
        }
        echo $option;
    }

    public function add_delivery(Request $request) {
        $data = $request->all();
        /* $check_fee_Ship = Feeship::where('fee_matp', $data['city'])->get();
        print_r($check_fee_Ship);
        if ($check_fee_Ship->fee_matp == $data['city'] && $check_fee_Ship->fee_maqh == $data['district'] && $check_fee_Ship->fee_xaid == $data['ward']) {
            $check_fee_Ship->fee_ship = $data['fee_ship'];
            $check_fee_Ship->save();
        } else { */
            $fee_ship = new Feeship();
            $fee_ship->fee_matp = $data['city'];
            $fee_ship->fee_maqh = $data['district'];
            $fee_ship->fee_xaid = $data['ward'];
            $fee_ship->fee_ship = $data['fee_ship'];
            $fee_ship->save();
        /* } */
        
    }
}
