<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        //fake ->recurso que utiliza uma biblioteca faker para gerar dados fictícios
        return [
            //Duas palavras para o nome
            'nome' => fake()->words(2, true),
            //Uma frase para a descrição
            'descricao' => fake()->sentence(),
            //Preço decimal entre 10 e 5000
            'preco' => fake()->randomFloat(2, 10, 5000),
            //Quantidade entre 1 e 100
            'quantidade' => fake()->numberBetween(1, 100),
        ];
    }
}
