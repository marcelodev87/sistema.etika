<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientMandato;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientMandatoController extends Controller
{

    public function all(){
        $mandatos = ClientMandato::all();
        return view('mandatos.all', compact('mandatos'));
    }
    public function index(Client $client)
    {
        return view('mandatos.index', compact('client'));
    }

    public function create(Client $client)
    {
        return view('mandatos.create', compact('client'));
    }

    public function store(Request $request, Client $client)
    {
        $rules = [
            'start_at' => 'required|date_format:d/m/Y',
            'end_at' => 'required|date_format:d/m/Y',
        ];
        $errors = [];
        $fields = [];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            session()->flash('flash-warning', $validator->errors());
            return redirect()->back()->withInput($request->all());
        }
        try {
            $client->mandatos()->create([
                'start_at' => Carbon::createFromFormat('d/m/Y', $request->start_at)->format('Y-m-d'),
                'end_at' => Carbon::createFromFormat('d/m/Y', $request->end_at)->format('Y-m-d'),
            ]);
            session()->flash('flash-success', 'Mandato adicionado com sucesso');
            return redirect()->route('app.clients.mandatos.index', $client->id);
        } catch (\Exception $e) {
            session()->flash('flash-warning', $e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
    }

    public function destroy(Client $client, ClientMandato $clientMandato)
    {
        $clientMandato->delete();
        session()->flash('flash-success', 'O mandato foi deletado');
        return redirect()->back();
    }
}
