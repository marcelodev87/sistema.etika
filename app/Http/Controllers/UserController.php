<?php

namespace App\Http\Controllers;

use App\Mail\SendPasswordToUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class UserController extends Controller
{

    # listagem
    public function index()
    {
        $users = User::where('id', '>', 1)->get();
        return view('users.index', compact('users'));
    }

    # tela de criação
    public function create()
    {
        return view('users.create');
    }

    # método store
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:5|max:50',
            'dob' => 'nullable|date',
            'gender' => 'required|string|min:5|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:20|confirmed',
        ];
        if ($request->has('emailPassword')) {
            unset($rules['password']);
        }
        $errors = [];
        $fields = [
            'name' => '\'nome completo\'',
            'dob' => '\'aniversário\'',
            'gender' => '\'genero\'',
            'email' => '\'e-mail\'',
            'password' => '\'senha\'',
            'password_confirmation' => '\'confirmação da senha\'',
        ];

        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->first()], 400);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'email' => $request->email,
                'password' => bcrypt(Str::random(40)),
                'status' => 1,
                'setor' => $request->sector,
            ]);
            $user->roles()->sync($request->role_id);

            # enviar e-mail com a senha
            if ($request->has('emailPassword')) {
                $password = Str::random(8);
                Mail::to($user->email)->send(new SendPasswordToUser(["name" => $user->name, "password" => $password]));
            } else {
                # definir a senha
                $password = bcrypt($request->password);
            }
            $user->update([
                'password' => bcrypt($password),
            ]);
            session()->flash('flash-success', 'Usuário criado com sucesso');
            return response()->json(["message" => "Usuário inserido com sucesso"]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }


        session()->flash('flash-success', 'Usuário foi criado com sucesso.');
        return redirect()->route('dashboard.users.index');
    }

    # tela de edição
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    # método update
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|min:5|max:50',
            'dob' => 'required|date',
            'gender' => 'required|string|min:5|max:50',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];
        $errors = [];
        $fields = [
            'name' => '\'nome completo\'',
            'dob' => '\'aniversário\'',
            'gender' => '\'genero\'',
            'email' => '\'e-mail\'',
        ];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            session()->flash('flash-warning', $validator->errors()->first());
            return redirect()->back()->withInput($request->all());
        }

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'status' => $request->status,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'status' => $request->status,
                'setor' => $request->sector,
            ]);

            $user->roles()->sync($request->role_id);

        } catch (\Exception $e) {
            session()->flash('flash-warning', $e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
        session()->flash('flash-success', 'Editado com sucesso');
        return redirect()->route('app.users.index');
    }

    # método updateStatus
    public function changeStatus(Request $request, User $user)
    {
        $user->update([
            'status' => ($user->status) ? 0 : 1
        ]);
        session()->flash('flash-success', 'O status do usuário \'' . $user->name . '\' foi mudado com sucesso');
        return redirect()->route('app.users.index');
    }

    # método destroy
    public function destroy(Request $request, User $user)
    {
        try {
            $user->delete();
            session()->flash('flash-success', 'Usuário deletado com sucesso');
        } catch (\Exception $e) {
            session()->flash('flash-warning', $e->getMessage());
        }
        return redirect()->route('app.users.index');
    }


}
