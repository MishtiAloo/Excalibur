<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('group_members', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('volunteer_id');
            $table->timestamps();

            $table->primary(['group_id','volunteer_id']);
            $table->foreign('group_id')->references('group_id')->on('search_groups')->cascadeOnDelete();
            $table->foreign('volunteer_id')->references('volunteer_id')->on('volunteers')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_members');
    }
};
