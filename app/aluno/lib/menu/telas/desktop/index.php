<div id="side-menu" class="menu-align-fixed no-mobile">
    <ul>
        <li class="style-menu-fixed">
            <a class="m-azul first" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>">
                <i class="icon-align-justify"></i>
                <span class="side-menu-item"><?php echo $idioma['conteudo']; ?></span>
            </a>
        </li>
        <!-- <?php // if(in_array('favoritos', $ava['modulos'])){ ?>  
            <li class="style-menu-fixed">
                <a class="m-vermelho" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/favoritos">
                    <i class="icon-heart"></i>
                    <span class="side-menu-item"><?php echo $idioma['favoritos']; ?></span>
                </a>
            </li>
        <?php // } ?> -->
        <?php if(in_array('tiraduvidas', $ava['modulos'])){ ?>  
            <li class="style-menu-fixed">
                <a class="m-verde" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/mensagens">
                    <i class="icon-magic"></i>
                    <span class="side-menu-item"><?php echo $idioma['tira_duvidas']; ?></span>
                </a>
            </li>
        <?php } ?>
        <?php if(in_array('chats', $ava['modulos'])){ ?>  
            <li class="style-menu-fixed">
                <a class="m-roxo" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/chats">
                    <i class="icon-comment"></i>
                    <span class="side-menu-item"><?php echo $idioma['chats']; ?></span>
                </a>
            </li>
        <?php } ?>
        <?php if(in_array('foruns', $ava['modulos'])){ ?>  
            <li class="style-menu-fixed">
                <a class="m-esmeralda" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/foruns">
                    <i class="icon-keyboard"></i>
                    <span class="side-menu-item"><?php echo $idioma['foruns']; ?></span>
                </a>
            </li>
        <?php } ?>
        <?php 
        if(in_array('simulado', $ava['modulos'])){ 
            if(!$ava['simulados_apartirde'] || $ava['simulados_apartirde'] == '0000-00-00') 
                $ava["simulados_apartirde"] = date("Y-m-d");
            
            if(/*$ava["simulados_link"] &&*/$ava["simulados_apartirde"] <= date("Y-m-d")) { ?>  
                <li class="style-menu-fixed">
                    <a class="m-laranja" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/simulado">
                        <i class="icon-file-text-alt"></i>
                        <span class="side-menu-item"><?php echo $idioma['simulado']; ?></span>
                    </a>
                </li>
            <?php 
            }
        } ?>
        <?php if(in_array('biblioteca', $ava['modulos'])){ ?>  
            <li class="style-menu-fixed">
                <a class="m-rosa" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/arquivos">
                    <i class="icon-download-alt"></i>
                    <span class="side-menu-item"><?php echo $idioma['biblioteca']; ?></span>
                </a>
            </li>
        <?php } ?>
        <?php if(in_array('anotacoes', $ava['modulos'])){ ?>  
            <li class="style-menu-fixed">
                <a class="m-bege" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/anotacoes">
                    <i class="icon-pencil"></i>
                    <span class="side-menu-item"><?php echo $idioma['anotacoes']; ?></span>
                </a>
            </li>
        <?php } ?>
        <?php if(in_array('professores', $ava['modulos'])){ ?>  
            <li class="style-menu-fixed">
                <a class="m-bege" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/meusprofessores">
                    <i class="icon-user"></i>
                    <span class="side-menu-item"><?php echo $idioma['meus_professores']; ?></span>
                </a>
            </li>
        <?php } ?>
        <?php if(in_array('colegas', $ava['modulos'])){ ?>  
            <li class="style-menu-fixed">
                <a class="m-carme" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/colegasdesala">
                    <i class="icon-group"></i>
                    <span class="side-menu-item"><?php echo $idioma['colegas_Curso']; ?></span>
                </a>
            </li>
        <?php } ?>
        <?php if(in_array('avaliacoes', $ava['modulos'])){ ?>  
            <li class="style-menu-fixed">
                <a class="m-amarelo" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/avaliacoes">
                    <i class="icon-file-text"></i>
                    <span class="side-menu-item"><?php echo $idioma['avaliacoes']; ?></span>
                </a>
            </li>
        <?php } ?>
        <?php /*if(in_array('contratos', $ava['modulos'])){ ?>  
            <li class="style-menu-fixed">
                <a class="m-amarelo" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/contratos">
                    <i class="icon-file-text"></i>
                    <span class="side-menu-item"><?php echo $idioma['contratos']; ?></span>
                </a>
            </li>
        <?php }*/ ?>
        <?php if(in_array('faq', $ava['modulos'])){ ?>  
            <li class="style-menu-fixed">
                <a class="m-verde-claro last" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/faq">
                    <i class="icon-question"></i>
                    <span class="side-menu-item"><?php echo $idioma['faq']; ?></span>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>
<div class="row-fluid side-menu-mob m-box">
    <div class="span12">
        <ul class="no-margin">
            <li class="style-menu-fixed">
                <a href="#myModal" role="button" class="first" data-toggle="modal">
                    <i class="icon-align-justify"></i>
                    <span class="side-menu-item"><?php echo $idioma['ferramentasportal']; ?></span>
                </a>
            </li>
        </ul>
    </div>
</div>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100%; overflow-y: scroll;">
    <div class="span12">
        <div>
            <i class="closed-i extra-align" data-dismiss="modal"> <strong><?php echo $idioma['fechar']; ?></strong></i>
        </div>
    </div>
    <div class="span12">
        <ul class="no-margin">
            <li class="style-menu-fixed">
                <a class="m-azul first" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>">
                    <i class="icon-align-justify"></i>
                    <span class="side-menu-item"><?php echo $idioma['ferramentasportal']; ?></span>
                </a>
            </li>
        </ul>
    </div>
    <div class="span12">
        <ul class="no-margin">
            <li class="style-menu-fixed">
                <a href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>" class="m-azul first" style="padding-top: 32px !important;">
                    <i class="icon-align-justify"></i>
                    <span class="side-menu-item"><?php echo $idioma['conteudo']; ?></span>
                </a>
            </li>
        </ul>
    </div>  
    <?php // if(in_array('favoritos', $ava['modulos'])){ ?>  
        <!-- <div class="span12">
            <ul class="no-margin">
                <li class="style-menu-fixed">
                    <a class="m-vermelho" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/favoritos">
                        <i class="icon-heart"></i>
                        <span class="side-menu-item"><?php echo $idioma['favoritos']; ?></span>
                    </a>
                </li>
            </ul>
        </div> -->
    <?php // } ?> 
    <?php if(in_array('tiraduvidas', $ava['modulos'])){ ?>  
        <div class="span12">
            <ul class="no-margin">
                <li class="style-menu-fixed">
                    <a class="m-verde" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/mensagens">
                        <i class="icon-magic"></i>
                        <span class="side-menu-item"><?php echo $idioma['tira_duvidas']; ?></span>
                    </a>
                </li>
            </ul>
        </div>
    <?php } ?>
    <?php if(in_array('chats', $ava['modulos'])){ ?>  
        <div class="span12">
            <ul class="no-margin">
                <li class="style-menu-fixed">
                    <a class="m-roxo" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/chats">
                        <i class="icon-comment"></i>
                        <span class="side-menu-item"><?php echo $idioma['chats']; ?></span>
                    </a>
                </li>
            </ul>
        </div>
    <?php } ?>
    <?php if(in_array('foruns', $ava['modulos'])){ ?>  
        <div class="span12">
            <ul class="no-margin">
                <li class="style-menu-fixed">
                    <a class="m-esmeralda" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/foruns">
                        <i class="icon-keyboard"></i>
                        <span class="side-menu-item"><?php echo $idioma['foruns']; ?></span>
                    </a>
                </li>
            </ul>
        </div>
    <?php } ?>
    <?php 
    if(in_array('simulado', $ava['modulos'])){ 
        if(!$ava['simulados_apartirde'] || $ava['simulados_apartirde'] == '0000-00-00') 
            $ava["simulados_apartirde"] = date("Y-m-d");
        
        if(/*$ava["simulados_link"] &&*/$ava["simulados_apartirde"] <= date("Y-m-d")) { ?>
            <div class="span12">
                <ul class="no-margin">
                    <li class="style-menu-fixed">
                        <a class="m-laranja" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/simulado">
                            <i class="icon-file-text-alt"></i>
                            <span class="side-menu-item"><?php echo $idioma['simulado']; ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        <?php 
        } 
    }
    ?>
    <?php if(in_array('biblioteca', $ava['modulos'])){ ?>  
        <div class="span12">
            <ul class="no-margin">
                <li class="style-menu-fixed">
                    <a class="m-rosa" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/arquivos">
                        <i class="icon-download-alt"></i>
                        <span class="side-menu-item"><?php echo $idioma['biblioteca']; ?></span>
                    </a>
                </li>
            </ul>
        </div>
    <?php } ?>
    <?php if(in_array('anotacoes', $ava['modulos'])){ ?>  
        <div class="span12">
            <ul class="no-margin">
                <li class="style-menu-fixed">
                    <a class="m-bege" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/anotacoes">
                        <i class="icon-user"></i>
                        <span class="side-menu-item"><?php echo $idioma['anotacoes']; ?></span>
                    </a>
                </li>
            </ul>
        </div>
    <?php } ?>
    <?php if(in_array('professores', $ava['modulos'])){ ?>  
        <div class="span12">
            <ul class="no-margin">
                <li class="style-menu-fixed">
                    <a class="m-bege" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/meusprofessores">
                        <i class="icon-user"></i>
                        <span class="side-menu-item"><?php echo $idioma['meus_professores']; ?></span>
                    </a>
                </li>
            </ul>
        </div>
    <?php } ?>
    <?php if(in_array('colegas', $ava['modulos'])){ ?>  
        <div class="span12">
            <ul class="no-margin">
                <li class="style-menu-fixed">
                    <a class="m-carme" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/colegasdesala">
                        <i class="icon-group"></i>
                        <span class="side-menu-item"><?php echo $idioma['colegas_Curso']; ?></span>
                    </a>
                </li>
            </ul>
        </div>
    <?php } ?>
    <?php if(in_array('avaliacoes', $ava['modulos'])){ ?>  
        <div class="span12">
            <ul class="no-margin">
                <li class="style-menu-fixed">
                    <a class="m-amarelo" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/avaliacoes">
                        <i class="icon-file-text"></i>
                        <span class="side-menu-item"><?php echo $idioma['avaliacoes']; ?></span>
                    </a>
                </li>
            </ul>
        </div>
    <?php } ?>
    
    <?php if(in_array('faq', $ava['modulos'])){ ?>  
        <div class="span12">
            <ul class="no-margin">
                <li class="style-menu-fixed">
                    <a class="m-verde-claro last" href="/<?php echo $url[0]; ?>/<?php echo $url[1]; ?>/<?php echo $url[2]; ?>/<?php echo $url[3]; ?>/<?php echo $url[4]; ?>/faq">
                        <i class="icon-question"></i>
                        <span class="side-menu-item"><?php echo $idioma['faq']; ?></span>
                    </a>
                </li>
            </ul>
        </div>
    <?php } ?>
</div>  