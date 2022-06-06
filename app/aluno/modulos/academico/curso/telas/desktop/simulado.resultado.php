<? header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?php incluirLib("head", $config, $usuario); ?>
    <style>
        .correct-option {
            background-color: #468847;
            border-radius: 5px;
            color:black;
        }

        .uncorrect-option {
            background-color: #f67676;
            border-radius: 5px;
            color:black;
        }

        .critica {
            font-size: 13px;
            font-style: italic;
            text-decoration: none;
            border: solid 1px #eee;
            padding: 5px 5px;
            background-color: #f6f6f6;
        }
    </style>
</head>
<body>

<!-- Topo -->
<?php incluirLib("topo", $config, $usuario); ?>
<!-- /Topo -->
<!-- Topo curso -->
<?php incluirLib("topo_curso", $config, $informacoesTopoCurso); ?>
<!-- /Topo curso -->
<!-- Conteudo -->
<div class="content" style="position: relative;">
    <div class="row container-fixed">
        <!-- Menu Fixo -->
        <?php incluirLib("menu", $config, $usuario); ?>
        <!-- /Menu Fixo -->   
        <!-- Box -->
        <div class="box-side box-bg">
            <span class="top-box box-azul">
                <h1><?php echo $idioma['titulo']; ?></h1>
                <i class="icon-file-text-alt"></i>            
            </span>
            <h2 class="ball-icon">&bull;</h2> 
            <div class="clear"></div>
            <div class="row-fluid box-item">
                <div class="span12">                
                    <div class="abox extra-align">
                        <div class="row-fluid m-box">
                            <div class="span3">
                                <div class="imagem-item">
                                    <img src="/api/get/imagens/avas_simulados_imagem_exibicao/249/138/<?php echo $simulado["imagem_exibicao_servidor"]; ?>" alt="Avaliação" />
                                </div>
                            </div>
                            <div class="span9">
                                <div class="row-fluid show-grid">
                                    <div class="span12 description-item">
                                        <div class="span8">
                                            <h1><?php echo $prova['nome']; ?></h1>
                                        </div>                               
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <form class="ac-custom ac-radio ac-checkbox ac-fill ac-cross" id="formProva" name="formProva" autocomplete="off" method="post" enctype="multipart/form-data" >
                            <?php 
							$ordem = 0;
							$corretas = 0;
                            foreach ($prova['perguntas'] as $pergunta) { 
								$ordem++;

                                $totalCorretas[$pergunta['idpergunta']] = 0;
                                $totalMarcadas[$pergunta['idpergunta']] = 0;
                                $totalCorretasMarcadas[$pergunta['idpergunta']] = 0;
								?>
                                <div class="row-fluid">
                                    <div class="span12 extra-align border-box">
                                        <div><h6><?php echo $ordem; ?>) <?php echo $pergunta['nome']; ?></h6></div> 
                                        <?=  ($pergunta['critica']) ? '<div><p class="critica"><strong> <?= $idioma["critica"] ?> </strong>' . $pergunta['critica'] . '</p></div>' : ''; ?>
										<?php if ($pergunta['imagem_servidor']) { ?>
                                            <div>
                                                <img src="/api/get/imagens/disciplinas_perguntas_imagens/x/300/<?php echo $pergunta["imagem_servidor"]; ?>">
                                            </div>
										<?php } ?>
										<?php 
										$type = 'radio';
										if($pergunta['multipla_escolha'] == 'S')
                                            $type = 'checkbox';
										?>
                                        <ul style="margin: 0;">
                                            <?php 
											foreach ($pergunta['opcoes'] as $opcao) { 
												$opcao['marcada'] = 'N';
                                                if($opcao['correta'] == 'S')
                                                    $totalCorretas[$pergunta['idpergunta']]++;

                                                if($pergunta['multipla_escolha'] == 'S') {
                                                    if(in_array($opcao['idopcao'], $_POST['opcoes_multipla'][$pergunta['idpergunta']])) {
                                                        $totalMarcadas[$pergunta['idpergunta']]++;
                                                        $opcao['marcada'] = 'S';
                                                        if($opcao['correta'] == 'S')
                                                            $totalCorretasMarcadas[$pergunta['idpergunta']]++;
                                                    }
                                                } else {
                                                    if($opcao['idopcao'] == $_POST['opcoes_unica'][$pergunta['idpergunta']]) {
                                                        $totalMarcadas[$pergunta['idpergunta']]++;
                                                        $opcao['marcada'] = 'S';
                                                        if($opcao['correta'] == 'S')
                                                            $totalCorretasMarcadas[$pergunta['idpergunta']]++;
                                                    }
                                                }

                                                $id = 'pergunta['.$pergunta['id_prova_pergunta'].']['.$opcao['idopcao'].']';
												$name = 'opcoes_unica['.$pergunta['id_prova_pergunta'].']';
												if($pergunta['multipla_escolha'] == 'S') 
													$name = 'opcoes_multipla['.$pergunta['id_prova_pergunta'].']['.$opcao['idopcao'].']';
													?>
                                                <li>
                                                    <input id="<?php echo $id; ?>" name="<?php echo $name; ?>" type="<?php echo $type; ?>" value="<?php echo $opcao['idopcao']; ?>" <?php if($opcao['marcada'] == 'S') { ?>checked<?php } ?> disabled><label for="<?php echo $id; ?>"><p <?php if($opcao['correta'] == 'S') { ?> class="correct-option" <?php } else { ?> class="uncorrect-option" <?php } ?> ><?php if($opcao['correta'] == 'S') { ?><i class="icon-ok"></i><?php } ?><?php echo $opcao['ordem']; ?>) <?php echo $opcao['nome']; ?></p></label>
													<?php if($opcao['marcada'] == 'S') { ?>
														<?php if($pergunta['multipla_escolha'] == 'N') { ?>
															<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M15.833,24.334c2.179-0.443,4.766-3.995,6.545-5.359 c1.76-1.35,4.144-3.732,6.256-4.339c-3.983,3.844-6.504,9.556-10.047,13.827c-2.325,2.802-5.387,6.153-6.068,9.866 c2.081-0.474,4.484-2.502,6.425-3.488c5.708-2.897,11.316-6.804,16.608-10.418c4.812-3.287,11.13-7.53,13.935-12.905 c-0.759,3.059-3.364,6.421-4.943,9.203c-2.728,4.806-6.064,8.417-9.781,12.446c-6.895,7.477-15.107,14.109-20.779,22.608 c3.515-0.784,7.103-2.996,10.263-4.628c6.455-3.335,12.235-8.381,17.684-13.15c5.495-4.81,10.848-9.68,15.866-14.988 c1.905-2.016,4.178-4.42,5.556-6.838c0.051,1.256-0.604,2.542-1.03,3.672c-1.424,3.767-3.011,7.432-4.723,11.076 c-2.772,5.904-6.312,11.342-9.921,16.763c-3.167,4.757-7.082,8.94-10.854,13.205c-2.456,2.777-4.876,5.977-7.627,8.448 c9.341-7.52,18.965-14.629,27.924-22.656c4.995-4.474,9.557-9.075,13.586-14.446c1.443-1.924,2.427-4.939,3.74-6.56 c-0.446,3.322-2.183,6.878-3.312,10.032c-2.261,6.309-5.352,12.53-8.418,18.482c-3.46,6.719-8.134,12.698-11.954,19.203 c-0.725,1.234-1.833,2.451-2.265,3.77c2.347-0.48,4.812-3.199,7.028-4.286c4.144-2.033,7.787-4.938,11.184-8.072 c3.142-2.9,5.344-6.758,7.925-10.141c1.483-1.944,3.306-4.056,4.341-6.283c0.041,1.102-0.507,2.345-0.876,3.388 c-1.456,4.114-3.369,8.184-5.059,12.212c-1.503,3.583-3.421,7.001-5.277,10.411c-0.967,1.775-2.471,3.528-3.287,5.298 c2.49-1.163,5.229-3.906,7.212-5.828c2.094-2.028,5.027-4.716,6.33-7.335c-0.256,1.47-2.07,3.577-3.02,4.809" style="stroke-dasharray: 499.664276123047px, 499.664276123047px; stroke-dashoffset: 0px; -webkit-transition: stroke-dashoffset 0.8s ease-in-out 0s; transition: stroke-dashoffset 0.8s ease-in-out 0s;"></path>
                                                            </svg>
														<?php } else { ?>
															<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M 10 10 L 90 90" style="stroke-dasharray: 113.137084960938px, 113.137084960938px; stroke-dashoffset: 0px; -webkit-transition: stroke-dashoffset 0.2s ease-in-out 0s; transition: stroke-dashoffset 0.2s ease-in-out 0s;"></path><path d="M 90 10 L 10 90" style="stroke-dasharray: 113.137084960938px, 113.137084960938px; stroke-dashoffset: 0px; -webkit-transition: stroke-dashoffset 0.2s ease-in-out 0.2s; transition: stroke-dashoffset 0.2s ease-in-out 0.2s;"></path>
                                                            </svg>
														<?php } ?>
                                                    <?php } ?>
                                                </li>
                                                <?php 
                                            }
                                            ?>
                                        </ul>
                                        <?php
                                        if( 
                                            ($totalCorretas[$pergunta['idpergunta']] == $totalMarcadas[$pergunta['idpergunta']]) &&
                                            ($totalCorretas[$pergunta['idpergunta']] == $totalCorretasMarcadas[$pergunta['idpergunta']]) &&
                                            ($totalMarcadas[$pergunta['idpergunta']] == $totalCorretasMarcadas[$pergunta['idpergunta']])
                                        ) {
                                            $corretas++;
                                            ?>
                                            <div class="span4">
                                                <div class="alert alert-success text-center"><i class="icon-ok" style="font-size:16px;"></i> <strong><?php echo $idioma['acertou']; ?></strong></div>  
                                            </div>
                                        <?php
                                        } else { ?>
                                            <div class="span4" align="center">
                                                <div class="alert alert-danger text-center"><i class="icon-remove" style="font-size:16px;color:#FFFFFF;"></i> <strong><?php echo $idioma['errou']; ?></strong></div>  
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <br />
                            <?php } ?>
                            <div class="row-fluid">
                                <div class="span6 alert alert-success text-center" style="font-size:20px;"><strong><?php echo $idioma['acertos'].' '.$corretas; ?></strong></div>
                                <div class="span6 alert alert-danger text-center" style="font-size:20px;"><strong><?php echo $idioma['erros'].' '.(count($prova['perguntas']) - $corretas); ?></strong></div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <a href="/<?php echo $url[0] ?>/<?php echo $url[1] ?>/<?php echo $url[2] ?>/<?php echo $matricula['idmatricula'] ?>/<?php echo $url[4] ?>/<?php echo $url[5] ?>/<?php echo $url[6] ?>/fazer" class="btn btn-azul btn-mob"><?php echo $idioma['refazer']; ?></a>
                                </div>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
        <!-- /Box -->
    </div>
</div>
<!-- /Conteudo -->
<?php incluirLib("rodape", $config, $usuario); ?>
</body>
</html>