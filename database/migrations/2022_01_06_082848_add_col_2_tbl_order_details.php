<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCol2TblOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_order_details', function (Blueprint $table) {
            $table->string('product_coupon');
            $table->string('product_feeship');   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_order_details', function (Blueprint $table) {
            $table->dropColumn('product_coupon');
            $table->dropColumn('product_feeship');
        });
    }
}
