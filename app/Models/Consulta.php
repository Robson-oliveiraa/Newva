<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'especialidade',
        'data_hora',
        'local',
        'observacoes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
