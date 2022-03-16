<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientPersona;
use App\ClientPersonaEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientPersonaEmailController extends Controller
{

    public function index(Client $client, ClientPersona $clientPersona)
    {
        return response()->json(['data' => $clientPersona->emails], 200);
    }


    public function store(Client $client, ClientPersona $clientPersona, Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];
        $errors = [];
        $fields = [
            'email' => 'e-mail',
        ];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $new = $clientPersona->emails()->create([
                'email' => $request->email,
            ]);
            return response()->json(['data' => $new], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function main(Client $client, ClientPersona $clientPersona, ClientPersonaEmail $clientPersonaEmail)
    {
        $clientPersona->emails()->update(['main' => 0]);
        $clientPersonaEmail->update(['main' => 1]);
        return response()->json([
            'data' => $clientPersona->emails()->orderBy('main', 'desc')->get()
        ], 200);
    }

    public function destroy(Client $client,  ClientPersona $clientPersona, ClientPersonaEmail $clientPersonaEmail)
    {
        try {
            $clientPersonaEmail->delete();
            return response()->json(['message' => 'Deletado com sucesso'], 200);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
