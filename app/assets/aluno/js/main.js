//(function($){
	jQuery(document)
    .ready(function(e) {

        showcontent();

        init();

        jQuery(window)
            .resize(function() {

                Organizar();

            });


        Organizar();
        menuApoio(0);
        hidetoolTip();
        initConteudoTip();
        initSubmenuTip();
        initCombo();

        jQuery("a[data-rjtp^='menu']")
            .each(function(index, element) {

                jQuery(this)
                    .mouseover(function() {
                        var titulo = jQuery(this)
                            .attr('data-titulo');
                        var descricao = jQuery(this)
                            .attr('data-descricao');
                        showtoolTip(jQuery(this), titulo, descricao);
                    })
                    .mouseout(function() {
                        hidetoolTip();
                    });
                //
            });

        jQuery('.boxcomentario')
            .hide();
        jQuery('.boxrota')
            .hide();
        jQuery('.combocurso-lista')
            .hide();

        var previousScroll = 0;
        var posScroll = 0;
        jQuery('.sidebar-anotacoes-conteudo')
            .scroll(function(event) {
                var currentScroll = jQuery(this)
                    .scrollTop();
                if (currentScroll > previousScroll) {
                    //console.log('down');


                } else {
                    //console.log('up');
                }
                jQuery('.sidebar-anotacoes-conteudo')
                    .css('background-position', '10px ' + (-1 * currentScroll) + 'px')
                previousScroll = currentScroll;

            });

    });

function showtoolTip(obj, titulo, desc) {
    jQuery('.avatooltip')
        .show();
    jQuery('.avatooltip')
        .css('top', obj.offset()
            .top);
    jQuery('.avatooltip')
        .css('left', obj.offset()
            .left + obj.width + 10);
    jQuery('.avatooltip strong')
        .html(titulo);
    jQuery('.avatooltip small')
        .html(desc);

}

function hidetoolTip() {
    jQuery('.avatooltip')
        .hide();
}

function Organizar() {


    console.log('organizar');


    if (chaveFullscreen) {

        console.log('chave full screen');

        var widthTotal = jQuery('.content')
            .width();
        var altura = jQuery(window)
            .innerHeight() - (jQuery('.topo')
                .height() - 61);
        jQuery('#coluna-dados')
            .width(widthTotal); //},velocidade,'easeOutCubic');
        jQuery('.statusbar')
            .width(widthTotal); //},velocidade,'easeOutCubic');
        jQuery('.conteudo .area')
            .height(altura - 100); //},velocidade,'easeOutCubic');
        jQuery('.conteudo')
            .height(altura); //},velocidade,'easeOutCubic');

    } else {
        console.log('chave else full screen');

        var altura = jQuery(window)
            .innerHeight() - jQuery('.conteudo')
            .offset()
            .top;

        jQuery('.conteudo')
            .height(altura);
        var larguraConteudo = jQuery('.conteudo')
            .width() - (jQuery('.coluna-menu')
                .width() + jQuery('.coluna-apoio')
                .width()) - 20;
        var largura = jQuery('.conteudo')
            .width() - (jQuery('.coluna-menu')
                .width() + jQuery('.coluna-apoio')
                .width()) - 30;
        if (chaveMenuApoio) {
            largura = jQuery('.conteudo')
                .width() - (jQuery('.coluna-menu')
                    .width() + 35) - 30;
        }

        //var dataset = -10;
        // console.log(jQuery(this).attr('data-size'));
        console.log(jQuery('.coluna-dados')
            .attr('data-size'));

        if (jQuery('.coluna-dados')
            .attr('data-size')) {
            console.log(largura);
            larguraConteudo -= jQuery('.coluna-dados')
                .attr('data-size');
            console.log(largura);
        }

        jQuery('#coluna-dados')
            .width(largura);

        jQuery('#coluna-conteudo')
            .width(larguraConteudo);

        jQuery('.coluna-dados-nomenu')
            .width(larguraConteudo + 30);
        jQuery('.coluna-dados-nomenu')
            .css('left', '0px')

        // jQuery('.statusbar').width(jQuery('.conteudo').width() ); //- (jQuery('.coluna-menu') .width() + 100)
        //  jQuery('.menu-site-nomenu').find('.statusbar').width(jQuery('.conteudo').width() - (jQuery('.coluna-menu').width()));
        jQuery('.apoio-content')
            .height(altura - 155);
        ajustarSidebar();
        jQuery('.area')
            .each(function(index, element) {
                var altura = jQuery(this)
                    .parent()
                    .height() - 100;
                jQuery(this)
                    .height(altura);
            });
        jQuery('.area-conteudo')
            .each(function(index, element) {

                var altura = jQuery(this)
                    .parent()
                    .height() - 10;
                jQuery(this)
                    .height(altura);
            });

    }


    ajustarConteudo();
    montagemMenu();

}

