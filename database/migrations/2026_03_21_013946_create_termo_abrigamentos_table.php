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
        Schema::create('termo_abrigamentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('idosa_id')
                  ->constrained('idosas')
                  ->cascadeOnDelete();

            $table->foreignId('responsavel_id')
                  ->constrained('responsaveis')
                  ->cascadeOnDelete();

            $table->date('data_inicio')->nullable();
            $table->text('observacoes')->nullable();

            $table->boolean('assinado_responsavel')->default(false);
            $table->boolean('assinado_psicologo')->default(false);
            $table->boolean('assinado_assistente_social')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('termo_abrigamentos');
    }
};
