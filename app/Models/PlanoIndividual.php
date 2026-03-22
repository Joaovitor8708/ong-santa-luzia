<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanoIndividual extends Model
{
    protected $table = 'plano_individuals';

    protected $fillable = [
        'idosa_id',
        'data_ingresso',
        'numero_prontuario',
        'origem_residencia',
        'motivo_institucionalizacao',
        'renda',
        'administra_financeiro',
        'escolaridade',
        'profissao',
        'religiao',
        'diagnostico_medico',
        'grau_dependencia',
        'possui_plano_saude',
        'descricao_medicacao',
        'restricao_alimentar',
        'rotina',
    ];

    protected $casts = [
        'data_ingresso' => 'date',
        'renda' => 'decimal:2',
        'possui_plano_saude' => 'boolean',
    ];

    public function idosa(): BelongsTo
    {
        return $this->belongsTo(Idosa::class, 'idosa_id');
    }
}