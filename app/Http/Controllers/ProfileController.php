<?php

namespace App\Http\Controllers;

use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    private $_userLogged;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->_userLogged = auth()->user();
            return $next($request);
        });
    }

    # mostra o perfil
    public function index()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    # método updateAvatar
    public function changeAvatar(Request $request)
    {

        $rules = [
            "avatar" => "required|mimes:png,jpeg,jpg,gif|max:4096"
        ];
        $errors = [];
        $fields = [];

        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->first()], 400);
        }

        $oldAvatar = null;
        if ($this->_userLogged->avatar != null) {
            $oldAvatar = $this->_userLogged->avatar;
        }

        $photo = Image::make($request->file('avatar'));
        $photo->orientate();
        $photo->resize(400, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $photo->crop(400, 400);
        $photo->encode('jpg', 80);

        $file_name = 'avatars/avatar_' . md5(time() . $this->_userLogged->id) . '.jpg';
        $image = Storage::disk('public')->put($file_name, $photo);
        try {
            $this->_userLogged->update([
                "avatar" => $file_name,
            ]);
            if ($oldAvatar != null) {
                Storage::disk('public')->delete($oldAvatar);
            }
            return response()->json(["message" => "Imagem carregada com sucesso", "avatar" => Storage::url($file_name)], 200);
        } catch (\Exception $e) {
            Storage::disk('public')->delete($image);
            return response()->json(["error" => $e->getMessage()], 400);
        }

    }


    # método updateInformation
    public function changeInformation(Request $request)
    {
        # verifica a senha
        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json(["error" => "Senha incorreta"], 401);
        }

        $rules = [
            'name' => 'required|string|min:6|max:200',
            'dob' => 'nullable|date_format:d/m/Y',
            'password' => 'required'
        ];
        $errors = [];
        $fields = [
            "name" => '<b>\'Nome\'</b>',
            "dob" => '<b>\'Data de Nascimento\'</b>',
            "password" => '<b>\'Senha\'</b>',
        ];

        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->first()], 400);
        }

        try {
            auth()->user()->update([
                'name' => $request->name,
                'dob' => ($request->dob != null) ? Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d') : null,
            ]);
            return response()->json(["message" => "Os dados foram atualziados", "name" => $request->name], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }

    # método updateEmail
    public function changeEmail(Request $request)
    {
        # verifica a senha
        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json(["error" => "Senha incorreta"], 401);
        }

        $rules = [
            'email' => 'required|email|confirmed|unique:users'
        ];
        $errors = [];
        $fields = [
            'email' => '\'<b>e-mail</b>\''
        ];

        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->first()], 400);
        }


        try {
            auth()->user()->update([
                'email' => $request->email,
            ]);
            return response()->json(["message" => "E-mail foi alterado com sucesso", "email" => $request->email], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }


    public function changePassword(Request $request)
    {
        # verifica a senha
        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return response()->json(["error" => "Senha incorreta"], 401);
        }

        # verifica se a senha é igual a antiga
        if ($request->current_password == $request->password) {
            return response()->json(["error" => "A nova senha não pode ser igual a senha atual"], 401);
        }

        $rules = [
            'password' => 'required|string|confirmed|min:6|max:18',
        ];
        $errors = [];
        $fields = [
            'password' => '\'<b>nova senha</b>\''
        ];

        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->first()], 400);
        }

        try {
            auth()->user()->update([
                'password' => bcrypt($request->password)
            ]);
            return response()->json(["message" => "Senha alterada com sucesso"], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }


}
