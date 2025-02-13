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
        Schema::table('mentors', function (Blueprint $table) {
            $table->softDeletes()->after('key');
        });
        Schema::table('students', function (Blueprint $table) {
            $table->softDeletes()->after('privacy');
        });
        Schema::table('faculties', function (Blueprint $table) {
            $table->softDeletes()->after('code');
        });
        Schema::table('study_programs', function (Blueprint $table) {
            $table->softDeletes()->after('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mentors', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('students', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('faculties', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('study_programs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
