<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Doador extends Model
{
    protected $fillable = [
        'nome',
        'cpf',
        'telefone',
        'email',
        'tipo',
        'observacoes',
    ];

    public function doacoes(): HasMany
    {
        return $this->hasMany(Doacao::class);
    }
}