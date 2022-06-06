<?php
/**
 * undocumented class
 *
 * @package default
 * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
 */
class Video implements Filtro
{
    /**
     * undocumented function
     *
     * @param $conteudo
     *
     * @return string
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function renderizar($conteudo)
    {
        preg_match_all('/\[\[video\]\[(\d+)\]\]/i', $conteudo, $matches);

        /** Class Videoteca usada para pegar informações dos vídeos */
        $videoTeca = new VideoTeca(new Core);

        foreach ($matches[0] as $position => $tag) {
            $information = $videoTeca->retornar($matches[1][$position]);
            $conteudo = $this->_render($information, $tag, $conteudo);
        }
        return $conteudo;
    }

    /**
     * undocumented function
     *
     * @param $information
     * @param $tag
     * @param $conteudo
     *
     * @return void
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    private function _render($information, $tag, $conteudo)
    {
        if ('youtube' == $information['variavel'] || 'vimeo' == $information['variavel']) {
            return $this->_renderYoutube($information, $tag, $conteudo);
        }

        return $this->_renderHtml($information, $tag, $conteudo);
    }

    /**
     * undocumented function
     *
     * @param $information
     * @param $tag
     * @param $conteudo
     *
     * @return void
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    private function _renderYoutube($information, $tag, $conteudo)
    {
        $youtube = '<iframe src="' . $information['arquivo'] . '?quality=540p" webkitallowfullscreen mozallowfullscreen allowfullscreen border="0"
            width="100%" height="400" style="border: medium none"></iframe>';

        return str_ireplace($tag, $youtube, $conteudo);
    }

    /**
     * undocumented function
     *
     * @param $information
     * @param $tag
     * @param $conteudo
     *
     * @return void
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    private function _renderHtml($information, $tag, $conteudo)
    {
        if($GLOBALS['config']['videoteca_local']){
            $poster  = '/storage/videoteca/' . Videoteca::getFolder($information['idvideo'], 'caminho') . '/' . $information['idvideo'] . '/' . $information['imagem'].'.jpg';
            $source = '/storage/videoteca/' . Videoteca::getFolder($information['idvideo'], 'caminho') . '/' . $information['idvideo'] . '/' . $information['arquivo'] . '_hd.mp4';
        }else{
            $dominio = $GLOBALS['config']['videoteca_endereco'][rand(0,(count($GLOBALS['config']['videoteca_endereco']) - 1 ))];
            $poster  = $dominio.'/' . Videoteca::getFolder($information['idvideo'], 'caminho') .'/' . $information['video_imagem'];
            $source = $dominio.'/' . Videoteca::getFolder($information['idvideo'], 'caminho') .'/' . $information['video_nome'];
        }


        $html = '<video id="video_' . $information['idvideo'] . '" width="100%" height="400px"
            		controls="controls" preload="none"
                    poster="'.$poster.'">
            	    <source src="'.$source.'" type=\'video/mp4\' />
        	   </video>';

        return str_ireplace($tag, $html, $conteudo);
    }
}