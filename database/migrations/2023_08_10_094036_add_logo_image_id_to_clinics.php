<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->uuid('logo_image_id')->nullable();
            // $table->foreignUuid('logo_image_id')->nullable()->references('id')->on('logo_images')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            // $table->dropForeign(['logo_image_id']);
            $table->dropColumn('logo_image_id');
        });
    }
};
