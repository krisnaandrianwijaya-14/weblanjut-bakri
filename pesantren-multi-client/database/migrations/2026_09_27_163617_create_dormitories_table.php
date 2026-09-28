<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dormitories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->string('name', 80);
            $table->enum('gender', ['putra', 'putri']);
            $table->string('location')->nullable();
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->timestamps();

            $table->unique(['client_id', 'name']);
            $table->unique(['client_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dormitories');
    }
};