function montagemMenu() {

    var areamenu = jQuery('.coluna-menu .area')
        .height();
    var totalmenu = Math.floor(areamenu / 60);
    var chaveDrop = false;
    var menus = new Array();
    jQuery('.coluna-menu .area .menu-box')
        .children('.icomenu')
        .each(function(index, element) {

            if (index < totalmenu - 2) {
                jQuery(this)
                    .show();
            } else {
                jQuery(this)
                    .hide();
                menus.push(jQuery(this));
                chaveDrop = true;
            }
        });
    jQuery('.btdrop')
        .hide();

    if (chaveDrop) {
        jQuery('.btdrop')
            .show();
    }

    jQuery('.coluna-menu .area')
        .height(totalmenu * 60)

    //console.log('total:', );
    var submenuY = 0;
    if (jQuery('.btdrop a')
        .children()
        .length > 0) {
        submenuY = jQuery('.btdrop a').offset().top;
    } else {
        //  console.log('nÃƒÂ£o existe')
        if (jQuery('.btdrop a').offset()) {
            submenuY = jQuery('.btdrop a').offset().top;
        };
    }

    jQuery('.submenu')
        .css('top', submenuY + 'px');

    jQuery('.submenu ul')
        .html('');
    var alturafinal = 0;
    for (var i = 0; i < menus.length; i++) {
        alturafinal += 46;

        jQuery('.submenu ul')
            .append('<li><a class="corpadrao" onMouseOver="javascript:submenuTipsOver()" onMouseOut="javascript:submenuTipsOut()" href="' + jQuery(menus[i])
                .children()
                .attr('href') + '"><span class="' + jQuery(menus[i])
                .children()
                .attr('class') + '"></span>' + jQuery(menus[i])
                .children()
                .attr('data-titulo') + '<small>' + jQuery(menus[i])
                .children()
                .attr('data-descricao') + '</small></a</li>');
        //console.log('criando o menu');
    }

    jQuery('.submenu')
        .height(alturafinal);
    jQuery('.submenu')
        .css('margin-top', '-' + ((alturafinal / 2) - 20) + 'px');
}




function log(texto) {
    console.log('debug:' + texto);
}


function showComboops() {
    if (chavecombo) {
        jQuery('.combocurso-lista')
            .children()
            .each(function(index, element) {
                jQuery(this)
                    .stop()
                    .animate({
                        opacity: 1
                    }, 200);
            });
    };
}

function hideComboops(id) {
    if (chavecombo) {
        jQuery('.combocurso-lista')
            .children()
            .each(function(index, element) {
                if (index != id) {
                    jQuery(this)
                        .stop()
                        .animate({
                            opacity: .5
                        }, 200);
                };
            });
    };
}

function init() {
    jQuery('.coluna-dados')
        .css('left', "80px");
    jQuery('.menu-site-nomenu')
        .css('margin-left', "0px");


    jQuery('.menu-principal')
        .children()
        .each(function(index, element) {
            jQuery(this)
                .children('a')
                .mouseover(function() {
                    var bg = jQuery(this)
                        .parent()
                        .children('.bg-menu');
                    bg.stop()
                        .animate({
                            height: 25
                        }, 200, 'easeOutCubic');
                })
                .mouseout(function() {
                    var bg = jQuery(this)
                        .parent()
                        .children('.bg-menu');
                    bg.stop()
                        .animate({
                            height: 3
                        }, 200, 'easeOutCubic');
                });
        });

}


/* -------------------------- */
var chaveconteudoTip = false;
var timeConteudoTip;

function menuPrincipal() {
    if (chaveconteudoTip) {
        chaveconteudoTip = false;
        fecharConteudoTip();
        timeConteudoTip = null;
    } else {
        chaveconteudoTip = true;
        abrirConteudoTip();
        timeConteudoTip = setTimeout(fecharConteudoTip, 5000);
    };
}

