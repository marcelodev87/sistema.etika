<div class="modal fade" tabindex="-1" role="dialog" id="modal-tasks">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tarefas</h4>
            </div>

            <form method="post" action="{{ route('app.clients.tasks.store', $client->id) }}" id="form-task">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="select-processes">Tarefas</label>
                        <select class="form-control" id="select-tasks" name="task_id">
                            <option value="">Selecione</option>
                            @foreach(\App\InternalTask::all() as $t)
                                <option value="{{ $t->id }}" data-price="{{ number_format($t->price, 2, ',', '.') }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="input-price">Preço</label>
                        <input type="text" name="price" class="form-control" data-mask="##0.000,00" data-mask-reverse="true" id="input-price">
                    </div>
                    <div class="form-group">
                        <label for="user_id">Responsável</label>
                        <select name="user_id" class="form-control" id="user_id">
                            @foreach(\App\User::all() as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="input-end">Dt. entrega</label>
                       <div class="input-group">
                           <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                           <input type="text" name="end_at" class="form-control" data-mask="00/00/0000 00:00" id="input-end" placeholder="dd/mm/aaaa hh:mm">
                       </div>
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
