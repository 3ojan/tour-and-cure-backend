<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('logo_images', function (Blueprint $table) {
            $table->uuid('user_id')->nullable()->after('file_name');
            // $table->foreignUuid('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('logo_images', function (Blueprint $table) {
            // $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
