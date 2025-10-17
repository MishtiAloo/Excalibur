<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hazards', function (Blueprint $table) {
            $table->unsignedBigInteger('report_id')->primary();
            $table->string('hazard_type'); // animal/collapse/chemical
            $table->string('severity')->default('low'); // low/medium/high
            $table->timestamps();

            $table->foreign('report_id')->references('report_id')->on('reports')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hazards');
    }
};
