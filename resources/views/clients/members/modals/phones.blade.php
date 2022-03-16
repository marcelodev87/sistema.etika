<div class="modal fade" tabindex="-1" role="dialog" id="modal-phones">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Telefones</h4>
            </div>

            <div class="modal-body">
                <div class="row collapse" id="phones-create">
                    <form method="post" action="" id="modal-form-phones">
                        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                            <div class="form-group">
                                <label for="phone">Telefone</label>
                                <input type="text" name="phone" class="form-control phone-mask" id="phone">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                            <div class="form-group" style="margin-top: 23px">
                                <button type="submit" class="btn btn-sm btn-success btn-block">
                                    <i class="fa fa-save"></i> Salvar
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <a href="#phones-create" class="btn btn-sm btn-info btn-block" data-toggle="collapse">
                            <i class="fa fa-plus"></i> Adicionar
                        </a>

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Número</th>
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
