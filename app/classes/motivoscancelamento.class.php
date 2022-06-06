<?php
class Motivos_Cancelamento extends Core {

    function ListarTodas() {
        $this->sql = "select ".$this->campos." from motivos_cancelamento where ativo = 'S'";

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

        $this->groupby = "idmotivo";
        return $this->retornarLinhas();
    }

    function retornarMotivoCancelar()
    {
        $this->sql = "select * FROM motivos_cancelamento where ativo = 'S' and cancela_automatico = 'S' order by idmotivo desc limit 1";
        return $this->retornarLinha($this->sql);
    }

    function Retornar() {
        $this->sql = "select ".$this->campos." from motivos_cancelamento where ativo = 'S' and idmotivo = '".$this->id."'";
        return $this->retornarLinha($this->sql);
    }

    function Cadastrar() {

        if($_POST['cancela_automatico'] == 'S'){
            $motivo = $this->retornarMotivoCancelar();
            if($motivo){
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_existe';
                $this->retorno["sucesso"] = false;
                return $this->retorno;
            }
        }
        return $this->SalvarDados();
    }

    function Modificar() {

        if($_POST['cancela_automatico'] == 'S'){
            $motivo = $this->retornarMotivoCancelar();
            if($motivo and $motivo['idmotivo'] != $_POST['idmotivo']){
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = 'erro_existe';
                $this->retorno["sucesso"] = false;
                return $this->retorno;
            }
        }
        return $this->SalvarDados();
    }

    function Remover() {
        return $this->RemoverDados();
    }

    function removePadrao(){
        $this->sql = "UPDATE motivos_cancelamento SET padrao = 'N' WHERE padrao = 'S';";
        $this->executaSql($this->sql);
    }

    public function retornarPadrao() {
        $this->sql = "select * from motivos_cancelamento where padrao = 'S'";
        return $this->retornarLinha($this->sql);
    }


}