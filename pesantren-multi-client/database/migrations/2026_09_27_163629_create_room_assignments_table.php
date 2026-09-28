<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('student_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign(['client_id', 'room_id'])
                  ->references(['client_id', 'id'])->on('rooms')->restrictOnDelete();

            $table->foreign(['client_id', 'student_id'])
                  ->references(['client_id', 'id'])->on('students')->restrictOnDelete();

            $table->index(['client_id', 'student_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_assignments');
    }
};
