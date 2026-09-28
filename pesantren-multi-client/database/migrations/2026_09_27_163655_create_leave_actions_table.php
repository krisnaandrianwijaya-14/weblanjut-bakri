<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('leave_request_id');
            $table->unsignedBigInteger('actor_user_id')->nullable();
            $table->string('old_status', 30);
            $table->string('new_status', 30);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign(['client_id', 'leave_request_id'])
                  ->references(['client_id', 'id'])->on('leave_requests')->cascadeOnDelete();

            $table->index(['client_id', 'leave_request_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_actions');
    }
};
