<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('instructions', function (Blueprint $table) {
            $table->id('instruction_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('case_id');
            $table->unsignedBigInteger('officer_id');
            $table->text('details');
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();

            $table->foreign('group_id')->references('group_id')->on('search_groups')->cascadeOnDelete();
            $table->foreign('case_id')->references('case_id')->on('cases')->cascadeOnDelete();
            $table->foreign('officer_id')->references('officer_id')->on('officers')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructions');
    }
};
