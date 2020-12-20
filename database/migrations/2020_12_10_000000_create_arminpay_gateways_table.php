<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArminpayGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arminpay_gateways', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->boolean('enabled')->default(false);
            $table->string('driver');
            $table->json('name')->nullable();       
            $table->json('config')->nullable(); 
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('arminpay_gateways');
    }
}
