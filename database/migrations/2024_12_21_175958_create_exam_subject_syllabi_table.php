<?php

use App\Constants\SetupConstant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exam_subject_syllabi', function (Blueprint $table) {
            $table->id();
            $table->enum('exam', SetupConstant::$exams); // Enum for exam
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->string('syllabus_link', 255); // Syllabus link as a string
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_subject_syllabi');
    }
};
