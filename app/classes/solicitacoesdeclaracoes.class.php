<?php
class SolicitacoesDeclaracoes extends Core
{
    public $idmatricula = null;

    public function listarTodas()
    {

        $this->sql = "SELECT {$this->campos}
              FROM matriculas_solicitacoes_declaracoes sd
                INNER JOIN matriculas m 
                    ON sd.idmatricula = m.idmatricula
                INNER JOIN escolas po
                    ON po.idescola = m.idescola
                INNER JOIN pessoas pe 
                    ON m.idpessoa = pe.idpessoa
                INNER JOIN declaracoes d 
                    ON d.iddeclaracao = sd.iddeclaracao
                LEFT JOIN matriculas_declaracoes md 
                    ON sd.idmatriculadeclaracao = md.idmatriculadeclaracao";
        if($this->idusuario) {
            $this->sql .= " inner join usuarios_adm ua on ua.idusuario = ".$this->idusuario."
                        left join usuarios_adm_sindicatos uai on 
                        po.idsindicato = uai.idsindicato and uai.ativo = 'S' and uai.idusuario = ua.idusuario ";
        }
        $this->sql .=" WHERE sd.ativo = 'S' AND (
                                                    uai.idusuario IS NOT NULL 
                                                    OR 
                                                    ua.gestor_sindicato = 'S'
                                                    ) ";

        $this->aplicarFiltrosBasicos();
        return $this->set('groupby', "sd.idsolicitacao_declaracao")
            ->retornarLinhas();

    }

    private function _filtroDeBusca()
    {
        if (! is_array($_GET['q'])) {
            return false;
        }

        foreach ($_GET['q'] as $campo => $valor) {

            $tipo = current(explode('|', $campo));
            $campo = end(explode('|', $campo));

            $valor = str_replace("'", '', $valor);

            /**
              * Se $<valor> for igual a "todos" não aplica o filtro
              * e passa para o próximo item do loop cuidado aqui com
              * essa regrita complexa
              *
              * @deprecated Simplificar esse código aqui depois
              */
            if (! (($valor || '0' === $valor) and 'todos' != $valor)) {
                continue 1;
            }


            if (1 == $tipo) {
                $this->sql .= " and ".$campo." = '".$valor."' ";
            }

            if (2 == $tipo) {

                $busca = str_replace(
                    array("\\'", "\\"),
                    array('', ''),
                    $valor
                );

                $busca = explode(' ', $busca);

                foreach ($busca as $ind => $buscar) {
                    $this->sql .= " and ".$campo." like '%".urldecode($buscar)."%' ";
                }
            }

            if (3 == $tipo) {
                $this->sql .= sprintf(
                    ' AND date_format(%s, "%d/%m/%Y") = "%s"',
                    $campo,
                    $valor
                );
            }
        }
    }

    public function retornar() {
        $this->sql = 'SELECT 
						'.$this->campos.'
					FROM 
						matriculas_solicitacoes_declaracoes sd
						INNER JOIN matriculas m ON sd.idmatricula = m.idmatricula
						INNER JOIN pessoas pe ON m.idpessoa = pe.idpessoa
						INNER JOIN declaracoes d ON d.iddeclaracao = sd.iddeclaracao
						LEFT JOIN matriculas_declaracoes md ON sd.idmatriculadeclaracao = md.idmatriculadeclaracao
					WHERE 
						sd.ativo = "S" AND 
						sd.idsolicitacao_declaracao = '.$this->id;

        return $this->retornarLinha($this->sql);
    }

        /**
         * @param  [string] $idmatricula
         * @return [boolean]
         */
        public function verificaDiasMinimo($idmatricula){

        $valido = false;    

         $this->sql = "SELECT data_matricula FROM matriculas WHERE idmatricula = ". $idmatricula;
         $datamatricula = $this->retornarLinha($this->sql);

         $agora = time();
         $data = strtotime($datamatricula["data_matricula"]);
         $diferenca = $agora - $data;
         $quantidade_dias_matricula = floor($diferenca/(60*60*24));

         $this->sql = "SELECT c.dias_minimo as dias_minimo FROM curriculos c
         INNER JOIN cursos cu ON (cu.idcurso = c.idcurso)
         INNER JOIN matriculas m ON(m.idcurso = cu.idcurso)
         WHERE m.idmatricula = " . $idmatricula;

         $dias_minimo = $this->retornarLinha($this->sql);

         if($quantidade_dias_matricula >= $dias_minimo["dias_minimo"]){
            $valido = true;
        }else{
            $valido = false;
        }

        return $valido;
    }

    public function salvarSolicitacao() {

		if (verificaPermissaoAcesso(true)) {

			$this->sql = 'INSERT INTO 
							matriculas_solicitacoes_declaracoes 
						SET
							idmatricula = '.$this->post['idmatricula'].',
							iddeclaracao = '.$this->post['iddeclaracao'].',
							data_cad = NOW(),
							data_solicitacao = NOW()';
			if ($this->executaSql($this->sql)) {
				$this->retorno['sucesso'] = true;
				$this->retorno['id'] = mysql_insert_id();
				
				$this->monitora_oque = 1;
				$this->monitora_onde = 166;
				$this->monitora_qual = $this->retorno['id'];
				$this->Monitora();

			} else {
				$this->retorno["erro"] = true;
				$this->retorno["erros"][] = 'erro_salvar_solicitacao';
			}
			return $this->retorno;
		}
	}

    public function indeferirSolicitacao()
    {

        $this->sql = "UPDATE 
                        matriculas_solicitacoes_declaracoes 
                    SET 
                        situacao = 'I',
                        motivo_cancelamento = '".$this->post['motivo_cancelamento']."',
                        idmatriculadeclaracao = NULL 
                    WHERE 
                    idsolicitacao_declaracao = ".(int) $this->id;
        $cancelar = $this->executaSql($this->sql);

        /*$this->sql = "INSERT INTO mensagens_alerta 
                    SET tipo_alerta = 'documentospedagogicos',
                        iddocumento =". (int) $this->id.",
                        situacao_documento = 'I',
                        idmatricula = (SELECT idmatricula FROM matriculas_solicitacoes_declaracoes WHERE idsolicitacao_declaracao = ".(int) $this->id.")";

            $this->executaSql($this->sql);*/

        if($cancelar){

            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 12;
            $this->monitora_onde = 166;
            $this->monitora_qual = $this->id;
            $this->Monitora();


        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }
        return $this->retorno;
    }


function deferirSolicitacao($idioma = null){
    $this->sql = "UPDATE matriculas_solicitacoes_declaracoes 
                    SET situacao = 'D'
                WHERE 
                    idsolicitacao_declaracao = ".(int) $this->id;


        /*$this->sql = $this->sql = "INSERT INTO mensagens_alerta 
        SET tipo_alerta = 'documentospedagogicos',
        iddocumento =". (int) $this->id.",
        situacao_documento = 'D',
        idmatricula = (SELECT idmatricula FROM matriculas_solicitacoes_declaracoes WHERE idsolicitacao_declaracao = ".(int) $this->id.")";

        $this->executaSql($this->sql);*/

    if ($this->executaSql($this->sql)) {   

        $this->retorno["sucesso"] = true;
        $this->monitora_oque = 11;
        $this->monitora_onde = 166;
        $this->monitora_qual = $this->id;
        $this->Monitora();



    } else {
        $this->retorno["erro"] = true;
        $this->retorno["erros"][] = $this->sql;
        $this->retorno["erros"][] = mysql_error();
    }
    return $this->retorno;
}
	
}