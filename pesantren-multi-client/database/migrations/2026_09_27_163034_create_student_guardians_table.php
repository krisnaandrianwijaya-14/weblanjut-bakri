<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_guardians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('guardian_id');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            // Composite FK — cegah santri/wali lintas client
            $table->foreign(['client_id', 'student_id'])
                  ->references(['client_id', 'id'])
                  ->on('students')
                  ->restrictOnDelete();

            $table->foreign(['client_id', 'guardian_id'])
                  ->references(['client_id', 'id'])
                  ->on('guardians')
                  ->restrictOnDelete();

            $table->unique(['client_id', 'student_id', 'guardian_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_guardians');
    }
};
