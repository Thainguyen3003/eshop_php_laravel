<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feeship extends Model
{
    public $timestamps = false;     // set time to false 
    protected $fillable = ['fee_matp', 'fee_maqh', 'fee_xaid', 'fee_ship'];
    protected $primaryKey = 'fee_id';
    protected $table = 'tbl_shipping_fee';

    public function city() {
        return $this->belongsTo('App\City', 'fee_matp');
    }

    public function district() {
        return $this->belongsTo('App\District', 'fee_maqh');
    }

    public function ward() {
        return $this->belongsTo('App\Ward', 'fee_xaid');
    }
}
