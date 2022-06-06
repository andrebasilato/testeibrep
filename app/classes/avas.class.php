<?php
class Ava extends Core
{
    /** Main `table` used in operations in this class */
    const CURRENT_TABLE = 'avas';

    public function listarTodas()
    {
        $this->sql = sprintf('SELECT %s FROM %s a WHERE a.ativo = "S"', $this->campos, self::CURRENT_TABLE);

        $this->aplicarFiltrosBasicos()->set('groupby', 'idava');
        return $this->retornarLinhas();
    }

    public function retornar()
    {
        $this->sql = 'SELECT ' . $this->campos . ' FROM avas WHERE ativo = "S" AND idava = ' . $this->id;
        return $this->retornarLinha($this->sql);
    }

    public function cadastrar()
    {
        $this->retorno = $this->SalvarDados();

        if ($this->retorno["sucesso"]) {
            $this->sql = "insert into
                          avas_rotas_aprendizagem
                        set
                          data_cad = now(),
                          idava = '" . $this->retorno["id"] . "',
                          nome = 'Rota de aprendizagem',
                          exibir_ava = 'N'";
            if ($this->executaSql($this->sql)) {
                $this->retorno["sucesso"] = true;
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }

    public function modificar()
    {
        return $this->salvarDados();
    }

    public function remover()
    {
        return $this->removerDados();
    }

    public function listarDisciplinas()
    {
        $this->sql = "SELECT
                    {$this->campos}
                  FROM
                    disciplinas d
                  WHERE
                    d.ativo = 'S' AND
                    not exists (
                      SELECT
                        ad.iddisciplina
                      FROM
                        avas_disciplinas ad
                      WHERE
                        d.iddisciplina = ad.iddisciplina AND
                        ad.idava = " . intval($this->id) . " AND
                        ad.ativo = 'S'
                    )";

        $this->groupby = "d.idava_disciplina";
        return $this->retornarLinhas();
    }

    function listarTodasDisciplinas()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    avas a
                    inner join avas_disciplinas ad on (a.idava = ad.idava)
                    inner join disciplinas d on (ad.iddisciplina = d.iddisciplina)
                    LEFT JOIN avas_avaliacoes aa ON (aa.iddisciplina_nota = d.iddisciplina AND aa.idava = a.idava)
                  where
                    ad.ativo = 'S' and d.ativo = 'S' and a.idava = " . intval($this->id);
        $this->groupby = "ad.idava_disciplina";
        $this->manter_groupby = true;
        return $this->retornarLinhas();
	}

    function AssociarDisciplinas($iddisciplina)
    {
        $this->sql = "select count(idava_disciplina) as total, idava_disciplina from avas_disciplinas where iddisciplina = '" . intval($iddisciplina) . "' and idava = " . intval($this->id);
        $total = $this->retornarLinha($this->sql);
        if ($total["total"] > 0) {
            $this->sql = "update avas_disciplinas set ativo = 'S' where idava_disciplina = " . $total["idava_disciplina"];
            $associar = $this->executaSql($this->sql);
            $this->monitora_qual = $total["idava_disciplina"];
        } else {
            $this->sql = "insert into avas_disciplinas set ativo = 'S', data_cad = now(), iddisciplina = '" . intval($iddisciplina) . "', idava = " . intval($this->id);
            $associar = $this->executaSql($this->sql);
            $this->monitora_qual = mysql_insert_id();
        }
        if ($associar) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 1;
            $this->monitora_onde = 173;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }
        return $this->retorno;
    }

    function DesassociarDisciplinas()
    {

        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        // Verificando se opção remover foi marcada
        if (!$this->post["remover"]) {
            $regras[] = "required,remover,remover_vazio";
        }

        // Validando formulário
        $erros = validateFields($this->post, $regras);

        // Se existir regras a serem aplicadas verificar se tem algum erro
        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "update avas_disciplinas set ativo = 'N' where idava_disciplina = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 173;
                $this->monitora_qual = intval($this->post["remover"]);
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }

