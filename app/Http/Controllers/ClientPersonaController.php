<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientPersonaAddress;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\ClientPersona;
use Illuminate\Http\Request;

class ClientPersonaController extends Controller
{

    public function index(Client $client)
    {
        return view('clients.members.index', compact('client'));
    }


    public function store(Client $client, Request $request)
    {
        $rules = [
            'name' => 'required|string|min:3|max:191',
            'document' => 'required|string|min:14|max:14',
            'role' => 'required|string|min:3|max:191',
            'gender' => 'required|string',
            'natural' => 'required',
            'dob' => 'nullable|date_format:d/m/Y',
            'marital_status' => 'nullable|string|min:3',
            'profession' => 'nullable|string|min:3',
        ];
        $errors = [];
        $fields = [
            'name' => 'nome completo',
            'document' => 'documento',
            'role' => 'cargo',
            'gender' => 'sexo',
            'marital_status' => 'estado civil',
            'profession' => 'profissão',
            'dob' => 'dt. nascimento',
        ];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }
        try {
            $member = $client->members()->create([
                'name' => $request->name,
                'document' => $request->document,
                'role' => $request->role,
                'gender' => $request->gender,
                'marital_status' => $request->marital_status,
                'natural' => $request->natural,
                'profession' => $request->profession,
                'dob' => ($request->dob) ? Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d') : null,
                'rg' => $request->rg,
                'rg_expedidor' => $request->rg_expedidor
            ]);
            return response()->json(['data' => $member], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    public function show(Client $client, ClientPersona $clientPersona)
    {

        $data = [];
        $data['dob'] = ($clientPersona->dob) ? Carbon::parse($clientPersona->dob)->format('d/m/Y') : null;
        $data['gender'] = $clientPersona->gender;
        $data['profession'] = $clientPersona->profession;
        $data['marital_status'] = $clientPersona->marital_status;
        $data['rg'] = $clientPersona->rg;
        $data['rg_expedidor'] = $clientPersona->rg_expedidor;
        $data['name'] = $clientPersona->name;
        $data['role'] = $clientPersona->role;
        $data['document'] = $clientPersona->document;
        $data['natural'] = $clientPersona->natural;
        return response()->json(['data' => $data], 200);
    }


    public function update(Request $request, Client $client, ClientPersona $clientPersona)
    {
        $rules = [
            'name' => 'required|string|min:3|max:191',
            'document' => 'required|string|min:14|max:14',
            'role' => 'required|string|min:3|max:191',
            'gender' => 'required|string',
            'natural' => 'required|string',
            'marital_status' => 'nullable|string|min:3',
            'profession' => 'nullable|string|min:3',
            'dob' => 'nullable|date_format:d/m/Y',
        ];
        $errors = [];
        $fields = [
            'name' => 'nome completo',
            'document' => 'documento',
            'role' => 'cargo',
            'gender' => 'sexo',
            'marital_status' => 'estado civil',
            'profession' => 'profissão',
            'dob' => 'dt. nascimento',
        ];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }
        try {
            $clientPersona->update([
                'name' => $request->name,
                'document' => $request->document,
                'role' => $request->role,
                'gender' => $request->gender,
                'marital_status' => $request->marital_status,
                'profession' => $request->profession,
                'dob' => ($request->dob) ? Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d') : null,
                'rg' => $request->rg,
                'rg_expedidor' => $request->rg_expedidor,
                'natural' => $request->natural
            ]);
            return response()->json(['data' => $clientPersona], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    public function information(Client $client, ClientPersona $clientPersona)
    {
        $persona = $clientPersona;
        $emails = $clientPersona->emails;
        $phones = $clientPersona->phones;
        $addresses = $clientPersona->addresses;
        return response()->json(['data' => [
            'persona' => [
                'name' => $persona->name,
                'document'=> $persona->document,
                'role' => $persona->role,
                'marital_status' => $persona->marital_status,
                'profession' => $persona->profession,
                'dob' => $persona->dob->format('d/m/Y'),
                'rg' => $persona->rg,
                'natural' => $persona->natural,
                'gender' => $persona->gender
            ],
            'emails' => $emails,
            'phones' => $phones,
            'addresses' => $addresses,
        ]], 200);
    }


    public function destroy(Client $client, ClientPersona $clientPersona)
    {
        $name = $clientPersona->name;
        try {
            $clientPersona->delete();
            return response()->json(['message' => 'O cliente ' . $name . ' foi excluído.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

    }
}
