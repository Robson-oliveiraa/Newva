<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarteiraVacina extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vacina_id',
        'medico_id',
        'data_aplicacao',
        'vencimento',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vacina()
    {
        return $this->belongsTo(Vacinas::class, 'vacina_id');
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }
}
