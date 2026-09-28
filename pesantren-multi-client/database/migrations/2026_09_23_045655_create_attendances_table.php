<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('student_id');
            $table->enum('type', ['academic', 'dormitory', 'gate']);
            $table->enum('status', ['present', 'late', 'absent', 'permission', 'sick']);
            $table->string('device_id', 64)->nullable();
            $table->string('location_type', 32)->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->timestamp('scanned_at');
            $table->timestamps();

            $table->index(['client_id', 'student_id', 'scanned_at']);
            $table->index(['client_id', 'type', 'scanned_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
