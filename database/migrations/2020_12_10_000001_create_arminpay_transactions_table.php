<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Armincms\Arminpay\Helper;

class CreateArminpayTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arminpay_transactions', function (Blueprint $table) { 
            $table->foreignId('gateway_id')->constrained('arminpay_gateways'); 
            $table->morphs('billable'); 
            $table->longPrice('amount');
            $table->string('currency')->default('IRR');
            $table->markable();
            $table->string('tracking_code')->unique()->primary();
            $table->string('callback_url')->nullable();
            $table->string('reference_number')->nullable();
            $table->longText('exception')->nullable();
            $table->json('payload')->nullable();
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
        Schema::dropIfExists('arminpay_transactions');
    }
}
