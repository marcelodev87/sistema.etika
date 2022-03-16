<div class="modal fade" tabindex="-1" role="dialog" id="modal-form-cadastrar">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Novo Membro</h4>
            </div>
            <form action="{{ route('app.clients.members.store', $client->id) }}" method="post" id="form-cadastrar">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-xs-12 col-md-sm-6 col-md-8 col-lg-8">
                            <fieldset class="form-group">
                                <label>Nome Completo</label>
                                <input class="form-control" name="name" type="text">
                            </fieldset>
                        </div>

                        <div class="col-md-xs-12 col-md-sm-6 col-md-4 col-lg-4">
                            <fieldset class="form-group">
                                <label>Dt. Nascimento</label>
                                <input class="form-control" name="dob" type="text" data-mask="00/00/0000">
                            </fieldset>
                        </div>

                        <div class="col-md-xs-12 col-md-sm-6 col-md-4 col-lg-4">
                            <fieldset class="form-group">
                                <label>Natural</label>
                                <input class="form-control" name="natural" type="text">
                            </fieldset>
                        </div>

                        <div class="col-md-xs-12 col-md-sm-6 col-md-4 col-lg-4">
                            <fieldset class="form-group">
                                <label>Documento</label>
                                <input class="form-control" name="document" type="text" data-mask="000.000.000-00">
                            </fieldset>
                        </div>

                        <div class="col-md-xs-12 col-md-sm-6 col-md-4 col-lg-4">
                            <fieldset class="form-group">
                                <label>RG</label>
                                <input class="form-control" name="rg" type="text">
                            </fieldset>
                        </div>

                        <div class="col-md-xs-12 col-md-sm-6 col-md-4 col-lg-4">
                            <fieldset class="form-group">
                                <label>RG Orgão Expedidor</label>
                                <input class="form-control" name="rg_expedidor" type="text">
                            </fieldset>
                        </div>

                        <div class="col-md-xs-12 col-md-sm-6 col-md-4 col-lg-6">
                            <fieldset class="form-group">
                                <label>Cargo</label>
                                <select class="form-control" name="role" required>
                                    <option value="">Selecione</option>
                                    @foreach(json_decode(file_get_contents(public_path('cargos.json')), true) as $role)
                                        <option value="{{ $role }}">{{ $role }}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-md-xs-12 col-md-sm-6 col-md-4 col-lg-6">
                            <fieldset class="form-group">
                                <label>Sexo</label>
                                <select class="form-control" name="gender">
                                    <option value="">Selecione</option>
                                    <option value="Feminino">Feminino</option>
                                    <option value="Masculino">Masculino</option>
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-md-xs-12 col-md-sm-6 col-md-4 col-lg-6">
                            <fieldset class="form-group">
                                <label>Estado Civil</label>
                                <select class="form-control" name="marital_status" required>
                                    <option value="">Selecione</option>
                                    @foreach(['Solteiro', 'Casado', 'Divorciado', 'Viúvo', 'Separado'] as $ec)
                                        <option value="{{ $ec }}">{{ $ec }}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-md-xs-12 col-md-sm-6 col-md-4 col-lg-6">
                            <fieldset class="form-group">
                                <label>Profissão</label>
                                <input type="text" name="profession" class="form-control">
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary btn-sm">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
