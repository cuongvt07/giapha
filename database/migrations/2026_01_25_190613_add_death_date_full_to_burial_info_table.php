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
        Schema::table('burial_info', function (Blueprint $table) {
            $table->string('death_date_full')->nullable()->after('burial_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('burial_info', function (Blueprint $table) {
            $table->dropColumn('death_date_full');
        });
    }
};
