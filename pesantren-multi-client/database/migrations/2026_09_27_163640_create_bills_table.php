<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('student_id');
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->date('due_date')->nullable();
            $table->string('period', 20)->nullable();
            $table->enum('status', ['draft', 'issued', 'partially_paid', 'paid', 'overdue', 'cancelled'])
                  ->default('issued');
            $table->timestamps();

            $table->foreign(['client_id', 'student_id'])
                  ->references(['client_id', 'id'])->on('students')->restrictOnDelete();

            $table->unique(['client_id', 'id']);
            $table->index(['client_id', 'student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
