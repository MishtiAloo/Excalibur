<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // Domain fields consolidated here so only one users table definition exists
            $table->string('nid')->nullable();
            $table->string('phone')->nullable();
            $table->string('role')->default('citizen'); // citizen/officer/volunteer/specialVolunteer/group_leader
            $table->string('status')->default('active'); // active/suspended/deactivated
            $table->unsignedTinyInteger('info_credibility')->default(0);
            $table->unsignedTinyInteger('responsiveness')->default(0);
            // Locations stored as lat/lng pairs
            $table->decimal('permanent_lat', 10, 7)->nullable();
            $table->decimal('permanent_lng', 10, 7)->nullable();
            $table->decimal('current_lat', 10, 7)->nullable();
            $table->decimal('current_lng', 10, 7)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
