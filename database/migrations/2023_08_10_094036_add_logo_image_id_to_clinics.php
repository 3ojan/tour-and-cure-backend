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
            $table->unsignedBigInteger('logo_image_id')->nullable();
            $table->foreign('logo_image_id')->references('id')->on('logo_images')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<< HEAD
    Schema::table('clinics', function (Blueprint $table) {
        // Firstly, we need to drop the foreign key constraint
        $table->dropForeign(['logo_image_id']);

        // Then drop the column itself
        $table->dropColumn('logo_image_id');
    });
=======
        Schema::table('clinics', function (Blueprint $table) {
            //
        });
>>>>>>> main
    }
};
