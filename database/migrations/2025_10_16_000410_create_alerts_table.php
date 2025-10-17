<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id('alert_id');
            $table->unsignedBigInteger('case_id');
            $table->string('alert_type'); // amber/silver/red/yellow
            $table->string('status')->default('active'); // active/expired/cancelled
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();

            $table->foreign('case_id')->references('case_id')->on('cases')->cascadeOnDelete();
            $table->foreign('approved_by')->references('officer_id')->on('officers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
