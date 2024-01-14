<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mentors', function (Blueprint $table){
            $table->foreign('faculty_id')->references('id')->on('faculties');
            $table->foreign('program_id')->references('id')->on('study_programs');
        });

        Schema::table('students', function (Blueprint $table){
            $table->foreign('faculty_id')->references('id')->on('faculties');
            $table->foreign('program_id')->references('id')->on('study_programs');
            $table->foreign('mentor_id')->references('id')->on('mentors');
        });

        Schema::table('study_programs', function (Blueprint $table){
            $table->foreign('faculty_id')->references('id')->on('faculties');
        });
    }


    public function down()
    {
        //
    }
};