function initConteudoTip() {
    jQuery('#menuTips a')
        .each(function(index, element) {
            jQuery(this)
                .mouseover(function() {
                    acaoMenuTip(timeConteudoTip, 1);
                })
                .mouseout(function() {
                    acaoMenuTip(timeConteudoTip, 0);
                });
        });


    fecharConteudoTip();
}



function acaoMenuTip(tipo, chave) {

    if (chave) {
        if (timeConteudoTip) {
            clearTimeout(timeConteudoTip);
        }
    } else {
        timeConteudoTip = setTimeout(fecharConteudoTip, 1000);
    }

}

function abrirConteudoTip() {
    chaveconteudoTip = true;
    var menuposY = jQuery('#menuBt-Tip')
        .offset()
        .top + 25;
    var menuposX = jQuery('#menuBt-Tip')
        .offset()
        .left - 60;

    jQuery('.menuConteudo')
        .css('top', menuposY);
    jQuery('.menuConteudo')
        .css('left', menuposX);
    jQuery('.menuConteudo')
        .fadeIn('fast');

}

function fecharConteudoTip() {
    jQuery('.menuConteudo')
        .fadeOut('fast');
    chaveconteudoTip = false;
}


/* --------------------------- */





var chaveSubmenuTip = false;


function submenuActive() {
    if (chaveconteudoTip) {
        chaveconteudoTip = false;
        fecharConteudoTip();
        timeConteudoTip = null;
    } else {
        chaveconteudoTip = true;
        abrirConteudoTip();
        timeConteudoTip = setTimeout(fecharConteudoTip, 1000);
    };
}

function initSubmenuTip() {

    jQuery('#menu-box a')
        .each(function(index, element) {
            jQuery(this)
                .mouseover(function() {
                    acaoMenuTip(timeSubmenuTip, 1);
                })
                .mouseout(function() {
                    acaoMenuTip(timeSubmenuTip, 0);
                });
        });

    jQuery('.btdrop')
        .mouseover(function() {
            //console.log('mouse over');
            abrirSubmenu();
        })
        .mouseout(function() {
            // console.log('mouse out');
            acaoSubMenuTip(timeSubmenuTip, 0);
        });

    fecharSubmenu();
}

function submenuTipsOver() {
    acaoSubMenuTip(timeSubmenuTip, 1);
}

function submenuTipsOut() {
    acaoSubMenuTip(timeSubmenuTip, 0);
}

var timeSubmenuTip;

function acaoSubMenuTip(tipo, chave) {

    if (chave) {
        if (timeSubmenuTip) {
            clearTimeout(timeSubmenuTip);
        }
    } else {
        timeSubmenuTip = setTimeout(fecharSubmenu, 1000);
    }

}

function abrirSubmenu() {
    chaveconteudoTip = true;
    jQuery('.submenu')
        .fadeIn('fast');


}

function fecharSubmenu() {
    jQuery('.submenu')
        .fadeOut('fast');
    chaveconteudoTip = false;
}





function initCombo() {
    jQuery('.clickarea')
        .each(function(index, element) {
            jQuery(this)
                .mouseover(function() {
                    acaoComboTip(timeComboTip, 1);
                })
                .mouseout(function() {
                    acaoComboTip(timeComboTip, 0);
                });
        });

};


var chavecombo = false;

function openCombo() {

    if (!chavecombo) {
        abrirCombomenu();
        acaoComboTip(timeComboTip, 0);
    } else {
        fecharCombomenu();
    }

}

var timeComboTip;

function acaoComboTip(tipo, chave) {
    if (chave) {
        if (timeComboTip) {
            clearTimeout(timeComboTip);
        }
    } else {
        timeComboTip = setTimeout(fecharCombomenu, 1000);
    }
}

function abrirCombomenu() {
    chavecombo = true;
    jQuery('.combocurso-lista')
        .fadeIn('fast');
}

function fecharCombomenu() {
    jQuery('.combocurso-lista')
        .fadeOut('fast');
    chavecombo = false;
}




/* -------------------------------------- */

var chaveMenuApoio = false;

