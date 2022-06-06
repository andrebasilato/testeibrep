<?php 
class Log_Emails extends Core {
    
    public function iniciaConexao($host,$usuario,$senha,$banco)
    {
       $connect = @mysql_connect($host,$usuario,$senha);
       @mysql_select_db($banco); 

       return $connect;
    }

   public function fechaConexao($conexao)
    {
       @mysql_close($conexao);
    }

    function ListarTodas() {        
        $this->sql = "SELECT
                    ".$this->campos." 
                  FROM 
                    emails_log el
                  WHERE 
                    1 = 1";
        
        $this->aplicarFiltrosBasicos();
        $this->groupby = "el.idemail";
        return $this->retornarLinhas();
    }
    
    function Retornar() {
        $this->sql = "SELECT 
                    ".$this->campos." 
                  FROM 
                    emails_log el
                  WHERE 
                    el.idemail = '".$this->id."'";      
        return $this->retornarLinha($this->sql);
    }   

    function reenviarEmail($idemail, $emailReenvio)
    {
        $this->id = $idemail;
        $this->campos = "*";
        $email = $this->Retornar();

        $this->Set("reenviar", true);

        if ($email) {
            ($emailReenvio == $email['para_email']) ? $this->naoSalvarLogEmail = true : $this->Set("email_reenvio", $emailReenvio) ;
            $enviar = $this->enviarEmail(
                $email['de_nome'],
                $email['de_email'],
                $email['assunto'],
                $email['mensagem'],
                $email['para_nome'],
                $email['para_email'],
                'layout',
                'utf-8'
            );
        }

        if (!$enviar) {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
            return $this->retorno;
        } else {
            if (!$this->salvarLogEmail) {
                $sql = 'UPDATE 
                                emails_log
                            SET
                                qnt_reenvio = qnt_reenvio+1
                            WHERE
                                idemail = '.$this->id;
                $this->executaSql($sql);
                $this->retorno["sucesso"] = true;
                $this->retorno["id"] = $email["idemail"];
                
                if (!$this->naomonitora) {
                    $this->monitora_oque = 13;
                    $this->monitora_onde = 209;
                    $this->monitora_qual = $email["idemail"];
                    $this->Monitora();
                }
            }
        }
        return $this->retorno;
    }

}

?>