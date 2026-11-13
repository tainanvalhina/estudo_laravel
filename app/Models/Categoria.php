<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;

    
//Permite que o Laravel preencha o campo nome em operações de atribuição em massa, como Categoria::create().
    protected $fillable = [
        'nome',
    ];

    //Define o relacionamento entre Categoria e Produto.
    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
}