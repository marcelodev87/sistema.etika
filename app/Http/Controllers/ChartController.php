<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function received()
    {
        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        $period = [];
        $values = [];
        $months = 11;
        for ($i = 0; $i < $months + 1; $i++) {
            $s = Carbon::now()->startOfMonth()->subMonths($months)->addMonths($i);
            $e = Carbon::now()->endOfMonth()->subMonths($months)->addMonths($i)->subDay();
            $total = 0;
            $total += DB::table('client_process_payments')
                ->whereBetween('payed_at', [$s->format('Y-m-d'), $e->format('Y-m-d')])
                ->sum('value');
            $total += DB::table('client_subscription_payments')
                ->whereBetween('pay_at', [$s->format('Y-m-d'), $e->format('Y-m-d')])
                ->sum('price');
            array_push($period, $s->formatLocalized('%b/%y'));
            array_push($values, (float)$total);
        }
        return response()->json(['periods' => $period, 'values' => $values], 200);
    }
}