function acoesApoio() {
    if (chaveMenuApoio) {
        chaveMenuApoio = false;
        apoioAbrir();
        fecharPrincipalSidebar();
        jQuery('.bt-remove-apoio span')
            .addClass('icon-arrow-1-right');
        jQuery('.bt-remove-apoio span')
            .removeClass('icon-arrow-1-left');
    } else {
        chaveMenuApoio = true;
        abrirPrincipalSidebar();
        apoioFechar();
        jQuery('.bt-remove-apoio span')
            .removeClass('icon-arrow-1-right');
        jQuery('.bt-remove-apoio span')
            .addClass('icon-arrow-1-left');
    }
}

var velocidade = 500;

function apoioFechar() {
    jQuery('.coluna-apoio')
        .animate({
            right: -345
        }, velocidade, 'easeOutCubic');
    jQuery('.bt-remove-apoio')
        .animate({
            'margin-left': -36
        }, velocidade, 'easeOutCubic');
}

function apoioAbrir() {
    jQuery('.coluna-apoio')
        .animate({
            right: 0
        }, velocidade, 'easeOutCubic');
    jQuery('.bt-remove-apoio')
        .animate({
            'margin-left': 0
        }, velocidade, 'easeOutCubic');
}

/* --------------------------------------------- */

function abrirPrincipalSidebar() {
    var widthatual = jQuery('#coluna-dados')
        .width() + 310;
    jQuery('#coluna-dados')
        .animate({
            width: widthatual
        }, velocidade, 'easeOutCubic');
}

function fecharPrincipalSidebar() {
    var widthatual = jQuery('#coluna-dados')
        .width() - 310;
    jQuery('#coluna-dados')
        .animate({
            width: widthatual
        }, velocidade, 'easeOutCubic');
}

/* ---------------------------------------------- */

var chaveFullscreen = false;

function acaoFullscreen() {
    if (chaveFullscreen) {
        chaveFullscreen = false;
        fullscreenOff();
    } else {
        chaveFullscreen = true;
        fullscreenOn();
    }
}

function fullscreenOn() {

    console.log('fulscreen on');

    var widthTotal = jQuery('.content')
        .width();
    var altura = jQuery(window)
        .innerHeight() - (jQuery('.topo')
            .height() - 61);

    jQuery('.coluna-menu')
        .animate({
            'margin-left': -70
        }, velocidade, 'easeOutCubic');
    jQuery('.coluna-apoio')
        .animate({
            'right': -345
        }, velocidade, 'easeOutCubic');
    jQuery('.topo')
        .animate({
            'margin-top': -61
        }, velocidade, 'easeOutCubic');
    jQuery('#coluna-dados')
        .animate({
            'left': 0,
            width: widthTotal
        }, velocidade, 'easeOutCubic');
    jQuery('.menu-site')
        .animate({
            'margin-left': 0
        }, velocidade, 'easeOutCubic');
    jQuery('.statusbar')
        .animate({
            'width': widthTotal
        }, velocidade, 'easeOutCubic');


    jQuery('.conteudo')
        .animate({
            'height': altura
        }, velocidade, 'easeOutCubic');

    jQuery('.conteudo .area')
        .animate({
            'height': altura - 100
        }, velocidade, 'easeOutCubic');


    jQuery('.principal-texto')
        .height(altura - 180)



}


function fullscreenOff() {

    console.log('fulscreen off');

    var widthTotal = jQuery('.content')
        .width();
    var largura = jQuery('.conteudo')
        .width() - (jQuery('.coluna-menu')
            .width() + jQuery('.coluna-apoio')
            .width()) - 30;

    var altura = jQuery(window)
        .innerHeight() - 107;
    jQuery('.conteudo')
        .height(altura);

    jQuery('.coluna-menu')
        .animate({
            'margin-left': 0
        }, velocidade, 'easeOutCubic');
    jQuery('.coluna-apoio')
        .animate({
            'right': 0
        }, velocidade, 'easeOutCubic');
    jQuery('.topo')
        .animate({
            'margin-top': -10
        }, velocidade, 'easeOutCubic');
    jQuery('#coluna-dados')
        .animate({
            'left': 80,
            width: largura
        }, velocidade, 'easeOutCubic');
    jQuery('.menu-site')
        .animate({
            'margin-left': 0
        }, velocidade, 'easeOutCubic');
    jQuery('.statusbar').animate({
        'width': widthTotal
    }, velocidade, 'easeOutCubic');

    jQuery('.conteudo')
        .animate({
            'height': altura
        }, velocidade, 'easeOutCubic');
    jQuery('.conteudo .area')
        .animate({
            'height': altura - 100
        }, velocidade, 'easeOutCubic');

    jQuery('.principal-texto')
        .height(altura - 180);
}



