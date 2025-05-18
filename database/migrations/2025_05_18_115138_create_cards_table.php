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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('module')->nullable();
            $table->string('topic')->nullable();
            $table->string('question')->nullable();
            $table->string('answer')->nullable();
            $table->string('category')->nullable();
            $table->string('difficulty')->nullable();
            $table->string('is_correct')->nullable();
            $table->string('is_bookmarked')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
