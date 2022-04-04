<?php

namespace App\Http\Controllers;

use App\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GeraDocumentoController extends Controller
{
    public function ataFundacaoView()
    {
        return view('documents.ataFuncao');
    }

    public function ataFundacaoGetPersonas(Request $request)
    {
        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        $rules = [
            'igreja' => 'required'
        ];
        $errors = [];
        $fields = [
            'igreja' => 'igreja'
        ];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }
        $client = Client::find($request->igreja);
        $personas = $client->members;
        // criando o array
        $array = [];
        foreach ($personas as $p) {
            $xx = [
                'id' => $p->id,
                'name' => $p->name,
                'role' => $p->role,
            ];
            array_push($array, $xx);
        }
        return response()->json(['personas' => $array], 200);
    }

    public function ataFundacaoPost(Request $request)
    {
        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        $rules = [
            'client_id' => 'required|integer|exists:clients,id',
            'presidente' => 'required|integer|exists:client_personas,id',
            'vice_presidente' => 'nullable|integer|exists:client_personas,id',
            'tesouraria.*' => 'nullable|integer|exists:client_personas,id',
            'secretaria.*' => 'nullable|integer|exists:client_personas,id',
        ];
        $errors = [];
        $fields = [
            'client_id' => 'igreja',
            'vice_presidente' => 'vice presidente',
            'tesouraria' => 'tesoureiro',
            'secretaria' => 'secretaria',
        ];

        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        // load Data
        $igreja = Client::find($request->client_id);
        $presidente = $igreja->members()->find($request->presidente);
        $vicePresidente = ($request->vice_presidente) ? $igreja->members()->find($request->vice_presidente) : null;

        $data = [];
        $data['igreja'] = [
            'nome' => $igreja->name,
            'cidade' => $igreja->city,
            'endereco' => $igreja->fullAddress(),
        ];
        $data['diretoria']['presidente'] = [
            'nome' => $presidente->name,
            'naturalidade' => $presidente->natural,
            'dt_nascimento' => $presidente->dob->format('d/m/Y'),
            'profissao' => $presidente->profession,
            'rg' => $presidente->rg,
            'exp_rg' => $presidente->rg_expedidor,
            'estado_civil' => $presidente->marital_status,
            'cpf' => $presidente->document,
            'endereco' => $presidente->fullAddress()
        ];

        if ($vicePresidente) {
            $data['diretoria']['vice_presidente'] = [
                'nome' => $vicePresidente->name,
                'naturalidade' => $vicePresidente->natural,
                'dt_nascimento' => $vicePresidente->dob->format('d/m/Y'),
                'profissao' => $vicePresidente->profession,
                'rg' => $vicePresidente->rg,
                'exp_rg' => $vicePresidente->rg_expedidor,
                'estado_civil' => $vicePresidente->marital_status,
                'cpf' => $vicePresidente->document,
                'endereco' => $vicePresidente->fullAddress()
            ];
        }

        $data['diretoria']['tesoureiros'] = [];
        foreach ($request->tesouraria as $t) {
            if ($t != null) {
                $tesoureiro = $igreja->members()->find($t);
                $x = [
                    'nome' => $tesoureiro->name,
                    'naturalidade' => $tesoureiro->natural,
                    'dt_nascimento' => $tesoureiro->dob->format('d/m/Y'),
                    'profissao' => $tesoureiro->profession,
                    'rg' => $tesoureiro->rg,
                    'exp_rg' => $tesoureiro->rg_expedidor,
                    'estado_civil' => $tesoureiro->marital_status,
                    'cpf' => $tesoureiro->document,
                    'endereco' => $tesoureiro->fullAddress()
                ];
                array_push($data['diretoria']['tesoureiros'], $x);
            }
        }
        $data['diretoria']['secretarios'] = [];
        foreach ($request->secretaria as $t) {
            if ($t != null) {
                $secretario = $igreja->members()->find($t);
                $x = [
                    'nome' => $secretario->name,
                    'naturalidade' => $secretario->natural,
                    'dt_nascimento' => $secretario->dob->format('d/m/Y'),
                    'profissao' => $secretario->profession,
                    'rg' => $secretario->rg,
                    'exp_rg' => $secretario->rg_expedidor,
                    'estado_civil' => $secretario->marital_status,
                    'cpf' => $secretario->document,
                    'endereco' => $secretario->fullAddress()
                ];
                array_push($data['diretoria']['secretarios'], $x);
            }
        }
        // post
        $data['post']['fundacao'] = $request->dt_fundacao;

        $endpoint = getenv('APP_URL') . '/documents/gera_ata_funcao.php';
        $data = ['data' => $data];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($data),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return response()->json(['html' => $response], 200);
    }

    public function estatutoEpiscopal(Request $request)
    {
        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        $igreja = null;
        if ($request->isMethod('post')) {
            $igreja = Client::find($request->client_id);
        }
        return view('documents.estatutoEpsicopal', ['request' => $request, 'igreja' => $igreja]);
    }

    public function estatudoCongregacional(Request $request)
    {
        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        $igreja = null;
        if ($request->isMethod('post')) {
            $igreja = Client::find($request->client_id);
        }
        return view('documents.estatutoCongregacional', ['request' => $request, 'igreja' => $igreja]);
    }

    public function contratoContabil(Request $request)
    {
        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        $post = [];
        if ($request->isMethod('post')) {
            $igreja = Client::find($request->client_id);
            $post[0] = $igreja;
            $p = $igreja->members()->where('role', 'Presidente')->first();
            if ($p) {
                $post[1] = $p;
            }
        }
        return view('documents.contratoContabil', compact('post', 'request'));
    }

    public function contratoAbertura(Request $request)
    {
        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        $post = [];
        if ($request->isMethod('post')) {
            $igreja = Client::find($request->client_id);
            $post[0] = $igreja;
            $p = $igreja->members()->where('role', 'Presidente')->first();
            if ($p) {
                $post[1] = $p;
            }
        }
        return view('documents.contratoAbertura', compact('post', 'request'));
    }

    public function editalConvocacao(Request $request)
    {
        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        $post = [];
        if ($request->isMethod('post')) {
            $igreja = Client::find($request->client_id);
            $post[0] = $igreja;
            $p = $igreja->members()->where('role', 'Presidente')->first();
            if ($p) {
                $post[1] = $p;
            }
        }
        return view('documents.edital', compact('post', 'request'));
    }
}
