<?php

class Busca extends Core {

	var $palavra;
	var $painel;
	/*
	PAINEIS DO SISTEMA
	*/
	public $painel_nome = array(
			"T"=>"Todos",
			"G"=>"Gestor",
			"A"=>"Aluno",
			"V"=>"Atendente",
			"P"=>"Professor"
	);
	/*
	OPÇÕES DO SISTEMA
	*/
	public $opcoes = array(
							array(
									"nome"=>"Cadastrar um Aluno",
									"descricao"=>"Com esta opção você poderá cadastrar um Aluno no sistema.",
									"link"=>"/cadastros/pessoas/cadastrar",
									"chaves"=>"cadastrar,aluno,cadastrar aluno,cadastrar um aluno",
									"paineis"=>"G"
								),
							array(
									"nome"=>"Buscar um Aluno",
									"descricao"=>"Nesta opção você terá a possibilidade de procurar por um Aluno do sistema e suas informações.",
									"link"=>"/cadastros/pessoas",
									"chaves"=>"buscar,listar,procurar,listagem,aluno,buscar aluno,buscar um aluno,pessoa,pessoas",
									"paineis"=>"G"
								),
							array(
									"nome"=>"Cadastrar um Tutor/Monitor",
									"descricao"=>"Com esta opção você poderá cadastrar um Tutor/Monitor no sistema.",
									"link"=>"/cadastros/professores,cadastrar",
									"chaves"=>"cadastrar,Tutor,Tutores,Monitor,cadastrar Tutor,Monitores,cadastrar Monitor,professor,professores",
									"paineis"=>"G"
								),
							array(
									"nome"=>"Buscar um Tutor/Monitor",
									"descricao"=>"Nesta opção você terá a possibilidade de procurar por um Tutor/Monitor do sistema e suas informações.",
									"link"=>"/cadastros/professores",
									"chaves"=>"buscar,listar,procurar,listagem,Tutor,Tutores,buscar Tutor,buscar um Tutor,Monitores,Monitor,professor,professores",
									"paineis"=>"G"
								),
							array(
									"nome"=>"Iniciar uma nova Matrícula",
									"descricao"=>"Realize uma matrícula agora mesmo através desta funcionalidade.",
									"link"=>"/academico/matriculas/novamatricula",
									"chaves"=>"cadastrar,academico,criar matricula,matricula,cadastrar matricula,iniciar,iniciar uma matricula",
									"paineis"=>"T"
								),
							array(
									"nome"=>"Visualizar Matrículas a serem aprovadas",
									"descricao"=>"Nesta opção você poderá visualizar todas as matrículas que estão aguardando aprovação.",
									"link"=>"/academico/matriculasaprovacao",
									"chaves"=>"matriculas aprovacao,academico,aprovacao,matricula,aprovar matricula,aprovar,iniciar uma matricula",
									"paineis"=>"T"
								),
							array(
									"nome"=>"Buscar uma Matrícula",
									"descricao"=>"Visualize todas as matrículas e suas informações no sistema.",
									"link"=>"/academico/matriculas",
									"chaves"=>"buscar,listar,academico,procurar,listagem,matrícula,buscar matrícula,buscar um matrícula",
									"paineis"=>"G,V"
								),
							array(
									"nome"=>"Buscar Visitas de Atendentes",
									"descricao"=>"Visualize todas as visitas dos atendentes.",
									"link"=>"/comercial/visitas",
									"chaves"=>"buscar,listar visitas,comercial,procurar visitas,listagem visitas,visitas,atendentes",
									"paineis"=>"G"
								),
							array(
									"nome"=>"Visualizar Mapa de Alcance",
									"descricao"=>"Visualize o mapa de alcance.",
									"link"=>"/comercial/mapadealcance",
									"chaves"=>"mapadealcance,listar mapa de alcance,comercial,procurar mapa de alcance,listagem mapa de alcance,mapa de alcance",
									"paineis"=>"G"
								),
							array(
									"nome"=>"Visualizar Relátorios do Sistema",
									"descricao"=>"Obtenha dados estatísticos relacionados a todas as áreas.",
									"link"=>"/relatorios",
									"chaves"=>"relatorios,relatorios,dossie,relatorios do sistema,buscar,listar,informação",
									"paineis"=>"G"
								),
							array(
									"nome"=>"Lista de Atendimentos",
									"descricao"=>"Visualize todos os atendimentos abertos.",
									"link"=>"/relacionamento/atendimentos",
									"chaves"=>"relacionamento,atendimentos,atendimento,relatorios do sistema,buscar,listar,informação",
									"paineis"=>"G"
								),
							array(
									"nome"=>"Visualizar o Fechamento de Caixa",
									"descricao"=>"Acompanhe todas as mudanças e informações do fechamento de caixa.",
									"link"=>"/financeiro/fechamento_caixa",
									"chaves"=>"financeiro,fechamento de caixa,fechamento,caixa,buscar,listar,informação",
									"paineis"=>"G"
								),
							array(
									"nome"=>"Visualizar Contas a Pagar",
									"descricao"=>"Acompanhe todas as contas a pagar.",
									"link"=>"/financeiro/contas/apagar",
									"chaves"=>"financeiro,contas,apagar,contas a pagar,buscar,listar,informação",
									"paineis"=>"G"
								),
							array(
									"nome"=>"Visualizar Contas a Receber",
									"descricao"=>"Acompanhe todas as contas a Receber.",
									"link"=>"/financeiro/contas/areceber",
									"chaves"=>"financeiro,contas,areceber,a receber,contas a receber,contas areceber,buscar,listar,informação",
									"paineis"=>"G"
								),
	);



	public function  buscarOpcoes (){

		$this->retorno = false;

		$this->palavra = $this->retiraAcentos($this->palavra);


		foreach($this->opcoes as $ind => $opcao){

			 $painelresult = $this->trataString(',', $opcao['paineis']);

			 if(in_array(strtolower($this->painel),$painelresult) or in_array(strtolower('T'),$painelresult)){

				$chave = $this->retiraAcentos($opcao['chaves']);
				$nome = $this->retiraAcentos($opcao['nome']);

				$proximidadeChave = $this->proximidade($this->palavra, str_replace(',',' ',$chave));
				//$proximidadeChave = $proximidadeChave + $this->proximidade($this->palavra, $nome);

				$arrayop['nome'] = $opcao['nome'];
				$arrayop['descricao'] = $opcao['descricao'];
				$arrayop['link'] = $opcao['link'];
				$arrayop['ordem'] = $proximidadeChave;
				$this->retorno[] =$arrayop;

			 }
		}
		function cmp($a, $b) {
		  return $a['ordem'] < $b['ordem'];
    	}
		usort($this->retorno, 'cmp');
		//echo '<pre>'.print_r($this->retorno);exit;
		return $this->retorno;

	}


	public function  trataString ($explode, $valor){
		$valor = explode($explode,$valor);
		$valor = array_map('trim',$valor);
		$valor = array_map('strtolower',$valor);
		$valor = array_filter($valor);
		return $valor;
	}
	public function  retiraAcentos ($valor){
		return preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $valor ) );;
	}

	public function  proximidade ($palavra, $texto){
		$arrayPos = explode(' ',$texto);
		$tot = 0;
		foreach($arrayPos as $ind => $val){
			$palavra = trim($palavra);
			$texto   = trim($val);
			$palavra = strtolower($palavra);
			$texto   = strtolower($val);
			if($palavra == $val){
				$tot +=110;
			}else{
				similar_text($palavra, $val, $porcent);
				if($porcent > 30)$tot +=$porcent;
			}
		}
		return $tot;
	}

}