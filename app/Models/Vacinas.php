<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacinas extends Model
{
    //
    use HasFactory;

    protected $fillable = ['nome', 'descricao', 'validade_meses'];

    public function aplicacoes()
    {
        return $this->hasMany(CarteiraVacina::class, 'vacina_id');
    }
}
