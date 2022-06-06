<?
class Pesquisas extends Core
{
	
var $idpesquisa   = NULL;
var $ind 		  = NULL;
var $val 		  = NULL;		
var $query 		  = NULL;
var $get		  = NULL;
var $linha_antiga = NULL;
var $linha_nova   = NULL;
var $files 		  = NULL;
var $linha		  = array();
var $post         = array();
 
	function ListarTodas() {		
		$this->sql = "SELECT ".$this->campos." FROM
							pesquisas where ativo='S'";
		
		$this->aplicarFiltrosBasicos();
		
		$this->groupby = "idpesquisa";
		$this->retorno = $this->retornarLinhas();
		
		foreach($this->retorno as $ind => $pesquisa) {
			
			// Verificamos o total na fila que vão receber email.
			$this->sql = "SELECT count(*) as total FROM pesquisas_fila where idpesquisa='".$pesquisa["idpesquisa"]."' and `enviar_email` = 'S' and ativo='S' group by idpesquisa";
			$nafilaemail = $this->RetornarLinha($this->sql);
			
			// Verificamos o total na fila que vão ou não receber email.
			$this->sql = "SELECT count(*) as total FROM pesquisas_fila where idpesquisa='".$pesquisa["idpesquisa"]."' and ativo='S' group by idpesquisa";
			$nafilatodos = $this->RetornarLinha($this->sql);
			
			// Verificamos o total na fila espera de email.
			$this->sql = "SELECT count(*) as total FROM pesquisas_fila where idpesquisa='".$pesquisa["idpesquisa"]."' and enviado = 'S' and `enviar_email` = 'S' and ativo='S' group by idpesquisa";
			$enviados = $this->RetornarLinha($this->sql);
			
			// Verificamos o total na fila que não vai receber a pesquisa por email.
			$this->sql = "SELECT count(*) as total FROM pesquisas_fila where idpesquisa='".$pesquisa["idpesquisa"]."' and `enviar_email` = 'N' and ativo='S' group by idpesquisa";
			$nao_enviados_email = $this->RetornarLinha($this->sql);

			// Respondidas
			$this->sql = "SELECT count(pf.idpesquisa) as total 
							FROM pesquisas_fila pf
							INNER JOIN pesquisas_respostas pr ON pf.idpesquisa_pessoa = pr.idpesquisa_pessoa
							WHERE pf.idpesquisa = '".$pesquisa['idpesquisa']."' GROUP BY pf.idpesquisa_pessoa ";
			$respondidos_query = $this->executaSql($this->sql);
			$respondidos = mysql_num_rows($respondidos_query);
			
		
			$this->retorno[$ind]["enviados"] = intval($enviados["total"])."/".intval($nafilaemail["total"]);
			
			$this->retorno[$ind]["nao_enviados_email"] = intval($nao_enviados_email["total"]);
			
			$this->retorno[$ind]["respondidos"] = intval($respondidos)."/".intval($nafilatodos["total"]);
			
			$this->retorno[$ind]["situacao_legenda"] = $GLOBALS["situacao_pesquisa"][$this->config["idioma_padrao"]][$pesquisa["situacao"]];
			$this->retorno[$ind]["situacao_legenda_cor"] = $GLOBALS["situacao_pesquisa_cor"][$pesquisa["situacao"]];
			
		}
		
		return $this->retorno;
	}
	
	
	function Retornar() {
		$this->sql = "SELECT ".$this->campos."
							FROM
							 pesquisas where ativo='S' and idpesquisa='".$this->id."'";			
		return $this->retornarLinha($this->sql);
	}
	
	function Cadastrar() {
		return $this->SalvarDados();	
	}
	
	function Modificar() {
		return $this->SalvarDados();	
	}
	
	function Remover() {
		return $this->RemoverDados();	
	}
	
	/* METODOS PARA ASSOCIAR A PERGUNTA */
	
	function ListarPerguntasAss() {		
		$this->sql = "(SELECT ".$this->campos." FROM
						    pesquisas_perguntas pp
						    INNER JOIN perguntas_pesquisas per ON (pp.idpergunta=per.idpergunta)
						where pp.ativo='S' and pp.idpesquisa = ".intval($this->id).")";
		
