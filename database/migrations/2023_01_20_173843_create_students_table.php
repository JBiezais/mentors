<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id');
            $table->foreignId('program_id');
            $table->foreignId('mentor_id')->nullable();
            $table->string('name');
            $table->string('lastName');
            $table->string('phone');
            $table->string('email');
            $table->text('comment')->nullable();
            $table->integer('lang')->nullable();
            $table->boolean('privacy')->default(0);
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
        Schema::dropIfExists('students');
    }
};
