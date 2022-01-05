<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feeship extends Model
{
    public $timestamps = false;     // set time to false 
    protected $fillable = ['fee_matp', 'fee_maqh', 'fee_xaid', 'fee_ship'];
    protected $primaryKey = 'fee_id';
    protected $table = 'tbl_shipping_fee';
}
