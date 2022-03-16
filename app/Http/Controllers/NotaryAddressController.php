<?php

namespace App\Http\Controllers;

use App\NotaryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotaryAddressController extends Controller
{
    public function index()
    {
        $notaryAddresses = NotaryAddress::all();
        return view('notaryAddress.index', compact('notaryAddresses'));
    }


    public function create()
    {
        return view('notaryAddress.create');
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email_1' => 'required|email',
            'phone_1' => 'required|min:14',
            'city' => 'required',
        ];
        $errors = [];
        $fields = [];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            session()->flash('flash-warning', $validator->errors()->first());
            return redirect()->back()->withInput($request->all());
        }

        try {
            NotaryAddress::create($request->except(['token']));
            session()->flash('flash-success', 'Cartório adicionado com sucesso');
            return redirect()->route('app.notaryAddresses.index');
        }catch (\Exception $e){
            session()->flash('flash-warning', $e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
    }


    public function edit(NotaryAddress $notaryAddress)
    {
        return view('notaryAddress.edit', compact('notaryAddress'));
    }


    public function update(Request $request, NotaryAddress $notaryAddress)
    {
        $rules = [];
        $errors = [];
        $fields = [];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            session()->flash('flash-warning', $validator->errors()->first());
            return redirect()->back()->withInput($request->all());
        }

        try {
            $notaryAddress->update($request->except(['token']));
            session()->flash('flash-success', 'Cartório editado com sucesso');
            return redirect()->route('app.notaryAddresses.index');
        }catch (\Exception $e){
            session()->flash('flash-warning', $e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
    }


    public function destroy(NotaryAddress $notaryAddress)
    {
        try {
            $notaryAddress->delete();
            session()->flash('flash-success', 'Cartório deletado com sucesso');
        }catch (\Exception $e){
            session()->flash('flash-warning', $e->getMessage());
        }
        return redirect()->back();
    }
}
