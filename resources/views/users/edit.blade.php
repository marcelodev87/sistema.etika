@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Usuários'])
    <li><a href="{!! route('app.index') !!}"><i class="fa fa-th"></i> Dashboard</a></li>
    <li><a href="{!! route('app.users.index') !!}"><i class="fa fa-users"></i> Usuários</a></li>
    <li class="active"><i class="fa fa-edit"></i> Editar</li>
    <li class="active">{{ $user->name }}</li>
    @endbreadcrumb
@endsection

@section('content')
    <div class="row">
        <form action="{!! route('app.users.update', $user->id) !!}" method="post">
            @csrf
            @method('put')
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                <div class="chart-box">

                    <h4 class="text-center">Dados do Usuário</h4>

                    <fieldset class="form-group">
                        <label>Nome Completo</label>
                        <input class="form-control" name="name" type="text" value="{{ old('name', $user->name) }}">
                    </fieldset>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <fieldset class="form-group">
                                <label>Aniversário</label>
                                <input class="form-control" name="dob" type="date" value="{{ old('dob', ($user->dob) ? $user->dob->format('Y-m-d') : '')}}" style="line-height: 15px">
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <fieldset class="form-group">
                                <label>Gênero</label>
                                <select class="form-control" name="gender">
                                    <option value="Feminino" {{ (old('gender', $user->gender) == "Feminino") ? 'selected' : null }}>Feminino</option>
                                    <option value="Masculino" {{ (old('gender', $user->gender) == "Masculino") ? 'selected' : null }}>Masculino</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <fieldset class="form-group">
                        <label>E-mail</label>
                        <input class="form-control" name="email" type="email" value="{{ old('email', $user->email) }}">
                    </fieldset>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <fieldset class="form-group">
                                <label>Usuário ativo?</label>
                                <select class="form-control" name="status" style="padding: 5px 10px">
                                    <option value="0" {{ (!$user->status) ? 'selected' : '' }}>Não</option>
                                    <option value="1" {{ ($user->status) ? 'selected' : '' }}>Sim</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <fieldset class="form-group">
                                <label>Papel</label>
                                <select name="role_id" class="form-control">
                                    @if(auth()->user()->hasRole('adm'))
                                        <option value="1" {{ (old('role_id', $user->hasRole('adm'))) ? 'selected' : '' }}>Administrador</option>
                                    @endif
                                    <option value="2" {{ (old('role_id', $user->hasRole('usr'))) ? 'selected' : '' }}>Usuário</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <fieldset class="form-group">
                        <label>Setor</label>
                        <select class="form-control" name="sector">
                            <option value="">Selecione</option>
                            @foreach(loadSectors() as $key => $value)
                                <option value="{{$value}}" {{ (old('sector', $user->sector) == $value) ? 'selected' : null }}>{{$value}}</option>
                            @endforeach
                        </select>
                    </fieldset>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <a href="{!! route('app.users.index') !!}" class="btn btn-sm btn-block btn-primary">
                                <i class="fa fa-reply"></i> Voltar
                            </a>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <button type="submit" class="btn btn-sm btn-block btn-success">
                                <i class="fa fa-save"></i> Salvar
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
@endsection
