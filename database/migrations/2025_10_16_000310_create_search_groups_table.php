<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('search_groups', function (Blueprint $table) {
            $table->id('group_id');
            $table->unsignedBigInteger('case_id');
            $table->unsignedBigInteger('leader_id');
            $table->string('type'); // citizen/terrainSpecial
            $table->string('intensity')->default('basic'); // basic/rigorous/extreme
            $table->string('status')->default('active'); // active/paused/completed/time_assigned/time_unassigned
            $table->dateTime('start_time')->nullable();
            $table->unsignedInteger('duration')->nullable(); // minutes
            $table->dateTime('report_back_time')->nullable();
            $table->unsignedInteger('max_volunteers');
            $table->unsignedInteger('available_volunteer_slots');
            $table->string('instruction')->nullable();
            $table->decimal('allocated_lat', 10, 7);
            $table->decimal('allocated_lng', 10, 7);
            $table->unsignedInteger('radius');
            $table->timestamps();

            $table->foreign('case_id')->references('case_id')->on('cases')->cascadeOnDelete();
            $table->foreign('leader_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_groups');
    }
};
