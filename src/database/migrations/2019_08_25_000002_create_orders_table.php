<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Component\Arminpay\Order;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('order_detail')->nullable();
            $table->longText('purchase_detail')->nullable(); 
            $table->nullableMorphs('customer');
            $table->string('tracking_code', 100)->nullable();
            $table->unsignedBigInteger('arminpay_id')->nullable();
            $table->string('coupon', 100)->nullable();
            $table->enum('status', [
                Order::PENDING, Order::CANCELDE, Order::FAILED, Order::SUCCESS
            ])->default(Order::PENDING);
            $table->json('extra')->default('[]');
            $table->timestamps();
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
        Schema::dropIfExists('orders');
    }
}
