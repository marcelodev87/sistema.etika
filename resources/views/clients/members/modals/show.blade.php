<div class="modal fade" tabindex="-1" role="dialog" id="modal-show">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Informações</h4>
            </div>

            <div class="modal-body">
                <div id="modal-show-area" class="row">
                    <div class="div-col-xs-12 col-sm-6 col-md-4">
                        <p>Nome Completo: <span class="persona-name"></span></p>
                        <p>Dt. Nascimento: <span class="persona-dob"></span></p>
                        <p>Documento: <span class="persona-document"></span></p>
                        <p>RG: <span class="persona-rg"></span> <span class="persona-rg-expedidor"></span></p>
                        <p>Sexo: <span class="persona-gender"></span></p>
                        <p>Estado Civil: <span class="persona-marital_status"></span></p>
                        <p>Cargo: <span class="persona-role"></span></p>
                        <p>Profissão: <span class="persona-profession"></span></p>
                        <p>Natural: <span class="persona-natural"></span></p>
                    </div>

                    <div class="div-col-xs-12 col-sm-6 col-md-4 ">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Email</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="tb-emails"></tbody>
                        </table>
                    </div>

                    <div class="div-col-xs-12 col-sm-6 col-md-4" >
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Telefone</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="tb-phones"></tbody>
                        </table>
                    </div>

                    <div class="div-col-xs-12 col-sm-12 col-md-12"  style="margin-top: 7px">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Rua</th>
                                <th>Número</th>
                                <th>Complemeto</th>
                                <th>Bairro</th>
                                <th>Cidade</th>
                                <th>Estado</th>
                                <th>Cep</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="tb-addresses"></tbody>
                        </table>
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
</div>
