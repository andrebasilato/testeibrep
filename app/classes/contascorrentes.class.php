<?php

class Contas_Correntes extends Core {

    public function ListarTodas()
    {
        $this->sql = "select ".$this->campos." from contas_correntes cc, bancos b
            where cc.ativo = 'S' and cc.idbanco=b.idbanco";

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
                    } elseif($campo[0] == 4)  {
                        $this->sql .= " and (SELECT COUNT(*) as total FROM contas_correntes_sindicatos ccci WHERE ccci.ativo = 'S' and cc.idconta_corrente = ccci.idconta_corrente and ccci.idsindicato = '".$valor."') > 0 ";
                    }
                }
            }
        }

        $this->groupby = "idconta_corrente";
        $retorno = $this->retornarLinhas();
        foreach($retorno as $ind => $valor) {
            $sql = "SELECT i.nome_abreviado
                FROM sindicatos i
                INNER JOIN contas_correntes_sindicatos cc ON (i.idsindicato = cc.idsindicato)
                WHERE cc.idconta_corrente = ".$valor["idconta_corrente"]." and cc.ativo = 'S' and i.ativo = 'S' ";
            $sel = mysql_query($sql);
            $j= 0;
            while ($cat = mysql_fetch_assoc($sel)) {
                if($j < 2) {
                    if(!$retorno[$ind]["inst"]) {
                        $retorno[$ind]["inst"] .= $cat["nome_abreviado"];
                    } else {
                        $retorno[$ind]["inst2"] .= ", ".$cat["nome_abreviado"];
                    }
                    $j++;
                }

                $retorno [$ind]["sindicato"] .= $cat["nome_abreviado"]."<br>";
            }
        }

        return $retorno;
    }

    public function Retornar()
    {
        $this->sql = "select ".$this->campos." from contas_correntes where ativo = 'S' and idconta_corrente = '".$this->id."'";
        return $this->retornarLinha($this->sql);
    }

    public function Cadastrar()
    {
        return $this->SalvarDados();
    }

    public function Modificar()
    {
        return $this->SalvarDados();
    }

    public function Remover()
    {
        return $this->RemoverDados();
    }

    public function buscarSindicato()
    {
        $this->sql = "SELECT i.idsindicato as 'key', i.nome_abreviado as value
            from sindicatos i
            where i.nome_abreviado like '%".$_GET["tag"]."%'
            AND i.ativo = 'S'
            AND i.ativo_painel = 'S'
            AND not exists (
                select cci.idsindicato
                from contas_correntes_sindicatos cci
                where cci.idsindicato = i.idsindicato
                and cci.ativo = 'S'
                and cci.idconta_corrente = " . $this->id . "
            )";
        $this->limite = -1; ///UMA INSTITUIÇÃO NÃO PODE TER DUAS CONTAS CORRENTES POR ISSO SO RETORNA INSTITUIÇÕES QUE NÃO POSSUEM CONTA CORRENTE
        $this->ordem_campo = "value";  // and cci.idconta_corrente = '".$this->id."'
        $this->groupby = "value";

        $dados = $this->retornarLinhas();
        return json_encode($dados);
    }

    public function listarSindicatosAss()
    {
        $this->sql = "select
            ".$this->campos."
            from
            sindicatos i
            inner join contas_correntes_sindicatos cci ON (i.idsindicato = cci.idsindicato)
            where
            cci.ativo = 'S' and
            cci.idconta_corrente = ".intval($this->id);

        $this->groupby = "cci.idconta_corrente_sindicato";
        return $this->retornarLinhas();
    }

    public function associarSindicatos($idconta_corrente, $arraySindicatos)
    {
        foreach ($arraySindicatos as $ind => $id) {
            $sql = 'SELECT count(idconta_corrente_sindicato) AS total
                FROM contas_correntes_sindicatos WHERE idsindicato = ' . (int) $id . ' AND ativo = "S"
                AND idconta_corrente = '.$idconta_corrente;
            $sindicatoJaVinculado = $this->retornarLinha($sql);

            if ($sindicatoJaVinculado['total'] > 0) {
                continue;
            }

            $sql = 'SELECT count(idconta_corrente_sindicato) AS total, idconta_corrente_sindicato
                FROM contas_correntes_sindicatos WHERE idconta_corrente = ' . (int) $idconta_corrente . ' AND
                idsindicato = ' . (int) $id;
            $totalAss = $this->retornarLinha($sql);

            if($totalAss['total'] > 0) {
                $this->sql = 'UPDATE contas_correntes_sindicatos SET ativo = "S"
                    WHERE idconta_corrente_sindicato = ' . $totalAss['idconta_corrente_sindicato'];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAss['idconta_corrente_sindicato'];
            } else {
                $this->sql = 'INSERT INTO contas_correntes_sindicatos SET ativo = "S", data_cad = now(),
                    idconta_corrente = ' . (int) $idconta_corrente . ', idsindicato = ' . (int) $id;
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }

            if ($associar) {
                $this->retorno['sucesso'] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 123;
                $this->Monitora();
            } else {
                $this->retorno['erro'] = true;
                $this->retorno['erros'][] = $this->sql;
                $this->retorno['erros'][] = mysql_error();
            }
        }

        return $this->retorno;
    }

    public function DesassociarSindicatos()
    {
        include_once("../includes/validation.php");
        $regras = array(); // stores the validation rules

        //VERIFICANDO SE OPNIAO REMOVER FOI MARCADA
        if(!$this->post["remover"])
            $regras[] = "required,remover,remover_vazio";

        //VALIDANDO FORMULÁRIO
        $erros = validateFields($this->post, $regras);

        //SE EXISTIR REGRAS A SEREM APLICADAS VERIFICAR SE TEM ALGUM ERRO
        if(!empty($erros)){
            $this->retorno["erro"] = true;
            $this->retorno["erros"] = $erros;
        }else{
            $this->sql = "update contas_correntes_sindicatos set ativo = 'N' where idconta_corrente_sindicato = ".intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if($desassociar){
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 123;
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

    public function ListarTodasContasCorrentesSindicato()
    {
        $this->sql = "select
            cc.idconta_corrente, concat(b.nome,' -> ',cc.nome) as nome
            from
            contas_correntes cc
            inner join bancos b on (cc.idbanco = b.idbanco)
            inner join contas_correntes_sindicatos cci on (cc.idconta_corrente = cci.idconta_corrente)
            where
            cc.ativo = 'S'";

        if($_SESSION['adm_gestor_sindicato'] != 'S')
            $this->sql .= " and cci.idsindicato in (".$_SESSION['adm_sindicatos'].")";

        $sindicatos = implode(', ', $_POST['idsindicato']);
        if($sindicatos)
            $this->sql .= " and cci.idsindicato in (".$sindicatos.") ";

        $this->sql .= " group by cc.idconta_corrente ";

        $this->limite = -1;
        $this->ordem_campo = 'nome';
        $this->orderm = 'asc';

        return $this->retornarLinhas();
    }

    public function ListarTodasContasCorrentesEscola()
    {
        $this->sql = "select
            cc.idconta_corrente, concat(b.nome,' -> ',cc.nome) as nome
            from
            contas_correntes cc
            inner join bancos b on (cc.idbanco = b.idbanco)
            inner join contas_correntes_sindicatos cci on (cc.idconta_corrente = cci.idconta_corrente)
            inner join sindicatos s on (s.idsindicato = cci.idsindicato)
            inner join escolas e on (e.idsindicato = s.idsindicato)
            where
            cc.ativo = 'S'";

        if($_SESSION['adm_gestor_sindicato'] != 'S')
            $this->sql .= " and cci.idsindicato in (".$_SESSION['adm_sindicatos'].")";

        $sindicatos = implode(', ', $_POST['idescola']);
        if($sindicatos)
            $this->sql .= " and e.idescola in (".$sindicatos.") ";

        $this->sql .= " group by cc.idconta_corrente ";

        $this->limite = -1;
        $this->ordem_campo = 'nome';
        $this->orderm = 'asc';

        return $this->retornarLinhas();
    }
}
