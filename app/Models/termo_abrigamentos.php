<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Idosas;
use App\Models\Responsaveis;

class TermoAbrigamentos extends Model
{
    protected $table = 'termo_abrigamentos';
    protected $fillable = [];

    public function idosa()
    {
        return $this->belongsTo(Idosas::class);
    }
    public function responsavel()
{
    return $this->belongsTo(Responsaveis::class);
}
}
