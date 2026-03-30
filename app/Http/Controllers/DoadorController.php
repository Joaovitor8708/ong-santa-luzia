<?php

namespace App\Http\Controllers;

use App\Models\Doador;
use Illuminate\Http\Request;

class DoadorController extends Controller
{
    public function index()
    {
        $doadores = Doador::withSum('doacoes', 'valor')
            ->with('doacoes')
            ->latest()
            ->get();

        return view('doadores.index', compact('doadores'));
    }

    public function create()
    {
        return view('doadores.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cpf' => ['nullable', 'string', 'max:20'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'tipo' => ['nullable', 'string', 'max:100'],
            'observacoes' => ['nullable', 'string'],
        ]);

        Doador::create($data);

        return redirect()->route('dashboard')->with('success', 'Doador cadastrado com sucesso!');
    }

    public function show(Doador $doador)
    {
        $doador->load('doacoes');

        return view('doadores.show', compact('doador'));
    }

    public function edit(Doador $doador)
    {
        return view('doadores.edit', compact('doador'));
    }

    public function update(Request $request, Doador $doador)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cpf' => ['nullable', 'string', 'max:20'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'tipo' => ['nullable', 'string', 'max:100'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $doador->update($data);

        return redirect()->route('dashboard', ['doador' => $doador->id])->with('success', 'Doador atualizado com sucesso!');
    }

    public function destroy(Doador $doador)
    {
        $doador->delete();

        return redirect()->route('dashboard')->with('success', 'Doador excluído com sucesso!');
    }
}