<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('student_id');
            $table->string('code', 40)->unique();
            $table->enum('type', ['pulang', 'keluar', 'sakit', 'lainnya']);
            $table->text('reason');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('destination')->nullable();
            $table->string('contact_name', 100)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected', 'cancelled'])
                  ->default('submitted');
            $table->timestamps();

            $table->foreign(['client_id', 'student_id'])
                  ->references(['client_id', 'id'])->on('students')->restrictOnDelete();

            $table->unique(['client_id', 'id']);
            $table->index(['client_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
