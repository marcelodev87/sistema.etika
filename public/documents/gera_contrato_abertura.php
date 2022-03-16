<?php
	session_start();
	include_once("seguranca.php");
	include_once("conexao.php");

	include "topo.php";
// sidebar menu -->
	include "sidebar.php";
//-- /sidebar menu -->
setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');

$id_empresa = $_POST['id_empresa'];
$id_servico = $_POST['servico'];



// BUSCA SERVIÇO --------------------------------- BUSCA SERVIÇO  ------------------------------------ BUSCA SERVIÇO

switch($id_servico){

		case "2":


			$clausula_primeira = "<p><strong>Cláusula Primeira:</strong> O objeto do presente consiste na prestação de Serviços Contábeis pela Contratada à Contratante abaixo discriminados, excetuando-se:</p>

				<p><strong>a)</strong> Elaboração de Estatuto Social e Ata de Fundação de uma Organização Religiosa (Igreja Evangélica) de acordo com informações fornecidas pelo CONTRATANTE. </p>

				<p><strong>b)</strong> Elaboração de pedido de CNPJ.</p>";

				$protocolo = "<p><strong>§ 4º -</strong> A CONTRATADA não se compromete em protocolar os pedidos de registro nos órgãos pertinentes, pois essa será uma obrigação do CONTRATANTE.</p>";
				break;

			case "1":
				$clausula_primeira = "<p><strong>Cláusula Primeira:</strong> O objeto do presente consiste na Abertura de uma Igreja, que inclui:</p>

				<p><strong>a)</strong> Elaboração e registro de Estatuto Social e Ata de Fundação de uma Organização Religiosa (Igreja Evangélica) de acordo com informações fornecidas pelo CONTRATANTE. </p>

				<p><strong>b)</strong> Elaboração e protocolo de pedido de CNPJ.</p>";

				$protocolo = "<p><strong>§ 4º -</strong> A CONTRATADA se compromete em protocolar os pedidos de registro nos órgãos pertinentes em até 7 dias úteis após tomar posse dos documentos necessários para tal.</p>";
				break;
			}

