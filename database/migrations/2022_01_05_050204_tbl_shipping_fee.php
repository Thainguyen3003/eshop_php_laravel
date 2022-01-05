<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblShippingFee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_shipping_fee', function (Blueprint $table) {
            $table->Increments('fee_id');
            $table->integer('fee_matp');
            $table->integer('fee_maqh');
            $table->integer('fee_xaid');
            $table->string('fee_ship');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_shipping_fee');
    }
}
