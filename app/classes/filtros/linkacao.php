<?php

class LinkAcao extends core
{
    public function renderizar($conteudo)
    {
        $url = $GLOBALS['url'];

        $this->sql = "select acl.*
				  from
                    avas_conteudos_linksacoes acl
					inner join avas_conteudos ac on (ac.idconteudo = acl.idava_conteudo)
				  where 
                    acl.ativo = 'S' and 
                    ac.ativo = 'S' and 
					acl.idava_conteudo = " . $conteudo['idconteudo'];

        $this->limite = -1;
        $this->groupby = "acl.idlinkacao";

        $linhas = $this->retornarLinhas();

        foreach ($linhas as $key => $value) {
            if($value['tipo'] == 'L'){
                $escopo = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '/' . $url[3] . '/' . $url[4] . '/link/' . $value['idlinkacao'] . '/' . $value['idava_conteudo'];
            } else {
                $escopo = 'acaoButton(' . $value['idlinkacao'] . ');';
            }
            
            $conteudo['conteudo'] = str_replace($value['variavel'], $escopo, $conteudo['conteudo']);
        }

        return $conteudo['conteudo'];
    }
}