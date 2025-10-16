<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media_reports', function (Blueprint $table) {
            $table->id('media_id');
            $table->unsignedBigInteger('report_id');
            $table->unsignedBigInteger('uploaded_by');
            $table->string('url');
            $table->timestamp('uploaded_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('report_id')->references('report_id')->on('reports')->cascadeOnDelete();
            $table->foreign('uploaded_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_reports');
    }
};
