@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Geração de Documentos'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Painel
        </a>
    </li>
    <li class="active">
        <i class="fa fa-copy"></i> Geração de Documentos
    </li>
    <li class="active">
        Estatuto Especial
    </li>
    @endbreadcrumb
@endsection

@section('style')
    <style>
        .chart-box {
            margin-bottom: 14px;
        }
    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-4">
            <form method="post" action="{{ route('app.documents.estatutoEpiscopal') }}">
                @csrf
                <div class="chart-box">

                    <div class="form-group">
                        <label>Igreja</label>
                        <select class="form-control selectpicker" name="client_id" title="Selecione a Igreja" required>
                            @foreach(\App\Client::where('type', 'igreja')->get() as $user)
                                <option value="{{$user->id}}" {{ ($request->has('client_id') && $request->client_id == $user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Sede</label>
                        <select name="sede" class="form-control selectpicker">
                            <option value="Provisória" {{ ($request->has('sede') && $request->sede == "Provisória") ? 'selected' : '' }}>Provisória</option>
                            <option value="Definitiva" {{ ($request->has('sede') && $request->sede == "Definitiva") ? 'selected' : '' }}>Definitiva</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Data de fundação da Igreja</label>
                        <input type="text" class="form-control" name="data_fundacao" value="{{ ($request->has('data_fundacao')) ? $request->data_fundacao : ''  }} " required minlength="10" data-mask="00/00/0000" placeholder="dd/mm/aaaa">
                    </div>
                    <div class="form-group">
                        <label>Mencionar membros fundadores?</label>
                        <input type="text" class="form-control" name="fundadores" value="{{ ($request->has('fundadores')) ? $request->fundadores : '' }}">
                    </div>

                    <div class="form-group">
                        <label>Haverá conselho fiscal?</label>
                        <select class="form-control selectpicker" name="conselho" required>
                            <option value="0" {{ ($request->has('conselho') && $request->conselho == 0) ? 'selected' : '' }}>Não</option>
                            <option value="1" {{ ($request->has('conselho') && $request->conselho == 1) ? 'selected' : '' }}>Sim</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Haverá vice residente?</label>
                        <select class="form-control selectpicker" name="vice" required>
                            <option value="0" {{ ($request->has('vice') && $request->vice == 0) ? 'selected' : '' }}>Não, o tesoureiro ocupará o cargo em caso de vacância</option>
                            <option value="1" {{ ($request->has('vice') && $request->vice == 1) ? 'selected' : '' }}>Sim</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tesoureiro</label>
                        <select class="form-control selectpicker outputMembers" name="tesouraria" required>
                            <option value="1" {{ ($request->has('tesouraria') && $request->tesouraria == 0) ? 'selected' : '' }}>Tesoureiro</option>
                            <option value="2" {{ ($request->has('tesouraria') && $request->tesouraria == 1) ? 'selected' : '' }}>1º e 2º Tesoureiro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Secretario</label>
                        <select class="form-control selectpicker" name="secretaria" required>
                            <option value="1" {{ ($request->has('secretaria') && $request->secretaria == 1) ? 'selected' : '' }}>Secretário</option>
                            <option value="2" {{ ($request->has('secretaria') && $request->secretaria == 2) ? 'selected' : '' }}>1º e 2º Secretário</option>
                            <option value="0" {{ ($request->has('secretaria') && $request->secretaria == 0) ? 'selected' : '' }}>Sem Secretário</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Madato do presidente:</label>
                        <select class="form-control selectpicker" name="m_presidente" required>
                            <option value="100" selected>Vitalício</option>
                            <option value="200">Indeterminado</option>
                            @for($i=1; $i<= 15; $i++)
                                <option value="{{ $i }}" {{ ($request->has('m_presidente') && $request->m_presidente == $i) ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Madato da diretoria:</label>
                        <select class="form-control selectpicker" name="m_diretoria" required>
                            @for($i=1; $i<= 15; $i++)
                                <option value="{{ $i }}" {{ ($request->has('m_diretoria') && $request->m_diretoria == $i) ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group">
                        <label>As movimentações financeiras serão feitas pelo:</label>
                        <select class="form-control selectpicker" name="m_financeiras" required>
                            <option value="1" {{ ($request->has('m_financeiras') && $request->m_financeiras == 1) ? 'selected' : '' }}>Pelo presidente, de forma isolada</option>
                            <option value="2" {{ ($request->has('m_financeiras') && $request->m_financeiras == 2) ? 'selected' : '' }}>Pelo Tesoureiro e pelo Presidente, em conjunto</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Escolha os cargos ministeriais que a igreja terá: Todas as igrejas já terão a função de pastor!</label>
                        @php
                            $cargos = ['Apóstolo', 'Bispo', 'Diácono', 'Dirigente', 'Evangelista', 'Missionário', 'Obreiro', 'Presbítero'];
                        @endphp
                        <select class="form-control selectpicker" name="cargom[]" multiple title="Selecione">
                            @foreach($cargos as $cargo)
                                <option value="{{ $cargo }}">{{ $cargo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Os membros do ministério serão remunerados por suas funções Eclesiáticas?</label>
                        <select class="form-control selectpicker" name="remuneracao" required>
                            <option value="1" {{ ($request->has('remuneracao') && $request->remuneracao == 1) ? 'selected' : '' }}>Sim, em todos os casos</option>
                            <option value="2" {{ ($request->has('remuneracao') && $request->remuneracao == 2) ? 'selected' : '' }}>Sim, apenas em casos de trabalho integral com registro em carteira</option>
                            <option value="3" {{ ($request->has('remuneracao') && $request->remuneracao == 3) ? 'selected' : '' }}>Não, todo trabalho ministerial será considerado voluntário</option>
                        </select>
                    </div>

                </div>

                <button type="submit" class="btn btn-success btn-block">
                    <i class="fa fa-print"></i> Gerar
                </button>
            </form>
        </div>

        <div class="col-md-8">
            <div class="chart-box">
                <div id="output">

                    @if($request->isMethod('post'))
                        @php
                            $nome_igreja = $igreja->name;
                            $endereco_igreja = $igreja->street . ', '. $igreja->street_number;
                            $complemento_igreja = $igreja->complement ?? '';
                            $bairro_igreja = $igreja->neighborhood;
                            $cidade_igreja = $igreja->city;
                            $uf_igreja = $igreja->state;
                            $cep_igreja = $igreja->zip;

                            $igrejaM = strtoupper($nome_igreja);

                            $presidente = $igreja->members()->where('role', 'Presidente')->first();
                            if(!$presidente){
                                $texto1 = "<h2 style='color:red !important;'>NÃO TEM PRESIDENTE CADASTRADO</h2>";
					            $nome_presidente = "Nome Presidente";
                            }else{
                                $nome_presidente = $presidente->name;
                            }

                        if ($request->fundadores != "") {
                            $fundadores ="<span style='color:blue !important;'>tem como fundadores o {$request->fundadores}</span>";
                        }else{
                            $fundadores ="";
                        }

                        $sede = "<span style='color:blue !important;'>{$request->sede}</span>";

                        switch($request->vice){
                            case "1":
                                $diretoria_vice = "<span style='color:red !important;'>Vice-Presidente</span>";
                                break;
                            case "0":
                                $vice = "";
                                $diretoria_vice = "";
                                break;
                        }

                        switch($request->tesouraria){
                            case "2":
                                $diretoria_tesouraria = "<span style='color:red !important;'>1° e 2° Tesoureiros</span>";
                                break;
                            case "1":
                                $diretoria_tesouraria = "<span style='color:red !important;'>Tesoureiro</span>";
                                break;
                        }

                        switch($request->secretaria){
                            case "2":
                                $diretoria_secretaria = "<span style='color:red !important;'>1° e 2° Secretários</span>";
                                break;
                            case "1":
                                $diretoria_secretaria = "<span style='color:red !important;'>Secretario</span>";
                                break;
                            case "0":
                                $diretoria_secretaria = "";
                                break;
                        }

                        $dtFundacao = \Carbon\Carbon::createFromFormat('d/m/Y', $request->data_fundacao)->format('Y-m-d');
                        $dissolucao = "<span style='color:red !important;'>do seu Presidente</span>";
                        $data_extenso = strftime("%d de %B de %Y", strtotime($dtFundacao));
                        $data_fundacao = "<span style='color:red !important;'>$data_extenso</span>";

                        $m_presidente = $request->m_presidente;
                        switch($m_presidente){
                            case "100":
                                $mandato_presidente = "<span style='color:red !important;'>O mandato do Presidente será vitalício.</span>";
                                break;
                            case "200":
                                $mandato_presidente = "<span style='color:red !important;'>O Presidente terá seu mandato por tempo indeterminado.</span>";
                                break;
                            default :
                                $m_presidente = "<span style='color:red !important;'>".$m_presidente." anos</span>";
                                $mandato_presidente = "<span style='color:red !important;'>O mandato do Presidente será de $m_presidente, podendo ser reeleito quantas vezes for necessário.</span>";
                                break;
                        }

                        $m_diretoria = $request->m_diretoria;
                        $m_diretoria = "<span style='color:red !important;'>".$m_diretoria." anos</span>";$escolha_diretoria = "<span style='color:red !important;'>A escolha da diretoria se dará através da indicação do Presidente.</span>";

                        $escolha_diretoria = "<span style='color:red !important;'>A escolha da diretoria se dará através da indicação do Presidente.</span>";

                        switch($request->m_financeiras){
                            case "1":
                                $cabe_presidente = "<span style='color:blue !important;'>V - Abrir e movimentar contas bancárias e assinar os documentos necessários para pagamentos e remessas de valores.</span></br>";
                                $cabe_tesoureiro = "<span style='color:blue !important;'>VI - Supervisionar os pagamentos feitos pelo presidente;</span></br>";
                                break;
                            case "2":
                                $cabe_tesoureiro = "<span style='color:blue !important;'>VI - Abrir e movimentar contas bancárias e assinar, juntamente com o Presidente, os documentos necessários para pagamentos e remessas de valores;</span></br>";
                                $cabe_presidente = "<span style='color:blue !important;'>V - Abrir e movimentar contas bancárias e assinar, juntamente com o Tesoureiro, os documentos necessários para pagamentos e remessas de valores;</span></br>";
                                break;
                        }

                        if($request->has('cargom')){
                            $cargom = $request->cargom;
                            $cargos_ministeriais = "<span style='color:red !important;'>".implode(", ", $cargom)."</span>";
                        }else{
                            $cargos_ministeriais = "<span style='color:red !important;'>Presbíteros, Diáconos e Obreiros</span>";
                        }

                        $ordenacao = "<span style='color:red !important;'>As ordenações ministeriais se darão através de indicação do Pastor Presidente da IGREJA</span>";

                        @endphp


                        <div class="right_col" role="main">
                            <h1> Geração de Documentos </h1>
                            <h2> <?php echo $nome_igreja; ?></h2></br>
                            <p>
                                <strong>FONTE: Arial 12</br> ESPAÇAMENTO: 1,5</br>TEXTO: Justificado</br>TÍTULOS: Centralizados, letras maiúsculas e em negrito</br></strong>
                            </p>

                            <div class="form-group">
                                <?php

                                // FUNÇÃO PARA FORMATAR CPF
                                $pattern = '/^([[:digit:]]{3})([[:digit:]]{3})([[:digit:]]{3})([[:digit:]]{2})$/';
                                $replacement = '$1.$2.$3-$4';

                                // FUNÇÃO PARA FORMATAR CEP
                                $pattern_cep = '/^([[:digit:]]{5})([[:digit:]]{3})$/';
                                $replacement_cep = '$1-$2';

                                $cap = 1;
                                $capR = numberIntegerToRoman($cap, true);
                                $a = 1;
                                echo "<div align=center  style='background-color:#FFFFFF;  padding: 25px 50px 25px 50px;'>

		<h2><strong>ESTATUTO DA $igrejaM</strong></h2>
		</br>
		<h2><strong>CAPÍTULO $capR</br> DA DENOMINAÇÃO, REGIME JURÍDICO, DURAÇÃO, SEDE E FORO</strong></h2>
		</br>


		<p align=justify style='color:red !important;'><strong>Art. $a º -</strong> Constituída por tempo indeterminado na cidade de $cidade_igreja - $uf_igreja, na data de $data_fundacao, a $igrejaM, é uma organização religiosa sem fins econômicos e com número ilimitado de membros, com sede $sede na $endereco_igreja – $complemento_igreja – $bairro_igreja – $cidade_igreja - $uf_igreja – CEP: " . preg_replace($pattern_cep, $replacement_cep, $cep_igreja) . ", doravante neste Estatuto denominada IGREJA.</br>";
                                /*
                                I - A IGREJA tem por denominação social $igrejaM;</br>
                                II - A IGREJA tem por finalidade prestar culto a Deus em Espírito e em Verdade e seguir as tradições e doutrinas do Evangelho do Senhor Jesus Cristo revelado nas Escrituras Sagradas, a Bíblia;</br>
                                III - A IGREJA terá sua sede $sede na $endereco_igreja – $complemento_igreja – $bairro_igreja – $cidade_igreja - $uf_igreja – CEP: ".preg_replace($pattern_cep, $replacement_cep, $cep_igreja).".</br>
                                IV - A IGREJA tem prazo de duração indeterminado;</br>
                                V - A IGREJA em tempo oportuno e no prazo menor possível estará criando seu regimento interno, que terá que ser registrado no cartório competente.</p></br>";	*/

                                $a++;$cap++;$capR = numberIntegerToRoman($cap, true);

                                echo "<div align=center>

		</br>
		<h2><strong>CAPÍTULO $capR</br> OBJETIVO E ATIVIDADE PRINCIPAL</strong></h2>
		</br>

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA tem por objetivo adorar e cultuar a Deus, conforme o disposto nas Escrituras Sagradas – Antigo e Novo Testamento. Para alcançar seus objetivos, a IGREJA deve:</br>
		<strong>§1° -</strong> Propagar o Evangelho de Nosso Senhor Jesus Cristo através da palavra de Deus, a Bíblia, doutrinar todos os membros, lhes ensinando como alcançar a experiência bíblica e prática das Escrituras, com vistas ao testemunho como cidadãos do Reino de Deus;</br>
		<strong>§2° -</strong> Apoiar a criação de novas IGREJAS, evangelizar por todos os meios de comunicação, tais como: folhetos, impressos, livros, jornais, revistas, CDs, DVDs, rádio, TV e internet com a finalidade de difundir o conhecimento de Deus para a salvação da humanidade e colaborar com a sociedade na libertação dos homens e na sua regeneração de vida;</br>
		<strong>§3° -</strong> Promover seminários, encontros, congressos, cruzadas evangelísticas, simpósios e reuniões para pessoas que queiram viver uma vida cristã dinâmica e verdadeira;</br>
		<strong>§4° -</strong> Exercer qualquer atividade permitida por lei que concorra para os mesmos fins;</br>";

                                $a++;$cap++;$capR = numberIntegerToRoman($cap, true);

                                echo "
		</br>
		<h2><strong>CAPÍTULO $capR</br> MEMBROS, SEUS DIREITOS E DEVERES</strong></h2>
		</br>

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Membros da IGREJA são pessoas que fazem parte desta organização religiosa com a finalidade de receberem orientação fundamentadas através da Bíblia Sagrada, que espontaneamente declaram seu desejo por escrito de se filiar a IGREJA.</p>";

                                $a++;

                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA pode ter número ilimitado de membros, pessoas de ambos os sexos, independente de nacionalidade, cor, condição social ou política, contanto que estejam em conformidade com as finalidades deste estatuto e regulamento interno da IGREJA.</br>

		<strong>Parágrafo Único –</strong> A IGREJA reserva-se ao direito de aceitar como membros os que forem batizados e aceitarem o batismo nas águas por imersão com bom testemunho público tendo unicamente a Bíblia Sagrada por sua regra de fé e governo.</p></br>";

                                $a++;

                                echo "
		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Direitos dos Membros:</br>
		<strong>§1º -</strong> Votarem e serem votados quando solicitados desde que estejam em conformidades com o regulamento interno da IGREJA;</br>
		<strong>§2º -</strong> Participar, quando solicitados, <span align=justify style='color:red !important;'> das reuniões de ministério;</span></br>
		<strong>§3º -</strong> Participar dos sacramentos;</br>
		<strong>§4º -</strong> Participar dos Cultos da IGREJA;</br>
		<strong>§5º -</strong> Participar dos estudos bíblicos;</br>
		<strong>§6º -</strong> Desligar-se do quadro de membros ou transferir-se para outra Igreja, comunicando ao Pastor sobre sua decisão;</br>
		<strong>§7º-</strong> Participar, quando solicitado pelo Pastor, sob cuja autoridade espiritual esteja, dos serviços ministeriais e outros empreendimentos com fins espirituais para a propagação do Evangelho do Nosso Senhor Jesus Cristo.</p></br>";


                                $a++;

                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Deveres dos Membros:</br>
		<strong>§1º -</strong> Cumprirem o estatuto e o regulamento interno, as decisões do corpo ministerial e administrativo da IGREJA;</br>
		<strong>§2º -</strong> Prestar ajuda e colaboração à IGREJA, quando solicitados, sempre gratuitamente;</br>
		<strong>§3º -</strong> Comparecer às <span align=justify style='color:red !important;'>reuniões de ministério, quando convocados;</span></br>
		<strong>§4º -</strong> Zelar pelo patrimônio moral e material da IGREJA;</br>
		<strong>§5º -</strong> Sendo eleito para qualquer cargo, inclusive da Diretoria, desempenhar suas funções com presteza, desinteressadamente, sem pretender ou exigir qualquer remuneração ou participação de seus bens patrimoniais.</p></br>";

                                $a++;

                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Das exclusões:</br>
		A exclusão dos membros, inclusive os da Diretoria, se dará havendo justa causa considerada de existência de motivos graves, depois de aprovada pelo <span align=justify style='color:red !important;'>Presidente</span>, cabendo o acusado pleno direito em sua defesa. São considerados casos graves os seguintes:</br>

		I - Os que abandonarem a IGREJA sem qualquer comunicação;</br>
		II - Os que deixarem de dar bom testemunho público;</br>
		III - Os que se desviarem dos preceitos bíblicos recomendados como regra e ensinamento;</br>
		IV - Os que praticarem imoralidade por sexualismo, conforme consta nas Epístolas aos I Coríntios, capítulo 6, versículo 9 e 10 e aos Romanos, capítulo 1, versículo 27 e 28 da Bíblia Sagrada;</br>
		V - Os que não cumprirem seus deveres expressos neste estatuto e no Regulamento Interno da IGREJA;</br>
		VI - Por praticarem rebeldia contra órgão de administração;</br>
		VII - Por roubo ou furto qualificado;</br>
		VIII - Por atos imorais à sociedade;</br>
		IX - Por praticarem bigamia;</br>
		X - Por praticarem pedofilia;</br>
		<strong>§1º -</strong> Os motivos considerados graves não previstos neste artigo serão resolvidos tomando como base a visão e posição da Bíblia Sagrada;</br>
		<strong>§2º -</strong> Nenhum direito patrimonial, econômico ou financeiro terá quem for desligado da IGREJA, ou participação de seus bens, por possuir apenas aquela qualidade de membro, como também solicitar devoluções de ofertas, coletas ou dízimos e outras contribuições que tenham efetuado.</p> </br> ";

                                $a++;

                                $governo = "<span style='color:red !important;'>Episcopal</span>";
                                $supensoes = "<p align=justify style='color:red !important;'><strong>Art. $a" . 'º' . " -</strong> Das suspensões:</br>
		<strong>§1º -</strong> Qualquer membro, inclusive da Diretoria, que ficar suspenso por tempo indeterminado por não ser considerado de justa causa ou falta grave, ficará sem direito de exercer suas funções, devendo ser transcrito em ata e aprovada pelo Presidente;</br>
		<strong>§2º -</strong> Vencendo a sua suspensão, o membro voltará a ter seus direitos, devendo ser transcrito em ata e aprovada pelo Presidente.</p>";

                                echo $supensoes;

                                $a++;$cap++;$capR = numberIntegerToRoman($cap, true);

                                echo "
		</br>
		<h2><strong>CAPÍTULO $capR</br>DO CARÁTER DOS RECURSOS E MODO DE APLICAÇÃO</strong></h2>
		</br>

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Os recursos da IGREJA serão obtidos voluntariamente através de dízimos, ofertas e doações espontâneas de pessoas físicas e jurídicas, que serão obrigatoriamente escrituradas em livros próprios que assegurem sua exatidão.</p> ";

                                $a++;

                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Os recursos da IGREJA serão aplicados integralmente na manutenção dos seus objetivos sociais.</p> ";

                                $a++;

                                echo "
		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> É vedada a remuneração, por qualquer forma, aos membros da Diretoria no exercício da função que foi eleito e a distribuição de lucros, dividendos, bonificações ou vantagens de seus patrimônios ou de suas rendas a dirigentes, administradores, mantenedores ou membros sob nenhuma forma ou pretexto, a título de participação de seu patrimônio.</p> ";

                                //echo $assG;


                                $a++;$cap++;$capR = numberIntegerToRoman($cap, true);

                                echo "
		</br>
		<h2><strong>CAPÍTULO $capR</br>DO MINISTÉRIO ECLESIÁSTICO</strong></h2>
		</br>

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> O governo eclesiástico da IGREJA se fará pela forma $governo, compreendendo sua liderança eclesiástica, a saber:</br>
		I - Pastor Presidente, tendo este pleno poder na direção da IGREJA;</br>
		II - Pastores Auxiliares, $cargos_ministeriais para auxiliá-lo na direção das igrejas e congregações filiais e apoio na Sede.</br>

		<strong>§1° -</strong> As Reuniões da Liderança IGREJA serão constituídas por todos os membros da liderança eclesiástica, estando todos obrigados a comparecer. </br>

		<strong>§2° -</strong> As Reuniões de Liderança serão convocadas para resolução de assuntos de interesse da IGREJA.</br>

		<strong>§3° -</strong> Pode o Pastor Presidente convocar reuniões para buscar conselho e opiniões com todos os membros da liderança eclesiástica, ou apenas parte destes, sendo que a decisão quanto aos assuntos tratados será tomada sempre pelo Pastor Presidente.</br>

		<strong>§4° -</strong> Na ausência ou impedimento permanente do Pastor Presidente, caso seu sucessor não seja por ele anunciado publicamente na Igreja Sede, o <span style='color:red !important;'>Vice Presidente / Tesoureiro</span> assume o cargo de maneira direta. </br>

		<strong>§5° -</strong> Deixam de fazer parte da liderança eclesiástica da IGREJA: </br>
		a) Aqueles que se enquadrarem em quaisquer características mencionadas no <span style='color:red !important;'>artigo 7</span>, sob decisão exclusiva do Pastor Presidente;</br>
		b) Aqueles que não andarem em unidade ministerial, em acordo com a IGREJA, livro-base de seu Regimento Interno.</p> ";


                                $a++;$cap++;$capR = numberIntegerToRoman($cap, true);

                                echo "

		</br>
		<h2><strong>CAPÍTULO $capR</br>DAS ORDENAÇÕES E OFÍCIOS ECLESIÁSTICOS</strong></h2>
		</br>


		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Na IGREJA, os ofícios eclesiásticos adotados são os de Pastor, $cargos_ministeriais, neste estatuto, denominados como Ministros.</p> ";

                                $a++;

                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Cabe ao Pastor Presidente da IGREJA preparar, consagrar e ordenar Ministros para o desempenho de funções eclesiásticas e de ordem para IGREJA, concedendo-lhes credenciais conforme suas funções.</p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA reserva-se no direito de caçar a credencial expedida ao ministro ordenado, a qualquer tempo que não permanecer fiel a doutrina por ela determinada, no cumprimento da boa ordem da fraternidade cristã e aos costumes previstos na Palavra de Deus.</p> ";

                                $a++;

                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Os Ministros trabalharão em serviços de organização do culto, assistência social e apoio ao trabalho Pastoral. Todos deverão participar do Curso de Formação de Líderes e dos cursos de reciclagem propostos pelo Pastor Presidente, sendo a ausência nestes considerado motivo suficiente para a exclusão do Ministro para exercício da função.</br>

		<strong>Parágrafo Único -</strong> Os Ministros serão selecionados dentre os membros que mais se destacaram em seu serviço ministerial.</p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A qualquer Ministro com função de desempenhar a pregação do Evangelho, a Santa Ceia, Batismo nas águas, realizar cerimônias fúnebres e de casamento desta IGREJA, não implica o reconhecimento de relação de emprego nem de vínculo empregatício de trabalho assalariado ou prestação de serviço remunerado, uma vez que a entidade não tem fins lucrativos e nem assume o risco de atividade econômica, não se podendo também falar em perda de danos morais, por estar dentro de sua espontânea vocação e convicção religiosa, mesmo que mantido pela instituição. </p>";

                                $a++;

                                // REMUNERAÇÕES MINISTERIAIS ----------------------- REMUNERAÇÕES MINISTERIAIS ----------------------- REMUNERAÇÕES MINISTERIAIS
                                switch ($_POST['remuneracao']) {

                                    case "1":

                                        $remuneracao = "<p align=justify style='color:red !important;'><strong>Art. $a" . 'º' . " -</strong> Todos os Ministros receberão ajuda de custo por seu ofício, porém isso não representará ou gerará vínculo empregatício, quer com a Denominação, quer com a IGREJA ou mesmo com a Igreja local onde for enviado, servindo nas Igrejas deste Ministério onde for enviado por espontânea doação do seu trabalho voluntário à Causa do Senhor Jesus Cristo.</br>
			<strong>Parágrafo Único: </strong> A ajuda de custo deverá ser oficializada através de um documento específico para este fim e todas as contribuições e recolhimentos ficam sob a responsabilidade do beneficiário, isentando a IGREJA de toda e qualquer responsabilidade.</p>";
                                        break;

                                    case "2":

                                        $remuneracao = "<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> Apenas os pastores receberão remuneração por seu ofício, a título de Prebenda Pastoral, sendo efetuados todos os descontos e pagamentos de impostos de contribuinte autônomo, desde que se dediquem de maneira integral a IGREJA e seus projetos.</br>

			<strong>Parágrafo Único: </strong> A nomeação como Ministro para exercício de qualquer outro cargo ministerial não representará ou gerará vínculo empregatício, quer com a Denominação, quer com a IGREJA ou mesmo com a Igreja local onde for enviado, servindo nas Igrejas deste Ministério onde for enviado por espontânea doação do seu trabalho voluntário à Causa do Senhor Jesus Cristo. </p>";
                                        break;

                                    case "3":

                                        $remuneracao = "<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> A nomeação como Ministro não representará ou gerará vínculo empregatício, quer com a Denominação, quer com a IGREJA ou mesmo com a Igreja local onde for enviado, servindo nas Igrejas deste Ministério onde for enviado por espontânea doação do seu trabalho voluntário à Causa do Senhor Jesus Cristo, sem exigir qualquer remuneração.</br>

			<strong>Parágrafo Único: </strong> Os Ministros podem receber valores a título de ajuda de custo ou reembolso, sempre que necessitarem de recursos financeiros para servirem a IGREJA e a seus objetivos. </p>";
                                        break;
                                }
                                echo "$remuneracao";

                                $a++;$cap++;$capR = numberIntegerToRoman($cap, true);

                                echo "

		</br>
		<h2><strong>CAPÍTULO $capR</br>DA ADMINISTRAÇÃO</strong></h2>
		</br>

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A Diretoria exercerá sua função com responsabilidade e poderes definitivos por este ato constitutivo;</p>";

                                $a++;

                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA terá uma Diretoria composta dos seguintes membros, a saber: Presidente, $diretoria_vice, $diretoria_tesouraria, $diretoria_secretaria.</br>

		<strong>Parágrafo único -</strong> $mandato_presidente O restante dos membros da diretoria terão o mandato de $m_diretoria, podendo ser reeleitos de forma isolada ou conjunta.</p></br>";



                                // MANDATO PRESIDENTE ---------------------------------- MANDATO PRESIDENTE ----------------------------- MANDATO PRESIDENTE
                                switch ($_POST['m_presidente']) {

                                    case "100":
                                        $a++;
                                        $mandato_presidente2 = "<p align=justify ><strong>Art. $a" . 'º' . " -</strong> O fundador da IGREJA, $nome_presidente, será Presidente da IGREJA de maneira vitalícia, enquanto o mesmo estiver cumprindo os preceitos das Sagradas Escrituras e a Sã Doutrina de Nosso Senhor Jesus Cristo.</br>

			<strong>§1° - </strong>Em caso de saída do cargo do presidente vitalício, o mesmo deverá indicar algum membro da Liderança para a ocupação do cargo de presidente, que passará a ser exercido por tempo indeterminado, enquanto o mesmo estiver cumprindo os preceitos das Sagradas Escrituras e a Sã Doutrina de Nosso Senhor Jesus Cristo.  </br>
			<strong>§2° - </strong>Em caso de saída do cargo do presidente vitalício, e o mesmo estiver impossibilitado de indicar algum membro da Liderança para a ocupação do cargo de presidente, o <span style='color:red !important;'>Vice Presidente / Tesoureiro</span> assume o cargo de maneira direta.</p></br>";
                                        break;

                                    case "200":
                                        $a++;
                                        $mandato_presidente2 = "<p align=justify ><strong>Art. $a" . 'º' . " -</strong> O fundador da IGREJA, $nome_presidente, será Presidente da IGREJA por tempo indeterminado, enquanto o mesmo estiver cumprindo os preceitos das Sagradas Escrituras e a Sã Doutrina de Nosso Senhor Jesus Cristo.</p>";
                                        break;

                                    default :

                                        $mandato_presidente = "<span style='color:red !important;'>O mandato do Presidente será de $m_presidente.</span>";
                                        $mandato_presidente2 = "";
                                        break;
                                }

                                echo $mandato_presidente2;

                                $a++;

                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong>  Ao Presidente compete:</br>
		I - Representar a IGREJA, ativa, passiva, judicial e, extrajudicialmente, em juízo ou fora dele;</br>
		II - Convocar e presidir reuniões do ministério;</br>
		III - Cumprir e fazer cumprir todos artigos, parágrafos e incisos deste estatuto e do regimento interno;</br>
		IV - Supervisionar os movimentos dos demais membros da diretoria;</br>
		V - Admitir e demitir funcionários e empregados;</br>
		VI - Ordenar pastores adjuntos;</br>
		<span style='color:red !important;'>$cabe_presidente</span> </p>
		<p align=justify style='color:MediumBlue !important;'><strong> VERIFICAR OS NÚMEROS DOS INCISOS ACIMA!!!!</strong></p>";



                                // VERIFICA SE TEM VICE-PRESIDENTE  ------------------ VERIFICA SE TEM VICE-PRESIDENTE -------------------- VERIFICA SE TEM VICE-PRESIDENTE
                                switch ($_POST['vice']) {

                                    case "0":

                                        $assume_presidencia = "<span style='color:LimeGreen !important;'>VII - Substituir interinamente o Presidente nas suas faltas, ou impedimentos ou vacância;</span>";
                                        $vice = "";
                                        break;
                                    case "1":
                                        $a++;
                                        $vice = "
			<p align=justify style='color:red !important;' ><strong>Art.  $a" . 'º' . " -</strong> Compete ao Vice-Presidente:</br>

			I - substituir o Presidente em suas ausências e impedimentos; </br>
			II - supervisionar o trabalho dos membros da Diretoria, cuidando para que não haja atrasos nas prestações de contas e permaneçam sempre em ordem os arquivos da Secretaria e Tesouraria.</p>";
                                        $assume_presidencia = "";
                                        break;
                                }

                                echo $vice;

                                $a++;

                                // VERIFICA SE TEM 1 OU 2 TESOUREIROS   ----------- VERIFICA SE TEM 1 OU 2 TESOUREIROS --------------VERIFICA SE TEM 1 OU 2 TESOUREIROS
                                switch ($_POST['tesouraria']) {

                                    case "2":

                                        $tesouraria = "<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> Compete ao 1° Tesoureiro:</br>
			I - Ter em boa ordem e com clareza as escrituras de todas as receitas e despesas da entidade;</br>
			II – Auxiliar o presidente no que for necessário;</br>
			III – Supervisionar e gerenciar todos os movimentos da tesouraria;</br>
			IV – Fazer todos os pagamentos mediante comprovantes em nome da IGREJA e ficarão sob sua guarda os documentos contábeis;</br>
			V – apresentar relatório de receita e despesas sempre que forem solicitados;</br>
			VI - Ler anualmente, em janeiro de cada ano, o relatório financeiro da tesouraria, ou quando solicitado pelo presidente a qualquer tempo;</br>
			$cabe_tesoureiro
			$assume_presidencia
			</br>
			<strong>Parágrafo Único –</strong> Compete ao 2º Tesoureiro, substituir o 1º Tesoureiro, em suas faltas e impedimentos, assumindo o cargo em caso de vacância.</p>
			<p align=justify style='color:MediumBlue !important;' ><strong> VERIFICAR OS NÚMEROS DOS INCISOS ACIMA!!!!</strong></p>";
                                        break;

                                    case "1":
                                        $tesouraria = "<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong>Compete ao Tesoureiro:</br>
			I - Ter em boa ordem e com clareza as escrituras de todas as receitas e despesas da entidade; </br>
			II - Auxiliar o presidente no que for necessário;</br>
			III - Supervisionar e gerenciar todos os movimentos da tesouraria;</br>
			IV - Fazer todos os pagamentos mediante comprovantes em nome da IGREJA e ficarão sob sua guarda os documentos contábeis;</br>
			$cabe_tesoureiro
			$assume_presidencia					</p>
			<p align=justify style='color:MediumBlue !important;'><strong> VERIFICAR OS NÚMEROS DOS INCISOS ACIMA!!!!</strong></p>";
                                        break;
                                }

                                echo $tesouraria;



                                // VERIFICA SE TEM 1 OU 2 SECRETARIOS   ----------- VERIFICA SE TEM 1 OU 2 SECRETARIOS --------------VERIFICA SE TEM 1 OU 2 SECRETARIOS

                                switch ($_POST['secretaria']) {

                                    case "2":
                                        $a++;
                                        $secretaria = "
			<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> Compete ao 1° Secretário:</br>
			I - Lavrar as atas das reuniões ordinárias ou extraordinárias da diretoria;</br>
			II - Ter em boa ordem o arquivo da secretaria;</br>
			III -Dirigir e supervisionar todo o trabalho da Secretaria.</br>
			$assume_presidencia

			</br><strong>Parágrafo Único –</strong> Compete ao 2º Secretário, substituir o 1º Secretário, em suas faltas e impedimentos, assumindo o cargo em caso de vacância.</p></br>";

                                        break;


                                    case "1":
                                        $a++;
                                        $secretaria = "
			<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> Compete ao Secretário:</br>
			I - Lavrar as atas das reuniões ordinárias ou extraordinárias da diretoria;</br>
			II - Ter em boa ordem o arquivo da secretaria;</br>
			III -Dirigir e supervisionar todo o trabalho da Secretaria.</br>
			</p></br>";

                                        break;

                                    case "0":
                                        $secretaria = "<p align=justify style='color:blue !important;' >I - Redigir atas e lê-las para aprovação;</br>
			II - Ter em boa ordem o arquivo da secretaria;</p>
			<p align=justify><strong> ^^^ INCLUIR TEXTO NO 'CABE AO TESOUREIRO'!!!! ^^^</strong></p>";
                                        break;
                                }
                                echo $secretaria;

                                $a++;
                                echo "
		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A função de Pastor Presidente será exercida sempre pelo Presidente da IGREJA.</p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Fica vedado a qualquer membro da Diretoria, quando substituir o Presidente interinamente em suas faltas ou impedimentos ou vacâncias, fazer operações estranhas aos interesses da IGREJA, tais como: avais, penhora, passar procurações, vender bens patrimoniais, fazer reforma parcial ou total deste estatuto ou modificar quaisquer estrutura da IGREJA, como a doutrina e os bons costumes impostos pela entidade.</p></br>";



                                // VERIFICA SE TEM CONSELHO -------------------------- VERIFICA SE TEM CONSELHO --------------------- VERIFICA SE TEM CONSELHO

                                switch ($_POST['conselho']) {

                                    case "1":

                                        $cap++;
                                        $a++;
                                        $capR = numberIntegerToRoman($cap, true);
                                        $conselho1 = "

			<h2><strong>CAPÍTULO $capR</br>DO CONSELHO FISCAL</strong></h2>

			<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> O Conselho Fiscal, que será composto por três membros, e tem por objetivo, indelegável, fiscalizar e dar parecer sobre todos os atos da Diretoria da IGREJA, com as seguintes atribuições;</br>

			I.  Examinar os livros de escrituração da IGREJA;</br>

			II.  Opinar e dar pareceres sobre balanços e relatórios financeiro e contábil.</br>

			III.  Requisitar ao Tesoureiro, a qualquer tempo, a documentação comprobatória das operações econômico-financeiras realizadas pela IGREJA;</br>

			IV.  Acompanhar o trabalho de eventuais auditores externos independentes;</br>

			V.  Convocar os membros da igreja para uma reunião de prestação de contas.</br> </br>

			<strong>Parágrafo único -</strong> O Conselho Fiscal reunir-se-á uma vez por ano, na segunda quinzena de janeiro, ou sempre que convocado  pelo Presidente da IGREJA, ou pela maioria simples de seus membros.</p> ";

                                        $a++;
                                        $conselho2 = "
			<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> As eleições para a Diretoria e Conselho Fiscal realizar-se-ão, conjuntamente, a cada $m_diretoria, por chapa completa de candidatos, podendo seus membros ser  reeleitos.</p>";

                                        $a++;
                                        $conselho3 = "
			<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> A perda da qualidade de membro do Conselho Fiscal acontecerá somente havendo justa causa, assim reconhecida em procedimento disciplinar, quando ficar comprovado:</br>

			I.  Malversação ou dilapidação do patrimônio social;</br>

			II.  Grave violação deste estatuto;</br>

			III.  Abandono do cargo, assim considerada a ausência não justificada em 03 (três) reuniões ordinárias consecutivas, sem expressa comunicação dos motivos da ausência, à secretaria da IGREJA;</br>

			IV.  Aceitação de cargo ou função incompatível com o exercício do cargo que exerce na IGREJA;</br>

			V.  Conduta duvidosa.</br> </br>

			<strong>§1° –</strong> Definida a justa causa, o membro do conselho será comunicado, através de notificação extrajudicial, dos fatos a ele imputados,  para que apresente sua defesa prévia à Diretoria, no prazo de 20 (vinte) dias, contados do recebimento da comunicação;</br>

			<strong>§2° –</strong> Após comunicar o acusado, o Pastor Presidente ou seu representante, deverá comunicar a IGREJA sobre o acusação e anunciar no culto de domingo subsequente a data para o julgamento da justa causa;</br>

			<strong>§3° –</strong> Após o decurso do prazo descrito no parágrafo primeiro, independentemente da apresentação de defesa, a representação será submetida à todos os membros efetivos da IGREJA, convocados para esse fim, não podendo ser deliberado sem voto concorde de 2/3 (dois terços) dos presentes.</p> ";

                                        $a++;
                                        $conselho4 = "
			<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> Em caso renúncia de qualquer membro do Conselho Fiscal, o cargo será preenchido pelos suplentes.</br> </br>

			<strong>Parágrafo Único -</strong> O pedido de renúncia se dará por escrito, devendo ser entregue ao presidente da IGREJA, a qual, no prazo máximo de 60 (sessenta) dias, contado  da data do pedido, realizará novas eleições para o cargo, através de uma reunião de liderança, convocada para este fim;</p>";

                                        $a++;
                                        $conselho5 = "
			<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> Os membros do Conselho Fiscal não receberão nenhum tipo de remuneração, de qualquer espécie ou natureza, pelas atividades exercidas na IGREJA.</p> ";

                                        $conselho = $conselho1 . $conselho2 . $conselho3 . $conselho4 . $conselho5;
                                        break;

                                    case "0":
                                        $conselho = "";
                                        break;

                                }
                                echo $conselho;


                                $a++;$cap++;$capR = numberIntegerToRoman($cap, true);

                                // VACANCIA PRESIDENTE  -------------------------- VACANCIA PRESIDENTE ------------------------- VACANCIA PRESIDENTE
                                $vacanciap = "
		<h2><strong>CAPÍTULO $capR</br>DA VACÂNCIA DOS CARGOS</strong></h2>
		</br>

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong>  Em caso de vacância do cargo de presidência, o cargo de Presidente será ocupado pelo <span style='color:red !important;' >$diretoria_vice / Tesoureiro.</span></br>

		<strong>§1º -</strong> A perda do mandato será declarada através de uma reunião, convocada para este fim, depois de uma junta de pastores do mesmo ministério tiver julgado o acusado, cabendo-lhe pleno direito de exercer suas defesas;</br>
		<strong>§2º -</strong> O substituto para  o cargo vago será eleito e empossado com aprovação da maioria dos presentes, que cumprirá o seu mandato pelo tempo de mandato restante.</p></br>";

                                echo $vacanciap;

                                $a++;

                                // VACANCIA DIRETORIA ----------------------------- VACANCIA DIRETORIA ------------------------------ VACANCIA DIRETORIA

                                $vacanciad = "<p align=justify style='color:red !important;'><strong>Art. $a" . 'º' . " -</strong> No caso de vacância dos demais cargos da diretoria, caberá ao presidente da IGREJA designar outro substituto ao cargo vago.</p>";

                                echo $vacanciad; ;
                                // DOS BENS ----------------------------  DOS BENS --------------------------  DOS BENS -------------
                                $a++;$cap++;$capR = numberIntegerToRoman($cap, true);
                                echo "

		</br>
		<h2><strong>CAPÍTULO $capR</br>DOS BENS</strong></h2>
		</br>

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Os bens da IGREJA serão administrados pela respectiva diretoria, cujo Presidente assinará os documentos oficiais de entidade, bem como cheques, procurações, títulos e contratos em geral, escritura pública, vendas e aquisição bancária, ou qualquer outra instituição financeira.</p>";

                                $a++;$cap++;$capR = numberIntegerToRoman($cap, true);
                                echo "

		</br>
		<h2><strong>CAPÍTULO $capR</br>DO PATRIMÔNIO</strong></h2>
		</br>

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA terá por patrimônio quaisquer bens imóveis, móveis e utensílios, veículos e semoventes, que possua ou venha a possuir, os quais serão escriturados em nome da IGREJA, e só poderão ser vendidos ou alienados com aprovação do presidente.</p>";

                                $a++;$cap++;$capR = numberIntegerToRoman($cap, true);
                                echo "

		</br>
		<h2><strong>CAPÍTULO $capR</br>DA CONVOCAÇÃO ESPECIAL</strong></h2>
		</br>

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Com o propósito de defender os interesses da IGREJA, se fará uma convocação especial em uma assembléia geral extraordinária, com todos os membros da diretoria e do ministério efetivos, a fim de tomarem ciência da deliberação doutrinárias para o bom crescimento e divulgação do evangelho. <br/>

		<strong>Parágrafo Único -</strong> Para tal convocação, o Presidente deverá convocar uma reunião, com no mínimo 15 dias de antecedência, através de edital fixado no quadro de avisos da IGREJA.
		</p>";

                                $a++;
                                $cap++;$capR = numberIntegerToRoman($cap, true);
                                echo "

		</br>
		<h2><strong>CAPÍTULO $capR</br>DAS FILIAIS</strong></h2>
		</br>

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA pode abrir filiais em todo o território nacional e, caso necessário, fora do País. </br>
		<strong>Parágrafo Único -</strong> Cabe à IGREJA matriz gerenciar todos os movimentos financeiros e econômicos das filiais.
		</p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Compreende-se como filiais as IGREJAS que são subordinadas e gerenciadas pela IGREJA matriz com a mesma norma deste estatuto. </p>";


                                //////// PAREI AQUI -------------------------------- //////// PAREI AQUI -------------------------------- //////// PAREI AQUI -------------------------------- //////// PAREI AQUI -------------------------------- //////// PAREI AQUI -------------------------------- //////// PAREI AQUI -------------------------------- //////// PAREI AQUI -------------------------------- //////// PAREI AQUI -------------------------------- //////// PAREI AQUI --------------------------------

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> As filiais abertas e as que se unirem serão vinculadas a IGREJA matriz, através da aceitação do Presidente.</p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> As filiais passarão a ser subordinadas e gerenciadas por este estatuto depois de lavrado em ata a devidamente registrado em cartório competente. </p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> No caso de divisão ou cisão unilateral de qualquer uma das filiais vinculadas à IGREJA matriz e subordinadas a este estatuto, além de serem desligados, perderão os direitos sobre seus bens patrimoniais, tais como: imóveis, utensílios, veículos, inclusive dinheiro em caixa, etc. Mesmo que seja a maioria, sem direito de reclamar em juízo ou fora dele contra a IGREJA matriz que é a fiel proprietária e mantenedora.</p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Fica vedado às filiais fazerem quaisquer operações estranhas, tais como penhora, outorgar procurações, vender bens patrimoniais bem como registrar, em cartório das pessoas jurídicas, atas ou estatuto, sem ordem por escrito do presidente da IGREJA matriz sob pena de nulidade e de serem embargadas.</p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> As filiais deverão, mensalmente, prestar conta de seus movimentos financeiros a tesouraria da IGREJA matriz e todas as despesas deverão ser devidamente comprovadas.</p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Caberá ao Presidente da IGREJA matriz, nomear ou substituir qualquer dirigente das filiais sem ônus ou prejuízos para a entidade mantenedora. </p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A filial poderá ser emancipada legalmente através da liberação do Presidente da IGREJA matriz e, neste caso poderá haver alienação dos bens patrimoniais em favor da filial emancipada, constando os referidos atos em ata que outorgou a emancipação. </p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> As filiais que receberem sua emancipação deverão elaborar seu estatuto, aprovado previamente pela IGREJA que concedeu sua emancipação. </p>";

                                $a++;
                                $cap++;$capR = numberIntegerToRoman($cap, true);
                                echo "

		</br>
		<h2><strong>CAPÍTULO $capR</br>DISPOSIÇÕES GERAIS</strong></h2>
		</br>

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Os membros da IGREJA não respondem, individual ou coletivamente, nem mesmo subsidiariamente pelas obrigações da IGREJA. </p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA não se responsabilizará por dívidas contraídas por terceiros, sem que haja para isso, uma prévia autorização por escrito assinada pelo tesoureiro, sendo nula com assinatura singular, não produzindo qualquer efeito de responsabilidade da entidade.</p> ";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Este estatuto só poderá ser reformado parcial ou totalmente, em casos especiais que a lei determine, ou por determinação do Presidente.</p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA, poderá alterar sua Razão Social ou Nome Fantasia a qualquer momento, mediante a aprovação do Pastor Presidente.</p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA poderá ser extinta quando for impossível sua continuidade por decisão $dissolucao, ou por sentença judicial transitada em Julgado. <br/>

		<strong>§1° -</strong> Em caso de extinção, o Presidente deve convocar uma reunião, com no mínimo 15 dias de antecedência, através de edital fixado no quadro de avisos da IGREJA, para deliberação da extinção.</br>
		<strong>§2° -</strong> Caso os membros da IGREJA discordem da decisão de extinção por parte do presidente, os mesmos poderão solicitar a continuidade das atividades, mediante a aprovação de ⅔ dos membros ativos da IGREJA.</br>
		<strong>§3° -</strong> Caso os membros decidam pela continuidade das atividades, realizar-se-á novas eleições para os cargos vagos, sendo eleitos aqueles que receberem a maioria simples dos votos.</br>
		<strong>§4° -</strong> Após a decisão pela continuidade das atividades e eleição da nova diretoria, a IGREJA continuará sendo regida por este estatuto. Caso necessário, os membros ativos da IGREJA podem propor e realizar uma reforma estatutária.</p>
		";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Em caso de dissolução, depois de pagos todos os compromissos, os bens e valores da IGREJA se reverterão em benefício de outra congênere ou o presidente decidirá quanto ao destino de seus bens, depois de resolvidos todos os compromissos. </p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Para fins contábeis, fiscais e de controle da IGREJA, o exercício social se encerra no dia 31 (trinta e um) de dezembro de cada ano civil.</p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA poderá elaborar um regimento interno.</p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA se nega a realizar qualquer cerimônia ou atividade que infrinja sua liberdade de crença, assegurada pelo Artigo 5° da Constituição Federal, tais como:</br>
		I - Casamento entre pessoas do mesmo sexo ou entre pessoas que realizaram a mudança de sexo;</br>
		II - Participar de movimentos folclóricos populares contrários a sua crença;</br>
		III - Participar ou defender partidos políticos com ideologias contrárias aos princípios bíblicos;</br>
		IV - Realizar batismos de pessoas que se neguem a seguir a Doutrina da IGREJA.
		</p>";

                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Os casos omissos deste estatuto serão resolvidos pelo presidente, os quais, depois de resolvidos e concluídos, serão transcritos em ata para que tenham força estatutária.</p>";


                                $a++;
                                echo "

		<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Este estatuto passará a vigorar depois de registrado em cartório competente, revogando-se as disposições em contrário.</p>



		<p>$cidade_igreja, $data_fundacao</p>



		<p>	_________________________________________________</p>
		<p>$nome_presidente</p>
		<p>PRESIDENTE</p>

		<p>__________________________________________________</p>
		<p>ADVOGADO</p>";


                                ?>

                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>


@endsection


@section('script')
    <script type="text/javascript">

    </script>
@endsection
