<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('dormitory_id');
            $table->string('number', 20);
            $table->unsignedTinyInteger('capacity')->default(8);
            $table->unsignedTinyInteger('occupied')->default(0);
            $table->timestamps();

            $table->foreign(['client_id', 'dormitory_id'])
                  ->references(['client_id', 'id'])->on('dormitories')->restrictOnDelete();

            $table->unique(['client_id', 'dormitory_id', 'number']);
            $table->unique(['client_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
