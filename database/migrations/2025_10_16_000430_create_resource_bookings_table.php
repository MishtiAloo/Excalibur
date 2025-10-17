<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resource_bookings', function (Blueprint $table) {
            $table->id('booking_id');
            $table->unsignedBigInteger('resource_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('checked_out_by');
            $table->timestamp('check_out_time')->nullable();
            $table->timestamp('check_in_time')->nullable();
            $table->timestamps();

            $table->foreign('resource_id')->references('resource_id')->on('resources')->cascadeOnDelete();
            $table->foreign('group_id')->references('group_id')->on('search_groups')->cascadeOnDelete();
            $table->foreign('checked_out_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_bookings');
    }
};
