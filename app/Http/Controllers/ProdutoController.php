<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreProdutoRequest;
use App\Models\Produto;
use App\Services\ProdutoService;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        return Produto::all();
    }

    public function show($id)
    {
        return Produto::findOrFail($id);
    }

    public function store(Request $request, ProdutoService $produtoService)
    {

        //retorna somente os dados que passaram pelas regras de validação.
        $dados = $request->validated();
    
        $produto = $produtoService->criar($dados);
    
        return response()->json($produto, 201);
    }



    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'quantidade' => 'required|integer|min:0',
        ]);

        $produto->update($dados);

        return response()->json($produto, 200);
    }

    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);

        $produto->delete();

        return response()->json([
            'message' => 'Produto excluído com sucesso!'
        ], 200);
    }
}