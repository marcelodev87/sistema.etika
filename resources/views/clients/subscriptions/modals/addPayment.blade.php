<div class="modal fade" tabindex="-1" role="dialog" id="modal-payment">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pagamento</h4>
            </div>

            <form method="post" action="{{ route('app.clients.subscriptions.payments.store', [$client->id, $clientSubscription->id]) }}" id="form-payment" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Data</label>
                                <input type="text" class="form-control" name="pay_at" data-mask="00/00/0000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Valor</label>
                                <input type="text" class="form-control" name="price" data-mask="000.000,00" data-mask-reverse="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descrição</label>
                        <textarea class="form-control summernote" name="description"></textarea>
                    </div>

                    <div class="form-group">
                        <input type="file" name="file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">
                        <i class="fa fa-times"></i> Fechar
                    </button>
                    <button type="submit" class="btn btn-sm btn-success ">
                        <i class="fa fa-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
