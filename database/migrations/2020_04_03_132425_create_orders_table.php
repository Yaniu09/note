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
            $table->bigIncrements('id');
            $table->string('user_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('company_name')->nullable();
            $table->string('address1');
            $table->string('address2');
            $table->string('island');
            $table->string('zip');
            $table->string('email');
            $table->string('phone');
            $table->longtext('ordernotes');
            $table->integer('payment_status')->default('0');
            $table->string('payment_method')->nullable();
            $table->integer('tax');
            $table->integer('discount');
            $table->integer('subtotal');
            $table->integer('total');
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
