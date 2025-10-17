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
            $table->string('type'); // citizen/covert/terrainSpecial
            $table->string('intensity')->default('basic'); // basic/rigorous/extreme/pinpoint
            $table->string('status')->default('active'); // active/paused/completed
            $table->unsignedInteger('allocated_time')->nullable();
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
