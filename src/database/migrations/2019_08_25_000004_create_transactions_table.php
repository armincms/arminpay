<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Component\Arminpay\Order;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('amount', 15, 4);
            $table->string('currency'); 
            $table->string('gateway'); 
            $table->string('reference_number', 100)->nullable(); 
            $table->string('tracking_code', 100);  
            $table->text('previous_url');  
            $table->text('callback_url');  
            $table->enum('status', [
                Order::PENDING, Order::CANCELDE, Order::FAILED, Order::SUCCESS
            ])->default(Order::PENDING);
            $table->json('extra')->default('[]');
            $table->timestamp('payment_date')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
