<?
class Grupos_Usuarios_Adm extends Core
{
		
	function ListarTodas() {		
		$this->sql = "SELECT ".$this->campos." FROM grupos_usuarios_adm where ativo='S'";
		
		if(is_array($_GET["q"])) {
			foreach($_GET["q"] as $campo => $valor) {
				//explode = Retira, ou seja retira a "|" da variavel campo
				$campo = explode("|",$campo);
				$valor = str_replace("'","",$valor);
				// Listagem se o valor for diferente de Todos ele faz um filtro
				if(($valor || $valor === "0") and $valor <> "todos") {
					// se campo[0] for = 1 é pq ele tem de ser um valor exato
					if($campo[0] == 1) {
						$this->sql .= " and ".$campo[1]." = '".$valor."' ";
					// se campo[0] for = 2, faz o filtro pelo comando like
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
		
		$this->groupby = "idgrupo";
		return $this->retornarLinhas();
	}
	
	
	function Retornar() {
		$this->sql = "SELECT ".$this->campos."
							FROM
							 grupos_usuarios_adm where ativo='S' and idgrupo='".$this->id."'";			
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
	
	function AssociarUsuarios($idgrupo, $arrayUsuario) {
		foreach($arrayUsuario as $ind => $idusuario) {
			$this->sql = "select count(idgrupo_usuario) as total, idgrupo_usuario from grupos_usuarios_adm_usuarios where idgrupo = '".intval($idgrupo)."' and idusuario = '".intval($idusuario)."'";
			$totalAss = $this->retornarLinha($this->sql); 
			if($totalAss["total"] > 0) {
				$this->sql = "update grupos_usuarios_adm_usuarios set ativo = 'S' where idgrupo_usuario = ".$totalAss["idgrupo_usuario"];
				$associar = $this->executaSql($this->sql);
				$this->monitora_qual = $totalAss["idgrupo_usuario"];					
			} else {
				$this->sql = "insert into grupos_usuarios_adm_usuarios set ativo = 'S', data_cad = now(), idgrupo = '".intval($idgrupo)."', idusuario = '".intval($idusuario)."'";
				$associar = $this->executaSql($this->sql);
				$this->monitora_qual = mysql_insert_id();
			}
			if($associar){
				$sql_relogin = 'update usuarios_adm set relogin = "S" where idusuario = ' . $idusuario;
				$this->executaSql($sql_relogin);
			
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 1;
				$this->monitora_onde = 92;
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}
		return $this->retorno;
	}
	function DesassociarUsuarios() {
		
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
			$this->sql = "UPDATE grupos_usuarios_adm_usuarios SET ativo = 'N' WHERE idgrupo_usuario = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
			
				$sql_antigo = 'select idusuario from grupos_usuarios_adm_usuarios where idgrupo_usuario = ' . $this->post['remover'];
				$linha_antiga = $this->retornarLinha($sql_antigo);
			
				$sql_relogin = 'update usuarios_adm set relogin = "S" where idusuario = ' . $linha_antiga['idusuario'];
				$this->executaSql($sql_relogin);
			
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 92;
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
	
	function ListarUsuariosAss() {		
		$this->sql = "SELECT ".$this->campos." FROM
							grupos_usuarios_adm_usuarios cg
							INNER JOIN usuarios_adm u ON (u.idusuario  = cg.idusuario)
							WHERE u.ativo='S' AND cg.ativo='S' AND cg.idgrupo = ".intval($this->id);
		
		$this->groupby = "u.idusuario";
		return $this->retornarLinhas();
	}
	
	function BuscarUsuarios($idgrupo) {
		
		$this->sql = "SELECT u.idusuario AS 'key', u.nome AS value 
						FROM usuarios_adm u 
						WHERE  u.nome LIKE '%".$_GET["tag"]."%' AND u.ativo='S' AND
						NOT EXISTS (
									SELECT g.idusuario 
									FROM grupos_usuarios_adm_usuarios g 
									WHERE g.idusuario = u.idusuario AND g.idgrupo = '".$idgrupo."' AND g.ativo = 'S'
									)";

		$this->limite = -1;
		$this->ordem_campo = "u.nome";
		$this->groupby = "u.idusuario";
		$dados = $this->retornarLinhas();
						
		return json_encode($dados);
		
	}
	
	private function deslogarUsuariosGrupo($idgrupo) {
		$sql_usuarios = '
			SELECT u.idusuario 
			FROM grupos_usuarios_adm_usuarios cg
			INNER JOIN usuarios_adm u ON (u.idusuario  = cg.idusuario and u.ativo = "S")
			WHERE cg.ativo = "S" AND cg.idgrupo = ' . $idgrupo;
		$resultado_usuarios = $this->executaSql($sql_usuarios);
		while ($usuario_ass = mysql_fetch_assoc($resultado_usuarios)) {
			$sql_relogin = 'update usuarios_adm set relogin = "S" where idusuario = ' . $usuario_ass['idusuario'];
			$this->executaSql($sql_relogin);
		}
	}
	
	function ListarAssuntosAss() {		
		$this->sql = "SELECT ".$this->campos." FROM
							atendimentos_assuntos_grupos aag
							INNER JOIN atendimentos_assuntos a ON (a.idassunto = aag.idassunto)
							WHERE a.ativo = 'S' AND aag.ativo = 'S' AND aag.idgrupo = ".intval($this->id);
		
		$this->groupby = "a.idassunto";
		return $this->retornarLinhas();
	}
	
	function BuscarAssuntos($idgrupo) {
		
		$this->sql = "SELECT a.idassunto AS 'key', a.nome AS value 
						FROM atendimentos_assuntos a 
						WHERE  a.nome LIKE '%".$_GET["tag"]."%'  AND a.ativo = 'S' AND a.ativo_painel = 'S' AND 
						NOT EXISTS (
									SELECT g.idassunto 
									FROM atendimentos_assuntos_grupos g 
									WHERE g.idassunto = a.idassunto AND g.idgrupo = '".$idgrupo."' AND g.ativo = 'S'
									)";

		$this->limite = -1;
		$this->ordem_campo = "a.nome";
		$this->groupby = "a.idassunto";
		$dados = $this->retornarLinhas();
						
		return json_encode($dados);		
	}
	
	function AssociarAssuntos($idgrupo, $arrayAssunto) {
		foreach($arrayAssunto as $ind => $idassunto) {
			$this->sql = "select count(idassunto_grupo) as total, idassunto_grupo from atendimentos_assuntos_grupos where idgrupo = '".intval($idgrupo)."' and idassunto = '".intval($idassunto)."'";
			$totalAss = $this->retornarLinha($this->sql); 
			if($totalAss["total"] > 0) {
				$this->sql = "update atendimentos_assuntos_grupos set ativo = 'S' where idassunto_grupo = ".$totalAss["idassunto_grupo"];
				$associar = $this->executaSql($this->sql);
				$this->monitora_qual = $totalAss["idassunto_grupo"];					
			} else {
				$this->sql = "insert into atendimentos_assuntos_grupos set ativo = 'S', data_cad = now(), idgrupo = '".intval($idgrupo)."', idassunto = '".intval($idassunto)."'";
				$associar = $this->executaSql($this->sql);
				$this->monitora_qual = mysql_insert_id();
			}
			if($associar){
				
				$this->deslogarUsuariosGrupo($idgrupo);			
				
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 1;
				$this->monitora_onde = 93;
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}
		return $this->retorno;
	}
	
	function DesassociarAssuntos() {
		
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
			$this->sql = "UPDATE atendimentos_assuntos_grupos SET ativo = 'N' WHERE idassunto_grupo = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
			
				$sql_grupo = 'select idgrupo from atendimentos_assuntos_grupos where idassunto_grupo = ' . $this->post['remover'];
				$assunto_grupo = $this->retornarLinha($sql_grupo);
			
				$this->deslogarUsuariosGrupo($assunto_grupo['idgrupo']);	
			
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 93;
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
	
	function ListarSubassuntosAss() {		
		$this->sql = "SELECT ".$this->campos." FROM
							atendimentos_assuntos_subassuntos_grupos asg
							INNER JOIN atendimentos_assuntos_subassuntos s ON (s.idsubassunto  = asg.idsubassunto)
							INNER JOIN atendimentos_assuntos ass ON (s.idassunto = ass.idassunto)
							WHERE s.ativo = 'S' AND asg.ativo = 'S' AND asg.idgrupo = ".intval($this->id);
		
		$this->groupby = "s.idsubassunto";
		return $this->retornarLinhas();
	}
	
	function BuscarSubassuntos($idgrupo) {
		
		$this->sql = "SELECT s.idsubassunto AS 'key', CONCAT(s.nome,' (', aa.nome, ')') AS value 
						FROM atendimentos_assuntos_subassuntos s 
						INNER JOIN atendimentos_assuntos_grupos aag ON s.idassunto = aag.idassunto AND aag.ativo = 'S'
						INNER JOIN atendimentos_assuntos aa ON s.idassunto = aa.idassunto
						WHERE s.nome LIKE '%".$_GET["tag"]."%' AND s.ativo = 'S' AND s.ativo_painel = 'S' AND
						NOT EXISTS (
									SELECT g.idsubassunto 
									FROM atendimentos_assuntos_subassuntos_grupos g 
									WHERE g.idsubassunto = s.idsubassunto AND g.idgrupo = '".$idgrupo."' AND g.ativo = 'S'
									)";

		$this->limite = -1;
		$this->ordem_campo = "s.nome";
		$this->groupby = "s.idsubassunto";
		$dados = $this->retornarLinhas();
						
		return json_encode($dados);		
	}
	
	function AssociarSubassuntos($idgrupo, $arraySubassunto) {
		foreach($arraySubassunto as $ind => $idsubassunto) {
			$this->sql = "select count(idsubassunto_grupo) as total, idsubassunto_grupo from atendimentos_assuntos_subassuntos_grupos where idgrupo = '".intval($idgrupo)."' and idsubassunto = '".intval($idsubassunto)."'";
			$totalAss = $this->retornarLinha($this->sql); 
			if($totalAss["total"] > 0) {
				$this->sql = "update atendimentos_assuntos_subassuntos_grupos set ativo = 'S' where idsubassunto_grupo = ".$totalAss["idsubassunto_grupo"];
				$associar = $this->executaSql($this->sql);
				$this->monitora_qual = $totalAss["idsubassunto_grupo"];					
			} else {
				$this->sql = "insert into atendimentos_assuntos_subassuntos_grupos set ativo = 'S', data_cad = now(), idgrupo = '".intval($idgrupo)."', idsubassunto = '".intval($idsubassunto)."'";
				$associar = $this->executaSql($this->sql);
				$this->monitora_qual = mysql_insert_id();
			}
			if($associar){
			
				$this->deslogarUsuariosGrupo($idgrupo);
			
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 1;
				$this->monitora_onde = 94;
				$this->Monitora();
			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = $this->sql;
				$this->retorno["erros"][] = mysql_error();
			}
		}
		return $this->retorno;
	}
	
	function DesassociarSubassuntos() {
		
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
			$this->sql = "UPDATE atendimentos_assuntos_subassuntos_grupos SET ativo = 'N' WHERE idsubassunto_grupo = ".intval($this->post["remover"]);
			$desassociar = $this->executaSql($this->sql);

			if($desassociar){
			
				$sql_grupo = 'select idgrupo from atendimentos_assuntos_subassuntos_grupos where idsubassunto_grupo = ' . $this->post['remover'];
				$subassunto_grupo = $this->retornarLinha($sql_grupo);
			
				$this->deslogarUsuariosGrupo($subassunto_grupo['idgrupo']);
			
				$this->retorno["sucesso"] = true;
				$this->monitora_oque = 3;
				$this->monitora_onde = 94;
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
	
}

?>