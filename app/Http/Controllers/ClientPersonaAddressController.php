<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientPersona;
use App\ClientPersonaAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientPersonaAddressController extends Controller
{

    public function index(Client $client, ClientPersona $clientPersona)
    {
        return response()->json(['data' => $clientPersona->addresses()->orderBy('main', 'desc')->get()], 200);
    }

    public function store(Client $client, ClientPersona $clientPersona, Request $request)
    {
        $rules = [
            'zip' => 'required|string|min:7|max:9',
            'state' => 'required|string|min:2|max:2',
            'city' => 'required|string|min:3',
            'neighborhood' => 'required|string|min:3',
            'street' => 'required|string|min:5',
            'street_number' => 'nullable|integer|min:0|max:99999',
            'complement' => 'nullable|string|min:3',
        ];
        $errors = [];
        $fields = [
            'zip' => 'cep',
            'state' => 'uf',
            'city' => 'cidade',
            'neighborhood' => 'bairro',
            'street' => 'logradouro',
            'street_number' => 'número',
            'complement' => 'complemento',
        ];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $new = $clientPersona->addresses()->create([
                'zip' => $request->zip,
                'state' => $request->state,
                'city' => $request->city,
                'neighborhood' => $request->neighborhood,
                'street' => $request->street,
                'number' => $request->street_number,
                'complement' => $request->complement ?? ""
            ]);
            return response()->json(['data' => $new], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function main(Client $client, ClientPersona $clientPersona, ClientPersonaAddress $clientPersonaAddress)
    {
        $clientPersona->addresses()->update(['main' => 0]);
        $clientPersonaAddress->update(['main' => 1]);
        return response()->json(['data' => $clientPersona->addresses()->orderBy('main', 'desc')->get()], 200);
    }

    public function destroy(Client $client,  ClientPersona $clientPersona, ClientPersonaAddress $clientPersonaAddress)
    {
        try {
            $clientPersonaAddress->delete();
            return response()->json(['message' => 'Deletado com sucesso'], 200);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
