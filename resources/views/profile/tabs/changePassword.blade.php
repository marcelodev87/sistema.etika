<div role="tabpanel" class="tab-pane fade" id="changePassword">
    <form action="{!! route('app.profile.update_password', $user->id) !!}" method="post" id="profile-update-password">
        @method('patch')

        <div class="row">
            <div class="col-md-4">
                <fieldset class="form-group">
                    <label>Senha Atual</label>
                    <input type="password" class="form-control" name="current_password">
                </fieldset>
            </div>

            <div class="col-md-4">
                <fieldset class="form-group">
                    <label>Nova Senha</label>
                    <input type="password" class="form-control" name="password">
                </fieldset>
            </div>

            <div class="col-md-4">
                <fieldset class="form-group">
                    <label>Confirme a nova senha</label>
                    <input type="password" class="form-control" name="password_confirmation">
                </fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-sm btn-success">
                    <i class="fa fa-save"></i> Salvar
                </button>
            </div>
        </div>
    </form>
</div>
