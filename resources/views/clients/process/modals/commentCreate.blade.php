<div class="modal fade" tabindex="-1" role="dialog" id="modal-comment">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Comentário</h4>
            </div>

            <form method="post" action="{{ route('app.clients.processes.tasks.comments.store', [$client->id, $clientProcess->id, ':TASK']) }}" id="form-comment" enctype="multipart/form-data">
                <input type="hidden" name="task_id" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <textarea class="form-control summernote" name="comment"></textarea>
                    </div>

                    <div class="form-group">
                        <input type="file" multiple name="files[]">
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
