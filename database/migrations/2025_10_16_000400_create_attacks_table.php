<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attacks', function (Blueprint $table) {
            $table->unsignedBigInteger('report_id')->primary();
            $table->string('attack_type'); // robbery/assault/terror/other
            $table->unsignedInteger('victims_count')->default(0);
            $table->string('attacker')->nullable();
            $table->timestamps();

            $table->foreign('report_id')->references('report_id')->on('reports')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attacks');
    }
};
