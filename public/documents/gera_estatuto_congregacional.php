<?php

if (!function_exists('numberIntegerToRoman')) {
    function numberIntegerToRoman($num, $debug = false)
    {
        $n = intval($num);
        $nRoman = '';

        $default = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1,
        );

        foreach ($default as $roman => $number) {
            $matches = intval($n / $number);
            $nRoman .= str_repeat($roman, $matches);
            $n = $n % $number;
        }
        return $nRoman;
    }
}
setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');

$array = $_POST['array'];
$nome_igreja = $array['igreja']['name'];
$cep_igreja = $array['igreja']['zip'];
$uf_igreja = $array['igreja']['state'];
$cidade_igreja = $array['igreja']['city'];
$bairro_igreja = $array['igreja']['neighborhood'];
$endereco_igreja = $array['igreja']['street'];
$complemento_igreja = $array['igreja']['complement'];
$igrejaM = strtoupper($nome_igreja);

// BUSCA PRESIDENTE

if (isset($array['presidente'])) {
    $nome_presidente = $array['presidente']['name'];
} else {
    $texto1 = "<h2 style='color:red !important;'>NÃO TEM PRESIDENTE CADASTRADO</h2>";
    $nome_presidente = "Nome Presidente";
}

// MEMBROS FUNDADORES
$fundadores = "";

// SEDE
$sd = $array['sede'];
$sede = "<span style='color:blue !important;'>" . $sd . "</span>";

// VERIFICA SE TEM VICE-PRESIDENTE
switch ($array['vice'] ?? 1) {
    case "1":
        $diretoria_vice = "<span style='color:red !important;'>Vice-Presidente</span>";
        break;
    case "0":
        $vice = "";
        $diretoria_vice = "";
        break;
}


// VERIFICA SE TEM 1 OU 2 TESOUREIROS

switch ($array['tesouraria'] ?? 1) {
    case "2":
        $diretoria_tesouraria = "<span style='color:red !important;'>1° e 2° Tesoureiros</span>";
        break;
    case "1":
        $diretoria_tesouraria = "<span style='color:red !important;'>Tesoureiro</span>";
        break;
}

