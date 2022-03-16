<div class="modal fade" tabindex="-1" role="dialog" id="modal-addresses">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Endereços</h4>
            </div>

            <div class="modal-body">
                <div class="row collapse" id="address-create">
                    <form method="post" action="" id="modal-form-address">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="zip">CEP</label>
                                <input type="text" name="zip" class="form-control" data-mask="00000-000" id="zip">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-1">
                            <div class="form-group">
                                <label for="state">UF</label>
                                <input type="text" name="state" class="form-control" data-mask="AA" id="state">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-4">
                            <div class="form-group">
                                <label for="city">Cidade</label>
                                <input type="text" name="city" class="form-control" id="city">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-4">
                            <div class="form-group">
                                <label for="neighborhood">Bairro</label>
                                <input type="text" name="neighborhood" class="form-control" id="neighborhood">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-7">
                            <div class="form-group">
                                <label for="street">Logradouro</label>
                                <input type="text" name="street" class="form-control" id="street">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2">
                            <div class="form-group">
                                <label for="street_number">Número</label>
                                <input type="text" name="street_number" class="form-control" data-mask="000000" id="street_number">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <label for="complement">Complemento</label>
                                <input type="text" name="complement" class="form-control" id="complement">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-sm btn-success btn-block">
                                    <i class="fa fa-save"></i> Salvar
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <a href="#address-create" class="btn btn-sm btn-default btn-block" data-toggle="collapse">
                            <i class="fa fa-plus"></i> Adicionar
                        </a>

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>CEP</th>
                                <th>UF</th>
                                <th>CIDADE</th>
                                <th>BAIRRO</th>
                                <th>LOGRADOURO</th>
                                <th>Nº</th>
                                <th>COMPLEMENTO</th>
                                <th class="text-center">PADRÃO</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">
                    <i class="fa fa-times"></i> Fechar
                </button>
            </div>
        </div>
    </div>
</div>
