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
        Schema::create('mentors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id');
            $table->foreignId('program_id');
            $table->string('name');
            $table->string('lastName');
            $table->string('phone');
            $table->string('email');
            $table->integer('mentees');
            $table->integer('year');
            $table->text('about');
            $table->text('why');
            $table->boolean('lv')->default(0);
            $table->boolean('ru')->default(0);
            $table->boolean('en')->default(0);
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
        Schema::dropIfExists('mentors');
    }
};
