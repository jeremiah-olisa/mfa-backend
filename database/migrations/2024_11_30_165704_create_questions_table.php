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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_id')->unique();
            $table->enum('test_type', ['WAEC', 'NECO', 'JAMB', 'OYO']);
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->text('question');
            $table->foreignId('answer_id')->constrained('options')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
