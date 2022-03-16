<div class="modal fade" tabindex="-1" role="dialog" id="modal-subscriptions">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Assinatura</h4>
            </div>

            <form method="post" action="{{ route('app.clients.subscriptions.store', [$client->id]) }}" id="form-subscription" >
                <input type="hidden" name="task_id" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Assinaturas</label>
                        <select class="form-control" name="subscription_id" id="select-subscription">
                            <option value="">Selecione</option>
                            @foreach(\App\Subscription::orderBy('name', 'asc')->get() as $sub)
                                <option value="{{ $sub->id }}" data-price="{{ brl($sub->price) }}">{{ $sub->name }} ({{brl($sub->price)}})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Preço</label>
                        <input type="text" class="form-control" name="price" data-mask="00.000,00" data-mask-reverse="true">
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
