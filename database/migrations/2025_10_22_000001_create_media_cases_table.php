<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media_cases', function (Blueprint $table) {
            $table->id('media_id');
            $table->unsignedBigInteger('case_id');
            $table->unsignedBigInteger('uploaded_by');
            $table->string('url');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('case_id')->references('case_id')->on('cases')->cascadeOnDelete();
            $table->foreign('uploaded_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_cases');
    }
};
