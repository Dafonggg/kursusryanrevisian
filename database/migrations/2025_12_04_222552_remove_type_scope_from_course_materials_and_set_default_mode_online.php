<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus kolom type dan scope dari course_materials
        Schema::table('course_materials', function (Blueprint $table) {
            $table->dropColumn(['type', 'scope']);
        });

        // Update semua kursus existing menjadi mode online
        DB::table('courses')->update(['mode' => 'online']);

        // Set default mode menjadi online untuk kursus baru
        Schema::table('courses', function (Blueprint $table) {
            $table->enum('mode', ['online','offline','hybrid'])->default('online')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan kolom type dan scope ke course_materials
        Schema::table('course_materials', function (Blueprint $table) {
            $table->string('type')->after('course_id');
            $table->enum('scope', ['online','offline','both'])->default('both')->after('type');
        });

        // Kembalikan default mode menjadi offline
        Schema::table('courses', function (Blueprint $table) {
            $table->enum('mode', ['online','offline','hybrid'])->default('offline')->change();
        });
    }
};
