<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    public $timestamps = false;     // set time to false 
    protected $fillable = ['coupon_name', 'coupon_code', 'coupon_desc', 'coupon_qty', 'coupon_feat', 'coupon_money'];
    protected $primaryKey = 'coupon_id';
    protected $table = 'tbl_coupon';
}
