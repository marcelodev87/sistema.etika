<div role="tabpanel" class="tab-pane fade" id="changeEmail">
    <form action="{!! route('app.profile.update_email', $user->id) !!}" method="post" id="profile-update-email">
        @method('patch')
        <div class="row">
            <div class="col-md-4">
                <fieldset class="form-group">
                    <label>Novo E-mail</label>
                    <input class="form-control" name="email" type="email">
                </fieldset>
            </div>


            <div class="col-md-4">
                <fieldset class="form-group">
                    <label>Confirmação do E-mail</label>
                    <input class="form-control" name="email_confirmation" type="email">
                </fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
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
