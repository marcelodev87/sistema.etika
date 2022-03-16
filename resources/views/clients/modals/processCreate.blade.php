<div class="modal fade" tabindex="-1" role="dialog" id="modal-processes">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Processos</h4>
            </div>

            <form method="post" action="{{ route('app.clients.processes.store', $client->id) }}" id="form-process">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="select-processes">Processos</label>
                        <select class="form-control" id="select-processes" name="process_id">
                            <option value="">Selecione</option>
                            @foreach(\App\InternalProcess::all() as $p)
                                <option value="{{ $p->id }}" data-price="{{ number_format($p->price, 2, ',', '.') }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="input-price">Preço</label>
                        <input type="text" name="price" class="form-control" data-mask="##0.000,00" data-mask-reverse="true" id="input-price">
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
