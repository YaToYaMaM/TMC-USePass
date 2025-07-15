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
        Schema::create('spot_reports', function (Blueprint $table) {
            $table->id('spot_id');
            $table->string('findings')->nullable();
            $table->string('team_leader')->nullable();
            $table->string('guard_name')->nullable();
            $table->string('action_taken')->nullable();
            $table->string('department_representative')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spot_reports');
    }
};
