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
        Schema::create('horario_disponivels', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('perito_id')->nullable();
            $table->foreign('perito_id')->references('id')->on('peritos')->onDelete('cascade');
            $table->date('data');
            $table->string('hora_inicio', length:4);
            $table->string('hora_fim', length:4);
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horario_disponivels');
    }
};
