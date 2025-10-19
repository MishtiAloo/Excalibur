<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->unsignedBigInteger('volunteer_id')->primary(); // FK to users
            $table->string('vetting_status')->default('pending'); // pending/approved/rejected
            $table->string('availability')->default('available'); // available/busy/inactive
            
            $table->timestamps();

            $table->foreign('volunteer_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
