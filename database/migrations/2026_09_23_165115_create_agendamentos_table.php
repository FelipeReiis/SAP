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
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->string('status', 10);
            $table->string('idempotency_key', 100)->unique();
            $table->unsignedInteger('perito_id')->nullable();
            $table->foreign('perito_id')->references('id')->on('peritos')->onDelete('cascade');
            $table->unsignedInteger('servidor_id')->nullable();
            $table->foreign('servidor_id')->references('id')->on('servidors')->onDelete('cascade');
            $table->unsignedInteger('disponibilidade_id')->nullable();
            $table->foreign('disponibilidade_id')->references('id')->on('a')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
