<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;
use App\District;
use App\Ward;
use App\Feeship;

class DeliveryController extends Controller
{
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
        $fee_ship = new Feeship();
        $fee_ship->fee_matp = $data['city'];
        $fee_ship->fee_maqh = $data['district'];
        $fee_ship->fee_xaid = $data['ward'];
        $fee_ship->fee_ship = $data['fee_ship'];
        $fee_ship->save();
    }
}
