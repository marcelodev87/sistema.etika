<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientMandato;
use App\ClientPersona;
use App\ClientProcess;
use App\InternalTask;
use App\InternalProcess;
use App\Subscription;
use App\ClientTask;
use App\ClientProcessLog;
use App\ClientSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ExternalController extends Controller
{
    private function authenticateUser($email, $password)
    {
        return Auth::attempt(['email' => $email, 'password' => $password]);
    }
    public function getEnterprises(Request $request)
    {
        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $clients = Client::all();

            return response()->json(['result' => $clients], 200);
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getEnterpriseById($id, Request $request)
    {
        if (is_null($id)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');


        if ($this->authenticateUser($email, $password)) {
            $enterprise = Client::find($id);

            if (!$enterprise) {
                return response()->json([
                    'message' => 'Empresa não encontrada',
                    'result' => []
                ], 404);
            }
            return response()->json(['result' => $enterprise], 200);
        }

        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getEnterpriseByDocument(Request $request, $document)
    {

        if (is_null($document)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }
        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',

        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $doc = preg_replace('/[^0-9]/', '', $document);

            $enterprise = Client::whereRaw("REPLACE(REPLACE(REPLACE(document, '.', ''), '-', ''), '/', '') = ?", $doc)->first();

            if (!$enterprise) {
                return response()->json([
                    'message' => 'Empresa não encontrada',
                    'result' => []
                ], 404);
            }
            return response()->json(['result' => $enterprise], 200);
        }

        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function createEnterprise(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:5|max:200',
            'document' => 'required|string|min:14|max:18',
            'type' => 'required',
            'internal_code' => 'nullable|integer',
            'email' => 'required|email',
            'phone' => 'nullable|string|min:14',
            'zip' => 'required|string|min:9',
            'state' => 'required|string|min:2|max:2',
            'city' => 'required|string|min:3',
            'neighborhood' => 'required|string|min:3',
            'street' => 'required|string|min:5',
            'street_number' => 'nullable|integer|min:0|max:99999',
            'complement' => 'nullable|string|min:3',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ];

        $errors = [];
        $fields = [
            'name' => '\'nome completo\'',
            'document' => '\'documento\'',
            'type' => '\'tipo\'',
            'internal_code' => '\'codigo interno\'',
            'zip' => '\'cep\'',
            'state' => '\'uf\'',
            'city' => '\'cidade\'',
            'neighborhood' => '\'bairro\'',
            'street' => '\'logradouro\'',
            'street_number' => '\'número\'',
            'complement' => '\'complemento\'',
            'login.email' => '\'email de login\'',
            'login.password' => '\'senha\''
        ];

        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }


        // LOGICA PARA VERIFICAR SE DOCUMENT JA ESTA CADASTRADO
        // $document = preg_replace('/[^0-9]/', '', $request->document);
        // $existingClient = Client::where('document', $document)->first();
        // if ($existingClient) {
        //     return response()->json(['message' => 'Já existe um cliente cadastrado com esse documento'], 400);
        // }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $newClient = Client::create([
                    'name' => $request->name,
                    'document' => $request->document,
                    'type' => $request->type,
                    'internal_code' => $request->internal_code,
                    'zip' => $request->zip,
                    'state' => $request->state,
                    'city' => $request->city,
                    'neighborhood' => $request->neighborhood,
                    'street' => $request->street,
                    'street_number' => $request->street_number,
                    'complement' => $request->complement ?? "",
                    'email' => $request->email,
                    'phone' => $request->phone,
                ]);
                return response()->json([
                    'message' => 'Novo cliente criado com sucesso',
                    'result' => $newClient
                ], 201);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        } else {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }
    }
    public function updateEnterprise(Request $request, Client $client)
    {
        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);
        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {

            $rules = [
                'name' => 'required|string|min:5|max:200',
                'document' => 'required|string|min:14|max:18',
                'type' => 'required',
                'internal_code' => 'nullable|integer',
                'email' => 'required|email',
                'phone' => 'nullable|string|min:14',
                'zip' => 'required|string|min:9',
                'state' => 'required|string|min:2|max:2',
                'city' => 'required|string|min:3',
                'neighborhood' => 'required|string|min:3',
                'street' => 'required|string|min:5',
                'street_number' => 'nullable|integer|min:0|max:99999',
                'complement' => 'nullable|string|min:3',
            ];

            $errors = [];
            $fields = [
                'name' => '\'nome completo\'',
                'document' => '\'documento\'',
                'type' => '\'tipo\'',
                'internal_code' => '\'codigo interno\'',
                'zip' => '\'cep\'',
                'state' => '\'uf\'',
                'city' => '\'cidade\'',
                'neighborhood' => '\'bairro\'',
                'street' => '\'logradouro\'',
                'street_number' => '\'número\'',
                'complement' => '\'complemento\'',
            ];

            $validator = Validator::make($request->all(), $rules, $errors, $fields);
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }

            try {
                $client->update([
                    'name' => $request->name,
                    'document' => $request->document,
                    'type' => $request->type,
                    'internal_code' => $request->internal_code,
                    'zip' => $request->zip,
                    'state' => $request->state,
                    'city' => $request->city,
                    'neighborhood' => $request->neighborhood,
                    'street' => $request->street,
                    'street_number' => $request->street_number,
                    'complement' => $request->complement ?? "",
                    'email' => $request->email,
                    'phone' => $request->phone,
                ]);
                return response()->json([
                    'message' => 'Cliente editado com sucesso.',
                    'result' => $client
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Erro ao editar'], 500);
            }
        }

        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }
    public function updateEnterpriseByDocument(Request $request, $document)
    {
        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);
        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {

            $rules = [
                'name' => 'required|string|min:5|max:200',
                'document' => 'required|string|min:14|max:18',
                'type' => 'required',
                'internal_code' => 'nullable|integer',
                'email' => 'required|email',
                'phone' => 'nullable|string|min:14',
                'zip' => 'required|string|min:9',
                'state' => 'required|string|min:2|max:2',
                'city' => 'required|string|min:3',
                'neighborhood' => 'required|string|min:3',
                'street' => 'required|string|min:5',
                'street_number' => 'nullable|integer|min:0|max:99999',
                'complement' => 'nullable|string|min:3',
            ];

            $errors = [];
            $fields = [
                'name' => '\'nome completo\'',
                'document' => '\'documento\'',
                'type' => '\'tipo\'',
                'internal_code' => '\'codigo interno\'',
                'zip' => '\'cep\'',
                'state' => '\'uf\'',
                'city' => '\'cidade\'',
                'neighborhood' => '\'bairro\'',
                'street' => '\'logradouro\'',
                'street_number' => '\'número\'',
                'complement' => '\'complemento\'',
            ];

            $validator = Validator::make($request->all(), $rules, $errors, $fields);
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }

            try {
                $doc = preg_replace('/[^0-9]/', '', $document);
                $enterprise = Client::whereRaw("REPLACE(REPLACE(REPLACE(document, '.', ''), '-', ''), '/', '') = ?", $doc)->first();

                $enterprise->update([
                    'name' => $request->name,
                    'document' => $request->document,
                    'type' => $request->type,
                    'internal_code' => $request->internal_code,
                    'zip' => $request->zip,
                    'state' => $request->state,
                    'city' => $request->city,
                    'neighborhood' => $request->neighborhood,
                    'street' => $request->street,
                    'street_number' => $request->street_number,
                    'complement' => $request->complement ?? "",
                    'email' => $request->email,
                    'phone' => $request->phone,
                ]);
                return response()->json([
                    'message' => 'Cliente editado com sucesso.',
                    'result' => $enterprise
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Erro ao editar'], 500);
            }
        }

        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }
    public function getMembers($id, Request $request)
    {
        if (is_null($id)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $members = ClientPersona::where('client_id', $id)
                ->with(['phones', 'emails', 'addresses']);

            if ($request->has('role')) {
                $role = $request->input('role');
                $members->where('role', $role);
            }

            $members = $members->get();

            if ($members->isEmpty()) {
                return response()->json([
                    'message' => 'Não há nenhum membro vinculado ao ID informado.',
                    'result' => []
                ], 200);
            }

            return response()->json(['result' => $members], 200);
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getMembersByDocument($document, Request $request)
    {
        if (is_null($document)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $doc = preg_replace('/[^0-9]/', '', $document);
            $members = ClientPersona::with('client')
                ->with(['phones', 'emails', 'addresses'])
                ->get()
                ->filter(function ($member) use ($doc) {
                    $clientDocument = str_replace(['.', '-', '/', ','], '', $member->client->document);
                    return $clientDocument == $doc;
                });

            if ($request->has('role')) {
                $role = $request->input('role');
                $members = $members->filter(function ($member) use ($role) {
                    return $member->role == $role;
                });
            }

            if ($members->isEmpty()) {
                return response()->json([
                    'message' => 'Não há nenhum membro vinculado ao ID informado.',
                    'result' => []
                ], 200);
            }

            return response()->json(['result' => $members], 200);
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getMemberById(Request $request, $member, $client)
    {
        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        if (is_null($member) || is_null($client)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $member = ClientPersona::where('client_id', $client)
                ->where('id', $member)
                ->with(['phones', 'emails', 'addresses'])
                ->first();

            if (!$member) {
                return response()->json([
                    'message' => 'Membro não encontrado',
                    'result' => []
                ], 404);
            }
            return response()->json(['result' => $member], 200);
        }

        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getMemberByDoc(Request $request, $member, $client)
    {
        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        if (is_null($member) || is_null($client)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $doc_member = preg_replace('/[^0-9]/', '', $member);
            $doc_client = preg_replace('/[^0-9]/', '', $client);

            $member = ClientPersona::with(['client', 'phones', 'emails', 'addresses'])
                ->get()
                ->filter(function ($member) use ($doc_member, $doc_client) {
                    $clientDocument = str_replace(['.', '-', '/', ','], '', $member->client->document);
                    $memberDocument = str_replace(['.', '-', '/', ','], '', $member->document);
                    return $clientDocument == $doc_client && $memberDocument == $doc_member;
                })->first();

            if (!$member) {
                return response()->json([
                    'message' => 'Membro não encontrado',
                    'result' => []
                ], 404);
            }
            return response()->json(['result' => $member], 200);
        }

        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function createMember(Request $request, Client $client)
    {
        if (is_null($client)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $rules = [
            'name' => 'required|string|min:3|max:191',
            'document' => 'required|string|min:14|max:14',
            'role' => 'required|string|min:3|max:191',
            'gender' => 'required|string',
            'natural' => 'required',
            'dob' => 'nullable|date_format:d/m/Y',
            'marital_status' => 'nullable|string|min:3',
            'profession' => 'nullable|string|min:3',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
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
            'login.email' => 'email de login',
            'login.password' => 'senha'
        ];

        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $createdMember = $client->members()->create([
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
                return response()->json([
                    'message' => "Novo membro vinculado com sucesso à empresa {$client->name}.",
                    'result' => $createdMember
                ], 201);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function createMemberByDoc(Request $request, $client)
    {
        if (is_null($client)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $rules = [
            'name' => 'required|string|min:3|max:191',
            'document' => 'required|string|min:14|max:14',
            'role' => 'required|string|min:3|max:191',
            'gender' => 'required|string',
            'natural' => 'required',
            'dob' => 'nullable|date_format:d/m/Y',
            'marital_status' => 'nullable|string|min:3',
            'profession' => 'nullable|string|min:3',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
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
            'login.email' => 'email de login',
            'login.password' => 'senha'
        ];

        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $doc = preg_replace('/[^0-9]/', '', $client);

            try {
                $enterprise = Client::whereRaw("REPLACE(REPLACE(REPLACE(document, '.', ''), '-', ''), '/', '') = ?", $doc)->first();

                $createdMember = $enterprise->members()->create([
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
                return response()->json([
                    'message' => "Novo membro vinculado com sucesso à empresa {$enterprise->name}.",
                    'result' => $createdMember
                ], 201);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function updateMember(Request $request, $member, $client)
    {
        if (is_null($member) || is_null($client)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $rules = [
            'name' => 'required|string|min:3|max:191',
            'document' => 'required|string|min:14|max:14',
            'role' => 'required|string|min:3|max:191',
            'gender' => 'required|string',
            'natural' => 'required|string',
            'marital_status' => 'nullable|string|min:3',
            'profession' => 'nullable|string|min:3',
            'dob' => 'nullable|date_format:d/m/Y',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
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
            'login.email' => 'email de login',
            'login.password' => 'senha'
        ];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $member = ClientPersona::where('client_id', $client)
                    ->where('id', $member)
                    ->with(['phones', 'emails', 'addresses'])
                    ->first();
                if ($member) {
                    $member->update([
                        'name' => $request->name,
                        'document' => $request->document,
                        'role' => $request->role,
                        'gender' => $request->gender,
                        'marital_status' => $request->marital_status,
                        'natural' => $request->natural,
                        'profession' => $request->profession,
                        'dob' => ($request->dob) ? Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d') : null,
                        'rg' => $request->rg,
                        'rg_expedidor' => $request->rg_expedidor,
                    ]);

                    $updatedMember = $member->fresh();

                    return response()->json([
                        'message' => "Membro editado com sucesso",
                        'result' => $updatedMember
                    ], 200);
                }
                return response()->json([
                    'message' => 'Membro não encontrado',
                    'result' => []
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function updateMemberByDoc(Request $request, $member, $client)
    {
        if (is_null($member) || is_null($client)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $rules = [
            'name' => 'required|string|min:3|max:191',
            'document' => 'required|string|min:14|max:14',
            'role' => 'required|string|min:3|max:191',
            'gender' => 'required|string',
            'natural' => 'required|string',
            'marital_status' => 'nullable|string|min:3',
            'profession' => 'nullable|string|min:3',
            'dob' => 'nullable|date_format:d/m/Y',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
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
            'login.email' => 'email de login',
            'login.password' => 'senha'
        ];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $doc_member = preg_replace('/[^0-9]/', '', $member);
                $doc_client = preg_replace('/[^0-9]/', '', $client);
                $member = ClientPersona::with(['phones', 'emails', 'addresses'])
                    ->get()
                    ->filter(function ($member) use ($doc_member, $doc_client) {
                        $clientDocument = str_replace(['.', '-', '/', ','], '', $member->client->document);
                        $memberDocument = str_replace(['.', '-', '/', ','], '', $member->document);
                        return $clientDocument == $doc_client && $memberDocument == $doc_member;
                    })->first();

                if ($member) {
                    $member->update([
                        'name' => $request->name,
                        'document' => $request->document,
                        'role' => $request->role,
                        'gender' => $request->gender,
                        'marital_status' => $request->marital_status,
                        'natural' => $request->natural,
                        'profession' => $request->profession,
                        'dob' => ($request->dob) ? Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d') : null,
                        'rg' => $request->rg,
                        'rg_expedidor' => $request->rg_expedidor,
                    ]);

                    $updatedMember = $member->fresh();

                    return response()->json([
                        'message' => "Membro editado com sucesso",
                        'result' => $updatedMember
                    ], 200);
                }
                return response()->json([
                    'message' => 'Membro não encontrado',
                    'result' => []
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getMandatos($client, Request $request)
    {
        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        if (is_null($client)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $members = ClientMandato::where('client_id', $client)
                ->with('client')
                ->get();

            if ($members->isEmpty()) {
                return response()->json([
                    'message' => 'Não há nenhum mandato para essa empresa',
                    'result' => []
                ], 200);
            }

            return response()->json(['result' => $members], 200);
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getMandatosByDoc($document, Request $request)
    {
        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        if (is_null($document)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $doc_client = preg_replace('/[^0-9]/', '', $document);
            $mandato = ClientMandato::with('client')
                ->get()
                ->filter(function ($mandato) use ($doc_client) {
                    $clientDocument = str_replace(['.', '-', '/', ','], '', $mandato->client->document);
                    return $clientDocument == $doc_client;
                });

            if ($mandato->isEmpty()) {
                return response()->json([
                    'message' => 'Não há nenhum mandato para essa empresa',
                    'result' => []
                ], 200);
            }

            return response()->json(['result' => $mandato], 200);
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function setMemberPhone(Request $request, $member, $enterprise)
    {
        if (is_null($member) || is_null($enterprise)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $rules = [
            'phone' => 'required|string|min:14|max:15',
            'main' => 'required|integer|in:0,1',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ];
        $errors = [];
        $fields = [
            'phone' => 'telefone',
            'main' => 'principal',
            'login.email' => 'email de login',
            'login.password' => 'senha'
        ];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $clientPersona = ClientPersona::where('client_id', $enterprise)
                    ->where('id', $member)->first();

                if (!$clientPersona) {
                    return response()->json(['message' => 'Membro não encontrado'], 404);
                }

                if ($request->main == 1) {
                    $clientPersona->phones()->where('main', 1)->update(['main' => 0]);
                }

                $newPhone = $clientPersona->phones()->create([
                    'phone' => $request->phone,
                    'main' => $request->main,
                ]);

                return response()->json([
                    'message' => "Telefone alocado com sucesso",
                    'result' => $newPhone
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }
    public function setMemberPhoneByDoc(Request $request, $member, $enterprise)
    {
        if (is_null($member) || is_null($enterprise)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $rules = [
            'phone' => 'required|string|min:14|max:15',
            'main' => 'required|integer|in:0,1',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ];
        $errors = [];
        $fields = [
            'phone' => 'telefone',
            'main' => 'principal',
            'login.email' => 'email de login',
            'login.password' => 'senha'
        ];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {

            try {
                $doc_member = preg_replace('/[^0-9]/', '', $member);
                $doc_client = preg_replace('/[^0-9]/', '', $enterprise);

                $clientPersona = ClientPersona::with('client')
                    ->get()
                    ->filter(function ($member) use ($doc_member, $doc_client) {
                        $clientDocument = str_replace(['.', '-', '/', ','], '', $member->client->document);
                        $memberDocument = str_replace(['.', '-', '/', ','], '', $member->document);
                        return $clientDocument == $doc_client && $memberDocument == $doc_member;
                    })->first();

                if (!$clientPersona) {
                    return response()->json(['message' => 'Membro não encontrado'], 404);
                }

                if ($request->main == 1) {
                    $clientPersona->phones()->where('main', 1)->update(['main' => 0]);
                }

                $newPhone = $clientPersona->phones()->create([
                    'phone' => $request->phone,
                    'main' => $request->main,
                ]);

                return response()->json([
                    'message' => "Telefone alocado com sucesso",
                    'result' => $newPhone
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }
    public function setMemberEmail(Request $request, $member, $enterprise)
    {
        if (is_null($member) || is_null($enterprise)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $rules = [
            'email' => 'required|email',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ];
        $errors = [];
        $fields = [
            'email' => 'e-mail',
            'login.email' => 'email de login',
            'login.password' => 'senha'
        ];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $clientPersona = ClientPersona::where('client_id', $enterprise)
                    ->where('id', $member)->first();

                if (!$clientPersona) {
                    return response()->json(['message' => 'Membro não encontrado'], 404);
                }

                $newEmail = $clientPersona->emails()->create([
                    'email' => $request->email,
                ]);

                return response()->json([
                    'message' => "E-mail alocado com sucesso",
                    'result' => $newEmail
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }
    public function setMemberEmailByDoc(Request $request, $member, $enterprise)
    {
        if (is_null($member) || is_null($enterprise)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $rules = [
            'email' => 'required|email',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ];
        $errors = [];
        $fields = [
            'email' => 'e-mail',
            'login.email' => 'email de login',
            'login.password' => 'senha'
        ];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $doc_member = preg_replace('/[^0-9]/', '', $member);
                $doc_client = preg_replace('/[^0-9]/', '', $enterprise);

                $clientPersona = ClientPersona::with('client')
                    ->get()
                    ->filter(function ($member) use ($doc_member, $doc_client) {
                        $clientDocument = str_replace(['.', '-', '/', ','], '', $member->client->document);
                        $memberDocument = str_replace(['.', '-', '/', ','], '', $member->document);
                        return $clientDocument == $doc_client && $memberDocument == $doc_member;
                    })->first();

                if (!$clientPersona) {
                    return response()->json(['message' => 'Membro não encontrado'], 404);
                }

                $newEmail = $clientPersona->emails()->create([
                    'email' => $request->email,
                ]);

                return response()->json([
                    'message' => "E-mail alocado com sucesso",
                    'result' => $newEmail
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }
    public function setMemberAddress(Request $request, $member, $enterprise)
    {
        if (is_null($member) || is_null($enterprise)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $rules = [
            'zip' => 'required|string|min:7|max:9',
            'state' => 'required|string|min:2|max:2',
            'city' => 'required|string|min:3',
            'neighborhood' => 'required|string|min:3',
            'street' => 'required|string|min:5',
            'street_number' => 'nullable|integer|min:0|max:99999',
            'complement' => 'nullable|string|min:3',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ];
        $errors = [];
        $fields = [
            'login.email' => 'email de login',
            'login.password' => 'senha',
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

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $clientPersona = ClientPersona::where('client_id', $enterprise)
                    ->where('id', $member)->first();

                if (!$clientPersona) {
                    return response()->json(['message' => 'Membro não encontrado'], 404);
                }

                $newAddress = $clientPersona->addresses()->create([
                    'zip' => $request->zip,
                    'state' => $request->state,
                    'city' => $request->city,
                    'neighborhood' => $request->neighborhood,
                    'street' => $request->street,
                    'number' => $request->street_number,
                    'complement' => $request->complement ?? ""
                ]);

                return response()->json([
                    'message' => "Endereço alocado com sucesso",
                    'result' => $newAddress
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }
    public function setMemberAddressByDoc(Request $request, $member, $enterprise)
    {
        if (is_null($member) || is_null($enterprise)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $rules = [
            'zip' => 'required|string|min:7|max:9',
            'state' => 'required|string|min:2|max:2',
            'city' => 'required|string|min:3',
            'neighborhood' => 'required|string|min:3',
            'street' => 'required|string|min:5',
            'street_number' => 'nullable|integer|min:0|max:99999',
            'complement' => 'nullable|string|min:3',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ];
        $errors = [];
        $fields = [
            'login.email' => 'email de login',
            'login.password' => 'senha',
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

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $doc_member = preg_replace('/[^0-9]/', '', $member);
                $doc_client = preg_replace('/[^0-9]/', '', $enterprise);

                $clientPersona = ClientPersona::with('client')
                    ->get()
                    ->filter(function ($member) use ($doc_member, $doc_client) {
                        $clientDocument = str_replace(['.', '-', '/', ','], '', $member->client->document);
                        $memberDocument = str_replace(['.', '-', '/', ','], '', $member->document);
                        return $clientDocument == $doc_client && $memberDocument == $doc_member;
                    })->first();

                if (!$clientPersona) {
                    return response()->json(['message' => 'Membro não encontrado'], 404);
                }

                $newAddress = $clientPersona->addresses()->create([
                    'zip' => $request->zip,
                    'state' => $request->state,
                    'city' => $request->city,
                    'neighborhood' => $request->neighborhood,
                    'street' => $request->street,
                    'number' => $request->street_number,
                    'complement' => $request->complement ?? ""
                ]);

                return response()->json([
                    'message' => "Endereço alocado com sucesso",
                    'result' => $newAddress
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }
    public function createMandato(Client $client, Request $request)
    {
        if (is_null($client)) {
            return response()->json(['message' => 'Cliente não encontrado'], 400);
        }

        $rules = [
            'start_at' => 'required|date_format:Y-m-d H:i:s',
            'end_at' => 'required|date_format:Y-m-d H:i:s',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $createdMandato = $client->mandatos()->create([
                    'start_at' => Carbon::createFromFormat('Y-m-d H:i:s', $request->start_at)->format('Y-m-d'),
                    'end_at' => Carbon::createFromFormat('Y-m-d H:i:s', $request->end_at)->format('Y-m-d'),
                ]);

                return response()->json([
                    'message' => 'Mandato adicionado com sucesso',
                    'result' => $createdMandato
                ], 201);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }

        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function createMandatoByDoc($document, Request $request)
    {
        if (is_null($document)) {
            return response()->json(['message' => 'Cliente não encontrado'], 400);
        }

        $rules = [
            'start_at' => 'required|date_format:Y-m-d H:i:s',
            'end_at' => 'required|date_format:Y-m-d H:i:s',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $doc = preg_replace('/[^0-9]/', '', $document);

                $enterprise = Client::whereRaw("REPLACE(REPLACE(REPLACE(document, '.', ''), '-', ''), '/', '') = ?", $doc)->first();

                $createdMandato = $enterprise->mandatos()->create([
                    'start_at' => Carbon::createFromFormat('Y-m-d H:i:s', $request->start_at)->format('Y-m-d'),
                    'end_at' => Carbon::createFromFormat('Y-m-d H:i:s', $request->end_at)->format('Y-m-d'),
                ]);

                return response()->json([
                    'message' => 'Mandato adicionado com sucesso',
                    'result' => $createdMandato->load('client')
                ], 201);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }

        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getSubscriptions(Request $request)
    {
        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $subscriptions = Subscription::all();

            if ($subscriptions->isEmpty()) {
                return response()->json([
                    'message' => 'Não há nenhuma assinatura',
                    'result' => []
                ], 200);
            }

            return response()->json(['result' => $subscriptions], 200);
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getSubscriptionsEnterprise(Request $request, $id)
    {
        if (is_null($id)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $subscriptions = ClientSubscription::where('client_id', $id)
                ->with('subscription')
                ->first();

            if (!$subscriptions) {
                return response()->json([
                    'message' => 'Não há nenhuma assinatura',
                    'result' => []
                ], 200);
            }


            return response()->json(['result' => $subscriptions], 200);
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getSubscriptionsEnterpriseByDoc(Request $request, $document)
    {
        if (is_null($document)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }
        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $doc = preg_replace('/[^0-9]/', '', $document);

            $subscriptions = ClientSubscription::with(['subscription', 'client'])
                ->get()
                ->filter(function ($subscription) use ($doc) {
                    $clientDocument = str_replace(['.', '-', '/', ','], '', $subscription->client->document);
                    return $clientDocument == $doc;
                })
                ->first();

            if (!$subscriptions) {
                return response()->json([
                    'message' => 'Não há nenhuma assinatura',
                    'result' => []
                ], 200);
            }


            return response()->json(['result' => $subscriptions], 200);
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getTasks(Request $request)
    {
        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $tasks = InternalTask::all();
            if ($tasks->isEmpty()) {
                return response()->json([
                    'message' => 'Não há nenhuma tarefa',
                    'result' => []
                ], 200);
            }

            return response()->json(['result' => $tasks], 200);
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getTasksDetails(Request $request, $id)
    {

        if (is_null($id)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $tasks = InternalTask::where('id', $id)->first();
            if (!$tasks) {
                return response()->json([
                    'message' => 'Não há nenhuma tarefa',
                    'result' => []
                ], 200);
            }

            return response()->json(['result' => $tasks], 200);
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getTasksById(Request $request, $id)
    {
        if (is_null($id)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }
        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $tasks = ClientTask::where('client_id', $id)
                ->with(['task', 'client'])
                ->get();
            if ($tasks->isEmpty()) {
                return response()->json([
                    'message' => 'Não há nenhuma tarefa',
                    'result' => []
                ], 200);
            }

            return response()->json(['result' => $tasks], 200);
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getTasksByDoc(Request $request, $document)
    {
        if (is_null($document)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $doc = preg_replace('/[^0-9]/', '', $document);

            $tasks = ClientTask::with(['task', 'client'])
                ->get()
                ->filter(function ($task) use ($doc) {
                    $clientDocument = str_replace(['.', '-', '/', ','], '', $task->client->document);
                    return $clientDocument == $doc;
                });

            if ($tasks->isEmpty()) {
                return response()->json([
                    'message' => 'Não há nenhuma tarefa',
                    'result' => []
                ], 200);
            }

            return response()->json(['result' => $tasks], 200);
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getTaskInEnterprise(Request $request, $id)
    {

        if (is_null($id)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $tasks = ClientTask::where('client_id', $id)->get();
            if ($tasks->isEmpty()) {
                return response()->json([
                    'message' => 'Nenhuma tarefa encontrada para este cliente',
                    'result' => []
                ], 404);
            }

            return response()->json(['result' => $tasks], 200);
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function createTask(Request $request)
    {

        $input = $request->all();
        $input['slug'] = Str::slug($input['name']);
        $input['price'] = str_replace(['.', ','], ['', '.'], $input['price']);
        $rules = [
            'name' => 'required|string|min:3',
            'slug' => 'required|string|unique:internal_tasks',
            'price' => 'required|numeric|min:0',
            'setor' => 'required',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ];
        $errors = [];
        $fields = [
            'name' => 'nome',
            'price' => 'price',
            'login.email' => 'email de login',
            'login.password' => 'senha'
        ];
        $validator = Validator::make($input, $rules, $errors, $fields);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $createdTask = InternalTask::create([
                    'name' => $input['name'],
                    'slug' => $input['slug'],
                    'price' => $input['price'],
                    'setor' => $input['setor']
                ]);

                return response()->json([
                    'message' => 'Tarefa adicionada com sucesso',
                    'result' => $createdTask
                ], 201);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }

        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getProcesses(Request $request)
    {
        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $process = InternalProcess::with('tasks')->get();

            if ($process->isEmpty()) {
                return response()->json([
                    'message' => 'Nenhum processo cadastrado',
                    'result' => []
                ], 200);
            }

            return response()->json(['result' => $process], 200);
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getProcessById(Request $request, $id)
    {
        if (is_null($id)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            $process = InternalProcess::find($id);

            if (is_null($process)) {
                return response()->json([
                    'message' => 'Nenhum processo encontrado',
                    'result' => []
                ], 200);
            }

            return response()->json([
                'result' => $process
            ], 200);
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function createProcess(Request $request)
    {
        $input = $request->all();

        $input['slug'] = Str::slug($input['name']);
        $input['price'] = str_replace(['.', ','], ['', '.'], $input['price']);

        $rules = [
            'name' => 'required|string|min:3',
            'slug' => 'required|string|unique:internal_processes',
            'price' => 'required|numeric|min:0',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ];

        $fields = [
            'name' => 'nome',
            'price' => 'preço',
            'login.email' => 'e-mail',
            'login.password' => 'senha',
        ];

        $validator = Validator::make($input, $rules, [], $fields);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $createdProcess = InternalProcess::create([
                    'name' => $input['name'],
                    'slug' => $input['slug'],
                    'price' => $input['price'],
                ]);

                return response()->json([
                    'message' => 'Processo criado com sucesso',
                    'result' => $createdProcess
                ], 201);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }

        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function updateProcess(Request $request, InternalProcess $internalProcess)
    {
        $input = $request->all();
        $input['slug'] = Str::slug($input['name']);
        $input['price'] = str_replace(['.', ','], ['', '.'], $input['price']);
        $rules = [
            'name' => 'required|string|min:3',
            'slug' => 'required|string|unique:internal_processes,slug,' . $internalProcess->id,
            'price' => 'required|numeric|min:0',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ];
        $errors = [];
        $fields = [
            'name' => 'nome',
            'price' => 'price',
            'login.email' => 'e-mail',
            'login.password' => 'senha',
        ];
        $validator = Validator::make($input, $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $internalProcess->update([
                    'name' => $input['name'],
                    'slug' => $input['slug'],
                    'price' => $input['price'],
                ]);

                $updatedProcess = $internalProcess->fresh();

                return response()->json([
                    'message' => 'Processo editado com sucesso',
                    'result' => $updatedProcess
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function setProcessInEnterprise(Request $request, Client $client)
    {
        $input = $request->all();
        $input['price'] = str_replace(['.', ','], ['', '.'], $input['price']);
        $rules = [
            'process_id' => 'required|integer|exists:internal_processes,id',
            'price' => 'required|numeric|min:0',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ];
        $errors = [];
        $fields = [
            'process_id' => 'processo',
            'price' => 'preço',
            'login.email' => 'e-mail',
            'login.password' => 'senha',
        ];
        $validator = Validator::make($input, $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $process = $client->processes()->create([
                    'process_id' => $input['process_id'],
                    'price' => $input['price']
                ]);
                $tasks = InternalProcess::find($input['process_id'])->tasks;
                foreach ($tasks as $task) {
                    try {
                        $process->tasks()->create([
                            'client_id' => $client->id,
                            'task_id' => $task->id,
                            'price' => 0,
                        ]);
                    } catch (\Exception $e) {
                        $process->delete();
                        return response()->json(['message' => $e->getMessage()], 400);
                    }
                }
                ClientProcessLog::create([
                    'user_id' => auth()->user()->id,
                    'client_process_id' => $process->id,
                    'action' => '<b>criou este processo</b>',
                    'type' => 'process',
                    'refer_id' => $process->id
                ]);

                $allProcesses = $client->processes()->with('tasks')->get();

                return response()->json([
                    'message' => 'Processo alocado com sucesso',
                    'result' => $allProcesses
                ], 201);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function setProcessInEnterpriseByDoc(Request $request, $document)
    {
        $input = $request->all();
        $input['price'] = str_replace(['.', ','], ['', '.'], $input['price']);
        $rules = [
            'process_id' => 'required|integer|exists:internal_processes,id',
            'price' => 'required|numeric|min:0',
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ];
        $errors = [];
        $fields = [
            'process_id' => 'processo',
            'price' => 'preço',
            'login.email' => 'e-mail',
            'login.password' => 'senha',
        ];
        $validator = Validator::make($input, $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $doc = preg_replace('/[^0-9]/', '', $document);
                $client = Client::all()->filter(function ($item) use ($doc) {
                    $clientDocument = str_replace(['.', '-', '/', ','], '', $item->document);
                    return $clientDocument == $doc;
                })->first();

                if ($client) {
                    $process = $client->processes()->create([
                        'process_id' => $input['process_id'],
                        'price' => $input['price']
                    ]);
                    $tasks = InternalProcess::find($input['process_id'])->tasks;
                    foreach ($tasks as $task) {
                        try {
                            $process->tasks()->create([
                                'client_id' => $client->id,
                                'task_id' => $task->id,
                                'price' => 0,
                            ]);
                        } catch (\Exception $e) {
                            $process->delete();
                            return response()->json(['message' => $e->getMessage()], 400);
                        }
                    }
                    ClientProcessLog::create([
                        'user_id' => auth()->user()->id,
                        'client_process_id' => $process->id,
                        'action' => '<b>criou este processo</b>',
                        'type' => 'process',
                        'refer_id' => $process->id
                    ]);

                    $allProcesses = $client->processes()->with('tasks')->get();

                    return response()->json([
                        'message' => 'Processo alocado com sucesso',
                        'result' => $allProcesses
                    ], 201);
                }
                return response()->json(['message' => 'Nenhum cliente com esse document'], 400);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getProcessInEnterprise(Request $request, $id)
    {
        if (is_null($id)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $process = ClientProcess::where('client_id', $id)->get();
                if (is_null($process)) {
                    return response()->json([
                        'message' => 'Nenhum processo encontrado',
                        'result' => []
                    ], 200);
                }

                return response()->json(['result' => $process], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
    public function getProcessInEnterpriseByDoc(Request $request, $document)
    {
        if (is_null($document)) {
            return response()->json(['message' => 'Parâmetros ausentes'], 400);
        }

        $request->validate([
            'login.email' => 'required|email',
            'login.password' => 'required|string',
        ]);

        $email = $request->input('login.email');
        $password = $request->input('login.password');

        if ($this->authenticateUser($email, $password)) {
            try {
                $doc = preg_replace('/[^0-9]/', '', $document);

                $process = ClientProcess::with(['process', 'client'])
                    ->get()
                    ->filter(function ($task) use ($doc) {
                        $clientDocument = str_replace(['.', '-', '/', ','], '', $task->client->document);
                        return $clientDocument == $doc;
                    });

                if (is_null($process)) {
                    return response()->json([
                        'message' => 'Nenhum processo encontrado',
                        'result' => []
                    ], 200);
                }

                return response()->json(['result' => $process], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Credenciais inválidas'], 404);
    }
}
