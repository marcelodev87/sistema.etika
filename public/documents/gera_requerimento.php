<?
					$presidente = "<p><strong>Presidente:</strong> {$data['diretoria']['nome_presidente']}, brasileiro(a), natural de {$data['diretoria']['naturalidade_presidente']}, nascido(a) em {$data['diretoria']['data_nasc_presidente']}, {$data['diretoria']['estado_civil_presidente']}, {$data['diretoria']['profissao_presidente']}, portador(a) do RG de n°: {$data['diretoria']['rg_presidente']} expedido pelo {$data['diretoria']['exp_rg_presidente']}, e inscrito(a) no CPF de n° {$data['diretoria']['CPF_presidente']}, residente e domiciliado(a) na {$data['diretoria']['endereco_presidente']}</p></br>";
					
					// se presidente não estiver cadastrado, retornar com os dados
					$presidente = "PRESIDENTE NÃO CADASTRADO";
					
					
				  
				
	$texto = "<div align=justify  style='background-color:#FFFFFF;  padding: 25px 50px 25px 50px;'>
				
				<h2>Ao {$data['cartorio']['nome_cartorio']}</h2>
				
				<p>$presidente, requer o registro neste Cartório da Organização Religiosa, denominada {$data['igreja']['nome']}. </p>
				
				<p>Nestes termos, pede deferimento.</p>
			
			<p>{$data['igreja']['cidade']} - {$data['igreja']['UF']}, _____/_____/______.</p>


				<p>	_________________________________________________</p>
					<p>{$data['diretoria']['nome_presidente']}</p>
					<p>PRESIDENTE</p>
	</div>
				";
				
						
		?>	