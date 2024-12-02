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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25)->unique();
            $table->string('label', 40)->unique();
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_id')->unique();
            $table->enum('test_type', ['WAEC', 'NECO', 'JAMB', 'OYO']);
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->text('question');
            $table->timestamps();
        });

        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->text('option');
            $table->string('option_key')->unique();
            $table->boolean('is_correct')->default(false);
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('options');
    }
};
