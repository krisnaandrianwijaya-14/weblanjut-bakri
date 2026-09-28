<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->string('name', 120);
            $table->string('relation', 30)->nullable();  // ayah, ibu, wali
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('occupation', 100)->nullable();
            $table->timestamps();

            $table->index(['client_id', 'phone']);
            $table->unique(['client_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guardians');
    }
};
