<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Idosas;

class Responsaveis extends Model
{
    protected $table = 'responsaveis';
    protected $fillable = [];

    public function idosa()
    {
        return $this->belongsTo(Idosas::class);
    }
}