        return $this->retorno;

    }

    public function listarAulasOnline()
    {
        $this->sql = "SELECT
                    {$this->campos}
                  FROM
                    aulas_online ao
                  WHERE
                    ao.ativo_painel = 'S' AND                    
                    ao.data_aula >= DATE_FORMAT(NOW(), '%Y-%m-%d')
                    AND not exists(
                    SELECT aao.idaula_online
                    FROM avas_aulas_online aao
                    WHERE ao.idaula = aao.idaula_online
                    AND aao.idava = " . intval($this->id) . "
                    AND aao.ativo = 'S'
                    )
                    AND exists(
                    SELECT ad.iddisciplina
                    FROM avas_disciplinas ad
                    WHERE ao.iddisciplina = ad.iddisciplina
                    AND ad.idava = " . intval($this->id) . "
                    AND ad.ativo = 'S'
                    )";

        $this->groupby = "aao.idavas_aulas_online";
        return $this->retornarLinhas();
    }

    function listarTodasAulasOnLine()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    avas a                    
                    inner join avas_aulas_online aao on (a.idava = aao.idava)
                    inner join aulas_online ao on (aao.idaula_online = ao.idaula)
                    inner join disciplinas d on (ao.iddisciplina = d.iddisciplina)
                  where
                    aao.ativo = 'S' and a.idava = " . intval($this->id);
        $this->groupby = "aao.idavas_aulas_online";
        //print_r2($this->sql);die();
        return $this->retornarLinhas();
    }

    function AssociarAulaOnLine($idaula_online)
    {
        $this->sql = "select count(idavas_aulas_online) as total, idavas_aulas_online from avas_aulas_online where idaula_online = '" . intval($idaula_online) . "' and idava = " . intval($this->id);
        $total = $this->retornarLinha($this->sql);
        if ($total["total"] > 0) {
            $this->sql = "update avas_aulas_online set ativo = 'S' where idavas_aulas_online = " . $total["idavas_aulas_online"];
            $associar = $this->executaSql($this->sql);
            $this->monitora_qual = $total["idavas_aulas_online"];
        } else {
            $this->sql = "insert into avas_aulas_online set ativo = 'S', data_cad = now(), idaula_online = '" . intval($idaula_online) . "', idava = " . intval($this->id);
            $associar = $this->executaSql($this->sql);
            $this->monitora_qual = mysql_insert_id();
        }
        if ($associar) {
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 1;
            $this->monitora_onde = 292;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = $this->retornarErrorQuery();
        }
        return $this->retorno;
    }

    function DesassociarAulaOnLine()
    {

        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        // Verificando se opção remover foi marcada
        if (!$this->post["remover"]) {
            $regras[] = "required,remover,remover_vazio";
        }

        // Validando formulário
        $erros = validateFields($this->post, $regras);

        // Se existir regras a serem aplicadas verificar se tem algum erro
        if (!empty($erros)) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        } else {
            $this->sql = "update avas_aulas_online set ativo = 'N' where idavas_aulas_online = " . intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql,false);

            if ($desassociar) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 292;
                $this->monitora_qual = intval($this->post["remover"]);
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = $this->retornarErrorQuery();
            }
        }

        return $this->retorno;

    }

    function ListarTodasPorDisciplinas($iddisciplina)
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    avas a
                    inner join avas_disciplinas ad on (a.idava = ad.idava)
                  where
                    a.ativo = 'S' and
                    ad.ativo = 'S' and
                    ad.iddisciplina = " . $iddisciplina . "
                  group by a.idava";

        $this->groupby = "a.idava";
        return $this->retornarLinhas();
	}
	
	public function atualizarHorasOfflineDisciplinas(){

		$qtdDisc = count($this->post['idava_disciplina']);
		for( $i = 0; $i < $qtdDisc; $i++ ){
			$sql = "UPDATE avas_disciplinas SET ";
			$sql.= "tempo_offline = '".$this->post['tempo_offline'][$i]."' ";
			$sql.= "WHERE idava_disciplina = '".$this->post['idava_disciplina'][$i]."' ";
			$retorno = $this->executaSql($sql);
		}
		return $retorno;

	}
  	
	function estruturaClone($array,$ignora){
		$text='';
		foreach($array as $ind => $val){
					if(!in_array($ind,$ignora)){
						if(is_null($val)){
							$text.=  "  , ".$ind." =  NULL " ;
						}else{
							$text .=  "  , ".$ind." =  '".mysql_escape_string($val)."'" ;
						}
					}
		}
		return $text;
	}
	
	
	function novoCloneArquivo($endereco){
		$extensao = strtolower(strrchr($endereco, "."));
        $endereco = date("YmdHis") . "_" . uniqid() . $extensao;
		return $endereco;
	}
	
	function clonarAva(){
			$array_ignora = array(
					0=>"data_cad",
					1=>"ativo",
					2=>"idava_clone",
					3=>"idava",
					4=>"idava_disciplina",
					5=>"idrota_aprendizagem",
					6=>"idobjeto",
					7=>"idconteudo",
					8=>"idobjeto_divisor",
					9=>"idvideo",
					10=>"idaudio",
					11=> "idpergunta",
					12=>"idenquete",
					13=>"idopcao",
					14=>"idlink",
					15=>"idexercicio",
					16=>"idexercicio_disciplina",
					17=>"idavaliacao",
					18=>"idavaliacao_disciplina",
					19=>"idsimulado",
					20=>"idsimulado_disciplina_pergunta",
					21 =>"id_pasta",
					22=>"id_discovirtual",
					23=>"idpasta",
					24=>"idforum",
					25=>"idfaq",
					26=>"iddownload",
                    27=>"idlinkacao",
                    28=>"idava_conteudo"
			);
			//COMEÇAR CLONAR AVA
			$this->sql = "select
								*
							  from
								avas
							  where
								idava = ".$this->idava." and ativo = 'S' 
				   ";
			$query = $this->executaSql($this->sql);
			$linha =  mysql_fetch_assoc($query);
			$linha['nome'] = $linha['nome'].'  CLONE DO ID: '.$this->idava;
			
			$this->sql = 'INSERT INTO avas SET ativo = "S", data_cad = NOW(), idava_clone = "'.$this->idava.'" ';
			$this->sql .= $this->estruturaClone($linha,$array_ignora);
		
			$query = $this->executaSql($this->sql);
			$idava = mysql_insert_id();
			
			//DISCIPLINAS INICIO
			$this->sql = "select
								*
							  from
								avas_disciplinas
							  where
								idava = ".$this->idava." and ativo = 'S' 
				   ";
			$query = $this->executaSql($this->sql);
			if(mysql_num_rows($query) > 0){
				while($linha =  mysql_fetch_assoc($query)){
					$this->sql = 'INSERT INTO avas_disciplinas SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'" ';
					$this->sql .= $this->estruturaClone($linha,$array_ignora);
					$this->executaSql($this->sql);
				}
			}
			//DISCIPLINAS FIM
			
			//CONTEUDOS INICIO
			$this->sql = "select
								*
							  from
								avas_conteudos
							  where
								idava = ".$this->idava." and ativo = 'S' 
				   ";
			$array_conteudo = array();	   
				   
			$query = $this->executaSql($this->sql);
			if (mysql_num_rows($query) > 0) {
				while ($linha =  mysql_fetch_assoc($query)) {
					$anteriorimg = $linha['imagem_exibicao_servidor'];
					if($linha['imagem_exibicao_servidor']){
						$linha['imagem_exibicao_servidor'] =   $this->novoCloneArquivo($linha['imagem_exibicao_servidor']);
					}
					$this->sql = 'INSERT INTO avas_conteudos SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'" ';
					$this->sql .= $this->estruturaClone($linha,$array_ignora);
					$this->executaSql($this->sql);
					$idconteudo = mysql_insert_id();
					if($anteriorimg and file_exists('../storage/avas_conteudos_imagem_exibicao/'.$anteriorimg)){
						copy('../storage/avas_conteudos_imagem_exibicao/'.$anteriorimg,'../storage/avas_conteudos_imagem_exibicao/'.$linha['imagem_exibicao_servidor']);
					}
					$array_conteudo[$linha['idconteudo']] = $idconteudo;
                    $this->sql = "SELECT
										*
									FROM
										avas_conteudos_linksacoes
									WHERE
										idava_conteudo = " . $linha['idconteudo'] . " and ativo = 'S' 	";
                    $query2 = $this->executaSql($this->sql);
                    if (mysql_num_rows($query2) > 0) {
                        while($linha2 =  mysql_fetch_assoc($query2)) {
                            $this->sql2 = 'INSERT INTO avas_conteudos_linksacoes SET ativo = "S", data_cad = NOW(), idava_conteudo = "'.$idconteudo.'" ';
                            $this->sql2 .= $this->estruturaClone($linha2,$array_ignora);
                            $this->executaSql($this->sql2);
                            $idlinkacao = mysql_insert_id();

                            if ($linha2['tipo'] == 'A') {
                                $variavel = '[[TRACK][' . $idlinkacao  . ']]';
                            } else {
                                $variavel = '[[LINK][' . $idlinkacao  . ']]';
                            }

                            $sql = "UPDATE avas_conteudos_linksacoes SET variavel = '" . $variavel  . "' WHERE idlinkacao = " . $idlinkacao;
                            $this->executaSql($sql);
                        }

                    }
				}
			}
			//CONTEUDOS FIM
			
	 		//OBJETOS DIVISORES INICIO
			$this->sql = "select
								*
							  from
								avas_objetos_divisores
							  where
								idava = ".$this->idava." and ativo = 'S' 
				   ";
			$array_objeto_divisor = array();	   
				   
			$query = $this->executaSql($this->sql);
			if(mysql_num_rows($query) > 0){
				while($linha =  mysql_fetch_assoc($query)){
					$this->sql = 'INSERT INTO avas_objetos_divisores SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'" ';
					$this->sql .= $this->estruturaClone($linha,$array_ignora);
					$this->executaSql($this->sql);
					$idobjeto_divisor = mysql_insert_id();
					$array_objeto_divisor[$linha['idobjeto_divisor']] = $idobjeto_divisor;
				}
			}
			//OBJETOS DIVISORES FIM
		
			//VIDEOS INICIO
			$this->sql = "select
								*
							  from
								avas_videotecas
							  where
								idava = ".$this->idava." and ativo = 'S' 
				   ";
			$array_video = array();	   
				   
			$query = $this->executaSql($this->sql);
			if(mysql_num_rows($query) > 0){
				while($linha =  mysql_fetch_assoc($query)){
					$this->sql = 'INSERT INTO avas_videotecas SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'" ';
					$this->sql .= $this->estruturaClone($linha,$array_ignora);
					$this->executaSql($this->sql);
					$idvideo = mysql_insert_id();
					$array_video[$linha['idvideo']] = $idvideo;
				}
			}
			//VIDEOS FIM
			 		
			//AUDIOS INICIO
			$this->sql = "select
								*
							  from
								avas_audios
							  where
								idava = ".$this->idava." and ativo = 'S' 
				   ";
			$array_audios = array();	   
				   
			$query = $this->executaSql($this->sql);
			if(mysql_num_rows($query) > 0){
				while($linha =  mysql_fetch_assoc($query)){
					$anteriorimg = $linha['imagem_exibicao_servidor'];
					$anterioraudio = $linha['arquivo_servidor'];
					if($linha['arquivo_servidor']){
						$linha['arquivo_servidor'] =   $this->novoCloneArquivo($linha['arquivo_servidor']);
					}
					if($linha['imagem_exibicao_servidor']){
						$linha['imagem_exibicao_servidor'] =    $this->novoCloneArquivo($linha['imagem_exibicao_servidor']);
					}
					$this->sql = 'INSERT INTO avas_audios SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'" ';
					$this->sql .= $this->estruturaClone($linha,$array_ignora);
					$this->executaSql($this->sql);
					$idaudio = mysql_insert_id();
					if($anteriorimg and file_exists('../storage/avas_audios_imagem_exibicao/'.$anteriorimg)){
						copy('../storage/avas_audios_imagem_exibicao/'.$anteriorimg,'../storage/avas_audios_imagem_exibicao/'.$linha['imagem_exibicao_servidor']);
					}
					if($anterioraudio and file_exists('../storage/avas_audios_arquivo/'.$anterioraudio)){
						copy('../storage/avas_audios_arquivo/'.$anterioraudio,'../storage/avas_audios_arquivo/'.$linha['arquivo_servidor']);
					}
					$array_audios[$linha['idaudio']] = $idaudio;
				}
			}
			//AUDIOS FIM
			
			//PERGUNTAS INICIO
			$this->sql = "select
								*
							  from
								avas_perguntas
							  where
								idava = ".$this->idava." and ativo = 'S' 
				   ";
			$array_perguntas = array();	   
				   
			$query = $this->executaSql($this->sql);
			if(mysql_num_rows($query) > 0){
				while($linha =  mysql_fetch_assoc($query)){
					$anteriorimg = $linha['imagem_exibicao_servidor'];
					if($linha['imagem_exibicao_servidor']){
						$linha['imagem_exibicao_servidor'] =    $this->novoCloneArquivo($linha['imagem_exibicao_servidor']);
					}
					$this->sql = 'INSERT INTO avas_perguntas SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'" ';
					$this->sql .= $this->estruturaClone($linha,$array_ignora);
					$this->executaSql($this->sql);
					$idpergunta = mysql_insert_id();
					if($anteriorimg and file_exists('../storage/avas_perguntas_imagem_exibicao/'.$anteriorimg)){
						copy('../storage/avas_perguntas_imagem_exibicao/'.$anteriorimg,'../storage/avas_perguntas_imagem_exibicao/'.$linha['imagem_exibicao_servidor']);
					}
					$array_perguntas[$linha['idpergunta']] = $idpergunta;
				}
			}
			//PERGUNTAS FIM
			
			//ENQUETES INICIO
			$this->sql = "select
								*
							  from
								avas_enquetes
							  where
								idava = ".$this->idava." and ativo = 'S' 
				   ";
			$array_enquetes = array();	   
				   
			$query = $this->executaSql($this->sql);
			if(mysql_num_rows($query) > 0){
				while($linha =  mysql_fetch_assoc($query)){
					$this->sql = 'INSERT INTO avas_enquetes SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'" ';
					$this->sql .= $this->estruturaClone($linha,$array_ignora);
					$this->executaSql($this->sql);
					$idenquete = mysql_insert_id();
					$array_enquetes[$linha['idenquete']] = $idenquete;
					$this->sql = "select
										*
									  from
										avas_enquetes_opcoes
									  where
										idenquete = ".$linha['idenquete']." and ativo = 'S' 	";
								$query2 = $this->executaSql($this->sql);
								if(mysql_num_rows($query2) > 0){
									while($linha2 =  mysql_fetch_assoc($query2)){
										$this->sql2 = 'INSERT INTO avas_enquetes_opcoes SET ativo = "S", data_cad = NOW(), idenquete = "'.$idenquete.'" ';
										$this->sql2 .= $this->estruturaClone($linha2,$array_ignora);
										$this->executaSql($this->sql2);
									}
									
								}
								
				}
			}
			//ENQUETES FIM
			
			//LINKS INICIO
			$this->sql = "select
								*
							  from
								avas_links
							  where
								idava = ".$this->idava." and ativo = 'S' 
				   ";
			$array_links = array();	   
				   
			$query = $this->executaSql($this->sql);
			if(mysql_num_rows($query) > 0){
				while($linha =  mysql_fetch_assoc($query)){
					$anteriorimg = $linha['imagem_exibicao_servidor'];
					if($linha['imagem_exibicao_servidor']){
						$linha['imagem_exibicao_servidor'] =    $this->novoCloneArquivo($linha['imagem_exibicao_servidor']);
					}
					$this->sql = 'INSERT INTO avas_links SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'" ';
					$this->sql .= $this->estruturaClone($linha,$array_ignora);
					$this->executaSql($this->sql);
					$idlink = mysql_insert_id();
					if($anteriorimg and file_exists('../storage/avas_links_imagem_exibicao/'.$anteriorimg)){
						copy('../storage/avas_links_imagem_exibicao/'.$anteriorimg,'../storage/avas_links_imagem_exibicao/'.$linha['imagem_exibicao_servidor']);
					}
					$array_links[$linha['idlink']] = $idlink;
				}
			}
			//LINKS FIM
			
			//FORUNS INICIO
			$this->sql = "select
									*
								  from
									avas_foruns
								  where
									idava = ".$this->idava." and ativo = 'S' 
					   ";
				$array_foruns = array();	   
					   
				$query = $this->executaSql($this->sql);
				if(mysql_num_rows($query) > 0){
					while($linha =  mysql_fetch_assoc($query)){
						$anteriorimg = $linha['imagem_exibicao_servidor'];
						if($linha['imagem_exibicao_servidor']){
							$linha['imagem_exibicao_servidor'] =    $this->novoCloneArquivo($linha['imagem_exibicao_servidor']);
						}
						$this->sql = 'INSERT INTO avas_foruns SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'" ';
						$this->sql .= $this->estruturaClone($linha,$array_ignora);
						$this->executaSql($this->sql);
						$idforum = mysql_insert_id();
						if($anteriorimg and file_exists('../storage/avas_foruns_imagem_exibicao/'.$anteriorimg)){
							copy('../storage/avas_foruns_imagem_exibicao/'.$anteriorimg,'../storage/avas_foruns_imagem_exibicao/'.$linha['imagem_exibicao_servidor']);
						}
						$array_foruns[$linha['idforum']] = $idforum;
					}
				}
			//FORUNS FIM
			
			//EXERCICIOS INICIO
			$this->sql = "select
								*
							  from
								avas_exercicios
							  where
								idava = ".$this->idava." and ativo = 'S' 
				   ";
			$array_exercicios = array();	   
				   
			$query = $this->executaSql($this->sql);
			if(mysql_num_rows($query) > 0){
				while($linha =  mysql_fetch_assoc($query)){
					$this->sql = 'INSERT INTO avas_exercicios SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'" ';
					$this->sql .= $this->estruturaClone($linha,$array_ignora);
					$this->executaSql($this->sql);
					$idexercicio = mysql_insert_id();
					$array_exercicios[$linha['idexercicio']] = $idexercicio;
					$this->sql = "select
										*
									  from
										avas_exercicios_disciplinas
									  where
										idexercicio = ".$linha['idexercicio']." and ativo = 'S' 	";
								$query2 = $this->executaSql($this->sql);
								if(mysql_num_rows($query2) > 0){
									while($linha2 =  mysql_fetch_assoc($query2)){
										$this->sql2 = 'INSERT INTO avas_exercicios_disciplinas SET ativo = "S", data_cad = NOW(), idexercicio = "'.$idexercicio.'" ';
										$this->sql2 .= $this->estruturaClone($linha2,$array_ignora);
										$this->executaSql($this->sql2);
									}
									
								}
								
				}
			}
			//EXERCICIOS FIM
			
			//AVALIACOES INICIO
			$this->sql = "select
								*
							  from
								avas_avaliacoes
							  where
								idava = ".$this->idava." and ativo = 'S' 
				   ";
			$array_avaliacoes = array();	   
				   
			$query = $this->executaSql($this->sql);
			if(mysql_num_rows($query) > 0){
				while($linha =  mysql_fetch_assoc($query)){
					$anteriorimg = $linha['imagem_exibicao_servidor'];
					if($linha['imagem_exibicao_servidor']){
						$linha['imagem_exibicao_servidor'] =    $this->novoCloneArquivo($linha['imagem_exibicao_servidor']);
					}
					$this->sql = 'INSERT INTO avas_avaliacoes SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'" ';
					$this->sql .= $this->estruturaClone($linha,$array_ignora);
					$this->executaSql($this->sql);
					if($anteriorimg and file_exists('../storage/avas_avaliacoes_imagem_exibicao/'.$anteriorimg)){
						copy('../storage/avas_avaliacoes_imagem_exibicao/'.$anteriorimg,'../storage/avas_avaliacoes_imagem_exibicao/'.$linha['imagem_exibicao_servidor']);
					}
					$idavaliacao = mysql_insert_id();
					
					$array_avaliacoes[$linha['idavaliacao']] = $idavaliacao;
					$this->sql = "select
										*
									  from
										avas_avaliacoes_disciplinas
									  where
										idavaliacao = ".$linha['idavaliacao']." and ativo = 'S' 	";
								$query2 = $this->executaSql($this->sql);
								if(mysql_num_rows($query2) > 0){
									while($linha2 =  mysql_fetch_assoc($query2)){
										$this->sql2 = 'INSERT INTO avas_avaliacoes_disciplinas SET ativo = "S", data_cad = NOW(), idavaliacao = "'.$idavaliacao.'" ';
										$this->sql2 .= $this->estruturaClone($linha2,$array_ignora);
										$this->executaSql($this->sql2);
									}
									
								}
								
				}
			}
			//AVALIACOES FIM
			
			//SIMULADOS INICIO
			$this->sql = "select
								*
							  from
								avas_simulados
							  where
								idava = ".$this->idava." and ativo = 'S' 
				   ";
			$array_simulados = array();	   
				   
			$query = $this->executaSql($this->sql);
			if(mysql_num_rows($query) > 0){
				while($linha =  mysql_fetch_assoc($query)){
					$anteriorimg = $linha['imagem_exibicao_servidor'];
					if($linha['imagem_exibicao_servidor']){
						$linha['imagem_exibicao_servidor'] =    $this->novoCloneArquivo($linha['imagem_exibicao_servidor']);
					}
					$this->sql = 'INSERT INTO avas_simulados SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'" ';
					$this->sql .= $this->estruturaClone($linha,$array_ignora);
					$this->executaSql($this->sql);
					if($anteriorimg and file_exists('../storage/avas_simulados_imagem_exibicao/'.$anteriorimg)){
						copy('../storage/avas_simulados_imagem_exibicao/'.$anteriorimg,'../storage/avas_simulados_imagem_exibicao/'.$linha['imagem_exibicao_servidor']);
					}
					$idsimulado = mysql_insert_id();
					
					$array_simulados[$linha['idsimulado']] = $idsimulado;
					/*
					$this->sql = "select
										*
									  from
										avas_simulados_disciplinas_perguntas
									  where
										idsimulado = ".$linha['idsimulado']." and ativo = 'S' 	";
								$query2 = $this->executaSql($this->sql);
								if(mysql_num_rows($query2) > 0){
									while($linha2 =  mysql_fetch_assoc($query2)){
										$this->sql2 = 'INSERT INTO avas_simulados_disciplinas_perguntas SET ativo = "S", data_cad = NOW(), idsimulado = "'.$idsimulado.'" ';
										$this->sql2 .= $this->estruturaClone($linha2,$array_ignora);
										$this->executaSql($this->sql2);
									}
									
								}
					*/
								
				}
			}
			//SIMULADOS FIM
			
			//DISCO VIRTUAL INICIO
			$this->sql = "select
								*
							  from
								avas_dicosvirtuais_pastas
							  where
								idava = ".$this->idava." and ativo = 'S' 
				   ";
			$dicosvirtuais_pastas = array();	   
				   
			$query = $this->executaSql($this->sql);
			if(mysql_num_rows($query) > 0){
				while($linha =  mysql_fetch_assoc($query)){
					
					$this->sql = 'INSERT INTO avas_dicosvirtuais_pastas SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'" ';
					$this->sql .= $this->estruturaClone($linha,$array_ignora);
					$this->executaSql($this->sql);
					$id_pasta = mysql_insert_id();
					mkdir('../storage/discovirtual/'.$idava.'/'.$id_pasta, 0777, true);
					chmod('../storage/discovirtual/'.$idava.'/'.$id_pasta, 0777);
					$dicosvirtuais_pastas[$linha['id_pasta']] = $id_pasta;
					$this->sql = "select
										*
									  from
										avas_discosvirtuais
									  where
										idava = ".$this->idava." and ativo = 'S' and idpasta = ".$linha['id_pasta'];
								$query2 = $this->executaSql($this->sql);
								if(mysql_num_rows($query2) > 0){
									while($linha2 =  mysql_fetch_assoc($query2)){
										$this->sql2 = 'INSERT INTO avas_discosvirtuais SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'", idpasta = "'.$dicosvirtuais_pastas[$linha2['idpasta']].'" ';
										$this->sql2 .= $this->estruturaClone($linha2,$array_ignora);
										$this->executaSql($this->sql2);
										if(file_exists('../storage/discovirtual/'.$this->idava.'/'.$linha2['idpasta'].'/'.$linha2['nome_no_disco'])){
											copy('../storage/discovirtual/'.$this->idava.'/'.$linha2['idpasta'].'/'.$linha2['nome_no_disco'],'../storage/discovirtual/'.$idava.'/'.$dicosvirtuais_pastas[$linha2['idpasta']].'/'.$linha2['nome_no_disco']);
										}
									}
								}
				}
			}
			//DISCO VIRTUAL FIM
			
			//FAQS INICIO
			$this->sql = "select
								*
							  from
								avas_faqs
							  where
								idava = ".$this->idava." and ativo = 'S' 
				   ";
			$array_faqs = array();	   
				   
			$query = $this->executaSql($this->sql);
			if(mysql_num_rows($query) > 0){
				while($linha =  mysql_fetch_assoc($query)){
					$this->sql = 'INSERT INTO avas_faqs SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'" ';
					$this->sql .= $this->estruturaClone($linha,$array_ignora);
					$this->executaSql($this->sql);
					$idfaq = mysql_insert_id();
					$array_faqs[$linha['idfaq']] = $idfaq;
				}
			}
			//FAQS FIM
			
			//BIBLIOTECA INICIO
			$this->sql = "select
								*
							  from
								avas_downloads_pastas
							  where
								idava = ".$this->idava." and ativo = 'S' 
				   ";
			$downloads_pastas = array();	   
			$downloads = array();	   
				   
			$query = $this->executaSql($this->sql);
			if(mysql_num_rows($query) > 0){
				while($linha =  mysql_fetch_assoc($query)){
					
					$this->sql = 'INSERT INTO avas_downloads_pastas SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'" ';
					$this->sql .= $this->estruturaClone($linha,$array_ignora);
					$this->executaSql($this->sql);
					$idpasta = mysql_insert_id();
					$downloads_pastas[$linha['idpasta']] = $idpasta;
					$this->sql = "select
										*
									  from
										avas_downloads
									  where
										idava = ".$this->idava." and ativo = 'S' and idpasta = ".$linha['idpasta'];
								$query2 = $this->executaSql($this->sql);
								if(mysql_num_rows($query2) > 0){
									while($linha2 =  mysql_fetch_assoc($query2)){
										$anteriorimg = $linha['imagem_exibicao_servidor'];
										if($linha2['imagem_exibicao_servidor']){
											$linha2['imagem_exibicao_servidor'] =    $this->novoCloneArquivo($linha2['imagem_exibicao_servidor']);
										}
										$this->sql2 = 'INSERT INTO avas_downloads SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'", idpasta = "'.$downloads_pastas[$linha2['idpasta']].'" ';
										$this->sql2 .= $this->estruturaClone($linha2,$array_ignora);
										$this->executaSql($this->sql2);
										$iddownload = mysql_insert_id();
										$downloads[$linha2['iddownload']] = $iddownload;
										if(file_exists('../storage/avas_downloads_arquivo/'.$anteriorimg)){
											copy('../storage/avas_downloads_arquivo/'.$linha2['nome_no_disco'],'../storage/avas_downloads_arquivo/'.$linha2['imagem_exibicao_nome']);
										}
									}
								}
				}
			}
			//BIBLIOTECA FIM
			
			//ROTAS INICIO
			$this->sql = "select
								*
							  from
								avas_rotas_aprendizagem
							  where
								idava = ".$this->idava." and ativo = 'S' 
				   ";
			$rotas = array();	   
				   
			$query = $this->executaSql($this->sql);
			if(mysql_num_rows($query) > 0){
				while($linha =  mysql_fetch_assoc($query)){
					
					$this->sql = 'INSERT INTO avas_rotas_aprendizagem SET ativo = "S", data_cad = NOW(), idava = "'.$idava.'" ';
					$this->sql .= $this->estruturaClone($linha,$array_ignora);
					$this->executaSql($this->sql);
					$idrota_aprendizagem = mysql_insert_id();
					$this->sql = "select
										*
									  from
										avas_rotas_aprendizagem_objetos
									  where
										idrota_aprendizagem = ".$linha['idrota_aprendizagem']." and ativo = 'S' 	ORDER BY ordem ASC ";
								$query2 = $this->executaSql($this->sql);
								if(mysql_num_rows($query2) > 0){
									while($linha2 =  mysql_fetch_assoc($query2)){
										
										$this->sql2 = 'INSERT INTO avas_rotas_aprendizagem_objetos 
															   SET ativo = "S", 
															   	   data_cad = NOW(), 
																   idrota_aprendizagem = "'.$idrota_aprendizagem.'", 
																   tipo = "'.$linha2['tipo'].'", 
																   ordem = "'.$linha2['ordem'].'" 
																  
										';
                                        if ($linha2['porcentagem']) {
                                            $this->sql2 .= "  , porcentagem = '" . $linha2['porcentagem'] . "' ";
                                        }
                                        if ($linha2['tempo']) {
                                            $this->sql2 .= "  , tempo = '" . $linha2['tempo'] . "' ";
                                        }
										if($linha2['vencimento']){
											$this->sql2 .=  " vencimento = '".$linha2['vencimento']."' "  ;
										} 
										if($array_audios[$linha2['idaudio']]){
											$this->sql2 .=  "  , idaudio = ".$array_audios[$linha2['idaudio']] ;
										} 
										if($array_conteudo[$linha2['idconteudo']]){
											$this->sql2 .=  "  , idconteudo = ".$array_conteudo[$linha2['idconteudo']] ;
										}
										if($downloads[$linha2['iddownload']]){
											$this->sql2 .=  "  , iddownload = ".$downloads[$linha2['iddownload']] ;
										}
										if($array_links[$linha2['idlink']]){
											$this->sql2 .=  "  , idlink = ".$array_links[$linha2['idlink']] ;
										}
										if($array_perguntas[$linha2['idpergunta']]){
											$this->sql2 .=  "  , idpergunta = ".$array_perguntas[$linha2['idpergunta']] ;
										}
										if($array_video[$linha2['idvideo']]){
											$this->sql2 .=  "  , idvideo = ".$array_video[$linha2['idvideo']] ;
										}
										if($array_simulados[$linha2['idsimulado']]){
											$this->sql2 .=  "  , idsimulado = ".$array_simulados[$linha2['idsimulado']] ;
										}
										if($array_enquetes[$linha2['idenquete']]){
											$this->sql2 .=  "  , idenquete = ".$array_enquetes[$linha2['idenquete']] ;
										}
										if($array_objeto_divisor[$linha2['idobjeto_divisor']]){
											$this->sql2 .=  "  , idobjeto_divisor = ".$array_objeto_divisor[$linha2['idobjeto_divisor']] ;
										}
										if($array_exercicios[$linha2['idexercicio']]){
											$this->sql2 .=  "  , idexercicio = ".$array_exercicios[$linha2['idexercicio']] ;
										}
										if($objetoexercicio[$linha2['idobjeto_pre_requisito']]){
											$this->sql2 .=  "  , idobjeto_pre_requisito = ".$objetoexercicio[$linha2['idobjeto_pre_requisito']] ;
										}
										
										$this->executaSql($this->sql2);
										$objetoexercicio[$linha2['idobjeto']] = mysql_insert_id();
									}
								}	
				}
			}
			//ROTAS FIM
			 	
			if (mysql_error() == '') {
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 7;
				$this->monitora_onde = 12;
				$this->Monitora(); 
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = $this->sq2;
				$this->retorno["erros"][] = mysql_error();
			}
        return $this->retorno;
	}
		
	
	
}