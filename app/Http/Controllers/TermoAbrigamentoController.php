<?php

namespace App\Http\Controllers;

use App\Models\Idosa;
use App\Models\Responsavel;
use App\Models\TermoAbrigamento;
use Illuminate\Http\Request;

class TermoAbrigamentoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $termos = TermoAbrigamento::with(['idosa', 'responsavel'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('idosa', function ($subQuery) use ($search) {
                        $subQuery->where('nome', 'like', "%{$search}%");
                    })->orWhereHas('responsavel', function ($subQuery) use ($search) {
                        $subQuery->where('nome', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('termos.index', compact('termos', 'search'));
    }

    public function create()
    {
        $idosas = Idosa::orderBy('nome')->get();
        $responsaveis = Responsavel::orderBy('nome')->get();

        return view('termos.create', compact('idosas', 'responsaveis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idosa_id' => ['required', 'exists:idosas,id'],
            'responsavel_id' => ['required', 'exists:responsaveis,id'],
            'data_inicio' => ['nullable', 'date'],
            'observacoes' => ['nullable', 'string'],
            'assinado_responsavel' => ['nullable', 'boolean'],
            'assinado_psicologo' => ['nullable', 'boolean'],
            'assinado_assistente_social' => ['nullable', 'boolean'],
        ]);

        $data['assinado_responsavel'] = $request->boolean('assinado_responsavel');
        $data['assinado_psicologo'] = $request->boolean('assinado_psicologo');
        $data['assinado_assistente_social'] = $request->boolean('assinado_assistente_social');

        TermoAbrigamento::create($data);

        return redirect()
            ->route('termos.index')
            ->with('success', 'Termo de abrigamento cadastrado com sucesso.');
    }

    public function show(TermoAbrigamento $termo)
    {
        $termo->load(['idosa', 'responsavel']);

        return view('termos.show', compact('termo'));
    }

    public function edit(TermoAbrigamento $termo)
    {
        $idosas = Idosa::orderBy('nome')->get();
        $responsaveis = Responsavel::orderBy('nome')->get();

        return view('termos.edit', compact('termo', 'idosas', 'responsaveis'));
    }

    public function update(Request $request, TermoAbrigamento $termo)
    {
        $data = $request->validate([
            'idosa_id' => ['required', 'exists:idosas,id'],
            'responsavel_id' => ['required', 'exists:responsaveis,id'],
            'data_inicio' => ['nullable', 'date'],
            'observacoes' => ['nullable', 'string'],
            'assinado_responsavel' => ['nullable', 'boolean'],
            'assinado_psicologo' => ['nullable', 'boolean'],
            'assinado_assistente_social' => ['nullable', 'boolean'],
        ]);

        $data['assinado_responsavel'] = $request->boolean('assinado_responsavel');
        $data['assinado_psicologo'] = $request->boolean('assinado_psicologo');
        $data['assinado_assistente_social'] = $request->boolean('assinado_assistente_social');

        $termo->update($data);

        return redirect()
            ->route('termos.index')
            ->with('success', 'Termo de abrigamento atualizado com sucesso.');
    }

    public function destroy(TermoAbrigamento $termo)
    {
        $termo->delete();

        return redirect()
            ->route('termos.index')
            ->with('success', 'Termo de abrigamento removido com sucesso.');
    }
}