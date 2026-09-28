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
Schema::create('permit_violations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('client_id')->constrained()->restrictOnDelete();
    $table->unsignedBigInteger('student_id');
    $table->unsignedBigInteger('leave_request_id');
    $table->integer('late_minutes');
    $table->text('notes')->nullable();
    $table->timestamp('recorded_at');
    $table->unsignedBigInteger('recorded_by')->nullable();
    $table->timestamps();
    // NO UPDATE / DELETE — append-only

    $table->index(['client_id', 'student_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permit_violations');
    }
};
