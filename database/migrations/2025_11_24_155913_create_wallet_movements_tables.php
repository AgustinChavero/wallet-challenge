<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletMovementsTables extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_movement_types', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->string('description')->nullable();

            $table->timestamps();
        });

        Schema::create('wallet_movements', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('type_id');
            
            $table->decimal('amount', 15, 2);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('wallet_movement_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_movements');
        Schema::dropIfExists('wallet_movement_types');
    }
}
