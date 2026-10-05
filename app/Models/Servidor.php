<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servidor extends Model
{
    protected $table = 'servidors';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'cpf',
        'email'
    ];
}
