<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public $timestamps = false;     // set time to false 
    protected $fillable = ['name_quanhuyen', 'type', 'matp'];
    protected $primaryKey = 'maqh';
    protected $table = 'tbl_quanhuyen';
}
