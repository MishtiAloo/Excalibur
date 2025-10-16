<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id('resource_id');
            $table->string('name');
            $table->decimal('stored_lat', 10, 7)->nullable();
            $table->decimal('stored_lng', 10, 7)->nullable();
            $table->string('condition')->default('good'); // new/good/moderate/old
            $table->string('availability')->default('available'); // available/in_use/delayed_checkout/under_maintenance
            $table->unsignedInteger('count')->default(0);
            $table->unsignedInteger('availableCount')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
