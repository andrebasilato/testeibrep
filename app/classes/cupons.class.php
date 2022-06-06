<?php
/**
 * `Cupons`
 *
 * @author     Gabriel Leite    <gabriel@alfamaweb.com.br>
 * @author     Henrique Feitosa <henriquef@alfamaweb.com.br>
 * @author     Jefersson Nathan <jeferssonn@alfamaweb.com.br>
 *
 * @package    Oráculo
 * @copyright  Copyright (c) 2014 Alfama Web (http://alfamaweb.com.br)
 * @license    Proprietary AlfamaWeb
 * @version    $Id$
 */
class Cupons extends Core
{
    /**
     * Lista todas as entradas da tabela `cupons`
     *
     * @return array
     */
    public function listarTodas() {
        $this->sql = sprintf('SELECT %s FROM cupons WHERE ativo = "S"', $this->campos);
        $this->aplicarFiltrosBasicos()->set('groupby', 'idcupom');

        return $this->retornarLinhas();
    }

    /**
     * Retorna um única linha do banco de dados
     *
     * @return array
     */
    public function retornar() {
        $this->sql = sprintf('SELECT %s FROM cupons WHERE ativo = "S" AND idcupom = %d', $this->campos, $this->id);

        return $this->retornarLinha($this->sql);
    }

    /**
     * @return array
     */
    public function cadastrar() {
        $configRemover = array('valor');
        $configRenomear = array();
        if ($this->post['tipo_desconto'] == 'V') {
            $configRemover = array('porcentagem');
        }
        $this->config['formulario'] = $this->alterarConfigFormulario($this->config['formulario'], $configRemover, $configRenomear);

        return $this->salvarDados();
    }

    /**
     * @return array
     */
    public function modificar() {
        $configRemover = array('valor');
        $configRenomear = array();
        if ($this->post['tipo_desconto'] == 'V') {
            $configRemover = array('porcentagem');
        }
        $this->config['formulario'] = $this->alterarConfigFormulario($this->config['formulario'], $configRemover, $configRenomear);


        return $this->salvarDados();
    }

    /**
     * @return array
     */
    public function remover() {
        return $this->removerDados();
    }

    function BuscarEscola() {      
        $this->sql = "select 
                        p.idescola as 'key', p.nome_fantasia as value 
                      from
                        escolas p 
                      where 
                         p.nome_fantasia like '%".$_GET["tag"]."%' AND p.ativo = 'S' AND p.ativo_painel = 'S' AND
                         not exists (select cp.idescola from cupons_escolas cp where cp.idescola = p.idescola and cp.idcupom = '".$this->id."' and cp.ativo = 'S')";
        $this->limite = -1;
        $this->ordem_campo = "value";
        $this->groupby = "value";
        
        $dados = $this->retornarLinhas();                       
        return json_encode($dados);     
    }
    
    function ListarEscolasAss() {      
        $this->sql = "select 
                        ".$this->campos." 
                      from
                        escolas p
                        inner join cupons_escolas cp ON (p.idescola = cp.idescola) 
                      where 
                        cp.ativo = 'S' and 
                        cp.idcupom = ".intval($this->id);
        
        $this->groupby = "cp.idcupom_escola";        
        return $this->retornarLinhas();
    }   
    
    function AssociarEscolas($idcupom, $arrayCupons) {
        foreach($arrayCupons as $ind => $id) {
                    
              $this->sql = "select count(idcupom_escola) as total, idcupom_escola from cupons_escolas where idcupom = '".intval($idcupom)."' and idescola = '".intval($id)."'";
              $totalAss = $this->retornarLinha($this->sql); 
              if($totalAss["total"] > 0) {
                  $this->sql = "update cupons_escolas set ativo = 'S' where idcupom_escola = ".$totalAss["idcupom_escola"];
                  $associar = $this->executaSql($this->sql);
                  $this->monitora_qual = $totalAss["idcupom_escola"];                 
              } else {
                  $this->sql = "insert into cupons_escolas set ativo = 'S', data_cad = now(), idcupom = '".intval($idcupom)."', idescola = '".intval($id)."'";
                  $associar = $this->executaSql($this->sql);
                  $this->monitora_qual = mysql_insert_id();
              }         
            
            if($associar){
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 222;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
            
        }
        return $this->retorno;
    }   
    
    function DesassociarEscolas() {
        
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
            $this->sql = "update cupons_escolas set ativo = 'N' where idcupom_escola = ".intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if($desassociar){
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 222;
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
    
    function BuscarCurso() {      
        $this->sql = "select 
                        c.idcurso as 'key', c.nome as value 
                      from
                        cursos c 
                      where 
                         c.nome like '%".$_GET["tag"]."%' AND c.ativo = 'S' AND c.ativo_painel = 'S' AND
                         not exists (select cc.idcurso from cupons_cursos cc where cc.idcurso = c.idcurso and cc.idcupom = '".$this->id."' and cc.ativo = 'S')";
        $this->limite = -1;
        $this->ordem_campo = "value";
        $this->groupby = "value";
        
        $dados = $this->retornarLinhas();                       
        return json_encode($dados);     
    } 

    function ListarCursosAss() {      
        $this->sql = "select 
                        ".$this->campos." 
                      from
                        cursos c
                        inner join cupons_cursos cc ON (c.idcurso = cc.idcurso) 
                      where 
                        cc.ativo = 'S' and 
                        cc.idcupom = ".intval($this->id);
        
        $this->groupby = "cc.idcupom_curso";        
        return $this->retornarLinhas();
    }  
    
    function AssociarCursos($idcupom, $arrayCupons) {
        foreach($arrayCupons as $ind => $id) {
                    
              $this->sql = "select count(idcupom_curso) as total, idcupom_curso from cupons_cursos where idcupom = '".intval($idcupom)."' and idcurso = '".intval($id)."'";
              $totalAss = $this->retornarLinha($this->sql); 
              if($totalAss["total"] > 0) {
                  $this->sql = "update cupons_cursos set ativo = 'S' where idcupom_curso = ".$totalAss["idcupom_curso"];
                  $associar = $this->executaSql($this->sql);
                  $this->monitora_qual = $totalAss["idcupom_curso"];                 
              } else {
                  $this->sql = "insert into cupons_cursos set ativo = 'S', data_cad = now(), idcupom = '".intval($idcupom)."', idcurso = '".intval($id)."'";
                  $associar = $this->executaSql($this->sql);
                  $this->monitora_qual = mysql_insert_id();
              }         
            
            if($associar){
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 1;
                $this->monitora_onde = 223;
                $this->Monitora();
            } else {
                $this->retorno["erro"] = true;
                $this->retorno["erros"][] = $this->sql;
                $this->retorno["erros"][] = mysql_error();
            }
            
        }
        return $this->retorno;
    }   
    
    function DesassociarCursos() {
        
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
            $this->sql = "update cupons_cursos set ativo = 'N' where idcupom_curso = ".intval($this->post["remover"]);
            $desassociar = $this->executaSql($this->sql);

            if($desassociar){
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 223;
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