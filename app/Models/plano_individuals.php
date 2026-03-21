<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Idosas;

class PlanoIndividuals extends Model
{
    protected $table = 'plano_individuals';
    protected $fillable = [];

    public function idosa()
    {
        return $this->belongsTo(Idosas::class);
    }
}
