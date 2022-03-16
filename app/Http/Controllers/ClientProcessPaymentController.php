<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientProcess;
use App\ClientProcessPayment;
use App\Mail\NewPayment;
use App\Mail\NewTaskNotification;
use App\Mail\PaymentNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClientProcessPaymentController extends Controller
{

    public function store(Request $request, Client $client, ClientProcess $clientProcess): JsonResponse
    {
        $input = $request->all();
        $input['pay_value'] = str_replace(['.', ','], ['', '.'], $input['pay_value']);
        $rules = [
            'pay_value' => 'required|numeric|min:0',
            'payed_at' => 'required|date_format:d/m/Y',
            'file' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120'
        ];
        $errors = [];
        $fields = [
            'pay_value' => 'valor',
            'payed_at' => 'data de pagamento',
            'file' => 'comprovante',
        ];
        $validator = Validator::make($input, $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $payment = $clientProcess->payments()->create([
                'value' => $input['pay_value'],
                'payed_at' => Carbon::createFromFormat('d/m/Y', $input['payed_at'])->format('Y-m-d'),
                'description' => $input['description'],
                'file' => $request->hasFile('file') ? Storage::disk('public')->put('comprovantes', $request->file('file')) : null,
            ]);
            Mail::to(getenv('ADM_NOTIFICATION'))->send( new NewPayment([
                'cliente' => $clientProcess->client->name,
                'valor' => $input['pay_value'],
            ]));
            return response()->json(['message' => 'Entrada salva com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'line' => $e->getLine(), $e->getFile(), $e->getTrace()], 400);
        }
    }

    public function destroy(Client $client, ClientProcess $clientProcess, ClientProcessPayment $clientProcessPayment)
    {
        $file = $clientProcessPayment->file;
        try {
            $clientProcessPayment->delete();
            if ($file) {
                Storage::disk('public')->delete($file);
            }
            session()->flash('flash-success', 'Deletado com sucesso');
        } catch (\Exception $e) {
            session()->flash('flash-warning', $e->getMessage());
        }
        return redirect()->back();
    }
}
