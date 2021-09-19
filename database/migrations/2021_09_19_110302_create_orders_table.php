<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            
            $table->increments('id');
            $table->string('reference')->unique();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('address_id')->unsigned()->index();
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->enum('order_status', ['received', 'paid', 'delivered']);
            // $table->integer('order_status_id')->unsigned()->index();
            // $table->foreign('order_status_id')->references('id')->on('order_statuses');
            $table->string('payment');
            $table->decimal('discounts')->default(0.00);
            $table->decimal('total_products');
            $table->decimal('tax')->default(0.00);
            $table->decimal('total');
            $table->decimal('total_paid')->default(0.00);
            $table->string('invoice')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
