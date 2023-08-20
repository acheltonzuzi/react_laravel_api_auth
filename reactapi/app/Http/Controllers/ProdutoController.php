<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Http\Requests\UpdateProdutoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Produto::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',
            'descricao' => 'required|string',
            'preco' => 'required|numeric',
            'categoria' => 'required|string',
            'tamanho' => 'required|string',
            'cor' => 'required|string',
            'quantidade' => 'required|numeric',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $product = new Produto();
        $product->nome = $request->input('nome');
        $product->descricao = $request->input('descricao');
        $product->preco = $request->input('preco');
        $product->quantidade = $request->input('quantidade');
        $product->cor = $request->input('cor');
        $product->tamanho = $request->input('tamanho');
        $product->categoria = $request->input('categoria');

        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $path = $imagem->store('public/imagens');
            $product->imagem = $path;
        }

        $product->save();

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produto $produto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produto $produto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdutoRequest $request, Produto $produto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produto $produto)
    {
        //
    }

    public function teste(Request $req)
    {
        return response()->json(['nome' => 'Achelton Pambo']);
    }
    
    public function getImage($filename)
    {
        $path = 'public/images/' . $filename;

        if (!Storage::exists($path)) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        return response($file, 200)->header('Content-Type', $type);
    }
}
