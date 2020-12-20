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
            $table->unsignedBigInteger('gateway_id'); 
            $table->morphs('billable'); 
            $table->longPrice('amount');
            $table->string('currency')->default('IRR');
            $table->enum('marked_as', Helper::statuses())->default(head(Helper::statuses()));
            $table->string('tracking_code')->unique()->primary();
            $table->string('callback_url')->nullable();
            $table->string('reference_number')->nullable();
            $table->longText('exception')->nullable();
            $table->json('payload')->nullable();
            $table->softDeletes(); 
            $table->timestamps(); 

            $table
                ->foreign('gateway_id')
                ->references('id')
                ->on('arminpay_gateways');
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
