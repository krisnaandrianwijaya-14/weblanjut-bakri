<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('schedule_id')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('class_id');
            $table->enum('status', ['present', 'late', 'absent', 'permission', 'sick']);
            $table->date('date');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('recorded_by')->nullable();
            $table->timestamps();

            $table->foreign(['client_id', 'student_id'])
                  ->references(['client_id', 'id'])->on('students')->restrictOnDelete();

            $table->unique(['client_id', 'student_id', 'class_id', 'date'], 'academic_att_unique');
            $table->index(['client_id', 'date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_attendances');
    }
};
