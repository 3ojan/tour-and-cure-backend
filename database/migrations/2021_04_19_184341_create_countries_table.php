<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //
            $table->string('name', 60);
            $table->string('iso', 3)->unique();
            $table->string('alpha2', 2)->nullable();
            $table->string('alpha3', 3)->nullable();
            $table->string('code', 3)->nullable();
            $table->string('iso3166_2', 20)->nullable();
            $table->string('tld', 10)->nullable();
            //
            $table->string('region')->nullable();
            $table->string('sub_region')->nullable();
            $table->string('intermediate_region')->nullable();
            //
            $table->string('region_code', 5)->nullable();
            $table->string('sub_region_code', 5)->nullable();
            $table->string('intermediate_region_code', 5)->nullable();
            //
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
        Schema::dropIfExists('countries');
    }
}
