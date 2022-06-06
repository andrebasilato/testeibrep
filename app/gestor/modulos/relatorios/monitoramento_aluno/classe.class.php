<?php
class Relatorio extends Core {

	function gerarRelatorio(){
		
		$this->sql = "SELECT
						".$this->campos.",						  
						aa.nome as audio, 
						v.titulo as video, 
						ad.nome as arquivo, 
						ma.anotacao as anotacao, 
						af.nome as forum, 
						ca.nome as material,
						aav.nome as avaliacoes, 
                        asi.nome as simulado,
                        if(mah.acao = 'respondeu', msi.total_perguntas, null) as total_perguntas,
                        if(mah.acao = 'respondeu', msi.total_perguntas_corretas, null) as total_perguntas_corretas,
						ac.nome as chat, 
						atm.mensagem as tira_duvidas, 
						mic.mensagem as mensagem_instantanea,
						ae.nome as exercicio
					FROM 
						matriculas_alunos_historicos mah 
						INNER JOIN matriculas m ON (mah.idmatricula = m.idmatricula)
						INNER JOIN pessoas p ON (m.idpessoa = p.idpessoa)
						left join avas a ON (mah.idava = a.idava)
						left join avas_audios aa ON (mah.oque = 'audio' and aa.idaudio = mah.id)
						left join videotecas v ON (mah.oque = 'video' and v.idvideo = mah.id)					
						left join avas_downloads ad ON (mah.oque = 'arquivo' and ad.iddownload = mah.id)
						left join matriculas_anotacoes ma ON (mah.oque = 'anotacao' and ma.idanotacao = mah.id)
						left join avas_foruns af ON (mah.oque = 'forum' and af.idforum = mah.id)
						left join curriculos_arquivos ca ON (mah.oque = 'material' and ca.idarquivo = mah.id)
						left join matriculas_avaliacoes mav ON (mah.oque = 'avaliacoes' and mav.idprova = mah.id)
						left join avas_avaliacoes aav ON (aav.idavaliacao = mav.idavaliacao)
                        left join matriculas_simulados msi ON (mah.oque = 'simulado' and msi.idmatricula_simulado = mah.id)
                        left join avas_simulados asi ON asi.idsimulado = msi.idsimulado
						left join avas_chats ac ON (mah.oque = 'chat' and ac.idchat = mah.id)
						left join avas_tiraduvidas_mensagens atm ON (mah.oque = 'tira_duvidas' and atm.idmensagem = mah.id)
						left join avas_mensagem_instantanea_conversas mic ON (mah.oque = 'mensagem_instantanea' and IF(mah.acao = 'cadastrou', mic.idmensagem_instantanea_conversa = mah.id, mic.idmensagem_instantanea = mah.id))
						left join matriculas_exercicios me ON (mah.oque = 'exercicio' and me.idmatricula_exercicio = mah.id)
						left join avas_exercicios ae ON (me.idexercicio = ae.idexercicio)
					WHERE 
						m.ativo = 'S' ";
		
		if(is_array($_GET["q"])) {
			foreach($_GET["q"] as $campo => $valor) {
				//explode = Retira, ou seja retira a "|" da variavel campo
				$campo = explode("|",$campo);
				$valor = str_replace("'","",$valor);
				// Listagem se o valor for diferente de Todos ele faz um filtro
				if(($valor || $valor === "0") && $valor <> "todos") {
					// se campo[0] for = 1 é pq ele tem de ser um valor exato
					if($campo[0] == 1) {
						$this->sql .= " and ".$campo[1]." = '".$valor."' ";
					// se campo[0] for = 2, faz o filtro pelo comando like
					} elseif($campo[0] == 2)  {
						$this->sql .= " and ".$campo[1]." like '%".urldecode($valor)."%' ";
					}  elseif($campo[0] == 'de_ate' || $campo[0] == 'de_ate_matricula') {
						if($valor == 'HOJ') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d")."'";
                        } elseif($valor == 'ONT') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') = '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))."'";
                        } else if($valor == 'SET') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")))."'    ";
                        } elseif($valor == 'QUI') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m-%d') <= '".date("Y-m-d")."'
                                          and date_format(".$campo[2].",'%Y-%m-%d') >= '".date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")))."' ";
                        } else if($valor == 'MAT') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m")."'";
                        } else if($valor == 'MPR') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))."'";
                        } else if($valor == 'MAN') {
                            $this->sql .= " and date_format(".$campo[2].",'%Y-%m') = '".date("Y-m", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")))."'";
                        }
                    }
				} 
			}
		}
		
		if($_GET["de"]) {
			$this->sql .= " and (mah.data_cad >= '".formataData($_GET["de"],'en',0)." 00:00:00') ";
		}
		
		if($_GET["ate"]) {
			$this->sql .= " and (mah.data_cad <= '".formataData($_GET["ate"],'en',0)." 23:59:59') ";
		}

		$this->sql .= ' GROUP BY mah.idhistorico';
		
		$this->groupby = "mah.idhistorico";
		$linhas = $this->retornarLinhas();
		
		$array_acao = array(
			'objeto_rota' => 'Objeto da rota',
			'material' => 'Material',
			'anotacao' => 'Anotação',
			'favorito' => 'Favorito',
			'arquivo' => 'Arquivo',
			'multimidia' => 'Multimídia',
			'video' => 'Vídeo',
			'audio' => 'Áudio',
			'forum' => 'Fórum',
			'tira_duvidas' => 'Tira dúvidas',
			'avaliacoes' => 'Avaliação',
            'simulado' => 'Simulado',
			'colegas' => 'Colegas',
			'chat' => 'Chat',
			'mensagem_instantanea' => 'Mensagem instantânea',
			'exercicio' => 'Exercício',
			'professores' => 'Monitores e tutores',
		);
		
		$array_tipo_objeto = array(
			'audio' => 'Áudio',
			'conteudo' => 'Conteúdo',
			'download' => 'Download',
			'link' => 'Link',
			'pergunta' => 'Pergunta',
			'video' => 'Vídeo',			
			'simulado' => 'Simulado',
			'enquete' => 'Enquete',
			'objeto_divisor' => 'Objeto divisor',
			'exercicio' => 'Exercício',
		);
		
		foreach ($linhas as $linha) {
			if ($linha['id']) {	
				//'multimidia','tira_duvidas','avaliacoes','colegas'
				$linha['ava'] = '--';
				if ($linha['oque'] == 'objeto_rota') {
					$sql = 'select arao.tipo, 
									aa.nome as audio, 
									ac.nome as conteudo, 
									ad.nome as download, 
									al.nome as link,
									ap.nome as pergunta,
									v.titulo as video, 
									ass.nome as simulado,
									ae.pergunta as enquete,
									aex.nome as exercicio,
									a.nome as ava
								from avas_rotas_aprendizagem_objetos arao
								inner join avas_rotas_aprendizagem ara on arao.idrota_aprendizagem = ara.idrota_aprendizagem
								inner join avas a on ara.idava = a.idava
								left join avas_audios aa on arao.tipo = "audio" and aa.idaudio = arao.idaudio
								left join avas_conteudos ac on arao.tipo = "conteudo" and ac.idconteudo = arao.idconteudo
								left join avas_downloads ad on arao.tipo = "download" and ad.iddownload = arao.iddownload
								left join avas_links al on arao.tipo = "link" and al.idlink = arao.idlink
								left join avas_perguntas ap on arao.tipo = "pergunta" and ap.idpergunta = arao.idpergunta
								left join videotecas v on arao.tipo = "video" and v.idvideo = arao.idvideo
								left join avas_simulados ass on arao.tipo = "simulado" and ass.idsimulado = arao.idsimulado
								left join avas_enquetes ae on arao.tipo = "enquete" and ae.idenquete = arao.idenquete
								left join avas_objetos_divisores aod on arao.tipo = "objeto_divisor" and aod.idobjeto_divisor = arao.idobjeto_divisor
								left join avas_exercicios aex on arao.tipo = "exercicio" and aex.idexercicio = arao.idexercicio
								where arao.idobjeto = ' . $linha['id'];
					$objeto = $this->retornarLinha($sql);
					
					if($objeto['tipo'] == 'objeto_divisor')
						continue;
					/*$idava = 0;
					switch ($objeto['tipo']) {
						case 'audio':
							$idava = $objeto['idava_audio'];
							break;
						case 'conteudo':
							$idava = $objeto['idava_conteudo'];
							break;
						case 'download':
							$idava = $objeto['idava_download'];
							break;
						case 'link':
							$idava = $objeto['idava_link'];
							break;
						case 'pergunta':
							$idava = $objeto['idava_pergunta'];
							break;
						case 'video':
							$idava = $objeto['idava_video'];
							break;
						case 'simulado':
							$idava = $objeto['idava_simulado'];
							break;
						case 'enquete':
							$idava = $objeto['idava_enquete'];
							break;
						case 'objeto_divisor':
							continue;
							break;
						case 'exercicio':
							$idava = $objeto['idava_exercicio'];
							break;
					}*/

					//if($idava)
					//$sql = 'select nome as ava from avas where idava = '.$idava;
					//$ava = $this->retornarLinha($sql);
				
					if($objeto['ava'])
						$linha['ava'] = $objeto['ava'];

					$linha['id'] = $objeto[$objeto['tipo']] . ' (' . $array_tipo_objeto[$objeto['tipo']] . ')';
				} else if ($linha['oque'] == 'favorito') {
					$sql = 'select arao.tipo, 
									aa.nome as audio, ac.nome as conteudo, ad.nome as download, al.nome as link, ap.nome as pergunta,
									v.titulo as video, ass.nome as simulado, ae.pergunta as enquete
								from matriculas_objetos_favoritos mof
								inner join avas_rotas_aprendizagem_objetos arao on mof.idobjeto = arao.idobjeto
								left join avas_audios aa on arao.tipo = "audio" and aa.idaudio = arao.idaudio
								left join avas_conteudos ac on arao.tipo = "conteudo" and ac.idconteudo = arao.idconteudo
								left join avas_downloads ad on arao.tipo = "download" and ad.iddownload = arao.iddownload
								left join avas_links al on arao.tipo = "link" and al.idlink = arao.idlink
								left join avas_perguntas ap on arao.tipo = "pergunta" and ap.idpergunta = arao.idpergunta
								left join videotecas v on arao.tipo = "video" and v.idvideo = arao.idvideo
								left join avas_simulados ass on arao.tipo = "simulado" and ass.idsimulado = arao.idsimulado
								left join avas_enquetes ae on arao.tipo = "enquete" and ae.idenquete = arao.idenquete
								left join avas_objetos_divisores aod on arao.tipo = "objeto_divisor" and aod.idobjeto_divisor = arao.idobjeto_divisor
								left join avas_exercicios aex on arao.tipo = "exercicio" and aex.idexercicio = arao.idexercicio
								where mof.idfavorito = ' . $linha['id'];
					$favorito = $this->retornarLinha($sql);
				
					$linha['id'] = $favorito[$favorito['tipo']] . ' (' . $array_tipo_objeto[$favorito['tipo']] . ')';
				} else if ($linha['oque'] == 'audio' 
						|| $linha['oque'] == 'arquivo' 
						|| $linha['oque'] == 'video' 
						|| $linha['oque'] == 'anotacao' 
						|| $linha['oque'] == 'material' 
						|| $linha['oque'] == 'avaliacoes' 
                        || $linha['oque'] == 'simulado' 
						|| $linha['oque'] == 'tira_duvidas'
						|| $linha['oque'] == 'chat' 
						|| $linha['oque'] == 'mensagem_instantanea'
						|| $linha['oque'] == 'exercicio'
						|| $linha['oque'] == 'forum') {
					$linha['id'] = $linha[$linha['oque']];
				}
				
			}
			$linha['oque'] = $array_acao[$linha['oque']];
			$retorno[] = $linha;
		}
		
		return $retorno;							  
	}
	
	function GerarTabela($dados,$q = null,$idioma,$configuracao = "listagem") {
		
		// Buscando os idiomas do formulario
		include("idiomas/pt_br/index.php");
		echo '<table class="zebra-striped" id="sortTableExample">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>Filtro</th>';
		echo '<th>Valor</th>';
		echo '</tr>';
		echo '</thead>';
		foreach($this->config["formulario"] as $ind => $fieldset){
			foreach($fieldset["campos"] as $ind => $campo){
				if($campo["nome"]{0} == "q"){
				  $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
				  $campoAux = $_GET["q"][$campoAux];
				  
				  if($campo["sql_filtro"]){
					  if($campo["sql_filtro"] == "array"){
						  $campoAux = str_replace(array("q[","]"),"",$campo["nome"]);
						  $campoAux = $GLOBALS[$campo["sql_filtro_label"]][$GLOBALS["config"]["idioma_padrao"]][$_GET["q"][$campoAux]];
					  } else {
						  $sql = str_replace("%",$campoAux,$campo["sql_filtro"]);
						  $seleciona = mysql_query($sql);
						  $linha = mysql_fetch_assoc($seleciona);
						  $campoAux = $linha[$campo["sql_filtro_label"]];
					  }
				  }
				  
				} elseif(is_array($_GET[$campo["nome"]])){
					
				  if($campo["array"]){
					  foreach($_GET[$campo["nome"]] as $ind => $val){
						 $_GET[$campo["nome"]][$ind] = $GLOBALS[$campo["array"]][$GLOBALS["config"]["idioma_padrao"]][$val];
					  }
				  } elseif($campo["sql_filtro"]){
					  foreach($_GET[$campo["nome"]] as $ind => $val){
						 $sql = str_replace("%",$val,$campo["sql_filtro"]);
						 $seleciona = mysql_query($sql);
						 $linha = mysql_fetch_assoc($seleciona);
						 $_GET[$campo["nome"]][$ind] = $linha[$campo["sql_filtro_label"]];
					  }
				  }
					
				  $campoAux = implode($_GET[$campo["nome"]], ", ");					
				} elseif($campo["sql_filtro"]){
					$sql = str_replace("%",$_GET[$campo["nome"]],$campo["sql_filtro"]);
					$seleciona = mysql_query($sql);
					$linha = mysql_fetch_assoc($seleciona);
					$_GET[$campo["nome"]] = $linha[$campo["sql_filtro_label"]];
					
					$campoAux = $_GET[$campo["nome"]];  
				} else {
				  $campoAux = $_GET[$campo["nome"]];  
				}
				if($campoAux <> ""){				  
					echo '<tr>';
					echo '<td><strong>'.$idioma[$campo["nomeidioma"]].'</strong></td>';	
					echo '<td>'.$campoAux.'</td>'; 
					echo '</tr>';
				}
                if($campo["id"] == "form_nome" ){
                    echo '<tr>';
                    echo '<td><strong>'.$idioma[$campo["nomeidioma"]].'</strong></td>';
                    echo '<td>'.$dados[0]['nome'].'</td>';
                    echo '</tr>';
                }
			}
		}
		echo '</table><br>';			
		
		
		echo '<table class="zebra-striped" id="sortTableExample">';
		echo '<thead>';
		echo '<tr>';
		foreach($this->config[$configuracao] as $ind => $valor){
		
				$tamanho = "";
				if($valor["tamanho"]) $tamanho = ' width="'.$valor["tamanho"].'"';
				
				$th = '<th class="';
				$th.= $class.' headerSortReloca" '.$tamanho.'>';
				echo $th;
				
				echo "<div class='headerNew'>".$idioma[$valor["variavel_lang"]]."</div>";
				
				echo '</th>';
				
		}
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		echo '<tr>';
					
		if(count($dados) == 0){
			echo '<tr>';
			echo '<td colspan="'.count($this->config[$configuracao]).'">Nenhum informação foi encontrada.</td>';
			echo '</tr>';
		} else {
			foreach($dados as $i => $linha){
				echo '<tr>';
				foreach($this->config[$configuracao] as $ind => $valor){
					
					if($valor["tipo"] == "banco") {
						echo '<td>'.stripslashes($linha[$valor["valor"]]).'</td>';
					} elseif($valor["tipo"] == "php" && $valor["busca_tipo"] != "hidden") {
						$valor = $valor["valor"]." ?>";
						$valor = eval($valor);							
						echo '<td>'.stripslashes($valor).'</td>';
					} elseif($valor["tipo"] == "array") {
						$variavel = $GLOBALS[$valor["array"]];
						echo '<td>'.$variavel[$this->config["idioma_padrao"]][$linha[$valor["valor"]]].'</td>';
					} elseif($valor["busca_tipo"] != "hidden") {
						echo '<td>'.stripslashes($valor["valor"]).'</td>';
					}
				}

				echo '</tr>';
			}

		}

		echo '</tbody>';
		echo '</table>';
	}
	
}

?>