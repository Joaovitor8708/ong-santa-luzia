<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TermoAbrigamentos;
use App\Models\PlanoIndividuals;

class Idosas extends Model
{
    protected $table = 'idosas';
    protected $fillable = [];

    public function termos()
    {
        return $this->hasMany(TermoAbrigamentos::class);
    }

    public function plano()
    {
        return $this->hasOne(PlanoIndividuals::class);
    }
}