function menuApoio(id) {
    jQuery('.apoio-menu ul')
        .children()
        .each(function(index, element) {
            jQuery(this)
                .children()
                .removeClass('corpadrao');
            if (id == index) {
                jQuery(this)
                    .children()
                    .addClass('corpadrao');
            }
        });
    switch (id) {
        case 0:
            jQuery('#setaApoio')
                .animate({
                    'left': 87
                }, 500, 'easeOutCubic');
            break;
        case 1:
            jQuery('#setaApoio')
                .animate({
                    'left': 185
                }, 500, 'easeOutCubic');
            break;
        case 2:
            jQuery('#setaApoio')
                .animate({
                    'left': 288
                }, 500, 'easeOutCubic');
            break;
    }

    jQuery('.sidebar')
        .animate({
            left: id * -345
        }, 500);
}

var chaveComentario = false;

function abrirComentario() {
    if (!chaveComentario) {
        chaveComentario = true;
        jQuery('.boxcomentario')
            .show();
        jQuery('.boxcomentario')
            .css('bottom', '80px');
        jQuery('.boxcomentario')
            .css('opacity', '0');
        jQuery('.boxcomentario')
            .animate({
                'right': 2,
                'bottom': 96,
                'opacity': 1
            }, 1000, 'easeOutCubic');
    } else {
        fecharComentario();
    }

}

function fecharComentario() {
    jQuery('.boxcomentario')
        .animate({
            'right': 2,
            'bottom': 80,
            'opacity': 0
        }, 300, 'easeOutCubic', function() {
            jQuery('.boxcomentario')
                .hide();
        });
    chaveComentario = false;
}



var chaveRota = false;

function abrirRota() {
    if (!chaveRota) {
        chaveRota = true;
        jQuery('.boxrota')
            .show();
        jQuery('.boxrota')
            .css('bottom', '80px');
        jQuery('.boxrota')
            .css('opacity', '0');
        jQuery('.boxrota')
            .animate({
                'bottom': 96,
                'opacity': 1
            }, 1000, 'easeOutCubic');
    } else {
        fecharRota();
    }

}

function fecharRota() {
    jQuery('.boxrota')
        .animate({
            'right': 2,
            'bottom': 80,
            'opacity': 0
        }, 300, 'easeOutCubic', function() {
            jQuery('.boxrota')
                .hide();
        });
    chaveRota = false;
}


function ajustarConteudo() {
    jQuery('.principal-texto')
        .height(jQuery('.coluna-dados .area')
            .height() - 80)
}


function ajustarSidebar() {
    jQuery('.sidebar-box')
        .height(jQuery('.apoio-content')
            .height());
    ajustarSidebarAnotacoes();
    ajustarSidebarMaterial();
}

function ajustarSidebarAnotacoes() {

    var altura = jQuery('.apoio-content')
        .height();
    jQuery('.sidebar-anotacoes-conteudo')
        .height(altura - 110)

}

function ajustarSidebarMaterial() {

    var altura = jQuery('.apoio-content')
        .height();
    jQuery('.sidebar-material-conteudo')
        .height(altura)

}



function ampliarFonte() {
    var fontAtual = parseInt(jQuery('.principal-texto')
        .css('font-size'));
    fontAtual++;
    if (fontAtual < 19) {
        jQuery('.principal-texto')
            .css('font-size', fontAtual + 'px');
    };

}

function reduzirFonte() {
    var fontAtual = parseInt(jQuery('.principal-texto')
        .css('font-size'));
    fontAtual--;
    if (fontAtual > 10) {
        jQuery('.principal-texto')
            .css('font-size', fontAtual + 'px');
    };
}


function showcontent() {
    if (jQuery('.content').hasClass('contenthide')) {
        //console.log('tem a classe');
        jQuery('.content').removeClass('contenthide')
    } else {
        //console.log('nao tem');
    }
}
//})(jQuery);

 