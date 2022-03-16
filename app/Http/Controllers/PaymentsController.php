<?php

namespace App\Http\Controllers;

use App\ClientProcessPayment;
use App\ClientSubscriptionPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentsController extends Controller
{
    public function index()
    {
        return view("pagamentos");
    }

    public function load(Request $request)
    {
        $mask = "d/m/Y H:i:s";
        $format = 'Y-m-d H:i:s';
        $start_at = Carbon::createFromFormat($mask, $request['start_at'] . "00:00:00")->format($format);
        $end_at = Carbon::createFromFormat($mask, $request['end_at'] . "23:59:59")->format($format);

        // array for payments
        $payments = [];

        // load subscriptions
        $subscriptions = ClientSubscriptionPayment::whereBetween('pay_at', [$start_at, $end_at])->get();
        foreach ($subscriptions as $row) {
            $data = [
                'timestamp' => $row->pay_at->timestamp,
                'client' => $row->subscription->client->name,
                'value' => "R$ ".number_format($row->price, 2, ',', '.'),
                'pay_at' => $row->pay_at->format('d/m/Y'),
                'file' => ($row->file) ? Storage::url($row->file) : null,
            ];
            array_push($payments, $data);
        }
        // load processes
        $processes = ClientProcessPayment::whereBetween('payed_at', [$start_at, $end_at])->get();
        foreach ($processes as $row) {
            $data = [
                'timestamp' => $row->payed_at->timestamp,
                'client' => $row->clientProcess->client->name,
                'value' => "R$ ".number_format($row->value, 2, ',', '.'),
                'pay_at' => $row->payed_at->format('d/m/Y'),
                'file' => ($row->file) ? Storage::url($row->file) : null,
            ];
            array_push($payments, $data);
        }
        usort($payments, function ($item1, $item2) {
            return $item1['timestamp'] <=> $item2['timestamp'];
        });
        return response()->json([
            'data' => [
                'payments' => $payments,
                'registers' => count($payments)
            ]
        ], 200);
    }
}
