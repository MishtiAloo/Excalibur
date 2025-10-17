<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('officers', function (Blueprint $table) {
            $table->unsignedBigInteger('officer_id')->primary(); // FK to users
            $table->string('badge_no');
            $table->string('department')->nullable();
            $table->string('rank')->nullable();
            $table->timestamps();

            $table->foreign('officer_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('officers');
    }
};
