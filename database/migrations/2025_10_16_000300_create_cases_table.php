<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id('case_id');
            $table->unsignedBigInteger('created_by');
            $table->string('case_type'); // missing
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('coverage_lat', 10, 7)->nullable();
            $table->decimal('coverage_lng', 10, 7)->nullable();
            $table->unsignedInteger('coverage_radius')->nullable();
            $table->string('status')->default('active'); // active/under_investigation/resolved/closed
            $table->string('urgency')->default('medium'); // low/medium/high/critical/national
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
