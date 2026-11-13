<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProdutoTest extends TestCase
{
    //prepara o banco de dados para os testes, executando as migrations e isolando os dados de cada teste.
    use RefreshDatabase;

    public function test_can_create_a_product(): void
    {
        //simulacao de dados
        $dados = [
            'nome' => 'Teclado Mecânico',
            'descricao' => 'Teclado para programação',
            'preco' => 250,
            'quantidade' => 10,
        ];
//envio de requisicao
        $response = $this->postJson('/api/produtos', $dados);

//verifica o resultado
        $response->assertStatus(201);

        //Verifica se o produto realmente foi salvo no banco de dados de testes.
        $this->assertDatabaseHas('produtos', [
            'nome' => 'Teclado Mecânico',
            'quantidade' => 10,
        ]);
    }


    public function test_can_list_products(): void
{
    //Cria um produto no banco de testes
    Produto::create([
        'nome' => 'Mouse',
        'descricao' => 'Mouse sem fio',
        'preco' => 100,
        'quantidade' => 5,
    ]);

    //Faz uma requisição GET para /api/produtos.
    $response = $this->getJson('/api/produtos');

    //Confirma que a API respondeu com status 200.
    $response->assertStatus(200);

    //1. Confirma que o produto criado aparece na resposta.
    $response->assertJsonFragment([
        'nome' => 'Mouse',
    ]);
}

public function test_can_show_existing_product(): void
{
    // Cria um produto automaticamente usando a Factory
    $produto = Produto::factory()->create();

    // Busca o produto criado, usando o ID dele
    $response = $this->getJson('/api/produtos/' . $produto->id);

    // Verifica se a resposta foi 200
    $response->assertStatus(200);

    // Verifica se o nome retornado é o nome do produto criado
    $response->assertJsonFragment([
        'nome' => $produto->nome,
    ]);
}

public function test_returns_404_when_product_does_not_exist(): void
{
    //buscando um produto que nao existe, para ver o que acontece
    $response = $this->getJson('/api/produtos/999');

//- assertStatus(404): verifica se a API retorna HTTP 404, que significa "não encontrado".
    $response->assertStatus(404);

}

public function test_can_update_a_product(): void
{

    //cria um produto no banco teste

    $produto = Produto::create([
        'nome' => 'Notebook',
        'descricao' => 'Notebook para estudos',
        'preco' => 3500,
        'quantidade' => 10,
    ]);

    //dados para update/alteracao

    $dadosAtualizados = [
        'nome' => 'Notebook Gamer',
        'descricao' => 'Notebook para jogos',
        'preco' => 5000,
        'quantidade' => 5,
    ];

//envia a atualizacao dos dados usando o id do produto criado anteriormente

    $response = $this->putJson(
        '/api/produtos/' . $produto->id,
        $dadosAtualizados
    );

    //Assert: verificamos se a resposta foi 200 e se os dados foram realmente atualizados no banco.
    $response->assertStatus(200);

    $this->assertDatabaseHas('produtos', [
        'id' => $produto->id,
        'nome' => 'Notebook Gamer',
        'preco' => 5000,
    ]);
}


public function test_can_delete_a_product(): void
{

    //cria um produto no banco teste
    $produto = Produto::create([
        'nome' => 'Notebook',
        'descricao' => 'Notebook para estudos',
        'preco' => 3500,
        'quantidade' => 10,
    ]);

    //requisicao para deletar produto
    $response = $this->deleteJson('/api/produtos/' . $produto->id);

//confirma a resposta
    $response->assertStatus(200);

    //verifica se o registro realmente deixou de existir na tabela produtos.
    $this->assertDatabaseMissing('produtos', [
        'id' => $produto->id,
    ]);
}


public function test_cannot_create_product_with_negative_price(): void
{
    //tenta criar produto com preço negativo
    $dados = [
        'nome' => 'Mouse',
        'descricao' => 'Mouse sem fio',
        'preco' => -100,
        'quantidade' => 5,
    ];

    //requisicao
    $response = $this->postJson('/api/produtos', $dados);

    //ve se ha erro de validacao
    $response->assertStatus(422);


    //verifica se o erro está especificamente no campo preco.
    $response->assertJsonValidationErrors('preco');
}


public function test_cannot_create_product_without_name(): void
{

    //dados sem o campo nome
    $dados = [
        'descricao' => 'Mouse sem fio',
        'preco' => 100,
        'quantidade' => 5,
    ];

    $response = $this->postJson('/api/produtos', $dados);


//verifica erro de validacao
    $response->assertStatus(422);
//verifica se o erro ocorreu no campo nome
    $response->assertJsonValidationErrors('nome');

}



public function test_cannot_create_product_with_invalid_quantity(): void
{

    //requisicao com quantidade do produto invalida
    $dados = [
        'nome' => 'Mouse',
        'descricao' => 'Mouse sem fio',
        'preco' => 100,
        'quantidade' => 'abc',
    ];

    $response = $this->postJson('/api/produtos', $dados);

//verifica erro na validacao
    $response->assertStatus(422);

//verifica se o erro é na quantidade
    $response->assertJsonValidationErrors('quantidade');
}


public function test_cannot_create_product_with_invalid_price(): void
{
     //requisicao com preco do produto invalido
    $dados = [
        'nome' => 'Mouse',
        'descricao' => 'Mouse sem fio',
        'preco' => 'abc',
        'quantidade' => 5,
    ];

    $response = $this->postJson('/api/produtos', $dados);

    $response->assertStatus(422);

    $response->assertJsonValidationErrors('preco');
}


public function test_invalid_product_is_not_saved_in_database(): void
{
    $dados = [
        'nome' => 'Mouse',
        'descricao' => 'Mouse sem fio',
        'preco' => -100,
        'quantidade' => 5,
    ];

    $response = $this->postJson('/api/produtos', $dados);

    $response->assertStatus(422);


//verifica se registro invalido não é salvo no banco de dados.
    $this->assertDatabaseMissing('produtos', [
        'nome' => 'Mouse',
    ]);
}

public function test_cannot_update_product_with_negative_price(): void
{

    //busca produto para update
    $produto = Produto::create([
        'nome' => 'Notebook',
        'descricao' => 'Notebook para estudos',
        'preco' => 3500,
        'quantidade' => 10,
    ]);
//tenta atualizar com dados inválidos
    $dadosAtualizados = [
        'nome' => 'Notebook Gamer',
        'descricao' => 'Notebook para jogos',
        'preco' => -500,
        'quantidade' => 5,
    ];

    $response = $this->putJson(
        '/api/produtos/' . $produto->id,
        $dadosAtualizados
    );

    $response->assertStatus(422);

//verifica se o erro é no preco mesmo
    $response->assertJsonValidationErrors('preco');

//verifca se o produto continua com os dados originais, mesmo depois da tentativa de atualização inválida.
    $this->assertDatabaseHas('produtos', [
        'id' => $produto->id,
        'nome' => 'Notebook',
        'preco' => 3500,
    ]);
}

public function test_cannot_delete_product_that_does_not_exist(): void
{
    //tentar excluir um produto inexistente
    $response = $this->deleteJson('/api/produtos/999');

    $response->assertStatus(404);
}

public function test_cannot_update_product_that_does_not_exist(): void
{

    $dadosAtualizados = [
        'nome' => 'Notebook Gamer',
        'descricao' => 'Notebook para jogos',
        'preco' => 5000,
        'quantidade' => 5,
    ];

//tenta atualizar um produto que nao existe
    $response = $this->putJson('/api/produtos/999', $dadosAtualizados);

    $response->assertStatus(404);
}
}