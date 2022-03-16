<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    public function clientsRegistred()
    {
        $clients = Client::count();
        return response()->json(['total' => $clients], 200);
    }
}
