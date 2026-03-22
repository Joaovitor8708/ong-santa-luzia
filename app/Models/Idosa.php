<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Idosa extends Model
{
    protected $table = 'idosas';

    protected $fillable = [
        'nome',
        'data_nascimento',
        'estado_civil',
        'rg',
        'orgao_emissor',
        'cpf',
        'filiacao',
        'naturalidade',
        'deficiencia',
        'data_abrigamento',
        'telefone',
        'endereco',
        'bairro',
        'cidade',
        'nome_social',
        'apelido',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_abrigamento' => 'date',
    ];

    public function planoIndividual(): HasOne
    {
        return $this->hasOne(PlanoIndividual::class, 'idosa_id');
    }

    public function termos(): HasMany
    {
        return $this->hasMany(TermoAbrigamento::class, 'idosa_id');
    }

    protected function cpf(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->formatCpf($value),
            set: fn ($value) => $this->onlyNumbers($value),
        );
    }

    protected function telefone(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->formatPhone($value),
            set: fn ($value) => $this->onlyNumbers($value),
        );
    }

    protected function rg(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => $this->onlyNumbers($value),
        );
    }

    private function onlyNumbers(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return preg_replace('/\D+/', '', $value);
    }

    private function formatCpf(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $value = $this->onlyNumbers($value);

        if (strlen($value) !== 11) {
            return $value;
        }

        return preg_replace(
            '/(\d{3})(\d{3})(\d{3})(\d{2})/',
            '$1.$2.$3-$4',
            $value
        );
    }

    private function formatPhone(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $value = $this->onlyNumbers($value);

        if (strlen($value) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $value);
        }

        if (strlen($value) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $value);
        }

        return $value;
    }
}