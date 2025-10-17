<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('special_volunteers', function (Blueprint $table) {
            $table->unsignedBigInteger('special_volunteer_id')->primary(); // FK to volunteers
            $table->string('terrain_type'); // water/forest/hilltrack/urban
            $table->string('vetting_status')->default('pending'); // pending/approved/rejected
            $table->unsignedBigInteger('verified_by_officer')->nullable();
            $table->timestamps();

            $table->foreign('special_volunteer_id')->references('volunteer_id')->on('volunteers')->cascadeOnDelete();
            $table->foreign('verified_by_officer')->references('officer_id')->on('officers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('special_volunteers');
    }
};
