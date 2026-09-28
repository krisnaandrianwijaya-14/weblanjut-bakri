<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::create('teachers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('client_id')->constrained()->restrictOnDelete();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->string('name', 120);
    $table->string('employee_number', 30)->nullable();
    $table->string('phone', 20)->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();

    $table->unique(['client_id', 'employee_number']);
    $table->unique(['client_id', 'id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
