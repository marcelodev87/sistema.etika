<div role="tabpanel" class="tab-pane fade" id="changeInformation">
    <form class="row" action="{!! route('app.profile.update_information', $user->id) !!}" method="post" id="profile-update-information">
        @method('put')

        <div class="row">
            <div class="col-md-4">
                <fieldset class="form-group">
                    <label>Nome</label>
                    <input class="form-control" name="name" type="text"
                           value="{{ old('name', $user->name) }}">
                </fieldset>
            </div>

            <div class="col-md-3">
                <fieldset class="form-group">
                    <label>Data de Nascimento</label>
                    <input class="form-control" name="dob" type="text" placeholder="dd/mm/aaaa" data-mask="00/00/0000"
                           value="{{ old('dob', (auth()->user()->dob) ? auth()->user()->dob->format('d/m/Y') : null) }}">
                </fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <fieldset class="form-group">
                    <label>Senha</label>
                    <input type="password" class="form-control" name="password">
                </fieldset>
            </div>
            <div class="col-md-12">

                <button type="submit" class="btn btn-sm btn-success">
                    <i class="fa fa-save"></i> Salvar
                </button>
            </div>
        </div>



    </form>
</div>
