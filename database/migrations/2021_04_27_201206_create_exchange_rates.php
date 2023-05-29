<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();

            $table->string('exchange_rates_number');
            $table->date('valid_at');
            $table->string('country_name');
            $table->string('country_iso');
            $table->string('currency_num');
            $table->string('currency_iso');
            $table->integer('unit');
            $table->double('buy_rate', 12, 6);
            $table->double('mid_rate', 12, 6);
            $table->double('sell_rate', 12, 6);

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
        Schema::dropIfExists('exchange_rates');
    }
}
