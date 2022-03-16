<?php
setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');

if (!isset($_POST['data'])) {
    return "";
}
$data = $_POST['data'];

$texto = "";
// presidente
if (isset($data['diretoria']['presidente'])) {
    $presidente = "
	<p>
	<strong>Presidente:</strong> {$data['diretoria']['presidente']['nome']}, brasileiro(a), natural de
	{$data['diretoria']['presidente']['naturalidade']}, nascido(a) em {$data['diretoria']['presidente']['dt_nascimento']},
	{$data['diretoria']['presidente']['estado_civil']}, {$data['diretoria']['presidente']['profissao']}, portador(a) do RG de n°:
	{$data['diretoria']['presidente']['rg']} expedido pelo {$data['diretoria']['presidente']['exp_rg']}, e inscrito(a) no CPF de n°
	{$data['diretoria']['presidente']['cpf']}, residente e domiciliado(a) na {$data['diretoria']['presidente']['endereco']}
	</p>
	</br>";
} else {
    $presidente = "PRESIDENTE NÃO CADASTRADO";
}


// vice-presidente
if (isset($data['diretoria']['vice_presidente'])) {
    $vice_presidente = "
	<p>
	<strong>Vice Presidente:</strong> {$data['diretoria']['vice_presidente']['nome']}, brasileiro(a), natural de
	{$data['diretoria']['vice_presidente']['naturalidade']}, nascido(a) em {$data['diretoria']['vice_presidente']['dt_nascimento']},
	{$data['diretoria']['vice_presidente']['estado_civil']}, {$data['diretoria']['vice_presidente']['profissao']},
	portador(a) do RG de n°: {$data['diretoria']['vice_presidente']['rg']} expedido pelo {$data['diretoria']['vice_presidente']['exp_rg']},
	e inscrito(a) no CPF de n° {$data['diretoria']['vice_presidente']['cpf']}, residente e domiciliado(a) na {$data['diretoria']['vice_presidente']['endereco']}
	</p>
	</br>";
} else {
    $vice_presidente = "VICE PRESIDENTE NÃO CADASTRADO";
}

// tesoureiros
$tesoureiros = [];
if (isset($data['diretoria']['tesoureiros'])) {
    foreach ($data['diretoria']['tesoureiros'] as $key => $val) {
        $x = $key + 1;
        $h = "
		<p>
		<strong>{$x}º Tesoureiro:</strong> {$val['nome']}, brasileiro(a), natural de
		{$val['naturalidade']}, nascido(a) em {$val['dt_nascimento']},
		{$val['estado_civil']}, {$val['profissao']}, portador(a) do RG de n°:
		{$val['rg']} expedido pelo {$val['exp_rg']}, e inscrito(a) no CPF de n°
		{$val['cpf']}, residente e domiciliado(a) na {$val['endereco']}
		</p>
		</br>";
        array_push($tesoureiros, $h);
    }
}


// secretários
$secretarios = [];
if (isset($data['diretoria']['secretarios'])) {
    foreach ($data['diretoria']['secretarios'] as $key => $val) {
        $x = $key + 1;
        $h = "
		<p>
		<strong>{$x}º Secretário:</strong> {$val['nome']}, brasileiro(a), natural de
		{$val['naturalidade']}, nascido(a) em {$val['dt_nascimento']},
		{$val['estado_civil']}, {$val['profissao']}, portador(a) do RG de n°:
		{$val['rg']} expedido pelo {$val['exp_rg']}, e inscrito(a) no CPF de n°
		{$val['cpf']}, residente e domiciliado(a) na {$val['endereco']}
		</p>
		</br>";
        array_push($secretarios, $h);
    }
}

$vice = $data['diretoria']['vice_presidente']['nome'] ?? 'VICE PRESIDENTE';
$tes = $data['diretoria']['tesoureiros'][0]['nome'] ?? 'TESOUREIRO';
$texto .= "<div align=justify  style='background-color:#FFFFFF;  padding: 25px 50px 25px 50px;'>";
$texto .= "<h2>ATA DE FUNDAÇÃO DA {$data['igreja']['nome']}</h2>";
$texto .= "<p>Ata da reunião, realizada no dia <span style='color:blue !important;'>{$data['post']['fundacao']} </span>as 19h, na {$data['igreja']['endereco']}.
Estando presente os membros fundadores, foi presidida esta reunião pelo Pastor {$data['diretoria']['presidente']['nome']}. A seguir, o Presidente
nomeou para secretário da reunião o(a) Sr(a). <span style='color:blue !important;'> {$vice} ,
{$tes} </span> e declarou instalada esta reunião e aberta a sessão, às dezenove horas do dia
<span style='color:blue !important;'>{$data['post']['fundacao']} </span>, informando que a presente convocação tem como finalidade a fundação da
{$data['igreja']['nome']}, a aprovação do Estatuto, a aprovação do endereço da sede e a ordenação da Diretoria através da indicação do Presidente
conforme estabelece o Estatuto, o qual depois de lido foi aprovado por unanimidade. Os candidatos, para o mandato de
<span style='color:blue !important;'>00/0000 a 00/0000</span>, foram escolhidos nos cargos assim qualificados:</p>";


$texto .= $presidente;

$texto .= $vice_presidente;

if (count($tesoureiros)) {
    foreach ($tesoureiros as $t) {
        $texto .= $t;
    }
} else {
    $texto .= "NÃO HÁ TESOUREIROS";
}

if (count($secretarios)) {
    foreach ($secretarios as $s) {
        $texto .= $s;
    }
} else {
    $texto .= "NÃO HÁ SECRETÁRIOS";
}

$texto .= "
<p>
Após a leitura, discussão e aprovação do estatuto e aprovação do endereço da sede, localizada na {$data['igreja']['endereco']}, o Presidente
deu posse à Diretoria eleita. Após o Ato Solene, o Presidente interrompeu a sessão por algum tempo, a fim que fosse lavrada a ata, que depois
de lida foi aprovada por unanimidade. Nada mais havendo a tratar, eu, secretário(a), lavrei a presente ata e assino com o Presidente.
</p>";

$texto .= "<p>{$data['igreja']['cidade']}, <span style='color:blue !important;'>{$data['post']['fundacao']}</span>.</p>";


$texto .= "<p>	_________________________________________________</p>";
$texto .= "<p>{$data['diretoria']['presidente']['nome']}</p>";
$texto .= "<p>PRESIDENTE</p>";

$texto .= "<p>__________________________________________________</p>";
$texto .= "<p>{$data['diretoria']['vice_presidente']['nome']}</p>";
$texto .= "<p>VICE PRESIDENTE</p>";


if (count($data['diretoria']['tesoureiros'])) {
    foreach ($data['diretoria']['tesoureiros'] as $k => $t) {
        $i = $k + 1;
        $texto .= "<p>	_________________________________________________</p>";
        $texto .= "<p>{$t['nome']}</p>";
        $texto .= "<p>{$i}º TESOUREIRO</p>";
    }
}

if (count($data['diretoria']['secretarios'])) {
    foreach ($data['diretoria']['secretarios'] as $k => $s) {
        $i = $k + 1;
        $texto .= "<p>	_________________________________________________</p>";
        $texto .= "<p>{$s['nome']}</p>";
        $texto .= "<p>{$i}º SECRETÁRIO</p>";
    }
}


$texto .= "</div>";
echo $texto;

?>
