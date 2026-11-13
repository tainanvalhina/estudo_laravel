<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produto;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      // criar 10 produtos
      //  Produto::factory()->count(10)->create();



//tenta localizar o produto.
        Produto::firstOrCreate(
            ['nome' => 'Notebook Dell'],

            //se nao achar, cadastre:
            [
                'descricao' => 'Notebook para estudos',
                'preco' => 3500,
                'quantidade' => 10,
            ]
        );
    //procura
        Produto::firstOrCreate(
            ['nome' => 'Mouse Gamer'],

            //se nao achar, cadastre:
            [
                'descricao' => 'Mouse gamer RGB',
                'preco' => 150,
                'quantidade' => 5,
            ]
        );
    //procura
        Produto::firstOrCreate(
            ['nome' => 'Teclado Mecânico'],
            //cadastre se nao existir:
            [
                'descricao' => 'Teclado mecânico RGB',
                'preco' => 300,
                'quantidade' => 8,
            ]
        );
    }
}
