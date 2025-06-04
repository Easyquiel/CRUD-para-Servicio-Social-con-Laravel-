<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('social_service_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('university');
            $table->string('career');
            $table->string('student_id');
            $table->string('phone');
            $table->string('emergency_contact');
            $table->string('emergency_phone');
            $table->string('schedule');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('activities');
            $table->string('status')->default('active');
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('social_service_students');
    }
};