		$this->groupby = "pp.idpesquisa_pergunta";
		return $this->retornarLinhas();
	}
	
	function ListarPerguntasResultado($idpesquisa) {
		//ARRAY IDS DA FILA
		$this->sql = "select idpesquisa_pessoa from pesquisas_fila where ativo = 'S' and idpesquisa = '".intval($idpesquisa)."' and data_resposta IS NOT NULL ";
		$query_fila = $this->executaSql($this->sql);
		$total = 0;
		while($fila = mysql_fetch_assoc($query_fila)) {
			$array_fila[] = $fila['idpesquisa_pessoa'];
			$total++;
		}
		if (count($array_fila) > 0) {
			$this->sql = "SELECT p.* FROM pesquisas_perguntas pp
								  INNER JOIN perguntas_pesquisas p ON (pp.idpergunta = p.idpergunta)
								  where pp.ativo='S' and pp.idpesquisa = '".intval($idpesquisa)."'	";
			$query = $this->executaSql($this->sql);
			while ($linha = mysql_fetch_assoc($query)) {
				$retorno[$linha['idpergunta']] = $linha;
				if ($linha['tipo'] == 'O') {
					$sql_opcoes = "select idopcao, titulo from pesquisas_perguntas_opcoes where idpergunta = '".$linha['idpergunta']."' and ativo = 'S' ";
					
					$query_opcoes = $this->executaSql($sql_opcoes);
					$porc_nao_resp = 0;
					$valor_nao_resp = 0;	
					
					if(!$array_fila) {$array_fila[0] = 0;}
					
					while($l = mysql_fetch_assoc($query_opcoes)) {
						$sql_op = "select count(1) as total from pesquisas_respostas 
									 where idpergunta = '".$linha['idpergunta']."' and idopcao = '".$l['idopcao']."' and idpesquisa_pessoa IN (".implode(',',$array_fila).") group by idopcao ";
						
						$total_op = $this->retornarLinha($sql_op);
	
						$retorno[$linha['idpergunta']]['respostas'][$l['idopcao']]['valor'] = ($total_op['total'])?$total_op['total']:0;
						$retorno[$linha['idpergunta']]['respostas'][$l['idopcao']]['nome'] = $l['titulo'];
						$retorno[$linha['idpergunta']]['respostas'][$l['idopcao']]['porcentagem'] = ($total_op['total']*100/$total);
						
						$porc_nao_resp += $retorno[$linha['idpergunta']]['respostas'][$l['idopcao']]['porcentagem'];
						$valor_nao_resp += $retorno[$linha['idpergunta']]['respostas'][$l['idopcao']]['valor'];
					}				
	
					if ($linha['multipla_escolha'] != 'S') {					
						if($porc_nao_resp < 100) {
							$retorno[$linha['idpergunta']]['respostas']['nao_responderam']['valor'] = ($total - $valor_nao_resp);
							$retorno[$linha['idpergunta']]['respostas']['nao_responderam']['porcentagem'] = (100 - $porc_nao_resp);
							$retorno[$linha['idpergunta']]['respostas']['nao_responderam']['nome'] = 'Não opinaram';
						}
					} else {
						$this->sql = "select count(1) as total from pesquisas_respostas where idpergunta = '".$linha['idpergunta']."' and idpesquisa_pessoa IN (".implode(',',$array_fila).") group by idpesquisa_pessoa ";
						$array_resp = $this->retornarLinhas();
						$v_nao = ($total - count($array_resp));
	
						$retorno[$linha['idpergunta']]['respostas']['nao_responderam']['valor'] = $v_nao;
						$retorno[$linha['idpergunta']]['respostas']['nao_responderam']['porcentagem'] = ($v_nao*100/$total);
						$retorno[$linha['idpergunta']]['respostas']['nao_responderam']['nome'] = 'Não opinaram';
					}
					
				} else {
					$sql_sub = "select resposta from pesquisas_respostas where idpergunta = '".$linha['idpergunta']."' and idpesquisa_pessoa IN (".implode(',',$array_fila).") ";
					$query_sub = $this->executaSql($sql_sub);
					while($l = mysql_fetch_assoc($query_sub)) {
						$retorno[$linha['idpergunta']]['subjetiva'][] = $l['resposta'];
					}
				}
			}
			
		}
		
		$sql = "select * from pesquisas where idpesquisa = '".$idpesquisa."' ";
		$pesq = $this->RetornarLinha($sql);
		
		$array_string = explode('[[P][', $pesq['layout']);

		foreach ($array_string as $ar_id) {
			$array_id = explode(']]', $ar_id);
			if (is_numeric($array_id[0]))
				$array_ordem[] = $retorno[$array_id[0]];
		}

		return $array_ordem;
	}
	
	function reenviarPesquisa($idpesquisa) {
		$this->sql = "select 
					p.idpesquisa, 
					p.nome, 
					pf.hash, 
					pf.idpesquisa_pessoa, 
					pf.email, 
					pf.nome as nome_pessoa,
					pf.data_envio
				  from 
					pesquisas p 
					inner join pesquisas_fila pf on p.idpesquisa = pf.idpesquisa
				  where 
					p.situacao = 2 and 
					p.ativo = 'S' and 
					pf.ativo = 'S' and 
					pf.enviado = 'S' and
					pf.enviar_email = 'S' and
					pf.idpesquisa = '".$idpesquisa."' and
					pf.data_resposta IS NULL
				  order by
				  	pf.data_cad, pf.idpesquisa_pessoa ";
	    $this->limite = -1;
	    $this->ordem_campo = false;
	    $this->ordem = false;
	    $fila = $this->retornarLinhas();
		
		mysql_query("START TRANSACTION");
		foreach($fila as $linha) {
			$sql_hist = "insert into pesquisas_fila_reenvio_historico set idpesquisa_pessoa = '".$linha['idpesquisa_pessoa']."', data = '".$linha['data_envio']."' ";
			$hist = $this->executaSql($sql_hist);
			$sql_env = "update pesquisas_fila set enviado = 'N' where idpesquisa_pessoa = '".$linha['idpesquisa_pessoa']."' and enviar_email = 'S' ";
			$env = $this->executaSql($sql_env);
			
			if (!$hist || !$env)
				$erro = true;
		}

		$sql_pesq = "update pesquisas set situacao = '1', total_reenvio = (total_reenvio + 1) where idpesquisa = '".$idpesquisa."'";
		$pesq = $this->executaSql($sql_pesq);
		if(!$pesq)
			$erro = true;
		
		if(!$erro) {			
		   $this->monitora_oque = 32;
		   $this->monitora_onde = 128;
		   $this->monitora_qual = $idpesquisa;
		   $this->Monitora();
		   mysql_query("COMMIT");
		   $info['sucesso'] = true;
		} else {
		   mysql_query("ROLLBACK");
		   $info['sucesso'] = false;
		}
		
		return $info;		
	}
	
	function AssociarPergunta() {

		if($this->post["pergunta"]) {
		
			$this->sql = "select idpesquisa_pergunta from pesquisas_perguntas where idpesquisa = '".$this->id."' and idpergunta = '".$this->val."' and ativo = 'N'";
			$this->linha = $this->retornarLinha($this->sql);
			
			if(!$this->linha){
				$this->sql = "insert into pesquisas_perguntas
							  (ativo, data_cad, idpesquisa, idpergunta)
							  values
							  ('S',now(),'".$this->id."','".$this->post["pergunta"]."')";

				$this->query = $this->executaSql($this->sql);
				
				
				if($this->query){
					$this->retorno["sucesso"] = true;
					$this->monitora_oque = 1;
					$this->monitora_onde = 130;
					$this->monitora_qual = mysql_insert_id();
					$this->Monitora();
				} else {
					$this->retorno["erro"] = true;
					$this->retorno["erros"][] = $this->sql;
					$this->retorno["erros"][] = mysql_error();
				}
			}else{
				
				$this->sql = "update pesquisas_perguntas set ativo = 'S' where idpesquisa_pergunta = '".$this->linha["idpesquisa_pergunta"]."'";
				$this->query = $this->executaSql($this->sql);

				if($this->query){
					$this->retorno["sucesso"] = true;
					$this->monitora_oque = 1;
					$this->monitora_onde = 130;
					$this->monitora_qual = $this->linha["idpesquisa_pergunta"];
					$this->Monitora();
				} else {
					$this->retorno["erro"] = true;
					$this->retorno["erros"][] = $this->sql;
					$this->retorno["erros"][] = mysql_error();
				}

			}
		}

		return $this->retorno;
	}

	function RemoverPergunta() {
		
		include_once("../includes/validation.php");		
		$regras = array(); // stores the validation rules
		
		//VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
		if(!$this->post["remover"])
			$regras[] = "required,remover,remover_vazio";
		
		//VALIDANDO FORMULÃRIO
		$erros = validateFields($this->post, $regras);

		//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{
			$this->sql = "update pesquisas_perguntas set ativo = 'N' where idpesquisa_pergunta = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 130;
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
	
	/* FIM */
	
	/* METODOS PARA ASSOCIAR AS PESSOAS*/
	
	function ListarPessoasAss() {		
		$this->sql = "(SELECT ".$this->campos." FROM
						    pesquisas_fila pp
						    INNER JOIN pessoas per ON (pp.idpessoa=per.idpessoa)
						where pp.ativo='S' and pp.idpesquisa = ".intval($this->id).")";
		
		$this->groupby = "pp.idpesquisa_pessoa";
		return $this->retornarLinhas();
	}
	
	function BuscarPessoa() {
		
		$this->sql = "select 
						p.idpessoa as 'key', p.nome as value 
					  from
						pessoas p
					  where 
					  	(p.nome like '%".$this->get["tag"]."%') AND p.ativo = 'S' AND p.ativo_painel = 'S' AND
					  NOT EXISTS (SELECT pp.idpessoa FROM pesquisas_fila pp WHERE pp.idpessoa = p.idpessoa AND ativo = 'S' AND pp.idpesquisa = '".$this->id."')";
		
		$this->limite = -1;
		$this->ordem_campo = "p.nome";
		$this->groupby = "pp.idpessoa";
		$dados = $this->retornarLinhas();
						
		return json_encode($dados);
		
	}
	
	function AssociarPessoas() {

		foreach($this->post["pessoas"] as $this->ind => $this->val) {

			$this->sql = "select idpesquisa_pessoa from pesquisas_fila where idpesquisa = '".$this->id."' and idpessoa = '".$this->val."' ";
			$this->linha = $this->retornarLinha($this->sql);

			if(!$this->linha){ 
				$this->sql = "insert into pesquisas_fila
							  (ativo, data_cad, idpesquisa, idpessoa)
							  values
							  ('S',now(),'".$this->id."','".$this->val."')";

				$this->query = $this->executaSql($this->sql);
				$this->monitora_qual = mysql_insert_id();
				
				$this->sql = "update pesquisas_fila set hash = '".md5($this->monitora_qual)."' where idpesquisa_pessoa = ".$this->monitora_qual;
				$this->query = $this->executaSql($this->sql);
				
				$sql_enviado = "update pesquisas set situacao = '1' where idpesquisa = '".$this->id."' ";
				$resultado_enviado = $this->executaSql($sql_enviado);
				
				if($this->query){
					$this->retorno["sucesso"] = true;
					$this->monitora_oque = 1;
					$this->monitora_onde = 129;
					$this->Monitora();
				} else {
					$this->retorno["erro"] = true;
					$this->retorno["erros"][] = $this->sql;
					$this->retorno["erros"][] = mysql_error();
				}
			}else{
				$this->sql = "update pesquisas_fila set ativo = 'S' where idpesquisa_pessoa = '".$this->linha["idpesquisa_pessoa"]."'";
				$this->query = $this->executaSql($this->sql);

				if($this->query){
					$this->retorno["sucesso"] = true;
					$this->monitora_oque = 2;
					$this->monitora_onde = 129;
					$this->monitora_qual = $this->linha["idpesquisa_pessoa"];
					$this->Monitora();
				} else {
					$this->retorno["erro"] = true;
					$this->retorno["erros"][] = $this->sql;
					$this->retorno["erros"][] = mysql_error();
				}

			}
		}

		return $this->retorno;
	}

	function RemoverPessoas() {
		
		include_once("../includes/validation.php");		
		$regras = array(); // stores the validation rules
		
		//VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
		if(!$this->post["remover"])
			$regras[] = "required,remover,remover_vazio";
		
		//VALIDANDO FORMULARIO
		$erros = validateFields($this->post, $regras);

		//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
		if(!empty($erros)){
			$this->retorno["erro"] = true;
			$this->retorno["erros"] = $erros;
		}else{
			$this->sql = "update pesquisas_fila set ativo = 'N' where idpesquisa_pessoa = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 129;
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
	
	/* FIM */

	/* METODO PARA CADASTRAR O LAYOUT*/
	
	function ListarImagens() {		
		$this->sql = "(SELECT ".$this->campos." FROM
						    pesquisas_imagens
						where ativo='S' and idpesquisa = ".intval($this->id).")";
		
		$this->groupby = "idpesquisa_imagem";
		return $this->retornarLinhas();
	}
	
	function CadastrarLayout() {

			$this->sql = "SELECT layout FROM pesquisas WHERE idpesquisa = '".$this->id."'";
			$this->linha_antiga = $this->retornarLinha($this->sql);

			$this->sql = "update pesquisas set layout = '".$this->post["layout"]."' where idpesquisa = '".$this->id."'";
			$this->query = $this->executaSql($this->sql);
			
			$this->sql = "SELECT layout FROM pesquisas WHERE idpesquisa = '".$this->id."'";
			$this->linha_nova = $this->retornarLinha($this->sql);
			
			if($this->query){
				$this->retorno["sucesso"] = true;
				if($this->linha_antiga){
					$this->monitora_oque = 2;
					$this->monitora_dadosantigos = $this->linha_antiga;
					$this->monitora_dadosnovos = $this->linha_nova;
				}else{
					$this->monitora_oque = 1;	
				}
				$this->monitora_qual = $this->id;
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}

		return $this->retorno;
	}
	
	/* FIM */
	
	/* METODO PARA CADASTRAR AS IMAGENS*/
	
	function uploadFile($file, $campoAux){
		$extensao = strtolower(strrchr($file["name"], "."));
		$nome_servidor = date("YmdHis")."_".uniqid().$extensao;
		
		if(move_uploaded_file($file["tmp_name"],$_SERVER["DOCUMENT_ROOT"]."/storage/".$campoAux["pasta"]."/".$nome_servidor)) {
			return $nome_servidor;
		} else 
			return false;
	}
	
	function CadastrarImagens($erros = NULL) { 
		$permissoes = 'jpg|jpeg|gif|png|bmp';
		$campo = array("pasta" => "pesquisas_imagens");
		foreach ($this->files['arquivos']['name'] as $ind => $arquivo)
			if ($arquivo != "") $setado = true;
			if ($setado) {
				//VALIDA
				foreach ($this->files['arquivos']['name'] as $ind => $arquivo) { 				
					$file['name'] = $this->files['arquivos']['name'][$ind];
					$file['tmp_name'] = $this->files['arquivos']['tmp_name'][$ind];
					$file['size'] = $this->files['arquivos']['size'][$ind];
		  
					unset($nome_servidor);
					   
					$file_aux['name'] = $file;
					$validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
					if($validacao_tamanho) {
						$this->retorno["erro"] = true;
						$this->retorno["erros"][] = $validacao_tamanho;
						return $this->retorno;
					}
				}
				
				//INSERE
				foreach ($this->files['arquivos']['name'] as $ind => $arquivo) { 
					
					$file['name'] 	  = $this->files['arquivos']['name'][$ind];
					$file['tmp_name'] = $this->files['arquivos']['tmp_name'][$ind];
					$file['size'] = $this->files['arquivos']['size'][$ind];
		  
					unset($nome_servidor);
					   
					$file_aux['name'] = $file;
					$validacao_tamanho = $this->ValidarArquivo($file_aux['name']);
					if($validacao_tamanho) {
						$this->retorno["erro"] = true;
						$this->retorno["erros"][] = $validacao_tamanho;
						return $this->retorno;
					}
								
					$nome_servidor = $this->uploadFile($file, $campo);				
	
					if($nome_servidor) {
						$sql = "insert into pesquisas_imagens set 
							  idpesquisa = '".$this->id."',
							  ativo = 'S',
							  data_cad = NOW(),
							  nome = '".$this->files['arquivos']['name'][$ind]."',
							  tipo = '".$this->files['arquivos']['type'][$ind]."',
							  tamanho = '".$this->files['arquivos']['size'][$ind]."',
							  servidor = '".$nome_servidor."' ";
						$query_arquivo = $this->executaSql($sql);
						$idarquivo = mysql_insert_id();
						if (!$query_arquivo) { 
							$erro = true;
						} else {
							$this->retorno["sucesso"] = true;
							$this->monitora_oque = 1;
							$this->monitora_onde = 131;
							$this->monitora_qual = $idarquivo;
							$this->Monitora();
						}
					}
				}
			//
			
		} else {
			$this->retorno["erro"] = true;
			$this->retorno["erros"][] = 'sem_arquivos';
		}
		return $this->retorno;
		
	}
	
	function RetornaImagens() { 
		$this->sql = "SELECT * FROM pesquisas_imagens 
					WHERE ativo = 'S' AND idpesquisa = ".$this->id;
		$this->limite = -1;
		$this->ordem = "asc";
		$this->ordem_campo = "idpesquisa_imagem";
		$this->groupby = "idpesquisa_imagem";
		$dados = $this->retornarLinhas();
	
		return $dados;
	}
	
	function RemoverImagens() { 
		$this->sql = "UPDATE pesquisas_imagens SET ativo='N' 
					WHERE idpesquisa_imagem = ".$this->id;
		$dados = $this->executaSql($this->sql);
		
		if ($dados) {
			$this->retorno["sucesso"] = true;
			$this->monitora_oque = 3;
			$this->monitora_onde = 131;
			$this->monitora_qual = $this->id;
			$this->Monitora();
		}
	
		return $this->retorno;
	}
	
	function RetornarImagemDownload() { 
		$this->sql = "SELECT * FROM pesquisas_imagens 
					  where 
					  	idpesquisa_imagem = ".$this->id;	
		$retorno = $this->retornarLinha($this->sql);
	
		return $retorno;
	}

	/* FIM */
	
	/* METODOS PARA MOSTRAR O PREVIEW DA PESQUISA */
	
	function RetornarPreviewPesquisa($responder = NULL) { 
		$dadosArray = array();
		$this->sql = "SELECT nome, layout FROM pesquisas WHERE idpesquisa = ".$this->id;	
		$retorno = $this->retornarLinha($this->sql);
		
		$variavel = explode("[[P][",$retorno["layout"]);		
		
		if($variavel){ 
			foreach($variavel as $ind => $val){			
				$id = explode("]]",$val);				
				if (is_numeric($id[0])) {
					$this->sql = "SELECT ativo FROM pesquisas_perguntas WHERE idpesquisa = '".$this->id."' AND idpergunta = '".intval($id[0])."'  ";
					$linha = $this->retornarLinha($this->sql);
					if ($linha['ativo'] == 'S')
						$indice[] = $id[0];
					else
						$indice_remover[] = $id[0];
				}
			}			
			//unset($indice[array_search("", $indice)]);
			
			foreach($indice_remover as $val) {
				$retorno["layout"] = str_replace("[[P][".$val."]]", "", $retorno["layout"]);
			}

			$i= 0;
			foreach($indice as $ind => $val){
				$i++;
				$this->sql = "SELECT p.* FROM  perguntas_pesquisas p INNER JOIN pesquisas_perguntas pp ON (pp.idpergunta=p.idpergunta) WHERE pp.idpesquisa = ".$this->id." AND pp.idpergunta = ".intval($val)."  ";
				$linha = $this->retornarLinha($this->sql);

				$this->sql  = "SELECT * FROM  pesquisas_perguntas_opcoes WHERE idpergunta = ".$linha["idpergunta"]." AND ativo_painel = 'S'";
				$this->ordem_campo = "numero";
				$this->ordem = "ASC";
				$this->groupby = "idopcao";
				$dados = $this->retornarLinhas();

				$pergunta  = "<div>								
							  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
				$pergunta .= "<tr>";
				$pergunta .= "<td colspan=".$this->total."><div style=\"margin-left:".$linha["espacamento_esquerda"]."px\">".$i.") ".$linha['nome']."</div></td>";
				$pergunta .= "</tr>";
				
				if($linha["tipo"] == "O"){				
					$colunas = 0;
					$horizontal = 0;						
					foreach($dados as $op => $opcoes){
						++$horizontal;
						if(intval($linha["quantidade_colunas"]) == 0){
							if($linha["sentido"] == "H"){
								if($horizontal == 0){
									$pergunta .= "<tr>";
								}
								if($linha["multipla_escolha"] == "S"){
									$pergunta .= "<td valign=\"baseline\">";
									$pergunta .= "<label><input type=\"checkbox\" name=\"opcoes_multipla[".$val."][".$opcoes["idopcao"]."]\" value=\"".$opcoes["numero"]."\" id=\"opcoes".$val.$opcoes["idopcao"]."\" />&nbsp".$opcoes["titulo"]."</label>";
									$pergunta .= "</td>";
								}else{
									$pergunta .= "<td>";
									$pergunta .= "<label><input type=\"radio\" name=\"opcoes_unica[".$val."]\" value=\"".$opcoes["idopcao"]."\" id=\"opcoes".$val.$opcoes["idopcao"]."\" />&nbsp".$opcoes["titulo"]."</label>";//[".$opcoes["idopcao"]."]
									$pergunta .= "</td>";
								}
								if($horizontal == $this->total){
									$pergunta .= "</tr>";
								}
							}else{
								if($linha["multipla_escolha"] == "S"){
									$pergunta .= "<tr>";
									$pergunta .= "<td valign=\"baseline\">";
									$pergunta .= "<label><input type=\"checkbox\" name=\"opcoes_multipla[".$val."][".$opcoes["idopcao"]."]\" value=\"".$opcoes["numero"]."\" id=\"opcoes".$val.$opcoes["idopcao"]."\" />&nbsp".$opcoes["titulo"]."</label>";
									$pergunta .= "</td>";
									$pergunta .= "</tr>";
								}else{
									$pergunta .= "<tr>";
									$pergunta .= "<td valign=\"baseline\">";
									$pergunta .= "<label><input type=\"radio\" name=\"opcoes_unica[".$val."]\" value=\"".$opcoes["idopcao"]."\" id=\"opcoes".$val.$opcoes["idopcao"]."\" />&nbsp".$opcoes["titulo"]."</label>";//[".$opcoes["idopcao"]."]
									$pergunta .= "</td>";
									$pergunta .= "</tr>";
								}
							}
						}else{
							++$colunas;
							if($colunas == 0){
								$pergunta .= "<tr>";
							}
							if($linha["sentido"] == "H"){
								if($horizontal == 0){
									$pergunta .= "<tr>";
								}
								if($linha["multipla_escolha"] == "S"){
									$pergunta .= "<td valign=\"baseline\">";
									$pergunta .= "<label><input type=\"checkbox\" name=\"opcoes_multipla[".$val."][".$opcoes["idopcao"]."]\" value=\"".$opcoes["numero"]."\" id=\"opcoes".$val.$opcoes["idopcao"]."\" />&nbsp".$opcoes["titulo"]."</label>";
									$pergunta .= "</td>";
								}else{
									$pergunta .= "<td valign=\"baseline\">";
									$pergunta .= "<label><input type=\"radio\" name=\"opcoes_unica[".$val."]\" value=\"".$opcoes["idopcao"]."\" id=\"opcoes".$val.$opcoes["idopcao"]."\" />&nbsp".$opcoes["titulo"]."</label>";//[".$opcoes["idopcao"]."]
									$pergunta .= "</td>";
								}
								if($horizontal == $this->total){
									$pergunta .= "</tr>";
								}
							}else{
								if($linha["multipla_escolha"] == "S"){
									$pergunta .= "<tr>";
									$pergunta .= "<td valign=\"baseline\">";
									$pergunta .= "<label><input type=\"checkbox\" name=\"opcoes_multipla[".$val."][".$opcoes["idopcao"]."]\" value=\"".$opcoes["numero"]."\" id=\"opcoes".$val.$opcoes["idopcao"]."\" />&nbsp".$opcoes["titulo"]."</label>";
									$pergunta .= "</td>";
									$pergunta .= "</tr>";
								}else{
									$pergunta .= "<tr>";
									$pergunta .= "<td valign=\"baseline\">";
									$pergunta .= "<label><input type=\"radio\" name=\"opcoes_unica[".$val."]\" value=\"".$opcoes["idopcao"]."\" id=\"opcoes".$val.$opcoes["idopcao"]."\" />&nbsp".$opcoes["titulo"]."</label>";//[".$opcoes["idopcao"]."]
									$pergunta .= "</td>";
									$pergunta .= "</tr>";
								}
							}
							
							if($colunas == intval($linha["quantidade_colunas"])){
								$pergunta .= "</tr>";
								$colunas = 0;
							}
							
							if($horizontal == $this->total){
								$pergunta .= "</tr>";
							}

						}
					}
				}else{
					$pergunta .= "<tr>";
					$pergunta .= "<td>";
					$pergunta .= "<textarea name=\"resposta[".$val."]\" id=\"resposta".$val."\" rows=\"4\" style=\"width:100%\"></textarea>";
					$pergunta .= "</tr>";
					$pergunta .= "</td>";
				}
				
				$pergunta .= "</table></div><br>";
				
				$retorno["layout"] = str_replace("[[P][".$val."]]", $pergunta, $retorno["layout"]);
			}
			
		}
		//echo $retorno["layout"];
		//exit;
		unset($indice);
		
		$variavel = explode("[[I][",$retorno["layout"]);
		if($variavel){
			foreach($variavel as $ind => $val){
				$id = explode("]]",$val);
				$indice[] = $id[0];
			}
			
			unset($indice[array_search("", $indice)]);
			
			foreach($indice as $ind => $val){
				$this->sql = "SELECT idpesquisa_imagem, servidor FROM pesquisas_imagens WHERE ativo = 'S' AND idpesquisa = ".$this->id." AND idpesquisa_imagem = ".intval($val)."";
				$linha = $this->retornarLinha($this->sql);
				$retorno["layout"] = str_replace("[[I][".$val."]]", "<div style=\"text-align:left; width:800px; text-align:center\"><img src=\"http://".$_SERVER["SERVER_NAME"]."/storage/pesquisas_imagens/".$linha["servidor"]."\" border=\"0\" /></div>", $retorno["layout"]);
			}
		}
		
		if ($responder) {
		  $resposta_campos = "<input type='hidden' name='idpesquisa' value='".$this->id."' />
							  <input type='hidden' name='act' value='responder_pesquisa' />
							  <input type='hidden' name='idpesquisa_pessoa' value='".$this->url[5]."' />	
							  <input type='hidden' name='hash' value='".$this->url[6]."' />
							  <input type='submit' name='responder' value='Responder' class='btn btn-primary' />
							  ";
		  if($this->url[7] == "gestor") {
			$resposta_campos .= "<input type='hidden' name='gestor_usu' value='".$_SESSION['adm_idusuario']."' />";
		  }
		  //$pergunta_nome = '<h3>'.$retorno['nome'].'</h3><br />';
		}
		$retorno["layout"] = "<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" name=\"form\" class=\"form-inline\">"./*$pergunta_nome.*/$retorno["layout"].$resposta_campos."</form>";
		
		return $retorno;
	}
	
	//LISTAR PESQUISA RESPONDIDA
	function RetornarPreviewPesquisaRespondida($idpesquisa_pessoa) {
		$dadosArray = array();
		$this->sql = "SELECT nome, layout FROM pesquisas WHERE idpesquisa = ".$this->id;	
		$retorno = $this->retornarLinha($this->sql);
		
		$respostasUsuario = $this->listarRespostasUsuario($idpesquisa_pessoa);
		$variavel = explode("[[P][",$retorno["layout"]);		
		
		if($variavel){
			foreach($variavel as $ind => $val){
				$id = explode("]]",$val);
				if (is_numeric($id[0])) {
					$this->sql = "SELECT ativo FROM pesquisas_perguntas WHERE idpesquisa = '".$this->id."' AND idpergunta = '".intval($id[0])."'  ";
					$linha = $this->retornarLinha($this->sql);
					if ($linha['ativo'] == 'S')
						$indice[] = $id[0];
					else
						$indice_remover[] = $id[0];
				}
			}			
			//unset($indice[array_search("", $indice)]);
			
			foreach($indice_remover as $val) {
				$retorno["layout"] = str_replace("[[P][".$val."]]", "", $retorno["layout"]);
			}
			
			$i= 0;
			foreach($indice as $ind => $val){
				$i++;
				$this->sql = "SELECT p.* FROM perguntas_pesquisas p INNER JOIN pesquisas_perguntas pp ON (pp.idpergunta=p.idpergunta) WHERE pp.idpesquisa = ".$this->id." AND pp.idpergunta = ".intval($val)."  ";
				$linha = $this->retornarLinha($this->sql);

				$this->sql  = "SELECT * FROM  pesquisas_perguntas_opcoes WHERE idpergunta = ".$linha["idpergunta"];
				$this->ordem_campo = "numero";
				$this->ordem = "ASC";
				$this->groupby = "idopcao";
				$dados = $this->retornarLinhas();

				$pergunta  = "<div>								
							  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
				$pergunta .= "<tr>";
				$pergunta .= "<td colspan=".$this->total."><div style=\"margin-left:".$linha["espacamento_esquerda"]."px\">".$i.") ".$linha['nome']."</div></td>";
				$pergunta .= "</tr>";
				
				if($linha["tipo"] == "O"){				
					$colunas = 0;
					$horizontal = 0;						
					foreach($dados as $op => $opcoes){
						$checked = "";
						if(in_array($opcoes["idopcao"],$respostasUsuario[$val])) {
							$checked = "checked=\"checked\"";
						}
						++$horizontal;
						if(intval($linha["quantidade_colunas"]) == 0){
							if($linha["sentido"] == "H"){
								if($horizontal == 0){
									$pergunta .= "<tr>";
								}
								if($linha["multipla_escolha"] == "S"){
									$pergunta .= "<td valign=\"baseline\">";
									$pergunta .= "<label><input type=\"checkbox\" disabled=\"disabled\" ".$checked." name=\"opcoes_multipla[".$val."][".$opcoes["idopcao"]."]\" value=\"".$opcoes["numero"]."\" id=\"opcoes".$val.$opcoes["idopcao"]."\" />&nbsp".$opcoes["titulo"]."</label>";
									$pergunta .= "</td>";
								}else{
									$pergunta .= "<td>";
									$pergunta .= "<label><input type=\"radio\" disabled=\"disabled\" ".$checked." name=\"opcoes_unica[".$val."]\" value=\"".$opcoes["idopcao"]."\" id=\"opcoes".$val.$opcoes["idopcao"]."\" />&nbsp".$opcoes["titulo"]."</label>";//[".$opcoes["idopcao"]."]
									$pergunta .= "</td>";
								}
								if($horizontal == $this->total){
									$pergunta .= "</tr>";
								}
							}else{
								if($linha["multipla_escolha"] == "S"){
									$pergunta .= "<tr>";
									$pergunta .= "<td valign=\"baseline\">";
									$pergunta .= "<label><input type=\"checkbox\" disabled=\"disabled\" ".$checked." name=\"opcoes_multipla[".$val."][".$opcoes["idopcao"]."]\" value=\"".$opcoes["numero"]."\" id=\"opcoes".$val.$opcoes["idopcao"]."\" />&nbsp".$opcoes["titulo"]."</label>";
									$pergunta .= "</td>";
									$pergunta .= "</tr>";
								}else{
									$pergunta .= "<tr>";
									$pergunta .= "<td valign=\"baseline\">";
									$pergunta .= "<label><input type=\"radio\" disabled=\"disabled\" ".$checked." name=\"opcoes_unica[".$val."]\" value=\"".$opcoes["idopcao"]."\" id=\"opcoes".$val.$opcoes["idopcao"]."\" />&nbsp".$opcoes["titulo"]."</label>";//[".$opcoes["idopcao"]."]
									$pergunta .= "</td>";
									$pergunta .= "</tr>";
								}
							}
						}else{
							++$colunas;
							if($colunas == 0){
								$pergunta .= "<tr>";
							}
							if($linha["sentido"] == "H"){
								if($horizontal == 0){
									$pergunta .= "<tr>";
								}
								if($linha["multipla_escolha"] == "S"){
									$pergunta .= "<td valign=\"baseline\">";
									$pergunta .= "<label><input type=\"checkbox\" disabled=\"disabled\" ".$checked." name=\"opcoes_multipla[".$val."][".$opcoes["idopcao"]."]\" value=\"".$opcoes["numero"]."\" id=\"opcoes".$val.$opcoes["idopcao"]."\" />&nbsp".$opcoes["titulo"]."</label>";
									$pergunta .= "</td>";
								}else{
									$pergunta .= "<td valign=\"baseline\">";
									$pergunta .= "<label><input type=\"radio\" disabled=\"disabled\" ".$checked." name=\"opcoes_unica[".$val."]\" value=\"".$opcoes["idopcao"]."\" id=\"opcoes".$val.$opcoes["idopcao"]."\" />&nbsp".$opcoes["titulo"]."</label>";//[".$opcoes["idopcao"]."]
									$pergunta .= "</td>";
								}
								if($horizontal == $this->total){
									$pergunta .= "</tr>";
								}
							}else{
								if($linha["multipla_escolha"] == "S"){
									$pergunta .= "<tr>";
									$pergunta .= "<td valign=\"baseline\">";
									$pergunta .= "<label><input type=\"checkbox\" disabled=\"disabled\" ".$checked." name=\"opcoes_multipla[".$val."][".$opcoes["idopcao"]."]\" value=\"".$opcoes["numero"]."\" id=\"opcoes".$val.$opcoes["idopcao"]."\" />&nbsp".$opcoes["titulo"]."</label>";
									$pergunta .= "</td>";
									$pergunta .= "</tr>";
								}else{
									$pergunta .= "<tr>";
									$pergunta .= "<td valign=\"baseline\">";
									$pergunta .= "<label><input type=\"radio\" disabled=\"disabled\" ".$checked." name=\"opcoes_unica[".$val."]\" value=\"".$opcoes["idopcao"]."\" id=\"opcoes".$val.$opcoes["idopcao"]."\" />&nbsp".$opcoes["titulo"]."</label>";//[".$opcoes["idopcao"]."]
									$pergunta .= "</td>";
									$pergunta .= "</tr>";
								}
							}
							
							if($colunas == intval($linha["quantidade_colunas"])){
								$pergunta .= "</tr>";
								$colunas = 0;
							}
							
							if($horizontal == $this->total){
								$pergunta .= "</tr>";
							}

						}
					}
				}else{
					$pergunta .= "<tr>";
					$pergunta .= "<td>";
					$pergunta .= "<textarea name=\"resposta[".$val."]\" disabled=\"disabled\" id=\"resposta".$val."\" rows=\"4\" style=\"width:100%\">".$respostasUsuario[$val][0]."</textarea>";
					$pergunta .= "</tr>";
					$pergunta .= "</td>";
				}
				
				$pergunta .= "</table></div><br>";
				
				$retorno["layout"] = str_replace("[[P][".$val."]]", $pergunta, $retorno["layout"]);
			}
			
		}
		//echo $retorno["layout"];
		//exit;
		unset($indice);
		
		$variavel = explode("[[I][",$retorno["layout"]);
		if($variavel){
			foreach($variavel as $ind => $val){
				$id = explode("]]",$val);
				$indice[] = $id[0];
			}
			
			unset($indice[array_search("", $indice)]);
			
			foreach($indice as $ind => $val){
				$this->sql = "SELECT idpesquisa_imagem, servidor FROM pesquisas_imagens WHERE ativo = 'S' AND idpesquisa = ".$this->id." AND idpesquisa_imagem = ".intval($val)."";
				$linha = $this->retornarLinha($this->sql);
				$retorno["layout"] = str_replace("[[I][".$val."]]", "<div style=\"text-align:left; width:800px; text-align:center\"><img src=\"http://".$_SERVER["SERVER_NAME"]."/storage/pesquisas_imagens/".$linha["servidor"]."\" border=\"0\" /></div>", $retorno["layout"]);
			}
		}
		
		$retorno["layout"] = "<form action=\"\" method=\"get\" enctype=\"multipart/form-data\" name=\"form\" class=\"form-inline\">".$retorno["layout"]."</form>";
		
		return $retorno;
	}
	
	function associarPessoasEmBloco($idempreendimento, $situacao) {//VERIFICAR 
		if ($idempreendimento || $situacao) {
			$this->sql = "select r.idpessoa 
								from reservas r
								inner join empreendimentos_unidades u on r.idunidade = u.idunidade 
								inner join empreendimentos_blocos b on u.idbloco = b.idbloco 
								inner join empreendimentos_etapas e on b.idetapa = e.idetapa
								inner join empreendimentos emp on e.idempreendimento = emp.idempreendimento  
								where ";
								if ($idempreendimento)
									$this->sql .= " emp.idempreendimento = $idempreendimento and ";																
			$this->sql .= " r.ativo = 'S' and u.ativo = 'S' and b.ativo = 'S' and e.ativo = 'S' and emp.ativo = 'S' 
							group by r.idpessoa";		
			$this->limite = -1;
			$this->ordem = "asc";
			$this->ordem_campo = "r.idpessoa";
			$dados = $this->retornarLinhas();
			
			include_once("pessoas.class.php");
			$pessoaObj = new Pessoas();		
			foreach ($dados as $dado) {
				$sit = $pessoaObj->retornaSituacaoPessoa($dado['idpessoa']);
				if ($situacao) {
					if ($situacao == $sit)
						$novo_dados[] = $dado['idpessoa'];					
				} else				
					$novo_dados[] = $dado['idpessoa'];
			}
			$this->post["pessoas"] = $novo_dados;
			if ($novo_dados)
				return $this->AssociarPessoas();				
			
		}
	}
	
	function listarPerguntas() {
		
		$this->sql = "select 
						p.idpergunta, p.nome
					  from
						perguntas_pesquisas p
					  where 
					  	p.ativo = 'S' AND p.ativo_painel = 'S' AND
					  NOT EXISTS (SELECT pp.idpergunta FROM pesquisas_perguntas pp WHERE pp.idpergunta = p.idpergunta AND pp.ativo = 'S' AND pp.idpesquisa = '".$this->id."')";
		
		$this->limite = -1;
		$this->ordem_campo = "p.nome";
		$this->groupby = "pp.idpergunta";
		$dados = $this->retornarLinhas();
						
		return $dados;
		
	}
	
	function clonarPesquisa() {
		$pesquisaPai = $this->Retornar();
	
		//COPIAR PESQUISA
		$this->sql = "	insert into pesquisas (ativo,ativo_painel,data_cad,nome,descricao,corpo_email,layout,de,ate,situacao,idpesquisa_pai) values('".$pesquisaPai['ativo']."', '".$pesquisaPai['ativo_painel']."', NOW(), '".$pesquisaPai['nome']."', '".$pesquisaPai['descricao']."', '".$pesquisaPai['corpo_email']."', '".$pesquisaPai['layout']."', '".$pesquisaPai['de']."', '".$pesquisaPai['ate']."', '0', ".$pesquisaPai['idpesquisa'].")";
		$this->query = mysql_query($this->sql) or die('Não inseriu pesquisa'.mysql_error());
		$idNovaPesquisa = mysql_insert_id();

		// -----------
	  if ($idNovaPesquisa) {	
		  //COPIAR PERGUNTAS
		  $perguntasPai = $this->ListarPerguntasAss();
		  if(is_array($perguntasPai)) {
			  foreach($perguntasPai as $id => $perguntaPai){
				  $this->sql = "insert into pesquisas_perguntas values(NULL, '".$perguntaPai['ativo']."', '".$perguntaPai['data_cad']."', ".$idNovaPesquisa.", ".$perguntaPai['idpergunta'].")";
				  $this->query = $this->executaSql($this->sql);				
			  }
		  }
		  // -----------
		  
		  
		  //COPIAR IMAGENS
		  $imagensPai = $this->RetornaImagens();
		  if(is_array($imagensPai)) {
			  $arrayImagens = array();
			  foreach($imagensPai as $id => $imagem){
				  $this->sql = "insert into pesquisas_imagens values(NULL, '".$imagem['ativo']."', '".$imagem['data_cad']."', ".$idNovaPesquisa.", '".$imagem['nome']."', '".$imagem['servidor']."', '".$imagem['tipo']."', ".$imagem['tamanho'].")";
				  $this->query = $this->executaSql($this->sql);
				  if ($this->query) $arrayImagens[] = $imagem['servidor'];
			  }
			  //COPIAR AS IMAGENS
			  if (is_array($arrayImagens)) {
				  foreach($arrayImagens as $id => $imagem){
					  copy($_SERVER["DOCUMENT_ROOT"]."/storage/pesquisas_imagens/".$imagem, $_SERVER["DOCUMENT_ROOT"]."/storage/pesquisas_imagens_clone/".$imagem);
				  }
			  }
		  }
		  // -----------
		    $this->monitora_onde = 128; //PESQUISAS
			
			$this->monitora_oque = 4;
			$this->monitora_qual = $pesquisaPai['idpesquisa'];
			$this->Monitora();
			
			$this->monitora_oque = 1;
			$this->monitora_qual = $idNovaPesquisa;
			$this->Monitora();		  
		  
		  return $idNovaPesquisa;
	  }
	  return false;
		
	}
	
	function listarFilaPesquisa($idpesquisa) {		
	  $this->sql = "select ".$this->campos.", p.nome AS nomePessoa, p.email AS pessoaEmail, 
	  u.nome AS usuAdm, u.email AS admEmail, pr.nome AS nomeProfessor, pr.email AS professorEmail, resp.nome AS pessoaMatricula, 
	  resp.email AS emailPessoaMatricula, u.idusuario as idusuario_adm, pr.idprofessor, p.idpessoa, pf.enviar_email as enviarEmail
	                from 
					  pesquisas_fila pf
					  left outer join usuarios_adm u ON(pf.idusuario_gestor = u.idusuario)
					  left outer join pessoas p ON(p.idpessoa = pf.idpessoa)					 
					  left outer join professores pr ON(pr.idprofessor = pf.idprofessor)
					  left outer join matriculas mat ON(mat.idmatricula = pf.idmatricula)
                      left outer join pessoas resp ON(resp.idpessoa = mat.idpessoa)
					where
					  pf.ativo = 'S' and 
					  pf.idpesquisa = ".$idpesquisa;
			
	  if(is_array($_GET["q"])) {
		foreach($_GET["q"] as $campo => $valor) {
		  $campo = explode("|",$campo);
		  $valor = str_replace("'","",$valor);
		  if(($valor || $valor === "0") && $valor <> "todos") {
			if($campo[0] == 1) {
			  $this->sql .= " and ".$campo[1]." = '".$valor."' ";
			} elseif($campo[0] == 2)  {
			  $busca = str_replace("\\'","",$valor);
			  $busca = str_replace("\\","",$busca);
			  $busca = explode(" ",$busca);
			  foreach($busca as $ind => $buscar){
				$this->sql .= " and pf.".$campo[1]." like '%".urldecode($buscar)."%' ";
			  }
			} elseif($campo[0] == 3)  {
				$this->sql .= " and date_format(".$campo[1].",'%d/%m/%Y') = '".$valor."' ";
			} 
		  } 
		}
	  }
	  
	  $this->sql .= " order by idpesquisa_pessoa desc";
	  
	  $this->groupby = "pf.idpesquisa";
	  $this->ordem = "desc";
	  $this->ordem_campo = "idpesquisa_pessoa";

	  $this->sqlAux = str_replace($this->campos, "count(".$this->groupby.") as total", $this->sql);
	  $linhaAux = $this->retornarLinha($this->sqlAux);
	  $this->total = intval($linhaAux["total"]);

	  $this->query = $this->executaSql($this->sql);
	  $this->retorno = array();
	  
	  while($linha = mysql_fetch_assoc($this->query)){
		$this->retorno[] = $linha;
	  }

	  return $this->retorno;
  }
  
  function listarFiltros($idpesquisa) {		
	$this->sql = "select pf.*, u.nome from pesquisas_filtros pf inner join usuarios_adm u on (pf.idusuario = u.idusuario) where pf.idpesquisa = ".$idpesquisa;
	$this->query = $this->executaSql($this->sql);
	$this->retorno = array();
	while($linha = mysql_fetch_assoc($this->query)){
	  $this->retorno[] = $linha;
	}
	return $this->retorno;
  }
  
	function listarFilaPesquisaWeb($idpessoa) {		
	  $this->sql = "select ".$this->campos.", pesq.nome as pesquisa, pesq.de, pesq.ate, pf.data_resposta, p.email AS pessoaEmail, 
	  resp.nome AS pessoaMatricula, resp.email AS emailPessoaMatricula, atendp.nome AS pessoaAtend, atendp.email AS emailPessoaAtend, pf.enviar_email as enviarEmail
					from 
					  pesquisas_fila pf
					  left outer join pesquisas pesq ON(pesq.idpesquisa = pf.idpesquisa)
					  left outer join pessoas p ON(p.idpessoa = pf.idpessoa)
					  left outer join matriculas mat ON(mat.idmatricula = pf.idmatricula)
					  left outer join pessoas resp ON(resp.idpessoa = mat.idpessoa)
					  left outer join atendimentos at ON(at.idatendimento = pf.idatendimento)
					  left outer join pessoas atendp ON(atendp.idpessoa = at.idpessoa)
					where
					  pf.ativo = 'S' 
					  AND ( p.idpessoa = $idpessoa OR resp.idpessoa = $idpessoa OR atendp.idpessoa = $idpessoa ) 
					  AND pf.enviado = 'S'
					  AND pesq.ativo_cliente = 'S' ";

	  if(is_array($_GET["q"])) {
		foreach($_GET["q"] as $campo => $valor) {
		  $campo = explode("|",$campo);
		  $valor = str_replace("'","",$valor);
		  if(($valor || $valor === "0") && $valor <> "todos") {
			if($campo[0] == 1) {
			  $this->sql .= " and ".$campo[1]." = '".$valor."' ";
			} elseif($campo[0] == 2)  {
			  $busca = str_replace("\\'","",$valor);
			  $busca = str_replace("\\","",$busca);
			  $busca = explode(" ",$busca);
			  foreach($busca as $ind => $buscar){
				$this->sql .= " and ".$campo[1]." like '%".urldecode($buscar)."%' ";
			  }
			} elseif($campo[0] == 3)  {
				$this->sql .= " and date_format(".$campo[1].",'%d/%m/%Y') = '".$valor."' ";
			}
		  } 
		}
	  }
	  
	  $this->ordem = "asc";
	  $this->groupby = "pf.idpesquisa_pessoa";	  
	  $this->ordem_campo = "idpesquisa_pessoa";
	  
	  $this->sqlAux = str_replace($this->campos, "count(".$this->groupby.") as total", $this->sql);
	  $linhaAux = $this->retornarLinha($this->sqlAux);
	  $this->total = intval($linhaAux["total"]);
	  
	  return $this->retornarLinhas();
	}
	
	function TotalPesquisaSemResposta($idpessoa) {		
	  $this->sql = "select ".$this->campos.", pesq.nome as pesquisa, pesq.de, pesq.ate, p.email AS pessoaEmail, 
	  resp.nome AS pessoaMatricula, 
	  resp.email AS emailPessoaMatricula, atendp.nome AS pessoaAtend, atendp.email AS emailPessoaAtend
					from 
					  pesquisas_fila pf
					  left outer join pesquisas pesq ON(pesq.idpesquisa = pf.idpesquisa)
					  left outer join pessoas p ON(p.idpessoa = pf.idpessoa)
					  left outer join matriculas mat ON(mat.idmatricula = pf.idmatricula)
					  left outer join pessoas resp ON(resp.idpessoa = mat.idpessoa)
					  left outer join atendimentos at ON(at.idatendimento = pf.idatendimento)
					  left outer join pessoas atendp ON(atendp.idpessoa = at.idpessoa)
					where
					  pf.ativo = 'S' 
					  AND ( p.idpessoa = $idpessoa OR resp.idpessoa = $idpessoa OR atendp.idpessoa = $idpessoa ) 
					  AND pf.enviado = 'S'
					  AND pf.data_resposta IS NULL
					  AND pesq.de <= '".date("Y-m-d")."' AND pesq.ate >= '".date("Y-m-d")."'
					  AND pesq.ativo_cliente = 'S'	";	  
	  $this->groupby = "pf.idpesquisa_pessoa";
	  
	  $this->sqlAux = str_replace($this->campos, "count(".$this->groupby.") as total", $this->sql);
	  $linhaAux = $this->retornarLinha($this->sqlAux);
	  return $linhaAux["total"];
	  
	}
	
	/*function listarFilaAddPesquisaUsuarios() { 
	
	unset($this->post["acao"]);
	
	$filtro = array();
	$dados = array();

	foreach($this->post as $campo => $valor) {
	  if(!empty($valor)) {
		if($campo == "data_nasc_dia") {
		  $campo = "date_format(data_nasc, '%d')";
		  $filtro[] = $campo." = '".$valor."'";
		}elseif($campo == "data_nasc_mes") {
		  $campo = "date_format(data_nasc, '%m')";
		  $filtro[] = $campo." = '".$valor."'";
		} elseif($campo == "idregiao") {
		  $idregiao = $valor;
		} else {
			$filtro[] = $campo." = '".$valor."'";
		}
	  }
	}
	
	$this->sql = "SELECT 
					idusuario, 
					idusuario as id,
					nome, 
					email,
					ativo_login
				  FROM 
				  	usuarios_adm
				  WHERE
				  	NOT EXISTS(
					  SELECT 
						usuarios_adm.idusuario
					  FROM
						pesquisas_fila 
					  WHERE 
						idpesquisa = '".$this->id."' AND 
						usuarios_adm.idusuario = pesquisas_fila.idusuario_gestor AND
						pesquisas_fila.ativo = 'S'
					) AND ativo = 'S' ";
												
	if(count($filtro) > 0) {
	  $filtro = join(" AND ",$filtro);
	  $this->sql .= " AND (".$filtro.")";
	}

	$this->limite = -1;
	$this->ordem_campo = "nome";
	$this->ordem = "ASC";
		
	$dados = $this->retornarLinhas();
	$dados["filtro"] = $filtro;
	return $dados;
	
  }*/

  function listarFilaAddPesquisaUsuarios() { 
  unset($this->post["acao"]);
	
	$filtro = array();
	$dados = array();
	foreach($this->post as $campo => $valor) {
	  if(!empty($valor)) {
		if($campo == "data_nasc_dia") {
		  $campo = "date_format(data_nasc, '%d')";
		}elseif($campo == "data_nasc_mes") {
		  $campo = "date_format(data_nasc, '%m')";
		} elseif ($campo == "idsindicato" || $campo == "idescola") {
		  	continue;
		}
		
		if ($campo == 'nome' || $campo == 'documento' || $campo == 'email')
			$filtro[] = $campo." like '%".$valor."%'";
		else
			$filtro[] = $campo." = '".$valor."'";
	  }
	}

	if ($this->post['idescola']) {
		$this->sql = "SELECT p.idsindicato 
						FROM escolas p INNER JOIN sindicatos i
						WHERE p.idescola = '{$this->post['idescola']}'";
		$sindicato = $this->retornarLinha($this->sql);
		$idsindicato = $sindicato['idsindicato'];
	}
	
	$this->sql = "SELECT 
					usuarios_adm.idusuario, 
					usuarios_adm.idusuario as id,
					usuarios_adm.nome,
					usuarios_adm.ativo_login,
					usuarios_adm.email
				  FROM 
				  	usuarios_adm
				  WHERE
				  	NOT EXISTS (
					  SELECT 
						usuarios_adm.idusuario
					  FROM
						pesquisas_fila 
					  WHERE 
						idpesquisa = '".$this->id."' AND 
						usuarios_adm.idusuario = pesquisas_fila.idusuario_gestor AND
						pesquisas_fila.ativo = 'S'
					) AND usuarios_adm.ativo = 'S' ";
		if ($this->post['idsindicato'] || $this->post['idescola']) {
			$this->sql .= " AND (
							  	(SELECT 
										uai.idusuario
							  		FROM
										usuarios_adm_sindicatos uai
									LEFT OUTER JOIN escolas p 
				  						ON p.idsindicato = uai.idsindicato
							  		WHERE 
										uai.idusuario = usuarios_adm.idusuario AND 
						  				uai.ativo = 'S' 
						  				AND (uai.idsindicato = '{$this->post['idsindicato']}' 
						  					OR uai.idsindicato = '{$idsindicato}')";
						if ($this->post['idescola']) {
						 	$this->sql .= " AND p.idescola = '{$this->post['idescola']}'";
						}
						$this->sql .= " LIMIT 1 ) IS NOT NULL 
								OR usuarios_adm.gestor_sindicato = 'S' 
							)";
					}
	if(count($filtro) > 0) {
	  $filtro = join(" AND ",$filtro);
	  $this->sql .= " AND (".$filtro.")";
	}

	//echo $this->sql;exit;
	
	$this->limite = -1;
	$this->ordem_campo = "nome";
	$this->ordem = "ASC";
	$dados = $this->retornarLinhas();
	$dados["filtro"] = $filtro;
	return $dados;

}
  
  /*function listarFilaAddPesquisaProfessores() { 
	
	unset($this->post["acao"]);
	
	$dados = array();
	$filtro = array();
	foreach($this->post as $campo => $valor) {
	  if(!empty($valor)) {
		if($campo == "data_nasc") {
			$campo = "date_format(professores.data_nasc, '%d/%m/%Y')";
			$filtro[] = $campo." = '".$valor."'";
		} elseif($campo == "data_nasc_mes") {
		  $campo = "date_format(professores.data_nasc, '%m')";
		  $filtro[] = $campo." = '".$valor."'";
		} else {
			$filtro[] = $campo." = '".$valor."'";
		}
	  } 
	}
	
	$this->sql = "SELECT 
					idprofessor, 
					idprofessor as id,  
					nome, 
					email,
					ativo_login
				  FROM 
				  	professores
				  WHERE
				  	NOT EXISTS(
					  SELECT 
						professores.idprofessor
					  FROM
						pesquisas_fila 
					  WHERE 
						idpesquisa = '".$this->id."' AND 
						professores.idprofessor = pesquisas_fila.idprofessor AND
						pesquisas_fila.ativo = 'S'
					) AND ativo = 'S'";
	if(count($filtro) > 0) {
	  $filtro = join(" AND ",$filtro);
	  $this->sql .= " AND (".$filtro.")";
	} 
	
	$this->limite = -1;
	$this->ordem_campo = "nome";
	$this->ordem = "ASC";
	
	$dados = $this->retornarLinhas();
	$dados["filtro"] = $filtro;
	return $dados;
	
  }*/

  function listarFilaAddPesquisaProfessores() {
	  unset($this->post["acao"]);
		
		$dados = array();
		$filtro = array();
		foreach($this->post as $campo => $valor) {
		  if(!empty($valor)) {
			if($campo == "data_nasc_dia") {
			  $campo = "date_format(data_nasc, '%d')";
			}elseif($campo == "data_nasc_mes") {
			  $campo = "date_format(data_nasc, '%m')";
			} 
			
			if ($campo == 'nome' || $campo == 'documento' || $campo == 'email')
				$filtro[] = $campo." like '%".$valor."%'";
			else
				$filtro[] = $campo." = '".$valor."'";
		  }
		}
		
		$this->sql = "SELECT p.idprofessor, p.idprofessor AS id, p.ativo_login, p.nome, p.email
							FROM professores p
							LEFT JOIN professores_avas pa ON (p.idprofessor = pa.idprofessor AND pa.ativo = 'S')
							LEFT JOIN professores_cursos pc ON (p.idprofessor = pc.idprofessor AND pc.ativo = 'S')
							LEFT JOIN professores_ofertas po ON (p.idprofessor = po.idprofessor AND po.ativo = 'S')
							WHERE NOT EXISTS (
								SELECT 
									p.idprofessor
								  FROM
									pesquisas_fila 
								  WHERE 
									idpesquisa = '".$this->id."' AND 
									p.idprofessor = pesquisas_fila.idprofessor AND
									pesquisas_fila.ativo = 'S'
							)
							AND p.ativo =  'S'";
		if(count($filtro) > 0) {
		  $filtro = join(" AND ",$filtro);
		  $this->sql .= " AND (".$filtro.")";
		}
		
		$this->sql .= " group by p.idprofessor ";
		
		$this->limite = -1;
		$this->ordem_campo = "nome";
		$this->ordem = "ASC";
		
		$dados = $this->retornarLinhas();
		$dados["filtro"] = $filtro;
		return $dados;
	}
  
  /*function listarFilaAddPesquisaPessoas() { 
	
	unset($this->post["acao"]);
	
	$dados = array();
	$filtro = array();
	foreach($this->post as $campo => $valor) {
	  if(!empty($valor)) {
		if($campo == "data_nasc_dia") {
		  $campo = "date_format(data_nasc, '%d')";
		}elseif($campo == "data_nasc_mes") {
		  $campo = "date_format(data_nasc, '%m')";
		}
		$filtro[] = $campo." = '".$valor."'";
	  }
	}
	
	$this->sql = "SELECT 
					idpessoa, 
					idpessoa as id,  
					nome, 
					email,
					ativo_login
				  FROM 
				  	pessoas
				  WHERE
				  	NOT EXISTS(
					  SELECT 
						pessoas.idpessoa
					  FROM
						pesquisas_fila 
					  WHERE 
						idpesquisa = '".$this->id."' AND 
						pessoas.idpessoa = pesquisas_fila.idpessoa AND
						pesquisas_fila.ativo = 'S'
					) AND ativo = 'S'";
	if(count($filtro) > 0) {
	  $filtro = join(" AND ",$filtro);
	  $this->sql .= " AND (".$filtro.")";
	}
	
	$this->limite = -1;
	$this->ordem_campo = "nome";
	$this->ordem = "ASC";
	
	$dados = $this->retornarLinhas();
	$dados["filtro"] = $filtro;
	return $dados;
	
  }*/

  function listarFilaAddPesquisaPessoas() { 
  	unset($this->post["acao"]);
	
	$dados = array();
	$filtro = array();
	foreach($this->post as $campo => $valor) {
	  if(!empty($valor)) {
		if($campo == "data_nasc_dia") {
		  $campo = "date_format(data_nasc, '%d')";
		}elseif($campo == "data_nasc_mes") {
		  $campo = "date_format(data_nasc, '%m')";
		}
		
		if ($campo == 'nome' || $campo == 'documento' || $campo == 'email')
			$filtro[] = $campo." like '%".$valor."%'";
		else
			$filtro[] = $campo." = '".$valor."'";
	  }
	}
	
	$this->sql = "SELECT 
					idpessoa, 
					idpessoa as id,
					ativo_login,
					nome, 
					email
				  FROM 
				  	pessoas
				  WHERE
				  	NOT EXISTS(
					  SELECT 
						pessoas.idpessoa
					  FROM
						pesquisas_fila 
					  WHERE 
						idpesquisa = '".$this->id."' AND 
						pessoas.idpessoa = pesquisas_fila.idpessoa AND
						pesquisas_fila.ativo = 'S'
					) AND ativo = 'S' ";
	if(count($filtro) > 0) {
	  $filtro = join(" AND ",$filtro);
	  $this->sql .= " AND (".$filtro.")";
	}
	$this->limite = -1;
	$this->ordem_campo = "nome";
	$this->ordem = "ASC";
	
	$dados = $this->retornarLinhas();
	$dados["filtro"] = $filtro;
	return $dados;
}
  
  /*function listarFilaAddPesquisaMatriculas() { 
	
	unset($this->post["acao"]);
	
	$dados = array();
	$filtro = array();

	foreach($this->post as $campo => $valor) {
	  if(!empty($valor)) {
		if($campo == 'data_de')  {
		  $filtro[] = " date_format(matriculas.data_cad,'%Y-%m-%d') >= '".formataData($valor,'en',0)."' ";
	    } else if($campo == 'data_ate')  {
		  $filtro[] = " date_format(matriculas.data_cad,'%Y-%m-%d') <= '".formataData($valor,'en',0)."' ";
	    } elseif($campo == "data_cad_reserva") {
		  $campo = "date_format(matriculas.data_cad, '%d/%m/%Y')";
		  $filtro[] = $campo." = '".$valor."'";
		}elseif($campo == "idoferta") {
		  $campo = "o.idoferta";
		  $filtro[] = $campo." = '".$valor."'";
		}elseif($campo == "idcurso") {
		  $campo = "c.idcurso";
		  $filtro[] = $campo." = '".$valor."'";
		} else if($campo == 'data_de_alteracao' && $valor)  {
		  $data_de_alteracao = formataData($valor,'en',0);
	    } else if($campo == 'data_ate_alteracao' && $valor)  {
		  $data_ate_alteracao = formataData($valor,'en',0);
	    } else if($campo == 'idsituacao' && $valor)  {
		  $idsituacao_para = $valor;
	    } else if(!empty($valor)) {
		  $filtro[] = $campo." = '".$valor."'";
		}		
	  }
	}
	
	$this->sql = "SELECT 
					idmatricula,
					idmatricula as id, 
					p.nome, 
					p.email,
					p.ativo_login
				  FROM 
				  	matriculas
					inner join pessoas p on (matriculas.idpessoa = p.idpessoa)
					INNER JOIN ofertas o ON ( o.idoferta = matriculas.idoferta ) 
					INNER JOIN cursos c ON ( c.idcurso = matriculas.idcurso ) 				    
				  WHERE
				  	NOT EXISTS(
					  SELECT 
						matriculas.idmatricula
					  FROM
						pesquisas_fila 
					  WHERE 
						idpesquisa = '".$this->id."' AND 
						matriculas.idmatricula = pesquisas_fila.idmatricula AND
						pesquisas_fila.ativo = 'S'
					) AND matriculas.ativo = 'S'";
	
	if( ($data_de_alteracao || $data_ate_alteracao) ) {
		$this->sql .= " AND EXISTS(
						  SELECT mh.* FROM `matriculas_historicos` mh WHERE mh.tipo = 'situacao' and mh.idmatricula = matriculas.idmatricula ";
						if($idsituacao_para)
							$this->sql .= " and mh.para = '".$idsituacao_para."' ";
						if ($data_de_alteracao)
							$this->sql .= " and date_format(mh.data_cad,'%Y-%m-%d') >= '".$data_de_alteracao."' ";
						if ($data_ate_alteracao)
							$this->sql .= " and date_format(mh.data_cad,'%Y-%m-%d') <= '".$data_ate_alteracao."' ";
		$this->sql .= " ) ";
	} else if ($idsituacao_para) {
		$filtro[] = " matriculas.idsituacao = '".$idsituacao_para."'";
	}
	
	if(count($filtro) > 0) {
	  $filtro = join(" AND ",$filtro);
	  $this->sql .= " AND (".$filtro.")";
	}

	$this->limite = -1;
	$this->ordem_campo = "p.nome";
	$this->ordem = "ASC";

	$dados = $this->retornarLinhas();
	$dados["filtro"] = $filtro;
	return $dados;
	
  }*/

  function listarFilaAddPesquisaMatriculas() { 
  unset($this->post["acao"]);
	
	$dados = array();
	$filtro = array();
	foreach($this->post as $campo => $valor) {

	  	if(!empty($valor)) {

	  		if ($campo == "idmatricula") {
			  	$campo = "matriculas.idmatricula";
			}

			if ($campo == "data_cad_de") {
				$filtro[] = " date_format(matriculas.data_cad, '%Y-%m-%d') >= '".formataData($valor,'en',0)."'";
			} elseif ($campo == "data_cad_ate") {
				$filtro[] = "date_format(matriculas.data_cad, '%Y-%m-%d') <= '".formataData($valor,'en',0)."'";
			} elseif ($campo == "data_alteracao_de") {
				$possuiFiltroAlteracaoDe = " date_format(mh.data_cad, '%Y-%m-%d') >= '".formataData($valor,'en',0)."'";
			} elseif ($campo == "data_alteracao_ate") {
				$possuiFiltroAlteracaoAte = " date_format(mh.data_cad, '%Y-%m-%d') <= '".formataData($valor,'en',0)."'";
			} elseif ($campo == 'nome' || $campo == 'documento' || $campo == 'email') {
				$filtro[] = "p.".$campo." like '%".$valor."%'";
			} else {
				$filtro[] = $campo." = '".$valor."'";
			}
			
	  	}
	}
	
	$this->sql = "SELECT 
					matriculas.idmatricula, matriculas.idmatricula AS id, p.nome, p.email, p.ativo_login
				FROM 
					matriculas
				INNER JOIN pessoas p ON (matriculas.idpessoa = p.idpessoa AND p.ativo = 'S') 
				
				WHERE 
				EXISTS (
					SELECT 
						mh.idhistorico 
					FROM 
						matriculas_historicos mh 
					WHERE 
						mh.idmatricula = matriculas.idmatricula AND
						mh.acao = 'modificou' AND 
						mh.tipo = 'situacao' AND 
						mh.para = matriculas.idsituacao ";

					if ($possuiFiltroAlteracaoDe) {
						$this->sql .= 'AND '.$possuiFiltroAlteracaoDe;
					}
					if ($possuiFiltroAlteracaoAte) {
						$this->sql .= 'AND '.$possuiFiltroAlteracaoAte;
					}
	$this->sql .= " ) 
				AND NOT EXISTS (
					SELECT 
						matriculas.idmatricula
					  FROM
						pesquisas_fila 
					  WHERE 
						idpesquisa = '".$this->id."' AND 
						matriculas.idmatricula = pesquisas_fila.idmatricula AND
						pesquisas_fila.ativo = 'S')
				AND matriculas.ativo =  'S' 
				AND p.ativo =  'S'";

	if(count($filtro) > 0) {
	  $filtro = join(" AND ",$filtro);
	  $this->sql .= " AND (".$filtro.")";
	}
	$this->limite = -1;
	$this->ordem_campo = "p.nome";
	$this->groupby = "matriculas.idmatricula";
	$this->ordem = "ASC";
	
	$dados = $this->retornarLinhas();
	$dados["filtro"] = $filtro;
	return $dados;

}
  
  function salvarFila() {
	/*******************FUNÇÃO QUE SALVA AS SELEÇÕES E AQUI QUE DIZ DE É OU NÃO PRA ENVIAR POR E-MAIL****************/	
	$this->monitora_oque = 1;
	$this->monitora_onde = 132;
	if($this->url[6] == "usuariosadm") { 
	  $primaria = "idusuario_gestor";
	  $tipo = "UA";
	} elseif($this->url[6] == "professores") { 
	  $primaria = "idprofessor";
	  $tipo = "PR";
	} elseif($this->url[6] == "pessoas") { 
	  $primaria = "idpessoa";
	  $tipo = "PE";
	} elseif($this->url[6] == "matriculas") {
	  $primaria = "idmatricula";
	  $tipo = "MA";
	}
	
	if(empty($this->post["filtro"])) {
	     $this->post["filtro"] = "NULL";
	} else {
	     $this->post["filtro"] = "'".$this->post["filtro"]."'";
	}
	
	$this->executaSql("BEGIN");
	
	$sql = "insert into 
			  pesquisas_filtros
			set 
			  data_cad = now(),
			  idpesquisa = '".$this->id."',
			  idusuario = '".$this->idusuario."',
			  filtro = ".$this->post["filtro"].",
			  busca = '".$this->post["busca"]."'";
	$this->executaSql($sql);
	$idfiltro = mysql_insert_id();
	foreach($this->post["id"] as $id => $nomeEmail) {
	
	/*Verifica se não deseja enviar por e-mail*/	
	  if (!$this->post["nao_envio"][$id]){
		  	$this->post["nao_envio"][$id] = 'S';}
					
	  $nomeEmail = explode("|",$nomeEmail);
	  $sql = "insert into 
				pesquisas_fila 
			  set 
				".$primaria." = ".$id.",
				idpesquisa = '".$this->id."',
				idfiltro = '".$idfiltro."',
				nome = '".mysql_real_escape_string($nomeEmail[0])."',
				email = '".mysql_real_escape_string($nomeEmail[1])."',
				enviar_email = '".$this->post["nao_envio"][$id]."',				
				data_cad = now(),
				tipo = '".$tipo."'";
	  if(!$this->executaSql($sql)) { 
	  	$this->retorno["erro"] = true;
		$this->retorno["erros"][] = $this->sql;
		$this->retorno["erros"][] = mysql_error();
		$this->executaSql("ROLLBACK");
		return $this->retorno;
	  } else {
		$this->monitora_qual = mysql_insert_id();
		$this->sql = "update pesquisas_fila set hash = '".md5($this->monitora_qual)."' where idpesquisa_pessoa = ".$this->monitora_qual;
		$this->query = $this->executaSql($this->sql);
		
		$sql_enviado = "update pesquisas set situacao = '1' where idpesquisa = '".$this->id."' ";
		$resultado_enviado = $this->executaSql($sql_enviado);
		
		$this->Monitora();
	  }
	}
	
	$this->executaSql("COMMIT");
	$this->retorno["sucesso"] = true;
	
	return $this->retorno;
  }
  
  function removerFila() {
		
	include_once("../includes/validation.php");		
	$regras = array(); // stores the validation rules
	
	//VERIFICANDO SE OPÃ‡ÃƒO REMOVER FOI MARCADA
	if(!$this->post["remover"])
	  $regras[] = "required,remover,remover_vazio";
	
	//VALIDANDO FORMULÃRIO
	$erros = validateFields($this->post, $regras);

	//SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
	if(!empty($erros)){
	  $this->retorno["erro"] = true;
	  $this->retorno["erros"] = $erros;
	}else{
	  $this->sql = "update pesquisas_fila set ativo = 'N' where idpesquisa_pessoa = ".intval($this->post["remover"]);
	  $remover = $this->executaSql($this->sql);
	  if($remover){
		$this->retorno["sucesso"] = true;
		$this->monitora_oque = 3;
		$this->monitora_onde = 132;
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

function responderPesquisa() { 
	mysql_query("START TRANSACTION");
	
	if(count($this->post['opcoes_unica']) > 0){
		foreach ($this->post['opcoes_unica'] as $pergunta => $opcao) {			
			$sql = "INSERT INTO pesquisas_respostas SET 
						idpesquisa_pessoa = '".$this->post['idpesquisa_pessoa']."', 
						idpergunta = '".$pergunta."', 
						data_cad = NOW(),
						idopcao = '".$opcao."' ";
			$inserir_unica = $this->executaSql($sql);
			if (!$inserir_unica) {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
				return $this->retorno;
			}
		}
	}

	if(count($this->post['opcoes_multipla']) > 0){
		foreach ($this->post['opcoes_multipla'] as $pergunta => $opcao) {
			foreach ($opcao as $ind_op => $op) {
				$sql = "INSERT INTO pesquisas_respostas SET 
							idpesquisa_pessoa = '".$this->post['idpesquisa_pessoa']."', 
							idpergunta = '".$pergunta."',
							data_cad = NOW(),
							idopcao = '".$ind_op."' ";
				$inserir_multipla = $this->executaSql($sql);
				if (!$inserir_multipla) {
					$this->retorno["erro"] = true;
					$this->retorno["erros"][] = $this->sql;
					$this->retorno["erros"][] = mysql_error();
					return $this->retorno;
				}
			}	
		}
	}	

	if(count($this->post['resposta']) > 0){
		foreach($this->post['resposta'] as $indice => $valor){
		    $sql = "INSERT INTO pesquisas_respostas SET 
						idpesquisa_pessoa = '".$this->post['idpesquisa_pessoa']."', 
						idpergunta = '".$indice."',
						idopcao = NULL,
						data_cad = NOW(),
						resposta = '".$valor."' ";
			$inserir_resposta = $this->executaSql($sql);
			if (!$inserir_resposta) {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
				return $this->retorno;
			}
		}
	}
	
	$sql = "UPDATE pesquisas_fila SET data_resposta = NOW(), ip = '".$_SERVER['REMOTE_ADDR']."' ";
				if ($this->post['gestor_usu'])
					$sql .= ", respondido_gestor = '".$this->post['gestor_usu']."' ";
	$sql .= " WHERE idpesquisa_pessoa = '".$this->post['idpesquisa_pessoa']."' ";

	$atualizar = $this->executaSql($sql);

	if (!$atualizar) {
		mysql_query("ROLLBACK");
		$this->retorno["erro"] = true;
		$this->retorno["erros"][] = $this->sql;
		$this->retorno["erros"][] = mysql_error();
		return $this->retorno;
	}	
	
	$this->sql = "SELECT nome, email FROM pesquisas_fila WHERE idpesquisa_pessoa = '".$this->post['idpesquisa_pessoa']."'";
	$pessoa = $this->retornarLinha($this->sql);
	
	$nomePara = $pessoa["nome"];
   
	$message  = "Ol&aacute; <strong>".$nomePara."</strong>,
				<br /><br />
				Sua participa&ccedil;&atilde;o foi realizada com sucesso.
				<br /><br />";
					 
	$nomePara = utf8_decode($pessoa["nome"]);

	$emailPara  = $pessoa["email"];
	$assunto  = utf8_decode("Sua participação na pesquisa foi realizada");	
	
	$nomeDe = utf8_decode($GLOBALS["config"]["tituloEmpresa"]);
	$emailDe = $GLOBALS["config"]["emailSistemaPesquisaAtendimento"];
	
	$this->EnviarEmail($nomeDe,$emailDe,$assunto,$message,$nomePara,$emailPara,"layout");
	
	mysql_query("COMMIT");
	$this->retorno["sucesso"] = true;
	return $this->retorno;
	//MSG
}

function verificarHashUsuarioPesquisa($pesquisa_pessoa, $hash) {
	$sql = "SELECT * FROM pesquisas_fila pf INNER JOIN pesquisas p ON (pf.idpesquisa = p.idpesquisa) WHERE pf.idpesquisa_pessoa = '".$pesquisa_pessoa."' AND pf.hash = '".$hash."' AND pf.ativo = 'S' ";
	$resultado = $this->executaSql($sql);
	return mysql_fetch_assoc($resultado);
} 

function verificaPesquisaRespondida($idpesquisa) {
	$this->sql = "SELECT count(pf.idpesquisa) as total 
					FROM pesquisas_fila pf
					INNER JOIN pesquisas_respostas pr ON pf.idpesquisa_pessoa = pr.idpesquisa_pessoa
					WHERE pf.idpesquisa = '".$idpesquisa."' and pf.ativo = 'S' GROUP BY pf.idpesquisa_pessoa ";
	$respondidos = 	$this->RetornarLinha($this->sql);
	return $respondidos['total'];
}

function verificaPesquisaRespondidaPorUsuario($idpesquisa_pessoa, $idpesquisa = NULL) {
	if ($idpesquisa)
		$this->id = $idpesquisa;
	$this->sql = "SELECT count(pf.idpesquisa) as total 
					FROM pesquisas_fila pf
					INNER JOIN pesquisas_respostas pr ON pf.idpesquisa_pessoa = pr.idpesquisa_pessoa
					WHERE pf.idpesquisa_pessoa = ".$idpesquisa_pessoa." and pf.idpesquisa = ".$this->id." and pf.ativo = 'S' GROUP BY pf.idpesquisa_pessoa";
	$respondidos = 	$this->RetornarLinha($this->sql);
	return $respondidos['total'];
}

function listarRespostasUsuario($idpesquisa_pessoa) {
	$this->sql = "SELECT 
					* 
				  FROM 
					pesquisas_fila pf
					INNER JOIN pesquisas_respostas pr ON pf.idpesquisa_pessoa = pr.idpesquisa_pessoa
				  WHERE 
					pf.idpesquisa_pessoa = ".$idpesquisa_pessoa." and 
					pf.idpesquisa = ".$this->id." and 
					pf.ativo = 'S'";
	$this->limite = -1;
    $this->groupby = "pf.idpesquisa_pessoa";
	$linhas = $this->retornarLinhas();
	$respostas = array();
	foreach($linhas as $ind => $val) {
		if($val["resposta"]) {
		  $respostas[$val["idpergunta"]][] = $val["resposta"];
		} else {
		  $respostas[$val["idpergunta"]][] = $val["idopcao"];
		}
	}
	return $respostas;
}

function AlterarSituacao($situacao) {	
	if($situacao <> "S" && $situacao <> "N"){
	   $info['sucesso'] = false;
	   $info['situacao'] = $situacao;
	   return json_encode($info);
	}
	
	if ($situacao == 'S') $st = 1;
	else $st = 3;
	
	$this->sql = "select * from pesquisas where idpesquisa = ".intval($this->id);
	$linhaAntiga = $this->retornarLinha($this->sql);
	
	$this->sql = "update pesquisas set situacao = '".mysql_real_escape_string($st)."' where idpesquisa='".intval($this->id)."'";	
	$executa = $this->executaSql($this->sql);
	
	$this->sql = "select * from pesquisas where idpesquisa = ".intval($this->id);
	$linhaNova = $this->retornarLinha($this->sql);
	
	$info = array();
	if($executa){
		
	   $this->monitora_oque = 2;
	   $this->monitora_qual = $this->id;
	   $this->monitora_dadosantigos = $linhaAntiga;
	   $this->monitora_dadosnovos = $linhaNova;
	   $this->Monitora();
	
	   $info['sucesso'] = true;
	   $info['situacao'] = $linhaNova["situacao"];
	} else {
	   $info['sucesso'] = false;
	   $info['situacao'] = $situacao;
	}	
	return json_encode($info);	
}

//CADASTRANDO CORPO DO EMAIL
function alterarCorpoEmail() {
	
	  $this->sql = "SELECT email_padrao FROM pesquisas WHERE idpesquisa = '".$this->id."'";
	  $email_padrao = $this->retornarLinha($this->sql);
	  
	  if ($_POST['email_padrao'])
		$email_padrao["email_padrao"] = $_POST['email_padrao'];
	  
	  if ($email_padrao["email_padrao"] == "S"){// Se o corpo do email era padrão--------------------------------
	  
		  $this->sql = "SELECT email_padrao FROM pesquisas WHERE idpesquisa = '".$this->id."'";
		  $this->linha_antiga = $this->retornarLinha($this->sql);
		  
		  $this->sql = "update pesquisas set email_padrao = '".$this->post["email_padrao"]."' where idpesquisa = '".$this->id."'";
		  $this->query = $this->executaSql($this->sql);
		  
		  $this->sql = "SELECT corpo_email FROM pesquisas WHERE idpesquisa = '".$this->id."'";
		  $this->linha_nova = $this->retornarLinha($this->sql);
		  
	  }elseif($email_padrao["email_padrao"] == "N"){// Se o corpo do email era personalizado--------------------------------
	  
		  $this->sql = "SELECT corpo_email FROM pesquisas WHERE idpesquisa = '".$this->id."'";
		  $this->linha_antiga = $this->retornarLinha($this->sql);
		  
		  $this->sql = "update pesquisas set corpo_email = '".$this->post["corpo_email"]."', email_padrao = '".$this->post["email_padrao"]."' where idpesquisa = '".$this->id."'";
		  $this->query = $this->executaSql($this->sql);
		  
		  $this->sql = "SELECT corpo_email FROM pesquisas WHERE idpesquisa = '".$this->id."'";
		  $this->linha_nova = $this->retornarLinha($this->sql);
	  }
	  if($this->query){
		  $this->retorno["sucesso"] = true;
		  if($this->linha_antiga){
			  $this->monitora_oque = 2;
			  $this->monitora_dadosantigos = $this->linha_antiga;
			  $this->monitora_dadosnovos = $this->linha_nova;
		  }else{
			  $this->monitora_oque = 1;	
		  }
		  $this->monitora_qual = $this->id;
		  $this->Monitora();
	  } else {
		  $this->retorno["erro"] = true;
		  $this->retorno["erros"][] = $this->sql;
		  $this->retorno["erros"][] = mysql_error();
	  }
  
  return $this->retorno;
}
	function RetornarCursosOferta() {
		$this->sql = "SELECT c.idcurso, c.nome
						  FROM cursos c
						  INNER JOIN ofertas_cursos oc on c.idcurso = oc.idcurso and oc.ativo = 'S'
						  WHERE oc.idoferta = '".$this->id."'";			
		$query = $this->executaSql($this->sql);
		$this->retorno = array();
		while($row = mysql_fetch_assoc($query)){
			$this->retorno[] = $row;
		}
		echo json_encode($this->retorno);
	}
	
	function RetornarEscolasOferta() {
		$this->sql = "SELECT p.idescola, p.nome_fantasia as nome
						  FROM escolas p
						  INNER JOIN ofertas_escolas op on p.idescola = op.idescola and op.ativo = 'S'
						  WHERE op.idoferta = '".$this->id."'";			
		$query = $this->executaSql($this->sql);
		$this->retorno = array();
		while($row = mysql_fetch_assoc($query)){
			$this->retorno[] = $row;
		}
		echo json_encode($this->retorno);
	}

	function RetornarTurmasOferta() {
		$this->sql = "SELECT tu.idturma, tu.nome
					FROM ofertas_turmas tu
					WHERE tu.idoferta = '".$this->id."'";			
		$query = $this->executaSql($this->sql);
		$this->retorno = array();
		while($row = mysql_fetch_assoc($query)){
			$this->retorno[] = $row;
		}
		echo json_encode($this->retorno);
	}

	function RetornarCursos($idoferta, $json = true) {		 
		$this->sql = "SELECT c.idcurso, c.nome FROM ofertas_cursos oc INNER JOIN cursos c ON oc.idcurso = c.idcurso where oc.idoferta = '".$idoferta."' AND oc.ativo = 'S' ";		  
		$this->ordem_campo = "c.nome";
		$this->groupby = "c.idcurso";
		$this->limite = -1;
		$this->ordem = "ASC";
		$dados = $this->retornarLinhas();
		
		if ($json) {
			return json_encode($dados);
		}
		else
			return $dados;
	}

}
?>