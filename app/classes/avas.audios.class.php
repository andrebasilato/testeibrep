<?php
class Audios extends Ava
{

    var $idava = NULL;

    function ListarTodasAudio()
    {
        $this->sql = "select
					" . $this->campos . "
				  from
					avas_audios aa
					inner join avas a on (aa.idava = a.idava)
				  where 
					aa.ativo = 'S' and 
					a.idava = " . $this->idava;

        $this->aplicarFiltrosBasicos();
        $this->groupby = "aa.idaudio";
        return $this->retornarLinhas();
    }

    function RetornarAudio()
    {
        $this->sql = "select
					" . $this->campos . "
				  from
					avas_audios aa
					inner join avas a on aa.idava = a.idava
				  where 
					aa.ativo = 'S' and 
					aa.idaudio = '" . $this->id . "' and
					a.idava = " . $this->idava;
        return $this->retornarLinha($this->sql);
    }

    function CadastrarAudio()
    {
        $this->post["idava"] = $this->idava;

        return $this->SalvarDados();
    }

    function ModificarAudio()
    {
        $this->post["idava"] = $this->idava;

        $this->config["formulario"][0]["campos"][2]["validacao"] = array("formato_arquivo" => "arquivo_invalido");

        return $this->SalvarDados();
    }

    function RemoverAudio()
    {
        return $this->RemoverDados();
    }

    function RemoverArquivo($modulo, $pasta, $dados, $idioma)
    {
        echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
    }

}