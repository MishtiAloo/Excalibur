<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nid')->nullable();
            $table->string('phone')->nullable();
            $table->string('role')->default('citizen'); // citizen/officer/volunteer/specialVolunteer/watchDog/group_leader
            $table->string('status')->default('active'); // active/suspended/inactive
            $table->unsignedTinyInteger('info_credibility')->default(0);
            $table->unsignedTinyInteger('responsiveness')->default(0);
            // locations stored as lat/lng pairs
            $table->decimal('permanent_lat', 10, 7)->nullable();
            $table->decimal('permanent_lng', 10, 7)->nullable();
            $table->decimal('current_lat', 10, 7)->nullable();
            $table->decimal('current_lng', 10, 7)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nid','phone','role','status','info_credibility','responsiveness',
                'permanent_lat','permanent_lng','current_lat','current_lng'
            ]);
        });
    }
};
