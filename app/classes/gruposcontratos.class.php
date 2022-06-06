<?
class Grupos_Contratos extends Core
{

    function ListarTodas() {
        $this->sql = "SELECT ".$this->campos." FROM contratos_grupos where ativo='S'";

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
                             contratos_grupos where ativo='S' and idgrupo='".$this->id."'";
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

    function AssociarContratos($idgrupo, $arrayContrato) {
        foreach($arrayContrato as $ind => $idcontrato) {
            $this->sql = "select count(idgrupo_contrato) as total, idgrupo_contrato from contratos_grupos_contratos where idgrupo = '".intval($idgrupo)."' and idcontrato = '".intval($idcontrato)."'";
            $totalAss = $this->retornarLinha($this->sql);
            if($totalAss["total"] > 0) {
                $this->sql = "update contratos_grupos_contratos set ativo = 'S' where idgrupo_contrato = ".$totalAss["idgrupo_contrato"];
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = $totalAss["idgrupo_contrato"];
            } else {
                $this->sql = "insert into contratos_grupos_contratos set ativo = 'S', data_cad = now(), idgrupo = '".intval($idgrupo)."', idcontrato = '".intval($idcontrato)."'";
                $associar = $this->executaSql($this->sql);
                $this->monitora_qual = mysql_insert_id();
            }
            if($associar){
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 42;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
        }
        return $this->retorno;
    }
    function DesassociarContratos() {

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
            $this->sql = "UPDATE contratos_grupos_contratos SET ativo = 'N' WHERE idgrupo_contrato = ".intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if($desassociar){
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 42;
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

    function ListarContratosAss() {
        $this->sql = "SELECT ".$this->campos." FROM
                            contratos_grupos_contratos cg
                            INNER JOIN contratos c ON (c.idcontrato  = cg.idcontrato)
                            INNER JOIN contratos_tipos ct ON (c.idtipo=ct.idtipo)
                            WHERE c.ativo='S' AND cg.ativo='S' AND cg.idgrupo = ".intval($this->id);

        $this->groupby = "ue.idusuario_empreendimento";
        return $this->retornarLinhas();
    }

    function BuscarEmpreendimento($idgrupo) {

        $this->sql = "SELECT c.idcontrato AS 'key', CONCAT(c.nome, ' (', ct.nome, ')') AS value
                        FROM contratos c
                        INNER JOIN contratos_tipos ct ON (c.idtipo=ct.idtipo)
                        WHERE
                        c.ativo = 'S' AND c.ativo_painel = 'S' AND
                        (c.nome LIKE '%".$_GET["tag"]."%' OR ct.nome LIKE '%".$_GET["tag"]."%') AND
                        NOT EXISTS (
                                    SELECT g.idcontrato
                                    FROM contratos_grupos_contratos g
                                    WHERE g.idcontrato = c.idcontrato AND g.idgrupo = '".$idgrupo."' AND g.ativo = 'S'
                                    )";

        $this->limite = -1;
        $this->ordem_campo = "c.nome";
        $this->groupby = "c.idcontrato";
        $dados = $this->retornarLinhas();

        return json_encode($dados);

    }

    function ListarContratosGrupo($idgrupo) {

        $this->sql = "SELECT c.idcontrato
                      FROM contratos_grupos_contratos cg
                         INNER JOIN contratos c ON (c.idcontrato  = cg.idcontrato)
                      WHERE cg.idgrupo = '".intval($idgrupo)."' and c.ativo='S' AND cg.ativo='S'";

        $this->groupby = "c.idcontrato";

        return $this->retornarLinhas();

    }

}

?>