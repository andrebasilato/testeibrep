<?php
class Conteudos extends Ava
{
    const APROVADO = 1;
    var $idava = NULL;

    function ListarTodasConteudo()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    avas_conteudos c
                    inner join avas a on (c.idava = a.idava)
                  where
                    c.ativo = 'S' and
                    a.idava = " . $this->idava;


        $this->aplicarFiltrosBasicos();
        $this->groupby = "c.idconteudo";
        return $this->retornarLinhas();
    }

    function RetornarConteudo()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    avas_conteudos c
                    inner join avas a on c.idava = a.idava
                  where
                    c.ativo = 'S' and
                    c.idconteudo = '" . $this->id . "' and
                    a.idava = " . $this->idava;
        return $this->retornarLinha($this->sql);
    }

    function CadastrarConteudo()
    {
        $this->post["idava"] = $this->idava;
        $this->config['formulario'] = $this->alterarConfigFormulario($this->config['formulario'], ['conteudo_cke', 'conteudo_online', 'html']);

        return $this->SalvarDados();
    }

    function ModificarConteudo()
    {
        $this->post["idava"] = $this->idava;
        if ($this->post["tipo_edicao"] == 'B') {
            $this->config['formulario'] = $this->alterarConfigFormulario($this->config['formulario'], ['conteudo_cke', 'html'], ['conteudo_online' => 'conteudo']);
            if ($this->post["tipo_edicao_atual"] != 'B') {
                $this->campos = "cf.*";
                $frames = $this->ListarTodosConteudosFrames();
                if (empty($frames)) {
                    $this->campos = "c.conteudo";
                    $conteudoAtual = $this->RetornarConteudo();
                    if (! empty($conteudoAtual["conteudo"])) {
                        $conteudoAtual["conteudo"] = str_replace('\'', "\\'", $conteudoAtual["conteudo"]);
                        $conteudo = htmlspecialchars("<html><head>
						    <meta charset=\'utf-8\'>
						    <title>Texto</title>
						    <meta name=\'viewport\' content=\'width=device-width, initial-scale=1.0\'>

						    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
						    <!--[if lt IE 9]>
						      <script src=\"js/html5shiv.js\"></script>
						      <script src=\"js/respond.min.js\"></script>
						    <![endif]-->
						<link rel=\'stylesheet\' type=\'text/css\' href=\'/assets/min/aplicacao.aluno.min.css\'>
						<link href=\'//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css\' rel=\'stylesheet\'>
						<link rel=\'stylesheet\' href=\'//cdn.jsdelivr.net/medium-editor/latest/css/medium-editor.min.css\' type=\'text/css\' media=\'screen\' charset=\'utf-8\' id=\'mediumCss0\'><link rel=\'stylesheet\' href=\'/assets/plugins/ibrepbuilder/elements/css/medium-bootstrap.css\' type=\'text/css\' media=\'screen\' charset=\'utf-8\' id=\'mediumCss1\'>
						<link rel=\'stylesheet\' type=\'text/css\' href=\'/assets/plugins/ibrepbuilder/bundles/fa-editor.css\'>
						<style>
							html {
								background: white !important;
							}
							body {
								background: white !important;
							}
						</style></head>
						<body>
							<div id=\'page\' class=\'page contents\'>
								<div class=\'item container-fluid\' style=\'padding-left: 0px; padding-right: 0px;\'>
						    ");
                        if($conteudoAtual["conteudo"] == strip_tags($conteudoAtual["conteudo"]))
                            $conteudo .= htmlspecialchars("<div class=\'editContent\'>");

                        $conteudo .= htmlspecialchars($conteudoAtual["conteudo"]);

                        if($conteudoAtual["conteudo"] == strip_tags($conteudoAtual["conteudo"]))
                            $conteudo .= htmlspecialchars("</div>");

                        $conteudo .= htmlspecialchars("<script type=\'text/javascript\' src=\'/assets/plugins/ibrepbuilder/elements/bundles/original_content.bundle.js\'></script>

						</div></div></body>");

                        $this->CadastrarFrame(htmlspecialchars_decode($conteudo), 1000, "/assets/plugins/ibrepbuilder/elements/original/texto_simples.html");
                    }
                }
            }
        } else if ($this->post["tipo_edicao"] == 'T') {
            $this->config['formulario'] = $this->alterarConfigFormulario($this->config['formulario'], ['conteudo_online', 'html'], ['conteudo_cke' => 'conteudo']);
            if($this->post["tipo_edicao_atual"] != 'T') {
                $this->campos = "c.conteudo";
                $conteudo = $this->RetornarConteudo();
                $this->post["conteudo"] = $conteudo["conteudo"];
                $this->RemoverFrames();
            }
        } else if($this->post["tipo_edicao"] == 'A') {
            $this->config['formulario'] = $this->alterarConfigFormulario($this->config['formulario'], ['conteudo_cke', 'conteudo_online']);
            if($this->post["tipo_edicao_atual"] != 'A') {
                $this->config['formulario'] = $this->alterarConfigFormulario($this->config['formulario'], ['html']);
                $this->campos = "c.conteudo";
                $conteudo = $this->RetornarConteudo();
                $this->post["conteudo"] = $conteudo["conteudo"];
                $this->RemoverFrames();
            }
        }

        return $this->SalvarDados();
    }

    function RemoverConteudo()
    {
        return $this->RemoverDados();
    }

    function RemoverArquivo($modulo, $pasta, $dados, $idioma)
    {
        echo $this->ExcluirArquivo($modulo, $pasta, $dados, $idioma);
    }

    function cadastrarLinkAcao()
    {
        $retorno = $this->SalvarDados();

        if($this->post['tipo'] == 'A'){
            $variavel = '[[TRACK][' . $retorno['id']  . ']]';
        } else {
            $variavel = '[[LINK][' . $retorno['id']  . ']]';
        }

        if ($retorno['sucesso']) {
            $sql = "UPDATE " . $this->config['banco']['tabela']
                    . " SET variavel = '" . $variavel  . "'"
                    . " WHERE " . $this->config['banco']['primaria'] . " = " . $retorno['id'];
            $this->executaSql($sql);
        }

        return $retorno;
    }

    function retornarLinksAcoes()
    {
        $this->sql = "select
                    " . $this->campos . "
                  from
                    avas_conteudos_linksacoes acl
                    inner join avas_conteudos ac on (ac.idconteudo = acl.idava_conteudo)
                  where
                    acl.ativo = 'S' and
                    ac.ativo = 'S' and
                    acl.idava_conteudo = " . $this->idconteudo;

        $this->limite = -1;
        $this->groupby = "acl.idlinkacao";

        return $this->retornarLinhas();
    }

    function removerLinKAcao()
    {
        return $this->RemoverDados();
    }

    function retornarLinkAcao()
    {
        $this->sql = "select
                    " . $this->campos . "
                    from
                    avas_conteudos_linksacoes acl
                    inner join avas_conteudos ac on (ac.idconteudo = acl.idava_conteudo)
                  where
                    acl.ativo = 'S' and
                    ac.ativo = 'S' and
                    acl.idlinkacao = " . $this->id;

        return $this->retornarLinha($this->sql);
    }

    public function confirmarClique()
    {
        $sql = "INSERT INTO matriculas_linksacoes_cliques
                (idlinkacao, idmatricula, data_cad) VALUES ("
                . $this->linkAcao['idlinkacao'] . ', ' . $this->matricula . ", NOW());";

        return $this->executaSql($sql);
    }

    public function verificaSeExisteObjetoReconhecimento()
    {
        $avas = array();
        foreach ($this->idava as $key => $value) {
            $avas[] = $value['idava'];
        }

        $this->sql = "SELECT count(*)
            FROM avas_rotas_aprendizagem_objetos arao
            INNER JOIN avas_rotas_aprendizagem ara ON (ara.idrota_aprendizagem = arao.idrota_aprendizagem)
            WHERE arao.tipo = 'reconhecimento'
            AND arao.ativo = 'S'
            AND ara.idava in (" . implode(', ', $avas) . ")";

        return $this->retornarLinha($this->sql);
    }

    public function verificaPreRequisito($rota, $idobjetorota, $matricula, $dadosSindicato, $curso)
    {
            $contador = 0;
            $contadorReal = 0;
            $contadorAux = 0;
            foreach ($rota as $key => $value) {
                if($value['tipo'] == 'reconhecimento'){
                    $this->sql = 'SELECT count(0) FROM reconhecimento_fotos
                            WHERE idmatricula = ' . $GLOBALS['idmatricula'] .
                            ' AND idobjetorota = ' . $value['idobjeto'] .
                            ' AND ativo = \'S\'' .
                            ' AND ativo_painel = \'S\'' .
                            ' AND resultado = \'S\'';
                    $quantidade = $this->retornarLinha($this->sql)['count(0)'];

                    if($quantidade == 0 && $matricula['biometria_liberada'] === 'N'){
                        $retorno['sucesso'] = false;
                        $retorno['ordem'] = $contadorReal + $contador + 1;
                        return $retorno;
                    } elseif($quantidade >= 1){
                        $retorno['sucesso'] = true;
                        $retorno['ordem'] = $contadorReal + $contador + 2;

                        if($value['ordem'] > $idobjetorota + $contador){
                            return $retorno;
                        }
                    }
                    $contador++;
                } elseif($value['tipo'] != 'objeto_divisor'){
                    $contadorReal++;
                } else {
                    $contadorAux++;
                }

                if($idobjetorota == $contadorReal + $contador){
                    if ($value['tipo'] == 'reconhecimento' && count($rota) >= $retorno['ordem']) {
                        return $retorno;
                    }
                    break;
                }
            }
    }

    //Não aplicar try catch no método abaixo e não chamar o método retornarLinhas da classe core.
    public function retornarNaoClicados($idMatricula)
    {
        $this->sql = "SELECT acl.* FROM avas_conteudos_linksacoes acl
            WHERE acl.idava_conteudo = " . $this->idava_conteudo . "
            AND acl.ativo = 'S'
            AND ((SELECT count(0) FROM matriculas_linksacoes_cliques WHERE idlinkacao = acl.idlinkacao AND idmatricula = " . (int)$idMatricula . " AND ativo = 'S') = 0) group by acl.idlinkacao";

        $resultado = mysql_query($this->sql);
        while ($linha = mysql_fetch_assoc($resultado)) {
            $this->retorno[] = $linha;
        }

        return $this->retorno;

    }

    public function verificaNaoClicadosUrl($rota, $idava, $idobjetorota, $idMatricula)
    {
        $contador = 0;
        $contadorReal = 0;
        foreach ($rota as $key => $value) {
            if($value['tipo'] == 'reconhecimento'){
                $contador++;

                if($value['ordem'] >= $idobjetorota + $contador){
                    break;
                }
            } elseif($value['tipo'] == 'conteudo') {
                $contadorReal++;
                if($value['ordem'] >= $idobjetorota + $contador - 1){
                    break;
                }
            } elseif($value['tipo'] == 'exercicio') {
                $contadorReal++;
                if($value['ordem'] >= $idobjetorota + $contador - 1){
                    break;
                }
            }

            if(empty($value['idconteudo'])){
                continue;
            }

            $this->set('idava_conteudo', $value['idconteudo']);

            $naoClicados = $this->retornarNaoClicados($idMatricula);

            if(!empty($naoClicados) && count($naoClicados) > 0){
                $retorno['sucesso'] = false;
                $retorno['ordem'] = $value['ordem'];
                $retorno['redirecionar'] = $contadorReal + $contador;

                return $retorno;
            }
        }

        $retorno['sucesso'] = true;
        return $retorno;
    }

    public function verificarDias($objeto, $data_inicio_ava)
    {
        if(!empty($data_inicio_ava) && !empty($objeto['dias'])){
            $data = explode(' ', $data_inicio_ava)[0];
            $dataMinima = date('Y/m/d', strtotime($data. " + {$objeto['dias']} days"));
            if($dataMinima > date('Y/m/d')){
                $retorno['sucesso'] = false;
                $retorno['data'] = formataData(str_replace('/', '-', $dataMinima), 'br', 0);
                return $retorno;
            }
        }else{
            $retorno['sucesso'] = false;
        }
        $retorno['sucesso'] = true;
        return $retorno;
    }

    function ListarTodosConteudosFrames() {
        $this->sql = "select
                        ".$this->campos."
                      from
                        avas_conteudos_frames cf
                        inner join avas_conteudos c on (cf.idconteudo = c.idconteudo)
                        inner join avas a on (c.idava = a.idava)
                      where
                        cf.ativo = 'S' and
                        a.idava = ".$this->idava." and
                        cf.idconteudo = ".$this->id;

        $this->groupby = "cf.idframe";
        return $this->retornarLinhas();
    }

    function ModificarCampoConteudo($conteudo)
    {
        $this->sql = "select * from avas_conteudos where ativo = 'S' and idconteudo = ".$this->id;

        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update
                        avas_conteudos
                      set
                        conteudo = '".$conteudo."'
                      where
                        idconteudo = '".$this->id."'";
        $executa = $this->executaSql($this->sql);

        $this->sql = "select * from avas_conteudos where idconteudo = ".$this->id;

        $linhaNova = $this->retornarLinha($this->sql);

        if($executa){
            $this->monitora_oque = 2;
            $this->monitora_onde = 23;
            $this->monitora_qual = $this->idconteudo;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();

            $this->retorno["sucesso"] = true;
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    function RetornarFrame($idFrame)
    {
        $this->sql = "select
                        " . $this->campos . "
                      from
                        avas_conteudos_frames
                    where
                        ativo = 'S' and
                        idframe = " . $idFrame;
        return $this->retornarLinha($this->sql);
    }

    function prepararFrames()
    {
        $conteudoConteudo = '';
        foreach ($this->post["pages"] as $page => $pageData) {
            $this->RemoverFrames();
            if (isset($pageData["blocks"])) {
                $bConfig = ['editableItems' =>
                    ['span.fa' => ['color', 'font-size'],
                        '.bg.bg1' => ['background-color'],
                        'nav a' => ['color', 'font-weight', 'text-transform'],
                        'img' => ['border-top-left-radius', 'border-top-right-radius', 'border-bottom-left-radius', 'border-bottom-right-radius', 'border-color', 'border-style', 'border-width'],
                        'hr.dashed' => ['border-color', 'border-width'],
                        '.divider > span' => ['color', 'font-size'],
                        'hr.shadowDown' => ['margin-top', 'margin-bottom'],
                        '.footer a' => ['color'],
                        '.social a' => ['color'],
                        '.bg.bg1, .bg.bg2, .header10, .header11' => ['background-image', 'background-color'],
                        '.frameCover' => [],
                        '.editContent' => ['content', 'color', 'font-size', 'background-color', 'font-family'],
                        'a.btn, button.btn' => ['border-radius', 'font-size', 'background-color'],
                        '#pricing_table2 .pricing2 .bottom li' => ['content']]
                    ,
                    'editableContent' =>
                        ['.editContent', '.navbar a', 'button', 'a.btn', '.footer a:not(.fa)', '.tableWrapper', 'h1', 'h2']
                ];
                foreach ($pageData["blocks"] as $block) {
                    $dom = new DomDocument;
                    libxml_use_internal_errors(true);
                    $dom->loadHTML($block['frameContent']);
                    libxml_use_internal_errors(false);
                    $xpath = new DOMXPath($dom);

                    $editContentList = $xpath->query('//div[contains(@class, "editContent")]');
                    foreach ($editContentList as $editContentNode) {
                        $editContentNode->removeAttribute('medium-editor-index');
                        $editContentNode->removeAttribute('contenteditable');
                        $editContentNode->removeAttribute('spellcheck');
                        $editContentNode->removeAttribute('data-medium-editor-element');
                        $editContentNode->removeAttribute('role');
                        $editContentNode->removeAttribute('aria-multiline');
                        $editContentNode->removeAttribute('data-medium-editor-editor-index');
                        $editContentNode->removeAttribute('medium-editor-index');
                        $editContentNode->removeAttribute('data-placeholder');
                        $editContentNode->removeAttribute('data-medium-focused');
                        $oldClasses = $editContentNode->getAttribute('class');
                        $oldStyle = $editContentNode->getAttribute('style');
                        $oldClasses = str_replace(' medium-editor-element', '', $oldClasses);
                        $editContentNode->setAttribute("class", $oldClasses);
                        $oldStyle = preg_replace("/outline\s*:\s*(\w+);\s*/", "", $oldStyle);
                        $oldStyle = preg_replace("/outline-offset\s*:\s*(\w+);\s*/", "", $oldStyle);
                        $oldStyle = preg_replace("/cursor\s*:\s*(\w+);\s*/", "", $oldStyle);
                        $editContentNode->setAttribute("style", $oldStyle);
                        if ($editContentNode->getAttribute('style') == '')
                            $editContentNode->removeAttribute('style');
                    }

                    $meapNodeList = $xpath->query('//div[contains(@class, "medium-editor-anchor-preview")]');
                    foreach ($meapNodeList as $meapNode)
                        $meapNode->parentNode->removeChild($meapNode);

                    $metapNodeList = $xpath->query('//div[contains(@class, "medium-editor-toolbar-anchor-preview")]');
                    foreach ($metapNodeList as $metapNode)
                        $metapNode->parentNode->removeChild($metapNode);

                    $metNodeList = $xpath->query('//div[contains(@class, "medium-editor-toolbar")]');
                    foreach ($metNodeList as $metNode)
                        $metNode->parentNode->removeChild($metNode);

                    $conteudoFrame = $dom->saveHTML();
                    $conteudoFrame = str_replace('\'', "\\'", $conteudoFrame);

                    $this->CadastrarFrame($conteudoFrame, $block['frameHeight'], $block["originalUrl"]);

                    $page = $dom->getElementById('page');
                    $children = $page->childNodes;

                    foreach ($children as $child) {
                        $newdoc = new DOMDocument();
                        $cloned = $child->cloneNode(TRUE);
                        $newdoc->appendChild($newdoc->importNode($cloned, TRUE));

                        $xp = new DOMXPath($newdoc);
                        $vwNodeList = $xp->query('//div[contains(@class, "videoWrapper")]');
                        $clonedChildren = array();

                        foreach ($vwNodeList as $vwNode) {
                            $parent = $vwNode->parentNode;
                            $vwChildren = $vwNode->childNodes;

                            foreach ($vwChildren as $vwChild)
                                array_push($clonedChildren, $vwChild->cloneNode(TRUE));

                            $vwNode->parentNode->removeChild($vwNode);

                            foreach ($clonedChildren as $clonedChild)
                                $parent->appendChild($newdoc->importNode($clonedChild, TRUE));
                        }

                        $fcNodeList = $xp->query('//div[contains(@class, "frameCover")]');

                        foreach ($fcNodeList as $fcNode)
                            $fcNode->parentNode->removeChild($fcNode);

                        foreach ($bConfig['editableItems'] as $key => $editableItem) {
                            $eiNodeList = $xp->query('//*[@*="' . $key . '"]');

                            foreach ($eiNodeList as $eiNode) {
                                $eiNode->removeAttribute('data-selector');
                                $eiNode->removeAttribute('contenteditable');
                                $eiNode->removeAttribute('spellcheck');
                                $eiNode->removeAttribute('data-medium-editor-element');
                                $eiNode->removeAttribute('role');
                                $eiNode->removeAttribute('aria-multiline');
                                $eiNode->removeAttribute('data-medium-editor-editor-index');
                                $eiNode->removeAttribute('medium-editor-index');
                                $eiNode->removeAttribute('data-placeholder');
                                $eiNode->removeAttribute('data-medium-focused');
                                $oldClasses = $eiNode->getAttribute('class');
                                $oldStyle = $eiNode->getAttribute('style');
                                $oldClasses = str_replace(' medium-editor-element', '', $oldClasses);
                                $eiNode->setAttribute("class", $oldClasses);
                                $oldStyle = preg_replace("/outline\s*:\s*(\w+);\s*/", "", $oldStyle);
                                $oldStyle = preg_replace("/outline-offset\s*:\s*(\w+);\s*/", "", $oldStyle);
                                $oldStyle = preg_replace("/cursor\s*:\s*(\w+);\s*/", "", $oldStyle);
                                $eiNode->setAttribute("style", $oldStyle);
                                if ($eiNode->getAttribute('style') == '')
                                    $eiNode->removeAttribute('style');
                            }
                        }

                        $conteudoConteudo .= $newdoc->saveHTML();
                    }
                }
            }
        }

        return str_replace('\'', "\\'", $conteudoConteudo);
    }

    function CadastrarFrame($conteudo, $altura, $urlOriginal) {
        $this->sql = "insert into
                        avas_conteudos_frames
                      set
                        idconteudo = '".$this->id."',
                        conteudo = '".$conteudo."',
                        altura = '".$altura."',
                        ativo = 'S',
                        data_cad = now(),
                        url_original = '".$urlOriginal."'";
        if($this->executaSql($this->sql)) {
            $this->monitora_qual = mysql_insert_id();
            $this->retorno["sucesso"] = true;
            $this->monitora_oque = 1;
            $this->monitora_onde = 286;
            $this->Monitora();
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }
        return $this->retorno;
    }

    function ModificarFrame($conteudo, $idFrame) {
        $this->sql = "select * from avas_conteudos_frames where idconteudo = ".$this->id." and idframe = ".intval($this->idframe);

        $linhaAntiga = $this->retornarLinha($this->sql);

        $this->sql = "update
                        avas_conteudos_frames
                      set
                        conteudo = '".$conteudo."'
                      where
                        idconteudo = '".$this->id."' and
                        idframe = ".intval($idFrame);
        $executa = $this->executaSql($this->sql);

        $this->sql = "select * from avas_conteudos_frames where idconteudo = '".$this->id."' and idframe = ".intval($idFrame);
        $linhaNova = $this->retornarLinha($this->sql);

        if($executa){
            $this->monitora_oque = 2;
            $this->monitora_onde = 286;
            $this->monitora_qual = $idFrame;
            $this->monitora_dadosantigos = $linhaAntiga;
            $this->monitora_dadosnovos = $linhaNova;
            $this->Monitora();

            $this->retorno["sucesso"] = true;
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }

        return $this->retorno;
    }

    function RemoverFrames() {
        $this->sql = "update avas_conteudos_frames set ativo = 'N' where idconteudo = ".$this->id;

        if($this->executaSql($this->sql)) {
            $this->sql = "select idframe from avas_conteudos_frames where idconteudo = ".$this->id;
            $linhaAntigas = $this->retornarLinhas();
            foreach($linhaAntigas as $linha) {
                $this->retorno["sucesso"] = true;
                $this->monitora_oque = 3;
                $this->monitora_onde = 286;
                $this->monitora_qual = $linha["idframe"];
                $this->Monitora();
            }
        } else {
            $this->retorno["erro"] = true;
            $this->retorno["erros"][] = $this->sql;
            $this->retorno["erros"][] = mysql_error();
        }
        return $this->retorno;
    }

    public function verificaTodosObjetosReconhecimento($ava)
    {
        $this->sql = "SELECT arao.idobjeto
            FROM avas_rotas_aprendizagem_objetos arao
            INNER JOIN avas_rotas_aprendizagem ara ON (ara.idrota_aprendizagem = arao.idrota_aprendizagem)
            WHERE arao.tipo = 'reconhecimento'
            AND arao.ativo = 'S'
            AND ara.idava = $ava";

        return $this->retornarLinhas($this->sql);
    }

    public function verificaObjetosReconhecimentoFoto($idmatricula, $idobjetorota)
    {
        $this->sql = "SELECT
                        idmatricula, idobjetorota, isidentical
                    FROM
                        reconhecimento_fotos rf
                    WHERE rf.ativo = 'S'
                    AND rf.idmatricula = $idmatricula
                    AND rf.idobjetorota = $idobjetorota
                    AND rf.isidentical = " . self::APROVADO;
        $queryString = mysql_query($this->sql);
        while ($linha = mysql_fetch_assoc($queryString)) {
            $retorno[] = $linha;
        }

        return $retorno;
    }
}

?>
