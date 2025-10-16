<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('evidences', function (Blueprint $table) {
            $table->unsignedBigInteger('report_id')->primary();
            $table->boolean('received')->default(false);
            $table->unsignedBigInteger('received_by')->nullable();
            $table->timestamps();

            $table->foreign('report_id')->references('report_id')->on('reports')->cascadeOnDelete();
            $table->foreign('received_by')->references('officer_id')->on('officers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidences');
    }
};
