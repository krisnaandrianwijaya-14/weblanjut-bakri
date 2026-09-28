<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('bill_id');
            $table->string('order_id', 64)->unique();
            $table->string('snap_token')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->nullable();
            $table->string('payment_type', 32)->nullable();
            $table->enum('status', ['initiated', 'pending', 'recorded', 'reversed', 'expired', 'failed'])
                  ->default('initiated');
            $table->timestamp('recorded_at')->nullable();
            $table->json('raw_webhook')->nullable();
            $table->timestamps();

            $table->foreign(['client_id', 'bill_id'])
                  ->references(['client_id', 'id'])->on('bills')->restrictOnDelete();

            $table->index(['client_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
