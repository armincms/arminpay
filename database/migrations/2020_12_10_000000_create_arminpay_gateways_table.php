<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->boolean('enabled')->default(false);
            $table->string('driver');
            $table->json('name')->nullable();
            $table->json('config')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('arminpay_gateways');
    }
}
