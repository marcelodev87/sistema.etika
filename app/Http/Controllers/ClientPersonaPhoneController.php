<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientPersona;
use App\ClientPersonaPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientPersonaPhoneController extends Controller
{
    public function index(Client $client, ClientPersona $clientPersona)
    {
        return response()->json(['data' => $clientPersona->phones()->orderBy('main', 'desc')->get()], 200);
    }

    public function store(Client $client, ClientPersona $clientPersona, Request $request)
    {
        $rules = [
            'phone' => 'required|string|min:14|max:15',
        ];
        $errors = [];
        $fields = [
            'phone' => 'telefone',
        ];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $new = $clientPersona->phones()->create([
                'phone' => $request->phone,
            ]);
            return response()->json(['data' => $new], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function main(Client $client, ClientPersona $clientPersona, ClientPersonaPhone $clientPersonaPhone)
    {
        $clientPersona->phones()->update(['main' => 0]);
        $clientPersonaPhone->update(['main' => 1]);
        return response()->json(['data' => $clientPersona->phones()->orderBy('main', 'desc')->get()], 200);
    }

    public function destroy(Client $client,  ClientPersona $clientPersona, ClientPersonaPhone $clientPersonaPhone)
    {
        try {
            $clientPersonaPhone->delete();
            return response()->json(['message' => 'Deletado com sucesso'], 200);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
