<?php

namespace App\Http\Controllers;

use App\Models\Idosa;
use App\Models\PlanoIndividual;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlanoIndividualController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $planos = PlanoIndividual::with('idosa')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('idosa', function ($subQuery) use ($search) {
                    $subQuery->where('nome', 'like', "%{$search}%")
                        ->orWhere('cpf', 'like', '%' . preg_replace('/\D+/', '', $search) . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('planos.index', compact('planos', 'search'));
    }

    public function create()
    {
        $idosas = Idosa::orderBy('nome')->get();

        return view('planos.create', compact('idosas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idosa_id' => ['required', 'exists:idosas,id', 'unique:plano_individuals,idosa_id'],
            'data_ingresso' => ['nullable', 'date'],
            'numero_prontuario' => ['nullable', 'string', 'max:100'],
            'origem_residencia' => ['nullable', 'string', 'max:255'],
            'motivo_institucionalizacao' => ['nullable', 'string'],
            'renda' => ['nullable', 'numeric', 'min:0'],
            'administra_financeiro' => ['nullable', 'string', 'max:255'],
            'escolaridade' => ['nullable', 'string', 'max:255'],
            'profissao' => ['nullable', 'string', 'max:255'],
            'religiao' => ['nullable', 'string', 'max:255'],
            'diagnostico_medico' => ['nullable', 'string'],
            'grau_dependencia' => ['nullable', 'string', 'max:255'],
            'possui_plano_saude' => ['nullable', 'boolean'],
            'descricao_medicacao' => ['nullable', 'string'],
            'restricao_alimentar' => ['nullable', 'string'],
            'rotina' => ['nullable', 'string'],
        ]);

        $data['possui_plano_saude'] = $request->boolean('possui_plano_saude');

        PlanoIndividual::create($data);

        return redirect()
            ->route('planos.index')
            ->with('success', 'Plano individual cadastrado com sucesso.');
    }

    public function show(PlanoIndividual $plano)
    {
        $plano->load('idosa');

        return view('planos.show', compact('plano'));
    }

    public function edit(PlanoIndividual $plano)
    {
        $idosas = Idosa::orderBy('nome')->get();

        return view('planos.edit', compact('plano', 'idosas'));
    }

    public function update(Request $request, PlanoIndividual $plano)
    {
        $data = $request->validate([
            'idosa_id' => [
                'required',
                'exists:idosas,id',
                Rule::unique('plano_individuals', 'idosa_id')->ignore($plano->id),
            ],
            'data_ingresso' => ['nullable', 'date'],
            'numero_prontuario' => ['nullable', 'string', 'max:100'],
            'origem_residencia' => ['nullable', 'string', 'max:255'],
            'motivo_institucionalizacao' => ['nullable', 'string'],
            'renda' => ['nullable', 'numeric', 'min:0'],
            'administra_financeiro' => ['nullable', 'string', 'max:255'],
            'escolaridade' => ['nullable', 'string', 'max:255'],
            'profissao' => ['nullable', 'string', 'max:255'],
            'religiao' => ['nullable', 'string', 'max:255'],
            'diagnostico_medico' => ['nullable', 'string'],
            'grau_dependencia' => ['nullable', 'string', 'max:255'],
            'possui_plano_saude' => ['nullable', 'boolean'],
            'descricao_medicacao' => ['nullable', 'string'],
            'restricao_alimentar' => ['nullable', 'string'],
            'rotina' => ['nullable', 'string'],
        ]);

        $data['possui_plano_saude'] = $request->boolean('possui_plano_saude');

        $plano->update($data);

        return redirect()
            ->route('planos.index')
            ->with('success', 'Plano individual atualizado com sucesso.');
    }

    public function destroy(PlanoIndividual $plano)
    {
        $plano->delete();

        return redirect()
            ->route('planos.index')
            ->with('success', 'Plano individual removido com sucesso.');
    }
}