<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->unsignedBigInteger('case_id');
            $table->unsignedBigInteger('search_group_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('report_type'); // evidence/sighting/general
            $table->text('description')->nullable();
            $table->decimal('location_lat', 10, 7)->nullable();
            $table->decimal('location_lng', 10, 7)->nullable();
            $table->string('sighted_person')->nullable();
            $table->timestamp('reported_at')->nullable();
            $table->string('status')->default('pending'); // pending/verified/responded/falsed/dismissed
            $table->timestamps();

            $table->foreign('case_id')->references('case_id')->on('cases')->cascadeOnDelete();
            $table->foreign('search_group_id')->references('group_id')->on('search_groups')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
