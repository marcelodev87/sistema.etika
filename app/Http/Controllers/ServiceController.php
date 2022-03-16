<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{

    public function index()
    {
        $services = Service::all();
        return view('services.index', compact('services'));
    }


    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:3',
            'valor' => 'required|string:min:2',
            'description' => 'required|string|min:3',
            'valorString' => 'required|string|min:5',
        ];
        $errors = [];
        $fields = [
            'name' => 'nome',
            'description' => 'descricao',
            'valorString' => 'valor em extenso'
        ];

        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            Service::create([
                'name' => $request->name,
                'description' => $request->description,
                'valor' => str_replace(['.', ','], ['', '.'], $request->valor),
                'valor_string' => $request->valorString,
            ]);
            session()->flash('flash-success', 'Serviço salvo com sucesso');
            return response()->json([], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $rules = [
            'name' => 'required|string|min:3',
            'valor' => 'required|string:min:2',
            'description' => 'required|string|min:3',
            'valorString' => 'required|string|min:5',
        ];
        $errors = [];
        $fields = [
            'name' => 'nome',
            'description' => 'descricao',
            'valorString' => 'valor em extenso'
        ];

        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $service->update([
                'name' => $request->name,
                'description' => $request->description,
                'valor' => str_replace(['.', ','], ['', '.'], $request->valor),
                'valor_string' => $request->valorString,
            ]);
            session()->flash('flash-success', 'Serviço editado com sucesso');
            return response()->json([], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    public function destroy(Service $service)
    {
        $service->delete();
        session()->flash('flash-success', 'Serviço deletado com sucesso');
        return redirect()->back();
    }
}
