<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produto extends Model
{
    use HasFactory;
    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'quantidade',
        'categoria_id'
    ];

    public function categoria()
{
    return $this->belongsTo(Categoria::class);
}

public function scopeDisponiveis($query)
{
    //um produto está disponível quando sua quantidade é maior que zero.
    return $query->where('quantidade', '>', 0);

    //no tinker para ver quais sao: $produtos = App\Models\Produto::disponiveis()->get();
}

public function scopeMaisCarosQue($query, $valor)
{
    return $query->where('preco', '>', $valor);
}

public function scopeEsgotados($query){
    return $query->where('quantidade', 0);
}


public function scopeDaCategoria($query, $nome)
{
    //filtra produtos que possuem uma categoria correspondente.
    return $query->whereHas('categoria', function ($query) use ($nome) {
        $query->where('nome', 'LIKE', "%{$nome}%");
    });
}

}