$query = mysqli_query($conectar, "SELECT a.*, b.* FROM empresa a, servicos b WHERE a.id_empresa = '".$id_empresa."' AND b.id_servico = '".$id_servico."'");

		while($linha = mysqli_fetch_array($query)) {
			$igreja = $linha['nome_empresa']; $cidade_igreja = $linha['cidade'];
			$servico = $linha['descricao']; $valor = $linha['valor']; $valor_extenso = $linha['valor_extenso'];


?>
        <!-- page content -->
        <div class="right_col" role="main">
		<h1> Geração de Documentos </h1>
		<h2> Contrato de Abertura <?php echo $igreja;} ?></h2></br>

	<div class="form-group">
		<?php

		// FUNÇÃO PARA FORMATAR CPF
		$pattern = '/^([[:digit:]]{3})([[:digit:]]{3})([[:digit:]]{3})([[:digit:]]{2})$/';
		$replacement = '$1.$2.$3-$4';

		// FUNÇÃO PARA FORMATAR CEP
		$pattern_cep = '/^([[:digit:]]{5})([[:digit:]]{3})$/';
		$replacement_cep = '$1-$2';

		$data = date('d/m/Y');


				// BUSCA PRESIDENTE
				$sql_presidente = "SELECT * FROM diretoria WHERE id_empresa = '".$id_empresa."' AND cargo = '1' ORDER BY id_membro DESC LIMIT 1";
				$result_presidente = $conectar->query($sql_presidente);
				$num_rows_presidente = mysqli_num_rows($result_presidente);
				// VERIFICA SE HOUVE RESULTADO NA BUSCA  - SE VAZIO, DEIXA EM BRANCO
				if($num_rows_presidente < 1){
					$texto1 = "<h2>NÃO TEM PRESIDENTE CADASTRADO</h2>";
					$nome_presidente = "Nome Presidente";
				   }else{

				while($linha_presidente = $result_presidente->fetch_assoc()) {

					$nome_presidente = $linha_presidente['nome']; $naturalidade_presidente = $linha_presidente['naturalidade'];
					$data_nas_presidente = $linha_presidente['data_nasc']; $data_nas_presidente=banco2data($data_nas_presidente);
					$estado_civil_presidente = $linha_presidente['estado_civil']; $profissao_presidente = $linha_presidente['profissao']; $rg_presidente = $linha_presidente['rg']; $exp_rg_presidente = $linha_presidente['orgao_expeditor'];
					$CPF_presidente = $linha_presidente['cpf']; $endereco_presidente = $linha_presidente['endereco']; $complemento_presidente = $linha_presidente['complemento']; $bairro_presidente = $linha_presidente['bairro'];	$cidade_presidente = $linha_presidente['cidade'];$UF_presidente = $linha_presidente['uf'];
					$CEP_presidente = $linha_presidente['cep'];

					$texto1 = "<strong>$nome_presidente</strong>, natural de $naturalidade_presidente, nascido em $data_nas_presidente, $estado_civil_presidente, $profissao_presidente, portador do RG de n°: $rg_presidente expedido pelo $exp_rg_presidente, e inscrito no CPF de n° ".preg_replace($pattern, $replacement, $CPF_presidente).", residente e domiciliado na $endereco_presidente – $complemento_presidente – $bairro_presidente – $cidade_presidente - $UF_presidente – CEP: ".preg_replace($pattern_cep, $replacement_cep, $CEP_presidente)." - Representante legal da $igreja.";
				   }	}

				$texto = "<div align=justify  style='background-color:#FFFFFF;  padding: 25px 50px 25px 50px;'>

				<h2>PROPOSTA DE PRESTAÇÃO DE SERVIÇOS</h2></br>

				<p><strong>CONTRATANTE:</strong> $texto1 </p>

				<p><strong>CONTRATADO:</strong> Empresa Contábil <strong>ÉTIKA SOLUÇÕES CONSULTORIA CONTÁBIL S/S LTDA</strong>, pessoa jurídica de direito privado, com sede na Av. Benjamin Pinto Dias, 1130 - Sala 106 - Centro - Belford Roxo - RJ, inscrita no CNPJ sob o nº: 11.490.162/0001-71.</p>

				<p>Pelo presente instrumento particular, as partes acima devidamente qualificadas, doravante denominadas simplesmente CONTRATANTE e CONTRATADA, na melhor forma de direito, ajustam e contratam a prestação de serviços contábeis, segundo as cláusulas e condições adiante arroladas:</p>

				</br>
				<h2>DO OBJETO</h2>
				</br>

				$clausula_primeira

				</br>
				<h2>DAS CONDIÇÕES DE EXECUÇÃO DOS SERVIÇOS</h2>
				</br>

				<p><strong>Cláusula Segunda:</strong> Os serviços serão executados nas dependências da CONTRATADA.</p>

				<p><strong>1.1 –</strong> A documentação indispensável para o desenvolvimento dos serviços arrolados na cláusula primeira será fornecida pela CONTRATANTE consistindo basicamente em: </p>
				<p><strong>1.1.1 –</strong> Regras gerais de funcionamento da Organização:</p>

				<p>- a denominação, os fins, a sede e o tempo de duração da Organização;</p>
				<p>- o modo de administração e representação, de forma ativa e passiva, judicial e extrajudicial;</p>
				<p>- se o ato constitutivo da Organização pode ser alterado, no tocante à administração, e de que modo;</p>
				<p>- se os membros da Organização respondem, ou não, subsidiariamente, pelas obrigações sociais;</p>
				<p>- as condições de extinção da Organização e o destino do seu patrimônio, caso ocorra.</p>

				<p><strong>1.1.2 –</strong> Documentos dos participantes da diretoria:</p>
				<p>- RG, CPF e Comprovante de Residência;</p>
				<p>- Estado Civil;</p>
				<p>- Profissão;</p>
				<p>- Função exercida na diretoria; </p>

				<p><strong>§ 1º -</strong> A documentação de que trata esta cláusula deverá ser enviada pela CONTRATANTE à CONTRATADA de forma completa e em boa ordem.</p>

				<p><strong>§ 2º -</strong> A CONTRATADA compromete-se a cumprir todos os prazos estabelecidos no acordo:</p>

				<p><strong>2.3.1 –</strong> Preparação de Estatuto e Ata de Fundação para análise: entrega em até 10 dias após o fornecimento completo das informações por parte do CONTRATANTE;</p>

				<p><strong>2.3.2 -</strong>  Elaboração do pedido de CNPJ (DBE - Documento Básico de Entrada) junto à Receita Federal em até 7 dias após o registro em cartório;</p>

				<p><strong>§ 3º -</strong> A remessa de documentos entre as partes será enviada por email.</p>

				$protocolo

				</br>
				<h2>DOS DEVERES DA CONTRATADA</h2>
				</br>

				<p><strong>Cláusula Terceira:</strong> A CONTRATADA desempenhará os serviços enumerados na Cláusula Primeira, com todo o zelo, diligência e honestidade, observada a legislação vigente, resguardando os interesses da CONTRATANTE, sem prejuízo da dignidade e independência profissional, sujeitando-se ainda às normas do Código de Ética Profissional, aprovado pela Resolução 803/96 e alterações subsequentes, do Conselho Federal de Contabilidade.</p>

				<p><strong>§ 1º –</strong> A CONTRATADA não assume nenhuma responsabilidade pelas consequências de informações, declarações ou documentação inidôneas ou incompletas que lhe forem apresentadas; bem como por omissões próprias da CONTRATANTE e por todos os seus prepostos, ou decorrentes do desrespeito à orientação prestada.</p>

				<p><strong>§ 2º -</strong> Responsabilizar-se-á a CONTRATADA por todos os documentos a ela entregues pela CONTRATANTE, enquanto permanecerem sob sua guarda para a consecução dos serviços pactuados, respondendo pelo mau uso, perda, extravio ou inutilização, salvo comprovado caso fortuito ou força maior.</p>

				<p><strong>§ 3º -</strong> Obriga-se a CONTRATADA a fornecer à CONTRATANTE, no escritório da primeira e dentro do horário normal de expediente, todas as informações relativas ao andamento dos serviços ora contratados nos prazos já estabelecidos anteriormente. </p>

				</br>
				<h2>DOS DEVERES DA CONTRATANTE</h2>
				</br>

				<p><strong>Cláusula Quarta:</strong> Obriga-se a CONTRATANTE a fornecer à CONTRATADA, todos os dados, documentos e informações que se façam necessárias ao bom desempenho dos serviços contratados, em tempo hábil; nenhuma responsabilidade cabendo à CONTRATADA caso receba a documentação intempestivamente ou não enviados, bem como quaisquer omissões documentais e os efeitos decorrentes, de acordo com a legislação em vigor.</p>

				<p><strong>Parágrafo Único:</strong> Obriga-se, ainda, a determinar a todos os setores da empresa que prestem o máximo de colaboração à CONTRATADA, quando na execução de suas tarefas, seja no que tange a fornecimento de informações e documentos, seja no que diz respeito ao cumprimento de instruções e determinações da CONTRATADA, e que se relacionem com os seus trabalhos.</p>

				</br>
				<h2>DOS HONORÁRIOS</h2>
				</br>

				<p><strong>Cláusula Quinta:</strong> Para a execução dos serviços constantes da Cláusula Primeira a CONTRATANTE pagará à CONTRATADA os honorários profissionais no valor de R$ $valor,00 ($valor_extenso), via depósito ou cobrança bancária. O valor não inclui as taxas de registro em Cartório e de honorários advocatícios.</p>

				<p><strong>§ 1º -</strong> Os serviços serão executados após a confirmação do pagamento por parte do CONTRATANTE.</p>

				<p><strong>§ 2° –</strong> Os serviços solicitados pela CONTRATANTE não especificados na Cláusula Primeira serão cobrados pela CONTRATADA em apartado, como extraordinários, segundo valor específico constante de orçamento previamente aprovado pela primeira. </p>

				</br>
				<h2>DA VIGÊNCIA E RESCISÃO</h2>
				</br>

				<p><strong>Cláusula Sexta:</strong> O presente contrato vigorará por prazo indeterminado a partir da sua assinatura e contemplará apenas o que foi descrito na cláusula primeira.</p>

				<p><strong>Cláusula Sétima:</strong> Em caso de cancelamento por parte do CONTRATANTE, a CONTRATADA não se compromete em devolver o valor pago pelo serviço.</p>

				<p><strong>§ 1º -</strong> A CONTRATADA se compromete a entregar o que está discriminado na cláusula primeira, não havendo a necessidade de devolução do valor pago pelo serviço contratado.</br>

				<p><strong>§ 2º -</strong> A CONTRATADA não se compromete a entregar o que está discriminado na cláusula primeira caso o solicitado não esteja de acordo com a legislação vigente do país. </p>

				</br>
				<h2>DO FORO</h2>
				</br>

				<p><strong>Cláusula Oitava:</strong> Fica eleito o Foro da Comarca de BELFORD ROXO - RJ, com expressa renúncia de qualquer outros, por mais privilegiado que seja, para dirimir quaisquer questões oriundas de interpretação e execução do presente contrato.</p>

				<p>E, por estarem as partes CONTRATANTE e CONTRATADA, de pleno acordo com o disposto neste instrumento particular, o assinam em duas vias de igual teor e forma, na presença das duas testemunhas abaixo, destinando-se uma via a cada parte interessada.</p>


				<p>$cidade_igreja, $data.</p>

				<p>	_________________________________________________</p>
					<p>ÉTIKA SOLUÇÕES CONSULTORIA</p>
					<p>CONTRATADA</p>


				<p>	_________________________________________________</p>
					<p>$nome_presidente</p>
					<p>CONTRATANTE</p></br></br>
	</div>
				";

				echo $texto;

		?>

		</div>
	</div>

        <!-- /page content -->

        <!-- footer content -->

		<?php
		include "rodape.php";

		?>
