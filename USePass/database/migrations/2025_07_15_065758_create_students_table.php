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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_first_name',100)->nullable();
            $table->string('student_last_name',100)->nullable();
            $table->string('student_middle_initial',50)->nullable();
            $table->string('student_gender',50)->nullable();
            $table->string('student_program',100)->nullable();
            $table->string('student_major',100)->nullable();
            $table->enum('student_unit', ['Tagum', 'Mabini'])->default('Tagum');

            $table->string('student_email',100)->unique();
            $table->string('student_phone_num',20)->nullable();
            $table->string('student_profile_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
