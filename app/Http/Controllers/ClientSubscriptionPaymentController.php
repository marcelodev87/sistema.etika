<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientSubscription;
use App\ClientSubscriptionPayment;
use App\Mail\NewPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClientSubscriptionPaymentController extends Controller
{
    public function store(Request $request, Client $client, ClientSubscription $clientSubscription)
    {
        $input = $request->all();
        $input['price'] = brlToNumeric($input['price']);
        $rules = [
            'pay_at' => 'required|date_format:d/m/Y',
            'price' => 'required|numeric|min:0',
            'file' => 'nullable|mimes:pdf,jpg,jpeg,gif,png|max:5120',
        ];
        $errors = [];
        $fields = [
            'pay_at' => 'data',
            'price' => 'valor',
            'file' => 'arquivo',
        ];

        $validator = Validator::make($input, $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $payment = $clientSubscription->payments()->create([
                'pay_at' => Carbon::createFromFormat('d/m/Y', $request->pay_at)->format('Y-m-d'),
                'price' => brlToNumeric($request->price),
                'description' => $input['description'],
                'file' => ($request->hasFile('file')) ? Storage::disk('public')->put('comprovantes', $request->file('file')) : null,
            ]);

            session()->flash('flash-succes', 'Pagamento adicionado com sucesso');
            return response()->json(['message' => 'Cadastrado'], 201);
        } catch (\Exception $e) {
            response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function destroy(Client $client, ClientSubscription $clientSubscription, ClientSubscriptionPayment $clientSubscriptionPayment)
    {
        $file = null;
        if ($clientSubscriptionPayment->file) {
            $file = $clientSubscriptionPayment->file;
        }
        try {
            $clientSubscriptionPayment->delete();
            if ($file) {
                Storage::disk('public')->delete($file);
            }
            session()->flash('flash-success', 'Pagamento deletado com sucesso');
        } catch (\Exception $e) {
            session()->flash('flash-warning', $e->getMessage());
        }
        return redirect()->back();
    }
}