switch ($array['secretaria'] ?? 1) {
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


// VERIFICA DISSOLUÇÃO
switch ($array['dissolucao'] ?? 2) {
    case "2":
        $dissolucao = "<span style='color:red !important;'>de 2/3 dos membros efetivos em comunhão através e uma assembléia geral extraordinária, convocada para este fim</span>";
        break;
    case "3":
        $dissolucao = "<span style='color:red !important;'>de 3/4 dos membros efetivos em comunhão através e uma assembléia geral extraordinária, convocada para este fim</span>";
        break;
}

// DATA FUNDAÇÃO
$date = $array['data_fundacao'] ?? '01-01-1999';
$data = date('d/m/Y', strtotime($array['data_fundacao'] ?? '01-01-1999'));
$data_extenso = strftime("%d de %B de %Y", strtotime($date));
$data_fundacao = "<span style='color:red !important;'>$data_extenso</span>";

// MANDATOS
$m_presidente = $array['m_presidente'] ?? 200;
switch ($m_presidente) {
    case "200":
        $mandato_presidente = "<span style='color:red !important;'>O Presidente terá seu mandato por tempo indeterminado.</span>";
        break;
    default:
        $m_presidente = "<span style='color:red !important;'>" . $m_presidente . " anos</span>";
        $mandato_presidente = "<span style='color:red !important;'>O mandato do Presidente será de $m_presidente, podendo ser reeleito quantas vezes for necessário.</span>";
        break;
}

// MANDATO DIRETORIA
$m_diretoria = $array['m_diretoria'] ?? 2;
$m_diretoria = "<span style='color:red !important;'>" . $m_diretoria . " anos</span>";
$escolha_diretoria = "<span style='color:red !important;'>Os membros da diretoria serão eleitos através de voto secreto.</span>";

// MOVIMENTAÇÕES FINANCEIRAS
$cabe_tesoureiro = "<span style='color:blue !important;'>VI - Abrir e movimentar contas bancárias e assinar, juntamente com o Presidente, os documentos necessários para pagamentos e remessas de valores;</span></br>";
$cabe_presidente = "<span style='color:blue !important;'>V - Abrir e movimentar contas bancárias e assinar, juntamente com o Tesoureiro, os documentos necessários para pagamentos e remessas de valores;</span></br>";


// ASSEMBLEIA GERAL - QUÓRUM
$ass1 = $array['ass_geral1'] ?? "ass_geral1";
$ass_geral1 = "<span style='color:red !important;'>" . $ass1 . "</span>";
$ass2 = $array['ass_geral2'] ?? "ass_geral2";
$ass_geral2 = "<span style='color:red !important;'>" . $ass2 . "</span>";

// CARGOS MINISTERIAIS
if (isset($array['cargom'])) {
    $cargom = $array['cargom'];
    $cargos_ministeriais = "<span style='color:red !important;'>" . implode(", ", $cargom) . "</span>";
} else {
    $cargos_ministeriais = "<span style='color:red !important;'>Presbíteros, Diáconos e Obreiros</span>";
}

// ORDENAÇÕES MINISTERIAIS

switch ($array['ordenacao'] ?? 1) {
    case "1":
        $ordenacao = "<span style='color:red !important;'>As ordenações ministeriais se darão através de voto dos membros presentes em Assembléia Geral Extraordinária convocada para este fim, sendo decidido através de maioria de votos simples. Em caso de empate, a decisão será tomada pelo Presidente.</span>";
        break;
    case "2":
        $ordenacao = "<span style='color:red !important;'>As ordenações ministeriais se darão através de indicação da Liderança da IGREJA</span>";
        break;
}
?>
<!-- page content -->
<div class="right_col" role="main">
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

				<h2><strong>ESTATUTO $igrejaM</strong></h2>
				</br>
				<h2><strong>CAPÍTULO $capR</br> DA DENOMINAÇÃO, REGIME JURÍDICO, DURAÇÃO, SEDE E FORO</strong></h2>
				</br>

				<p align=justify style='color:red !important;'>
				    <strong>Art. $a º -</strong> Constituída por tempo indeterminado na cidade de $cidade_igreja - $uf_igreja, na data de $data_fundacao, a $igrejaM, é uma organização religiosa
				    sem fins lucrativos e com número ilimitado de membros, com sede $sede na $endereco_igreja – $complemento_igreja – $bairro_igreja – $cidade_igreja - $uf_igreja –
				    CEP: " . preg_replace($pattern_cep, $replacement_cep, $cep_igreja) . ", doravante neste Estatuto denominada IGREJA $fundadores ;</br></br>
					I - A IGREJA tem por denominação social $igrejaM;</br>
					II - A IGREJA tem por finalidade prestar culto a Deus em Espírito e em Verdade e seguir as tradições e doutrinas do Evangelho do Senhor Jesus Cristo revelado nas Escrituras Sagradas, a Bíblia;</br>
					III - A IGREJA tem prazo de duração indeterminado;</br>
					IV - A IGREJA em tempo oportuno e no prazo menor possível estará criando seu regimento interno, que terá que ser registrado no cartório competente.</p></br>";

        $a++;
        $cap++;
        $capR = numberIntegerToRoman($cap, true);

        echo "<div align=center>

				</br>
				<h2><strong>CAPÍTULO $capR</br> OBJETIVO E ATIVIDADE PRINCIPAL</strong></h2>
				</br>

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA tem por objetivo adorar e cultuar a Deus, conforme o disposto nas Escrituras Sagradas – Antigo e Novo Testamento. Para alcançar seus objetivos, a IGREJA deve:</br></br>
					<strong>§1° -</strong> Propagar o Evangelho de Nosso Senhor Jesus Cristo através da palavra de Deus, a Bíblia, doutrinar todos os membros, lhes ensinando como alcançar a experiência bíblica e prática das Escrituras, com vistas ao testemunho como cidadãos do Reino de Deus;</br>
					<strong>§2° -</strong> Apoiar a criação de novas IGREJAS, evangelizar por todos os meios de comunicação, tais como: folhetos, impressos, livros, jornais, revistas, CDs, DVDs, rádio e TV com a finalidade de difundir o conhecimento de Deus para a salvação da humanidade e colaborar com a sociedade na libertação dos homens e na sua regeneração de vida;</br>
					<strong>§3° -</strong> Promover seminários para a família;</br>
					<strong>§4° -</strong> Promover encontros, congressos, cruzadas evangelísticas, simpósios e reuniões para pessoas que queiram viver uma vida cristã dinâmica e verdadeira;</br>
					<strong>§5° -</strong> Exercer qualquer atividade permitida por lei que concorra para os mesmos fins;</br>
					<strong>§6° -</strong> Criar tantos departamentos se fizerem necessários.</p>";

        $a++;
        $cap++;
        $capR = numberIntegerToRoman($cap, true);

        echo "
				</br>
				<h2><strong>CAPÍTULO $capR</br> MEMBROS, SEUS DIREITOS E DEVERES</strong></h2>
				</br>

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Membros da IGREJA são pessoas que fazem parte de uma organização religiosa com a finalidade de receberem orientação fundamentadas através da Bíblia Sagrada, que espontaneamente declaram seu desejo por escrito de se filiar a IGREJA.</p>";

        $a++;

        echo "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA pode ter número ilimitado de membros, pessoas de ambos os sexos, independente de nacionalidade, cor, condição social ou política, contanto que estejam em conformidade com as finalidades deste estatuto e regulamento interno da IGREJA.</br></br>

				<strong>Parágrafo Único –</strong> A IGREJA reserva-se ao direito de aceitar como membros os que forem batizados e aceitarem o batismo nas águas por imersão com bom testemunho público tendo unicamente a Bíblia Sagrada por sua regra de fé e governo.</p>";

        $a++;

        echo "
				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Direitos dos Membros:</br>
					<strong>§1º -</strong> Votarem e serem votados quando solicitados desde que estejam em conformidades com o regulamento interno da IGREJA;</br>
					<strong>§2º -</strong> Participar, quando solicitados, <span align=justify style='color:red !important;'> das Assembléias Gerais Ordinárias e Extraordinárias;</span></br>
					<strong>§3º -</strong> Participar dos sacramentos;</br>
					<strong>§4º -</strong> Participar dos Cultos da IGREJA;</br>
					<strong>§5º -</strong> Participar dos estudos bíblicos;</br>
					<strong>§6º -</strong> Desligar-se do quadro de membros ou transferir-se para outra Igreja, comunicando ao Pastor sobre sua decisão;</br>
					<strong>§7º-</strong> Participar, quando solicitado pelo Pastor, sob cuja autoridade espiritual esteja, dos serviços ministeriais e outros empreendimentos com fins espirituais para a propagação do Evangelho do Nosso Senhor Jesus Cristo.
					";

        $a++;

        echo "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Deveres dos Membros:</br>
					<strong>§1º -</strong> Cumprirem o estatuto e o regulamento interno, as decisões do corpo ministerial e administrativo da IGREJA;</br>
					<strong>§2º -</strong> Prestar ajuda e colaboração à IGREJA, quando solicitados, sempre gratuitamente;</br>
					<strong>§3º -</strong> Comparecer às <span align=justify style='color:red !important;'>Assembléias Gerais Ordinárias e Extraordinárias, quando convocados;</span></br>
					<strong>§4º -</strong> Zelar pelo patrimônio moral e material da IGREJA;</br>
					<strong>§5º -</strong> Sendo eleito para qualquer cargo, inclusive da Diretoria, desempenhar suas funções com presteza, desinteressadamente, sem pretender ou exigir qualquer remuneração ou participação de seus bens patrimoniais.</p>";

        $a++;

        echo "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Das exclusões:</br>
				A exclusão dos membros, inclusive os da Diretoria, se dará havendo justa causa considerada de existência de motivos graves, depois de aprovada pela maioria de votos dos presentes através de uma <span align=justify style='color:red !important;'>Assembléia Geral Extraordinária</span> convocada para este fim, cabendo o acusado pleno direito em sua defesa. São considerados casos graves os seguintes:</br> </br>

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
					XI - Os motivos considerados graves não previstos neste artigo serão resolvidos tomando como base a visão e posição da Bíblia Sagrada;</br>
					XII - Nenhum direito patrimonial, econômico ou financeiro terá quem for desligado da IGREJA, ou participação de seus bens, por possuir apenas aquela qualidade de membro, como também solicitar devoluções de ofertas, coletas ou dízimos e outras contribuições que tenham efetuado.</p> ";

        $a++;


        // GOVERNO
        $governo = "<span style='color:red !important;'>Congregacional</span>";
        $supensoes = "<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> Das suspensões:</br> </br>
					<strong>§1º -</strong> A juízo da Diretoria, qualquer membro, inclusive da Diretoria, que ficar suspenso por tempo indeterminado por não ser considerado de justa causa ou falta grave, ficará sem direito de votar e ser votado, devendo ser transcrito em ata e aprovada pela maioria dos presentes através de uma Assembleia Geral Extraordinária convocada para esse fim;</br>
					<strong>§2º -</strong> Vencendo a sua suspensão, o membro voltará a ter seus direitos, devendo ser transcrito em ata e aprovada pela maioria dos presentes através de uma Assembleia Geral Extraordinária convocada para esse fim.</p>";
        $a++;
        $cap++;
        $capR = numberIntegerToRoman($cap, true);
        $assG1 = "

				</br>
				<h2><strong>CAPÍTULO $capR</br> DAS ASSEMBLÉIAS</strong></h2>
				</br>

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A Assembléia Geral é constituída pelos membros da IGREJA.</p>";

        $a++;

        $assG2 = "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> As Assembléias podem ser Ordinárias ou Extraordinárias, sempre lideradas pelo Presidente.</br></br>
					<strong>§1º -</strong> As Assembléias Ordinárias serão constituídas pelos membros da diretoria da IGREJA e Liderança;</br>
					<strong>§2º -</strong> As Assembléias Extraordinárias serão constituídas pelos membros da IGREJA;</p>";

        $a++;

        $assG3 = "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A Assembléia Geral Extraordinária se reunirá para tratar de assuntos urgentes e apreciar exclusivamente os casos que motivarem a convocação especial e será realizada a qualquer tempo e hora para resolver os casos surgidos:</br></br>
					I - Eleger um substituto em caso de vacância de membros da Diretoria, caso necessário;</br>
					II - Alterar o estatuto parcial ou totalmente;</br>
					III - Elaborar programa de atividades e executá-lo;</br>
					IV - Resolver os casos omissos neste estatuto;</br>
					V - Destituir a diretoria e/ou comissão de contas, conselhos deliberativos;</br>
					VI - Dissolver a IGREJA.</p>";

        $a++;

        $assG4 = "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Qualquer Assembléia instalar-se-á, em primeira convocação, com mais de $ass_geral1 em comunhão e mais de $ass_geral2 nas convocações seguintes.</br></br>

					<strong>§1° –</strong> A Assembléia Geral Ordinária se reunirá nos dois primeiros meses de cada ano, com a finalidade de aprovar as contas da Diretoria e planejar as atividades do ano vigente.</br>

					<strong>§2° –</strong> As Assembléias Gerais Extraordinárias serão convocadas através de edital de convocação, com antecedência mínima de sete dias, em papel timbrado, devendo ser fixado em lugar visível, contendo local, hora, dia, mês, ano e a ordem do dia a ser tratada.</p>";
        $assG = $assG1 . $assG2 . $assG3 . $assG4;

        echo $supensoes;

        $a++;
        $cap++;
        $capR = numberIntegerToRoman($cap, true);

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

        echo $assG;


        $a++;
        $cap++;
        $capR = numberIntegerToRoman($cap, true);

        echo "
				</br>
				<h2><strong>CAPÍTULO $capR</br>DO MINISTÉRIO ECLESIÁSTICO</strong></h2>
				</br>

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> O governo eclesiástico da IGREJA se fará pela forma $governo, compreendendo sua liderança eclesiástica, a saber:</br></br>
					I - Pastor Presidente, tendo este pleno poder na direção da IGREJA;</br>
					II - Pastores Auxiliares, $cargos_ministeriais para auxiliá-lo na direção das igrejas e congregações filiais e apoio na Sede.</br></br>

					<strong>§ 1° —</strong> As reuniões da liderança eclesiástica da IGREJA são denominadas de reuniões de Liderança, sendo este constituído pelo Pastor Presidente e todos os demais Pastores Auxiliares, $cargos_ministeriais em exercício, estando todos obrigados a comparecer. </br>

					<strong>§ 2° —</strong> As reuniões de Liderança serão ordinárias, com datas pré-marcadas e Extraordinárias, convocadas para resolução de situações que não possam aguardar as datas ordinárias previstas para a reunião de Liderança.</br>

					<strong>§ 3° -</strong> Pode o Pastor Presidente convocar reuniões para buscar conselho e opiniões com todos os Pastores Auxiliares e Liderança, ou apenas parte destes, sendo que a decisão quanto aos assuntos levantados será tomada sempre pelo Pastor Presidente (Bíblia Sagrada, Livro dos Atos dos Apóstolos, capítulo 15, versos 12 ao 20).</br>

					<strong>§ 4° -</strong> A liderança do Pastor Presidente é por tempo indeterminado. </br>

					<strong>§ 5° -</strong> Na ausência ou impedimento permanente do Pastor Presidente, caso seu sucessor não seja por ele anunciado publicamente na Igreja Sede, o <span style='color:red !important;'>Vice Presidente / Tesoureiro</span> assume o cargo de maneira direta. </br>

					<strong>§ 6° -</strong> Deixam de ser Pastores Auxiliares, $cargos_ministeriais da IGREJA: </br></br>
					a) Aqueles que se enquadrarem em quaisquer características mencionadas no <span style='color:red !important;'>artigo 7</span>, sob decisão exclusiva do Pastor Presidente;</br>
					b) Aqueles que não andarem em unidade ministerial, em acordo com a IGREJA, livro-base de seu Regimento Interno.</p> ";

        $a++;
        $cap++;
        $capR = numberIntegerToRoman($cap, true);

        echo "

				</br><h2><strong>CAPÍTULO $capR</br>OFÍCIOS ECLESIÁSTICOS</strong></h2>
				</br>

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Na IGREJA, os ofícios eclesiásticos adotados são o do Pastor, $cargos_ministeriais</p> ";

        $a++;

        echo "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Só poderão ser ordenados como Pastores e qualquer outro ofício eclesiástico ou denominacional aqueles que forem preparados pela Liderança da IGREJA.</p> ";

        $a++;

        echo "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Os $cargos_ministeriais trabalharão em serviços de organização do culto, assistência social e apoio ao trabalho Pastoral. Todos deverão participar do Curso de Formação de Líderes e dos cursos anuais de reciclagem, sendo a ausência nestes considerado motivo suficiente para a não renovação na função.</br>

				<strong>Parágrafo Único -</strong> Os $cargos_ministeriais serão levantados dentre os membros que mais se destacaram em seu serviço ministerial.</p>";

        $a++;

        // REMUNERAÇÕES MINISTERIAIS ----------------------- REMUNERAÇÕES MINISTERIAIS ----------------------- REMUNERAÇÕES MINISTERIAIS
        switch ($data['remuneracao'] ?? 1) {

            case "1":

                $remuneracao = "<p align=justify style='color:red !important;'><strong>Art. $a" . 'º' . " -</strong> Todos os pastores receberão ajuda de custo por seu ofício, porém isso não representará ou gerará vínculo empregatício, quer com a Denominação, quer com a IGREJA ou mesmo com a Igreja local onde for enviado, servindo nas Igrejas deste Ministério onde for enviado por espontânea doação do seu trabalho voluntário à Causa do Senhor Jesus Cristo.</br></br>
			<strong>Parágrafo Único: </strong> A ajuda de custo deverá ser oficializada através de um documento específico para este fim e todas as contribuições e recolhimentos ficam sob a responsabilidade do beneficiário, isentando a IGREJA de toda e qualquer responsabilidade.</p>";
                break;

            case "2":

                $remuneracao = "<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> Todos os pastores recebrão remuneração por seu ofício, desde que se dediquem de maneira integral a IGREJA e seus projetos. Essa remuneração será oficializada através de registro em carteira e todas as contribuições serão de responsabilidade da IGREJA.</p>";
                break;

            case "3":

                $remuneracao = "<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> Nenhuma nomeação ministerial, seja como Pastor Auxiliar, $cargos_ministeriais, representará ou gerará vínculo empregatício, quer com a Denominação, quer com a IGREJA ou mesmo com a Igreja local onde for enviado, servindo nas Igrejas deste Ministério onde for enviado por espontânea doação do seu trabalho voluntário à Causa do Senhor Jesus Cristo. </p>";
                break;
        }
        echo "$remuneracao";

        $a++;

        echo "
				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Toda nomeação ou ordenação só se dará após avaliação e aprovação do Pastor Presidente.</p>";

        $a++;

        echo "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Os membros da Liderança, a princípio, darão sua contribuição gratuitamente, sem exigir qualquer remuneração. </p></br>";

        $a++;
        $cap++;
        $capR = numberIntegerToRoman($cap, true);

        echo "

				</br>
				<h2><strong>CAPÍTULO $capR</br>SOBRE A DENOMINAÇÃO</strong></h2>
				</br>

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA, poderá alterar sua Razão Social ou Nome Fantasia a qualquer momento, mediante a convocação e aprovação em uma Assembleia Geral Extraordinária.</p>";

        $a++;
        $cap++;
        $capR = numberIntegerToRoman($cap, true);

        echo "

				</br>
				<h2><strong>CAPÍTULO $capR</br>DA ADMINISTRAÇÃO</strong></h2>
				</br>

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A Diretoria, como pessoa jurídica, exercerá sua função com responsabilidade e poderes definitivos por este ato constitutivo;</p>";

        $a++;

        echo "

				<p align=justify >
				    <strong>Art. $a" . 'º' . " -</strong> A IGREJA, para ser mantida de modo eficiente e de acordo com a providência e a vontade de Deus, terá uma Diretoria
				    composta dos seguintes membros, a saber: Presidente, $diretoria_vice, $diretoria_tesouraria, $diretoria_secretaria.
				    </br></br>

                    <strong>Parágrafo único -</strong> $mandato_presidente O restante dos membros da diretoria terão o mandato de $m_diretoria, podendo ser reeleitos de forma
                    isolada ou conjunta.
				</p>";


        // MANDATO PRESIDENTE ---------------------------------- MANDATO PRESIDENTE ----------------------------- MANDATO PRESIDENTE
        switch ($data['m_presidente'] ?? 200) {


            case "200":
                $a++;
                $mandato_presidente2 = "<p align=justify ><strong>Art. $a" . 'º' . " -</strong> O fundador da IGREJA, $nome_presidente, será Presidente da IGREJA por tempo indeterminado, enquanto o mesmo estiver cumprindo os preceitos das Sagradas Escrituras e a Sã Doutrina de Nosso Senhor Jesus Cristo.</p>";
                break;

            default:

                $mandato_presidente = "<span style='color:red !important;'>O mandato do Presidente será de $m_presidente.</span>";
                $mandato_presidente2 = "";
                break;
        }

        echo $mandato_presidente2;

        $a++;

        echo "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong>  Ao Presidente compete:</br>
					I - Representar a IGREJA, ativa, passiva, judicial e, extrajudicialmente, em juízo ou fora dele;</br>
					II - Convocar e presidir assembléias gerais ordinária, extraordinárias e as reuniões do ministério;</br>
					III - Cumprir e fazer cumprir todos artigos, parágrafos e incisos deste estatuto e do regimento interno;</br>
					IV - Supervisionar os movimentos dos demais membros da diretoria;</br>
					V - Admitir e demitir funcionários e empregados;</br>
					VI - Ordenar pastores adjuntos;</br>
					<span style='color:red !important;'>$cabe_presidente</span> </p>
					<p align=justify style='color:MediumBlue !important;'><strong> VERIFICAR OS NÚMEROS DOS INCISOS ACIMA!!!!</strong></p>";


        // VERIFICA SE TEM VICE-PRESIDENTE  ------------------ VERIFICA SE TEM VICE-PRESIDENTE -------------------- VERIFICA SE TEM VICE-PRESIDENTE
        switch ($array['diretoria']['vice'] ?? 0) {

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
        switch ($array['diretoria']['tesouraria'] ?? 2) {

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

        switch ($array['diretoria']['secretaria'] ?? 0) {

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

        switch ($array['conselho'] ?? 1) {

            case "1":

                $cap++;
                $a++;
                $capR = numberIntegerToRoman($cap, true);
                $conselho1 = "

	<h2><strong>CAPÍTULO $capR</br>DO CONSELHO FISCAL</strong></h2>

	<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> O Conselho Fiscal, que será composto por três membros, e tem por objetivo, indelegável, fiscalizar e dar parecer sobre todos os atos da Diretoria da IGREJA, com as seguintes atribuições;</br>

		I.  Examinar os livros de escrituração da IGREJA;</br>

		II.  Opinar e dar pareceres sobre balanços e relatórios financeiro e contábil, submetendo-os a Assembléia Geral Ordinária ou Extraordinária;</br>

		III.  Requisitar ao Tesoureiro, a qualquer tempo, a documentação comprobatória das operações econômico-financeiras realizadas pela IGREJA;</br>

		IV.  Acompanhar o trabalho de eventuais auditores externos independentes;</br>

		V.  Convocar Extraordinariamente a Assembléia Geral.</br> </br>

	<strong>Parágrafo único -</strong> O Conselho Fiscal reunir-se-á ordinariamente, uma vez por ano, na segunda quinzena de janeiro, em sua maioria absoluta, e extraordinariamente, sempre que convocado  pelo Presidente da IGREJA, ou pela maioria simples de seus membros.</p> ";

                $a++;
                $conselho2 = "
	<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> As eleições para a Diretoria e Conselho Fiscal realizar-se-ão, conjuntamente, a cada $m_diretoria, por chapa completa de candidatos apresentada à Assembléia Geral, podendo seus membros ser  reeleitos.</p>";

                $a++;
                $conselho3 = "
	<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> A perda da qualidade de membro do Conselho Fiscal, será determinada pela Assembléia Geral, sendo admissível somente havendo justa causa, assim reconhecida em procedimento disciplinar, quando ficar comprovado:</br>

		I.  Malversação ou dilapidação do patrimônio social;</br>

		II.  Grave violação deste estatuto;</br>

		III.  Abandono do cargo, assim considerada a ausência não justificada em 03 (três) reuniões ordinárias consecutivas, sem expressa comunicação dos motivos da ausência, à secretaria da IGREJA;</br>

		IV.  Aceitação de cargo ou função incompatível com o exercício do cargo que exerce na IGREJA;</br>

		V.  Conduta duvidosa.</br> </br>

			<strong>§1° –</strong> Definida a justa causa, o diretor ou conselheiro será comunicado, através de notificação extrajudicial, dos fatos a ele imputados,  para que apresente sua defesa prévia à Diretoria, no prazo de 20 (vinte) dias, contados do recebimento da comunicação;</br>

			<strong>§2° –</strong> Após o decurso do prazo descrito no parágrafo anterior, independentemente da apresentação de defesa, a representação será submetida à Assembléia Geral Extraordinária, devidamente convocada para esse fim, composta dos membros efetivos da IGREJA, não podendo ela deliberar sem voto concorde de 2/3 (dois terços) dos presentes, sendo em primeira chamada, com a maioria absoluta dos membros e em segunda chamada, uma hora após a primeira, com qualquer número de membros,  onde será garantido o amplo direito de defesa.</p> ";


                $a++;
                $conselho4 = "
	<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> Em caso renúncia de qualquer membro do Conselho Fiscal, o cargo será preenchido pelos suplentes.</br> </br>

			<strong>§1° -</strong> O pedido de renúncia se dará por escrito, devendo ser protocolado na secretaria da IGREJA, a qual, no prazo máximo de 60 (sessenta) dias, contado  da data do protocolo, o submeterá à deliberação da Assembléia Geral;</br>

			<strong>§2° -</strong> Ocorrendo renúncia coletiva do Conselho Fiscal, o Presidente renunciante, qualquer membro da Diretoria poderá convocar a Assembléia Geral Extraordinária, que elegerá uma comissão provisória composta por 05 (cinco) membros, que administrará a entidade e fará realizar novas eleições, no prazo máximo de 60 (sessenta) dias, contados da data de realização da referida assembléia. Os diretores e conselheiros eleitos, nestas condições, complementarão o mandato dos renunciantes.</p>";

                $a++;
                $conselho5 = "
	<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> Os membros do Conselho Fiscal não perceberão nenhum tipo de remuneração, de qualquer espécie ou natureza, pelas atividades exercidas na IGREJA.</p> ";

                $conselho = $conselho1 . $conselho2 . $conselho3 . $conselho4 . $conselho5;
                break;

            case "0":
                $conselho = "";
                break;
        }
        echo $conselho;


        $a++;
        $cap++;
        $capR = numberIntegerToRoman($cap, true);

        // VACANCIA PRESIDENTE  -------------------------- VACANCIA PRESIDENTE ------------------------- VACANCIA PRESIDENTE
        switch ($array['vacanciap'] ?? 1) {

            case "1":
                $vacanciap = "
				<h2><strong>CAPÍTULO $capR</br>DA PERDA DE MANDATO</strong></h2>
				</br>

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong>  Em caso de vacância do cargo de presidência, o cargo de Presidente será ocupado pelo <span style='color:red !important;' >$diretoria_vice / Tesoureiro.</span></br></br>

					<strong>§1º -</strong> A perda do mandato será declarada através de uma assembléia geral extraordinária, convocada para este fim, depois de uma junta de pastores do mesmo ministério tiver julgado o acusado, cabendo-lhe pleno direito de exercer suas defesas;</br>
					<strong>§2º -</strong> O substituto para  o cargo vago será eleito e empossado com aprovação da maioria dos presentes, que cumprirá o seu mandato pelo tempo de mandato restante.</p>";
                break;

            case "2":
                $vacanciap = "
				<h2><strong>CAPÍTULO $capR</br>DA PERDA DE MANDATO</strong></h2>
				</br>

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong>  Em caso de vacância do cargo de presidência, o novo Presidente será eleito e empossado através de uma Assembléia Geral Extraordinária, convocada para este fim com edital, no prazo mínimo de 30 (trinta) dias corridos.</br></br>

					<strong>§1º -</strong> A perda do mandato será declarada através de uma assembléia geral extraordinária, convocada para este fim, depois de uma junta de pastores do mesmo ministério tiver julgado o acusado, cabendo-lhe pleno direito de exercer suas defesas;</br>
					<strong>§2º -</strong> O novo presidente será eleito e empossado com aprovação da maioria dos presentes, que cumprirá o seu mandato por tempo indeterminado.</p>";
                break;
        }
        echo $vacanciap;

        $a++;

        // VACANCIA DIRETORIA ----------------------------- VACANCIA DIRETORIA ------------------------------ VACANCIA DIRETORIA
        $vacanciad = "<p align=justify style='color:red !important;' ><strong>Art. $a" . 'º' . " -</strong> No caso de vacância dos demais cargos da diretoria, caberá ao presidente convocar uma Assembleia Geral Extraordinária para eleger  um substituto ao cargo vago através de voto secreto e com a maioria simples dos votos.</p>";

        echo $vacanciad;;

        $a++;
        $cap++;
        $capR = numberIntegerToRoman($cap, true);
        echo "

				</br>
				<h2><strong>CAPÍTULO $capR</br>DOS BENS</strong></h2>
				</br>

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Os bens da IGREJA serão administrados pela respectiva diretoria, cujo Presidente assinará os documentos oficiais de entidade, bem como cheques, procurações, títulos e contratos em geral, escritura pública, vendas e aquisição bancária, ou qualquer outra instituição financeira.</p>";

        $a++;
        $cap++;
        $capR = numberIntegerToRoman($cap, true);
        echo "

				</br>
				<h2><strong>CAPÍTULO $capR</br>DO PATRIMÔNIO</strong></h2>
				</br>

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA terá por patrimônio quaisquer bens imóveis, móveis e utensílios, veículos e semoventes, que possua ou venha a possuir, os quais serão escriturados em nome da IGREJA, e só poderão ser vendidos ou alienados com aprovação da maioria dos membros efetivos da entidade, através de uma assembléia geral extraordinária, convocada para este fim.</p>";

        $a++;
        $cap++;
        $capR = numberIntegerToRoman($cap, true);
        echo "

				</br>
				<h2><strong>CAPÍTULO $capR</br>DA CONVOCAÇÃO ESPECIAL</strong></h2>
				</br>

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Com o propósito de defender os interesses da IGREJA, se fará uma convocação especial em uma assembléia geral extraordinária, com todos os membros da diretoria e do ministério efetivos, a fim de tomarem ciência da deliberação doutrinárias para o bom crescimento e divulgação do evangelho. <br/>

				<strong>Parágrafo Único -</strong> Para tal convocação, o Presidente deverá convocar uma reunião, com no mínimo 15 dias de antecedência, através de edital fixado no quadro de avisos da IGREJA.
				</p>";

        $a++;
        $cap++;
        $capR = numberIntegerToRoman($cap, true);
        echo "

				</br>
				<h2><strong>CAPÍTULO $capR</br>DAS ORDENAÇÕES</strong></h2>
				</br>

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Cabe ao pastor titular da IGREJA consagrar e ordenar ministros do evangelho (pastores), diáconos e auxiliares para o desempenho de funções eclesiásticas e de ordem para IGREJA, concedendo-lhes credenciais conforme suas funções.</p>";

        $a++;
        echo "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA reserva-se no direito de caçar a credencial expedida ao ministro ordenado, ao diácono, ao auxiliar, a qualquer tempo que não permanecer fiel a doutrina por ela determinada, no cumprimento da boa ordem da fraternidade cristã e aos costumes previstos na Palavra de Deus.</p> ";

        $a++;
        echo "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A qualquer ministro de confissão religiosa, como pastores,  presbíteros, diáconos ou os que tiverem na escala de serem separados para o ministério eclesiástico, como também os dirigentes nomeados para dirigir as filiais, com função de desempenhar a pregação do Evangelho, a Santa Ceia, Batismo nas águas, realizar cerimônias fúnebres e de casamento desta IGREJA, não implica o reconhecimento de relação de emprego nem de vínculo empregatício de trabalho assalariado ou prestação de serviço remunerado, uma vez que a entidade não tem fins lucrativos e nem assume o risco de atividade econômica, não se podendo também falar em perda de danos morais, por estar dentro de sua espontânea vocação e convicção religiosa, mesmo que mantido pela instituição. </p>";

        $a++;
        $cap++;
        $capR = numberIntegerToRoman($cap, true);
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

        $a++;
        echo "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> As filiais abertas e as que se unirem serão vinculadas a IGREJA matriz, através de uma Assembléia Geral Extraordinária, convocada para este fim e com força estatutária.</p>";

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

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A filial poderá ser emancipada legalmente através de uma Assembléia Geral Extraordinária convocada para este fim, presidida pelo Presidente da IGREJA matriz e, neste caso poderá haver alienação dos bens patrimoniais em favor da filial emancipada, constando os referidos atos em ata da Assembléia que outorgou a emancipação. </p>";

        $a++;
        echo "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> As filiais que receberem sua emancipação deverão elaborar seu estatuto, aprovado previamente pela IGREJA que concedeu sua emancipação. </p>";

        $a++;
        $cap++;
        $capR = numberIntegerToRoman($cap, true);
        echo "

				</br>
				<h2><strong>CAPÍTULO $capR</br>DISPOSIÇÕES GERAIS</strong></h2>
				</br>

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA, como pessoa jurídica, responderá com os seus bens pelas obrigações por ela contraídas e não aos membros, individual ou subsidiariamente, com os seus bens particulares. </p>";

        $a++;
        echo "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA não se responsabilizará por dívidas contraídas por terceiros, sem que haja para isso, uma prévia autorização por escrito assinada pelo tesoureiro, sendo nula com assinatura singular, não produzindo qualquer efeito de responsabilidade da entidade.</p> ";

        $a++;
        echo "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Este estatuto só poderá ser reformado parcial ou totalmente, em casos especiais que a lei determine, ou por aprovação da maioria de votos de seus membros efetivos em comunhão quando se fizer necessário, através de uma Assembléia Geral Extraordinária, convocada para esse fim com aprovação de 2/3 na primeira convocação e mais de 1/3 nas convocações seguintes. Assim como a destituição dos membros da diretoria.</p>";

        $a++;
        echo "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> A IGREJA poderá ser extinta quando for impossível sua continuidade por decisão $dissolucao, ou por sentença judicial transitada em Julgado. <br/>

				<strong>§1° -</strong> Em caso de extinção, o Presidente deve convocar uma reunião, com no mínimo 15 dias de antecedência, através de edital fixado no quadro de avisos da IGREJA, para deliberação da extinção.</br>
				<strong>§2° -</strong> Caso os membros da IGREJA discordem da decisão de extinção por parte do presidente, os mesmos poderão solicitar a continuidade das atividades, mediante a aprovação de ⅔ dos membros ativos da IGREJA.</br>
				<strong>§3° -</strong> Caso os membros decidam pela continuidade das atividades, realizar-se-á novas eleições para os cargos vagos, sendo eleitos aqueles que receberem a maioria simples dos votos.</br>
				<strong>§4° -</strong> Após a decisão pela continuidade das atividades e eleição da nova diretoria, a IGREJA continuará sendo regida por este estatuto. Caso necessário, os membros ativos da IGREJA podem propor e realizar uma reforma estatutária.</p>";

        $a++;
        echo "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Em caso de dissolução, depois de pagos todos os compromissos, os bens e valores da IGREJA se reverterão em benefício de outra congênere ou a Assembléia Geral Extraordinária decidirá quanto ao destino de seus bens, depois de resolvidos todos os compromissos. </p>";

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

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Os casos omissos deste estatuto serão resolvidos em uma Assembléia Geral Extraordinária, os quais, depois de resolvidos e concluídos, serão transcritos em ata para que tenham força estatutária.</p>";

        $a++;
        echo "

				<p align=justify ><strong>Art. $a" . 'º' . " -</strong> Este estatuto passará a vigorar depois de registrado em cartório competente, revogando-se as disposições em contrário.</p>



				<p>$cidade_igreja, $data_fundacao. $data $date</p>



				<p>	_________________________________________________</p>
					<p>$nome_presidente</p>
					<p>PRESIDENTE</p>

				<p>__________________________________________________</p>
					<p>ADVOGADO</p>";


        ?>

    </div>
</div>


</div>
