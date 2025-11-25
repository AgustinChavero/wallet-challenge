<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSessionsTables extends Migration
{
    public function up(): void
    {
        Schema::create('payment_session_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('code')->unique();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('payment_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('wallet_id');
            $table->uuid('status_id');

            $table->string('token')->unique();
            $table->decimal('amount', 15, 2);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('payment_session_statuses')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_sessions');
        Schema::dropIfExists('payment_session_statuses');
    }
}
