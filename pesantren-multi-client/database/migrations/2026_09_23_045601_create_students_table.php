<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->string('name', 120);
            $table->string('student_number', 30);
            $table->string('qr_token', 64)->unique()->nullable();
            $table->string('rfid_uid', 64)->unique()->nullable();
            $table->enum('status', ['pending', 'active', 'suspended'])->default('active');
            $table->timestamps();

            $table->unique(['client_id', 'student_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
