<?php
?>
<div class="container-fluid" style="width:750px;">
    <div class="row-fluid">
        <div class="span12">
            <div class="page-header">
                <h1><?= $idioma['titulo'] ?></h1>
            </div>
            <br>
            <br?>
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1" data-toggle="tab"><?= $idioma['informacoes_gerais'] ?></a></li>
                    <li><a href="#tab2" data-toggle="tab"><?= $idioma['get'] ?></a></li>
                    <li><a href="#tab3" data-toggle="tab"><?= $idioma['post'] ?></a></li>
                    <li><a href="#tab4" data-toggle="tab"><?= $idioma['xml_requisicao'] ?></a></li>
                    <li><a href="#tab5" data-toggle="tab"><?= $idioma['xml_resposta'] ?></a></li>
                    <li><a href="#tab6" data-toggle="tab"><?= $idioma['json'] ?></a></li>
                    <li><a href="#tab8" data-toggle="tab"><?= $idioma['json_resposta'] ?></a></li>
                    <li><a href="#tab7" data-toggle="tab"><?= $idioma['server'] ?></a></li>
                    <?php
                    if ($linha['erro']) {
                        ?>
                        <li><a href="#tab10" data-toggle="tab"><?= $idioma['erro'] ?></a></li>
                        <?php
                    }
                    ?>
                    <li><a href="#tab9" data-toggle="tab"><?= $idioma['reprocessar_xml'] ?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1">
                        <?php
                        if ($linha['idtransacao_reprocessada']) { ?>
                            <div class="alert alert-info fade in">
                                <strong>Transação realizada a partir de um reprocessamento: <a href="?q[1|idtransacao]=<?=$linha['idtransacao_reprocessada']?>"><?= $linha['idtransacao_reprocessada'] ?></a> </strong>
                            </div>
                        <?php
                        } elseif ($linha['idtransacaoGerada']) { ?>
                            <div class="alert alert-info fade in">
                                <strong>Transação reprocessada gerou a transação: <a href="?q[1|idtransacao]=<?=$linha['idtransacaoGerada']?>"><?= $linha['idtransacaoGerada'] ?></a> </strong>
                            </div>
                        <?php
                        } ?>
                        <p><strong><?= $idioma['id'] ?>: </strong><?= $linha['idtransacao'] ?></p>
                        <p><strong><?= $idioma['interface'] ?>: </strong><?= $GLOBALS["orio_interfaces_label"]["pt_br"][$linha["idinterface"]] ?></p>
                        <p><strong><?= $idioma['descricao'] ?>: </strong><?= $GLOBALS["orio_interfaces_descricoes"]["pt_br"][$linha["idinterface"]] ?></p>
                        <p><strong><?= $idioma['tipo'] ?>: </strong><span class="label" style="background-color:#<?= $GLOBALS["tipo_transacao_cores"][$linha["tipo"]] ?>;"><?= $GLOBALS["tipo_transacao"]["pt_br"][$linha["tipo"]] ?></span></p>
                        <p><strong><?= $idioma['ip'] ?>: </strong><?= $linha['ip'] ?></p>
                        <p><strong><?= $idioma['situacao'] ?>: </strong><span class="label" style="background-color:#<?= $GLOBALS["situacao_transacao_cores"][$linha["situacao"]]?>"><?= $GLOBALS["situacao_transacao"]["pt_br"][$linha["situacao"]] ?></span></p>
                        <p><strong><?= $idioma['tempo'] ?>: </strong><?= $linha['tempo'] ?></p>
                        <p><strong><?= $idioma['data_requisicao'] ?>: </strong><?= formataData($linha['data_cad'], 'br', 1) ?></p>
                    </div>
                    <div class="tab-pane" id="tab2">
                        <p>
                        <?php
                        $gets = json_decode($linha['_get'], true);
                        if ($gets) {
                            if(is_array($gets)){
                            foreach ($gets as $ind => $val) {
                                if (is_array($val)) {
                                    $json = json_encode($gets, JSON_PRETTY_PRINT);
                                    echo "<pre class='prettyprint'>" . htmlentities($json) . "</pre>";
                                } else {
                                    echo "<strong>" .$ind . ":</strong> " . $val . " <br> ";
                                }
                            }
                            } else {
                                echo "<strong>URL:</strong> " . $gets ." <br> ";
                            }
                        } else {
                            echo $idioma['sem_informacao'];
                        } ?>
                        </p>
                    </div>
                    <div class="tab-pane" id="tab3">
                        <p>
                        <?php
                        $posts = json_decode($linha['_post'], true);
                        if ($posts) {
                            $json = json_encode($posts, JSON_PRETTY_PRINT);
                            echo "<pre class='prettyprint'>" . htmlentities($json) . "</pre>";
                        } else {
                            echo $idioma['sem_informacao'];
                        } ?>
                        </p>
                    </div>
                    <div class="tab-pane" id="tab4">
                        <p>
                        <?php
                        $xml = $linha['xml_requisicao'];
                        if ($xml) {
                            echo "<pre class='prettyprint'>" . htmlentities($xml) . "</pre>";
                        } else {
                            echo $idioma['sem_informacao'];
                        } ?>
                        </p>
                    </div>
                    <div class="tab-pane" id="tab5">
                        <p>
                        <?php
                        $xml = $linha['xml_resposta'];
                        if ($xml) {
                            echo "<pre class='prettyprint'>" . htmlentities($xml) . "</pre>";
                        } else {
                            echo $idioma['sem_informacao'];
                        } ?>
                        </p>
                    </div>
                    <div class="tab-pane" id="tab6">
                        <p>
                        <?php
                        $json = $linha['json'];
                        if ($json) {
                            $json = json_decode($json, true);
                            $json = json_encode($json, JSON_PRETTY_PRINT);
                            echo "<pre class='prettyprint'>" . htmlentities($json) . "</pre>";
                        } else {
                            echo $idioma['sem_informacao'];
                        } ?>
                        </p>
                    </div>
                    <div class="tab-pane" id="tab8">
                        <p>
                        <?php
                            $jsonResposta = stripslashes(str_replace(['"{', '}"'], ['{', '}'], $linha['json_resposta']));
                            if ($jsonResposta) {
                                $jsonResposta = json_decode($jsonResposta);
                                $jsonResposta = json_encode($jsonResposta, JSON_PRETTY_PRINT);
                                echo '<pre class="prettyprint">' . $jsonResposta . '</pre>';
                            } else {
                                echo $idioma['sem_informacao'];
                            }
                        ?>
                        </p>
                    </div>
                    <div class="tab-pane" id="tab7">
                        <p>
                        <?php
                        $server = json_decode($linha['_server']);
                        if ($server) {
                            foreach ($server as $ind => $val) {
                                if (is_array($val)) {
                                    echo "<strong>" .$ind . ":</strong> <br>";
                                    foreach ($val as $i => $v) {
                                        echo " $i: " . $v . "<br>";
                                    }
                                } else {
                                    echo "<strong>" .$ind . ":</strong> " . $val . " <br> ";
                                }
                            }
                        } else {
                            echo $idioma['sem_informacao'];
                        } ?>
                        </p>
                    </div>
                    <?php
                    if ($linha['erro']) {
                        ?>
                        <div class="tab-pane" id="tab10">
                            <p>
                            <?php
                            $xml = $linha['erro'];
                            if ($xml) {
                                echo "<pre class='prettyprint'>" . htmlentities($xml) . "</pre>";
                            } else {
                                echo $idioma['sem_informacao'];
                            } ?>
                            </p>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="tab-pane" id="tab9">
                        <form method="post">
                            <input type="hidden" id="acao" name="acao" value="reprocessar">
                            <input type="hidden" id="idtransacao" name="idtransacao" value="<?= $linha['idtransacao']; ?>">
                            <p>
                                <?php
                                if (! empty($linha['json'])) {
                                    $jsonArray = json_decode($linha['json'], true);
                                    $jsonArray = json_encode($jsonArray, JSON_PRETTY_PRINT);
                                    echo '<textarea id="json_alterar" name="json_alterar" style="margin: 0px; width: 100%; height: 450px;">' . $jsonArray . '</textarea>';
                                } elseif ($linha['tipo'] == 'E' && !empty($linha['_post'])) {
                                    $jsonArray = json_decode($linha['_post'], true);
                                    $jsonArray = json_encode($jsonArray, JSON_PRETTY_PRINT);
                                    echo '<textarea id="json_alterar" name="json_alterar" style="margin: 0px; width: 100%; height: 450px;">' . $jsonArray . '</textarea>';
                                } elseif (! empty($linha['xml_requisicao'])) {
                                    echo '<textarea id="xml_alterar" name="xml_alterar" style="margin: 0px; width: 100%; height: 450px;">' . $linha['xml_requisicao'] . '</textarea>';
                                } else {
                                    echo $idioma['sem_informacao'];
                                }
                                ?>
                                <br /><br />
                                <input type="submit" class="btn btn-primary" value="<?= $idioma['btn_salvar']; ?>">
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
