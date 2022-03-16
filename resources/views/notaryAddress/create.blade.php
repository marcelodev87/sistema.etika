@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Cartórios'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>

    <li>
        <a href="{!! route('app.notaryAddresses.index') !!}">
            <i class="fa fa-warehouse"></i> Cartórios
        </a>
    </li>

    <li class="active">
        <i class="fa fa-plus"></i> Adicionar
    </li>
    @endbreadcrumb
@endsection

@section('style')
    <link href="https://cdn.jsdelivr.net/combine/npm/summernote@0.6.12/dist/summernote-bs3.min.css,npm/summernote@0.6.12/dist/summernote.min.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 text-right mb-1">
            <a href="{!! route('app.notaryAddresses.index') !!}" class="btn btn-sm btn-default">
                <i class="fa fa-reply"></i> Voltar
            </a>
        </div>
        <div class="col-md-12">
            <div class="chart-box">
                <form method="post" action="{{ route('app.notaryAddresses.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="input-name">Nome</label>
                                <input type="text" name="name" id="input-name" class="form-control" value="{{ old('name') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="input-email-1">E-mail 1</label>
                                <input type="email" name="email_1" id="input-email-1" class="form-control" value="{{ old('email_1') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="input-email-2">E-mail 2</label>
                                <input type="email" name="email_2" id="input-email-2" class="form-control" value="{{ old('email_2') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="input-phone-1">Telefone 1</label>
                                <input type="text" name="phone_1" id="input-phone-1" class="form-control phone-mask" value="{{ old('phone_1') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="input-phone-2">Telefone 2</label>
                                <input type="text" name="phone_2" id="input-phone-2" class="form-control phone-mask" value="{{ old('phone_2') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="input-phone-3">Telefone 3</label>
                                <input type="text" name="phone_3" id="input-phone-3" class="form-control phone-mask" value="{{ old('phone_3') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                            <div class="form-group">
                                <label for="zip">CEP</label>
                                <input type="text" name="zip" id="zip" class="form-control" value="{{ old('zip') }}" data-mask="00000-000">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-1">
                            <div class="form-group">
                                <label for="state">UF</label>
                                <input type="text" name="state" id="state" class="form-control" value="{{ old('state') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="city">Cidade</label>
                                <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="neighborhood">Bairro</label>
                                <input type="text" name="neighborhood" id="neighborhood" class="form-control" value="{{ old('neighborhood') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-7">
                            <div class="form-group">
                                <label for="street">Logradouro</label>
                                <input type="text" name="street" id="street" class="form-control" value="{{ old('street') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                            <div class="form-group">
                                <label for="street_number">Número</label>
                                <input type="text" name="street_number" id="street_number" class="form-control" value="{{ old('street_number') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="complement">Complemento</label>
                                <input type="text" name="complement" id="complement" class="form-control" value="{{ old('complement') }}">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="textarea-obs1">Observação 1</label>
                                <textarea class="summernote" id="textarea-obs1" name="observation_1">{{ old('observation_1') }}</textarea>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="textarea-obs2">Observação 2</label>
                                <textarea class="summernote" id="textarea-obs2" name="observation_2">{{ old('observation_2', '<h3>DOCUMENTOS NECESSÁRIOS PARA REGISTRO:</h3>
                                - Estatuto (2) vias - Assinado por presidente e advogado e rubricado em todas as páginas. (Advogado precisa reconhecer firma?)<br><br>
                                - Ata (2) vias - (Firma reconhecida?)(Assinatura do Advogado?)<br><br>
                                - Lista de Presença(?) - 1 via(?)<br><br>
                                - Lista de Membros Fundadores(?) - 1 via(?)<br><br>
                                - Lista de Membros da Diretoria(?) - 1 via(?)<br><br>
                                - Edital de convocação(?) - 1 via(?)<br><br>
                                - Requerimento - 1 via(?) (Tem modelo específico? Pegar no balcão ou solicitar por e-mail);<br><br>
                                - (Cópia RG e CPF de todos os membros eleitos?);<br><br>

                                <h3>VALORES REFERENTES A ATA E ESTATUTO:</h3>
                                (Ata - R$ 000,00)<br>
                                (Estatuto - R$ 000,00)<br>
                                (R$ 000,00 1° folha e R$00,00 por folha adicional)<br><br>

                                <h3>TEMPOS ESTIMADO PRA SAÍDA DO PROCESSO APÓS A ENTRADA</h3>
                                  (7 à 10 dias úteis.)<br><br>

                                <h3>(É POSSÍVEL ENVIAR A DOCUMENTAÇÃO SEM ASSINATURAS E APÓS A ANÁLISE, ENVIARMOS EM DEFINITIVO PARA REGISTRO?)</h3>
                                <h3>Horário de Funcionamento: Seg - Sex : 09 às 17:00hrs.</h3>') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fa fa-save"></i> Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.6.12/dist/summernote.min.js"></script>
    <script type="text/javascript">
        $('.summernote').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        });
        $('.note-codable').hide();
        $('div.note-group-select-from-files').remove();

    </script>
@endsection
