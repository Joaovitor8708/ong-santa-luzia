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
        Schema::create('plano_individuals', function (Blueprint $table) {
             $table->id();

            $table->foreignId('idosa_id')
                  ->unique()
                  ->constrained('idosas')
                  ->cascadeOnDelete();

            $table->date('data_ingresso')->nullable();
            $table->string('numero_prontuario')->nullable();
            $table->string('origem_residencia')->nullable();
            $table->text('motivo_institucionalizacao')->nullable();
            $table->decimal('renda', 10, 2)->nullable();
            $table->boolean('administra_financeiro')->default(false);
            $table->string('escolaridade')->nullable();
            $table->string('profissao')->nullable();
            $table->string('religiao')->nullable();
            $table->text('diagnostico_medico')->nullable();
            $table->string('grau_dependencia')->nullable();
            $table->boolean('possui_plano_saude')->default(false);
            $table->text('descricao_medicacao')->nullable();
            $table->text('restricao_alimentar')->nullable();
            $table->text('rotina')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plano_individuals');
    }
};
