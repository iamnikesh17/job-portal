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
        //
        Schema::table('jobs', function (Blueprint $table) {
            // Add a new column
            $table->integer('status')->after('experience')->default(1);
            $table->integer('isFeatured')->after('status')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('jobs', function (Blueprint $table) {
            // Add a new column
            $table->dropColumn('status');
            $table->dropColumn('isFeatured');
        });
    }
};
