<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sightings', function (Blueprint $table) {
            $table->unsignedBigInteger('report_id')->primary();
            $table->string('sighted_person')->nullable();
            $table->timestamp('time_seen')->nullable();
            $table->timestamps();

            $table->foreign('report_id')->references('report_id')->on('reports')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sightings');
    }
};
