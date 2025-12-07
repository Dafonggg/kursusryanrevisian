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
        Schema::create('exam_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->onDelete('cascade');
            $table->string('exam_type'); // 'final_exam' or 'practicum'
            $table->unsignedBigInteger('exam_id');
            $table->string('file_path')->nullable();
            $table->text('answer_text')->nullable();
            $table->integer('score')->nullable();
            $table->text('feedback')->nullable();
            $table->string('status')->default('pending'); // pending, graded, passed, failed
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['exam_type', 'exam_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_submissions');
    }
};
