<?php

class Polo
{
    private $funcoesComuns;
    private $acessoBanco;

    public function __construct(\OrIO\FuncoesComuns $funcoesComuns)
    {
        $this->acessoBanco = $funcoesComuns->acessoBanco;
        $this->funcoesComuns = $funcoesComuns;
        $this->acessoBanco->ignorarTratamentoErro = true;
    }

    public function retornarPolos()
    {
        $this->acessoBanco->sql = "SELECT
                p.idpolo,
                p.nome_fantasia,
                p.endereco,
                p.bairro,
                p.numero,
                c.nome as cidade,
                e.nome as estado,
                e.sigla
            FROM
                polos p
                INNER JOIN instituicoes i ON (p.idinstituicao = i.idinstituicao)
                INNER JOIN cidades c ON (p.idcidade = c.idcidade)
                INNER JOIN estados e ON (c.idestado = e.idestado)
            where
                p.ativo = 'S'";
                
        $this->acessoBanco->limite = -1;
        $this->acessoBanco->groupby = "p.idpolo";
        $polos = $this->acessoBanco->retornarLinhas();  
        
        if (empty($polos)) {
            throw new \Exception("erro_retornar_polos", 500);            
        }

        foreach ($polos as $key => $polo) {
            $endereco = '';
            $endereco .= (!empty($polo['endereco'])) ? $polo['endereco'].', ' : '';
            $endereco .= (!empty($polo['bairro'])) ? $polo['bairro'].', ' : '';
            $endereco .= (!empty($polo['numero'])) ? $polo['numero'].', ' : '';
            $endereco .= (!empty($polo['cidade']) && !empty($polo['sigla'])) ? $polo['cidade'].'/'.$polo['sigla'] : '';
                       
            $retorno[] = [
                'idpolo' => $polo['idpolo'],
                'nome' => $polo['nome_fantasia'],
                'endereco' => $endereco
            ];
        }
        
        return $retorno;
    }
}
