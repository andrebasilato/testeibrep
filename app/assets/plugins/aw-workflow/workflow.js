//Global Vars
var ctx = null;
var cnv = null;
var cnv2 = null;
var cam = null;
var background;
var blocks = [];
var relacionamento = [];
var relacionamentoClick = null;
var jsonfile = {};
var workflowStage = null;
var cursorPoint = null;
var keyShift = false;

var drag = false;
var dragBall = false;
var dragObj = null;
var dragObjAll = Array();
var dragCam = false;
var camDif = {'x': 0, 'y': 0};

var idObjetos = 0;
var lg = $('#legenda');
var chaveDraw = false;
var chaveOrdem = false;
var chaveLoop = false;
var itemSelected = null;
var wfopcoes = Array();
var wfacoes = Array();
var wfprerequisitos = Array();

var statusBar = null;

var workflowLink = '';

function ajustarWorkflow() {
    'use strict';
    cnv.width = $('.workflow').width();
    cnv.height = $('.workflow').height();
}

window.onload = function () {
    'use strict';
    statusBar = document.querySelector('#statusbar');
    cnv = document.querySelector('canvas');
    cnv2 = $('#canvas');
    ctx = cnv.getContext('2d');

    workflowStage = {
        'width': 3000,
        'height': 2000
    };
    cursorPoint = {
        'posX': 0,
        'posY': 0
    };

    ajustarWorkflow();

    cnv.onmousedown = mouseDown;
    cnv.onmouseup = mouseUp;
    cnv.onmouseleave = mouseUp;
    cnv.onmousemove = mouseMove;

    cnv.addEventListener("dblclick", mouseDobleClick, false);
    cnv.addEventListener("touchstart", touchStartHandler, false);
    cnv.addEventListener("touchmove", touchMoveHandler, false);
    cnv.addEventListener("touchend", touchEndHandler, false);

    background = new Image();
    background.src = "/assets/img/wf_bg.jpg";


    cam = {
        x: 0,
        y: 0,
        width: cnv.width,
        height: cnv.height,
        leftEdge: function () {
            return this.x + (this.width * 0.25);
        },
        rightEdge: function () {
            return this.x + (this.width * 0.75);
        },
        topEdge: function () {
            return this.y + (this.height * 0.25);
        },
        bottomEdge: function () {
            return this.y + (this.height * 0.75);
        }
    };

    background.onload = function () {
        carregarDados();
    };

    $(window).resize(function () {
        organizar();
    });

};

function organizar() {
    'use strict';
    cam = {
        x: 0,
        y: 0,
        width: cnv.width,
        height: cnv.height,
    };

    ajustarWorkflow();
    draw();
    loop();
}

function touchStartHandler(e) {
    'use strict';
    actionCanvasStart(e.changedTouches[0].clientX - cnv2.offset().left, e.changedTouches[0].clientY - cnv2.offset().top);
}

function touchMoveHandler(e) {
    'use strict';
    actionCanvasMove(e.changedTouches[0].clientX - cnv2.offset().left, e.changedTouches[0].clientY - cnv2.offset().top);
}

function touchEndHandler(e) {
    'use strict';
    actionCanvasEnd(e.changedTouches[0].clientX - cnv2.offset().left, e.changedTouches[0].clientY - cnv2.offset().top);
}

function mouseDown(e) {
    'use strict';
    if (e.shiftKey) {
        keyShift = true;
    }
    actionCanvasStart(e.offsetX, e.offsetY);
    // console.log(e.layerX, e.layerY);
    // console.log(e);
    e.preventDefault();
}

function mouseUp(e) {
    'use strict';
    keyShift = false;
    actionCanvasEnd(e.offsetX, e.offsetY);
    e.preventDefault();
}

function mouseMove(e) {
    'use strict';
    actionCanvasMove(e.offsetX, e.offsetY);
    e.preventDefault();
}

function actionCanvasStart(cnvX, cnvY) {

    var chaveDrag = true;
    for (var blk in blocks) {
        var item = blocks[blk];
        var newX = cnvX - cam.x;
        var newY = cnvY - cam.y;
        if (newX > ((item.posX + item.width) - 40)
            && newX < item.posX + item.width
            && newY > item.posY
            && newY < item.posY + item.height) {
            dragObj = {'obj': item, 'difX': newX, 'difY': newY};
            dragBall = true;
            chaveDrag = false;
        }
        if (keyShift) {
            dragObjAll[item.id] = {'obj': item, 'difX': item.posX - cam.x, 'difY': item.posY - cam.y};
        }
    }

    for (var itemRel in relacionamento) {
        var item = relacionamento[itemRel];
        var newX = cnvX - cam.x;
        var newY = cnvY - cam.y;
        var raio = 12;
        if (newX > (item.posX - raio)
            && newX < item.posX + raio
            && newY > item.posY - raio
            && newY < item.posY + raio) {
            // console.log('click no relacionamento', itemRel);
            // dragObj = {'obj': item, 'difX': newX, 'difY': newY};
            // dragBall = true;
            unsetObject();
            relacionamentoClick = itemRel;
            chaveDrag = false;
        }
    }


    if (chaveDrag) {
        relacionamentoClick = null;

        for (var blk in blocks) {
            var item = blocks[blk];
            var newX = cnvX - cam.x;
            var newY = cnvY - cam.y;
            if (newX > item.posX && newX < item.posX + item.width && newY > item.posY && newY < item.posY + item.height) {
                item.clicked = true;
                dragObj = {'obj': item, 'difX': newX - item.posX, 'difY': newY - item.posY};
                drag = true;
            }
        }

        if (!drag) {
            dragCam = true;
            camDif.x = Math.abs(cam.x) + Math.abs(cnvX);
            camDif.y = Math.abs(cam.y) + Math.abs(cnvY);
            unsetObject();
            //relacionamentoClick = null;
        }
    }
    ;

    chaveLoop = true;
}

function actionCanvasEnd(cnvX, cnvY) {
    'use strict';
    if (dragBall) {
        dragBall = false;
        for (var blk in blocks) {
            var item = blocks[blk];
            var newX = cnvX - cam.x;
            var newY = cnvY - cam.y;
            if (newX > item.posX && newX < item.posX + item.width && newY > item.posY && newY < item.posY + item.height) {
                // console.log('tem relacionamento');
                setRelacionamento(dragObj.obj, item);
            } else {
                //  // console.log('item fora');
            }
        }
        chaveDraw = true;
    }
    if (dragObj) {
        var item = dragObj.obj;
        var newX = cnvX - cam.x;
        var newY = cnvY - cam.y;
        if (newX > item.posX && newX < item.posX + item.width && newY > item.posY && newY < item.posY + item.height) {
            item.clicked = false;
            // console.log('item selecionado');
            setObject(item);
        } else {
            // console.log('item fora');
        }
    }
    if (drag) {
        drag = false;
        dragObj = null;
        //saveData();
    }

    chaveLoop = false;
    dragCam = false;
}

function actionCanvasMove(cnvX, cnvY) {
    'use strict';
    if (keyShift && drag) {
        // console.log('movimento a galera toda');
//TODO Tem que ver esse movimento de todos os blocos usando segundando a tecla shift;

        for (var blk in blocks) {
            var item = blocks[blk];
            var newX = cnvX - cam.x;
            var newY = cnvY - cam.y;

            // item.setPos(newX - item.difX,newY - item.difY);
            //// console.log(dragObjAll[item.id].difX);
            item.setPos(newX,// - dragObjAll[item.id].difX,
                cnvY - dragObjAll[item.id].difY)
        }
        /* var item = dragObj.obj;
         var newX =  cnvX - cam.x;
         var newY =  cnvY - cam.y;
         item.setPos(newX - dragObj.difX,newY - dragObj.difY);*/


    } else if (drag) {
        var item = dragObj.obj;
        var newX = cnvX - cam.x;
        var newY = cnvY - cam.y;
        item.setPos(newX - dragObj.difX, newY - dragObj.difY)
    }
    if (dragCam) {
        cam.x = -1 * (camDif.x - cnvX);
        cam.y = -1 * (camDif.y - cnvY);
    }

    if (dragBall) {
        var item = dragObj;
        //var newX =  e.layerX - cam.x;
        //var newY =  e.layerY - cam.y;
        dragObj.difX = cnvX - cam.x;
        dragObj.difY = cnvY - cam.y;
    }
}

function carregarDados() {
    var ts = new Date().getTime();
    $.getJSON(workflowLink, function (json) {
        jsonfile = json;
        for (var i in jsonfile.blocos) {
            createBlocks(json.blocos[i]);
            if (idObjetos < Number(json.blocos[i].idsituacao)) {
                idObjetos = Number(json.blocos[i].idsituacao);
            }
        }
        montarOpcoes();
        draw();
        loop();
    });
}

function montarOpcoes() {
    for (var item in jsonfile.opcoes) {
        var itemCombo = jsonfile.opcoes[item];
        if (itemCombo.tipo === 'visualizacao') {
            if (itemCombo.combo) {
                if (!wfopcoes[itemCombo.combo]) {
                    wfopcoes[itemCombo.combo] = Array();
                }
                wfopcoes[itemCombo.combo].push(itemCombo);
            }
        }
        if (itemCombo.tipo === 'prerequisito') {
            wfprerequisitos.push(itemCombo);
        }
        if (itemCombo.tipo === 'acao') {
            wfacoes.push(itemCombo);
        }
    }
}
function getOpcoes() {
    return wfopcoes;
}
function getItemByIdapp(id) {
    for (var item in blocks) {
        if (blocks[item].db.idapp == id) {
            return blocks[item].db;
        }
    }
}

function saveData(button) {

    var event = button.getAttribute("onclick");
    button.setAttribute("onclick", "");

    for (var item in jsonfile.blocos) {
        jsonfile.blocos[item] = getItemByIdapp(jsonfile.blocos[item].idapp);
    }

    $.post(workflowLink, {parametros: JSON.stringify(jsonfile), acao:'gravar'}, function (data) {

        var retorno = JSON.parse(data),
            blocos = jsonfile.blocos;

        for (var i = 0; i < blocos.length; i++){
            blocos[i]['idsituacao'] = retorno[blocos[i]['idapp']];
        }

        alert('Salvo com sucesso.');
        button.setAttribute("onclick", event);
    });

}

function createBlocks(db) {
    var block = new wfBox(db, jsonfile.flags);
    blocks[db.idapp] = block;
    block.draw(ctx);
}

function workFlowRemove() {
    var r = confirm("Você deseja deletar?");
    if (r === true) {
        delete blocks[itemSelected.idapp];
        for (var item in jsonfile.blocos) {
            if (jsonfile.blocos[item].idapp == itemSelected.idapp) {
                delete jsonfile.blocos[item];
            }
        }

        for (var item in jsonfile.relacionamentos) {
            if (jsonfile.relacionamentos[item].idsituacao_de_app == itemSelected.idapp || jsonfile.relacionamentos[item].idsituacao_para_app == itemSelected.idapp) {
                delete jsonfile.relacionamentos[item];
            }
        }
        removeUndefined(jsonfile.blocos);
        removeUndefined(jsonfile.relacionamentos);
    }

    draw();
}

function workflowRemoverRelacionamento(){
    var r = confirm("Você deseja deletar o relacionamento?");
    if (r === true) {
        var obj = getObjectRel();
        for (var item in jsonfile.relacionamentos) {
            if (jsonfile.relacionamentos[item].idrelacionamento == obj.id) {
                delete jsonfile.relacionamentos[item];
            }
        }
        removeUndefined(jsonfile.relacionamentos);
        draw();
    } else {

    }

    $('#modalWorkFlowRequisito').modal('hide');
}

function createBox() {

    idObjetos++;
    var id = idObjetos;
    var idgerado = newId();
    var posX = (cnv.width / 2) - cam.x;
    var posY = (cnv.height / 2) - cam.y;
    var dados = {
        // "idsituacao": idObjetos,
        "ativo": "S",
        "data_cad": "2013-07-23 16:44:41",
        "nome": "Novo Box " + idObjetos,
        "posicao_x": posX,
        "posicao_y": posY,
        "cor_bg": "ff0000",
        "cor_nome": "ffffff",
        "idapp": idgerado,
        "sla": null,
        "ordem": "0",
        "sigla": "",
        "acoes": []
    };
    //// console.log('flags',jsonfile.flags);

    for(var i in jsonfile.flags){
        dados[i] = 'N';

    }

    jsonfile.blocos.push(dados);
    createBlocks(dados);
    chaveDraw = true;
}

function setRelacionamento(item1, item2) {
    // console.log('relacionamento',item1.id,item2.id);
    var id = jsonfile.relacionamentos.length + 1;
    var dados = {
        "idrelacionamento": newId(),
        // "idsituacao_de": item1.db.idsituacao,
        "idsituacao_de_app": item1.db.idapp,
        // "idsituacao_para": item2.db.idsituacao,
        "idsituacao_para_app": item2.db.idapp,
        "acoes": []
    };

    var chaveAdd = true;
    for (var i in jsonfile.relacionamentos) {
        var jsItem = jsonfile.relacionamentos[i];
        if (jsItem.idsituacao_de_app == item1.db.idapp && jsItem.idsituacao_para_app == item2.db.idapp || item1.id == item2.id) {
            chaveAdd = false;
            //// console.log('relacionamento ja existe');
        }
        ;

        //// console.log('teste')
        //jsonfile.relacionamentos
    }
    if (chaveAdd) {

        jsonfile.relacionamentos.push(dados);
    }
}

function setObject(item) {
    //// console.log(item);
    $('#label-objeto').html(item.db.nome);
    $('#label-objeto').css('color', '#' + item.db.cor_nome)
    $('#label-objeto').css('background-color', '#' + item.db.cor_bg)
    $('#label-objeto').show();
    itemSelected = item.db;
}

function mouseDobleClick() {
    if (itemSelected) {
        workFlowModal();
    } else if (relacionamentoClick) {
        workFlowModalRelacionamento();
    }

}

function workFlowModalRequisito() {

}

//modalTituloRequisito

function workFlowModal() {

    var total = 0;
    var obj = getObject();

    $('#modalTitulo').html('Editando <span class="semi-bold">'+obj.nome+'</span>');

    $('#wf-situacao').val(obj.nome);
    $('#wf-sigla').val(obj.sigla);

    $('#wf-corbg').val('#' + obj.cor_bg);
    $('#wf-cortx').val('#' + obj.cor_nome);

    $('#wf-corbg').colorpicker('setValue', '#' + obj.cor_bg);
    $('#wf-cortx').colorpicker('setValue', '#' + obj.cor_nome);


    var opcoes = getOpcoes();


    $('#wf-ordem').html('');
    var cont = 0;
    var selectOrdem = '0';

    for (var i in blocks) {

        var selectedCombo = '';
        if (obj.ordem == cont) {
            selectOrdem = obj.ordem;

            selectedCombo = 'selected';

        }
        $('#wf-ordem').append('<option value="' + cont + '" '+ selectedCombo +' >' + cont + '</option>');
        cont++;
    }

    $("#wf-ordem").select2("data", {id: selectOrdem, text: selectOrdem});

    $('#atrib-base').html('');
    var sintaxOpcoes = '';

    sintaxOpcoes +=
        '<div class="coluna">' +
        '<h3>Flags</h3>' +
        '<div class="base" id="colunaFlag">';

    for (var flag in jsonfile.flags) {
        var flagLabel = jsonfile.flags[flag];
        var chaveCheked = '';
        for (var i in blocks) {
            var blc = blocks[i].db;
            if (blc[flag] == 'S') {
                if (obj == blc) {
                    chaveCheked = ' checked="checked" ';
                } else {
                    chaveCheked = ' checked="checked" disabled="true" ';
                }
            }
        }
        sintaxOpcoes += '<div class="checkbox check-success">' +
            '<input  type="checkbox" value="' + flag + '" id="ch' + flag + '" ' + chaveCheked + ' >' +
            '<label for="ch' + flag + '">' + flagLabel + '</label>' +
            '</div>';
    }

    sintaxOpcoes += '</div></div>';

    for (var item in opcoes) {
        var itemOpcao = opcoes[item];
        sintaxOpcoes +=
            '<div class="coluna ">' +
            '<h3>' + item + '</h3>' +
            '<div class="base modalAcoes">';
        for (var itens in itemOpcao) {
            var chaveCheked = '';
            for (var a = 0; a < obj.acoes.length; a++) {
                if (itemOpcao[itens].idopcao == obj.acoes[a].idopcao) {
                    chaveCheked = ' checked="checked" ';
                }
            }
            sintaxOpcoes += '<div class="checkbox check-success">' +
                '<input type="checkbox" value="' + itemOpcao[itens].idopcao + '" id="ch' + itemOpcao[itens].idopcao + '" ' + chaveCheked + '>' +
                '<label for="ch' + itemOpcao[itens].idopcao + '">' + itemOpcao[itens].nome + '</label> </div>';
        }
        sintaxOpcoes += '</div></div>';
    }


    $('#atrib-base').html(sintaxOpcoes);
    $('#atrib-base').children('.coluna').each(function () {
        total += $(this).innerWidth();
    });
    $('#atrib-base').width(total);
    $('#modalWorkFlowBlocos').modal('show');

    if (bloqueio_workflow) {
        $('input').prop('disabled', true);
        $('select').prop('disabled', true);
    }
}


function workFlowModalRelacionamento() {


    var total = 0;
    var obj = getObjectRel();

    var dadosRel = getRelacionamentoId(obj.id);

    var objde = getItemByIdapp(dadosRel.idsituacao_de_app);
    var objpara = getItemByIdapp(dadosRel.idsituacao_para_app);


    $('#modalTituloRequisito').html('Editando de ' + objde.nome + ' para ' + objpara.nome);


    $('#wf-acoes').html('');
    $('#wf-requisitos').html('');
    $('#atrib-relacionamento').html('');


    // console.log('wfacoes:, ', wfacoes);

    for (var i in wfacoes) {
        $('#wf-acoes').append('<option value="'+wfacoes[i].idopcao+'" >'+wfacoes[i].nome+'</option>');
    }
    for (var i in wfprerequisitos) {
        $('#wf-requisitos').append('<option value="'+wfprerequisitos[i].idopcao+'" >'+wfprerequisitos[i].nome+'</option>');
    }

    if(dadosRel){
        // console.log('dados rel',dadosRel);
    }



    var relColAcao = '\
        <div class="row-fluid">\
        <div class="span6">\
        <h3>Ações</h3>';

    //jsonfile

    for(var i=0;i<dadosRel.acoes.length;i++){
        if(dadosRel.acoes[i].tipo == 'acao'){
            var opcaoSelect = getOpcaoId(dadosRel.acoes[i].idopcao);
            relColAcao += relObjectItem(opcaoSelect.nome,dadosRel.acoes[i].idacao );
        }
    }

    relColAcao += '</div>';

    var relColrequisitos = '\
        <div class="span6">\
        <h3>Pré requisitos</h3>';

    //
    for(var i=0; i < dadosRel.acoes.length; i++){
        if(dadosRel.acoes[i].tipo == 'prerequisito'){
            var opcaoSelect = getOpcaoId(dadosRel.acoes[i].idopcao);
            relColrequisitos += relObjectItem(opcaoSelect.nome,dadosRel.acoes[i].idacao );
        }
    }

    relColrequisitos += '</div></div>';

    var relconteudo = relColAcao + '' + relColrequisitos;

    $('#atrib-relacionamento').html(relconteudo);
    /*


     var cont =0;
     var selectOrdem = '0';
     for(var i in blocks){
     if(obj.ordem == cont){
     selectOrdem =obj.ordem;
     }
     $('#wf-ordem').append('<option value="'+cont+'" >'+cont+'</option>');
     cont++;
     }

     $("#wf-ordem").select2("data", { id: selectOrdem, text: selectOrdem });

     $('#atrib-base').html('');
     var sintaxOpcoes = '';

     sintaxOpcoes +=
     '<div class="coluna">' +
     '<h3>Flags</h3>' +
     '<div class="base" id="colunaFlag">';

     for(var flag in jsonfile.flags){
     var flagLabel = jsonfile.flags[flag];
     var chaveCheked = '';
     for(var i in blocks){
     var blc = blocks[i].db;
     if(blc[flag] == 'S'){
     if(obj == blc){
     chaveCheked = ' checked="checked" ';
     } else {
     chaveCheked = ' checked="checked" disabled="true" ';
     }
     }
     }
     sintaxOpcoes += '<div class="checkbox check-success">' +
     '<input  type="checkbox" value="'+ flag +'" id="ch'+flag+'" '+ chaveCheked +' >' +
     '<label for="ch'+flag+'">'+ flagLabel +'</label>' +
     '</div>';
     }

     sintaxOpcoes += '</div></div>';


     //sintaxOpcoes += '<div id="modalAcoes">';

     for(var item in opcoes){
     var itemOpcao = opcoes[item];
     sintaxOpcoes +=
     '<div class="coluna ">' +
     '<h3>'+ item +'</h3>' +
     '<div class="base modalAcoes">';
     for(var itens in itemOpcao){
     var chaveCheked = '';
     for(var a =0 ;a< obj.acoes.length;a++){
     if(itemOpcao[itens].idopcao == obj.acoes[a].idopcao){
     chaveCheked = ' checked="checked" ';
     }
     }
     sintaxOpcoes += '<div class="checkbox check-success">' +
     '<input type="checkbox" value="'+itemOpcao[itens].idopcao+'" id="ch'+itemOpcao[itens].idopcao+'" '+ chaveCheked+'>' +
     '<label for="ch'+itemOpcao[itens].idopcao+'">'+ itemOpcao[itens].nome +'</label> </div>';
     }
     sintaxOpcoes += '</div></div>';
     }
     // sintaxOpcoes += '</div>';

     $('#atrib-base').html(sintaxOpcoes);
     $('#atrib-base').children('.coluna').each(function () {
     total += $(this).innerWidth();
     });
     $('#atrib-base').width(total);

     */

    $('#modalWorkFlowRequisito').modal('show');

}

function relObjectItem(titulo,idrelItem){
    var blockitem = '<div class="item-rel">\
        <span>'+titulo+'</span>';
    if (!bloqueio_workflow) {
        blockitem += '\
        <button class="btn btn-success " onclick="editRelItem(\''+idrelItem+'\')" >Opções</button>';
    }
    blockitem += '\
    </div>';
    return blockitem;
}

function getRelacionamentoId(id){
    for(var i=0;i<jsonfile.relacionamentos.length;i++){
        if(jsonfile.relacionamentos[i].idrelacionamento == id){
            return jsonfile.relacionamentos[i];
        }
    }
    return null;
}

function getOpcaoId(id){
    for(opcao of Object.values(jsonfile.opcoes)){
        if(opcao.idopcao == id){
            return opcao;
        }
    }
    return null;
}

function editRelItem(id){


    var obj = getObjectRel();


    var dadosRel = getRelacionamentoId(obj.id);

    for(var i=0; i < dadosRel.acoes.length; i++){
        if(dadosRel.acoes[i].idacao == id){
            var opcaoSelect = getOpcaoId(dadosRel.acoes[i].idopcao);
            console.log(opcaoSelect);

            var relColAcao = '\
            <div class="row-fluid"><div class="span12 wf-att-titulo"> '+ opcaoSelect.nome +'</div></div>\
            <div class="row-fluid"><div class="span12">';

            //jsonfile

            for(var a=0;a<dadosRel.acoes[i].parametros.length; a++){
                relColAcao += getObjHTML(dadosRel.acoes[i].parametros[a] );
            }

            relColAcao += '' +
                '<div class="wf-rel-acoes">\
                <button class="btn btn-success"  onclick="wfrelbtAtualizar(\'' + id +'\')">Atualizar</button>\
                <button class="btn btn-warning" onclick="wfrelbtCancelar()">Cancelar</button>\
                <button class="btn btn-danger" onclick="wfrelbtDeletar(\''+ dadosRel.acoes[i].idacao +'\')">Deletar Atributo</button>\
                </div>' +
                '</div></div>';

            $('#atrib-relacionamento').html(relColAcao);
        }
    }

}

function wfrelbtAtualizar(id){
    var obj = getObjectRel();
    var dadosRel = getRelacionamentoId(obj.id);
    for(var i=0; i < dadosRel.acoes.length; i++){
        if(dadosRel.acoes[i].idacao == id){
            for(var a=0;a<dadosRel.acoes[i].parametros.length; a++){
                var param = dadosRel.acoes[a].parametros[a];
                param.valor = $('#wfobj'+param.idparametro).val();
            }
        }
    }
    workFlowModalRelacionamento();
}

function wfrelbtCancelar(){

    workFlowModalRelacionamento();
}

function wfrelbtDeletar(idbox){

    var obj = getObjectRel();
    var dadosRel = getRelacionamentoId(obj.id);
    for(var i=0; i < dadosRel.acoes.length; i++){
        if(idbox == dadosRel.acoes[i].idacao){
            delete dadosRel.acoes[i];
        }

    }

    removeUndefined(dadosRel.acoes);

    workFlowModalRelacionamento();
}

function getObjHTML(obj){
    if(obj){
        if(obj.tipo == "textarea"){
            var html = '<div class="wf-relhtml">\
            <h3>'+obj.nome+'</h3>\
            <textarea name="nom" id="wfobj'+ obj.idparametro +'"  rows="4">' + obj.valor + '</textarea>\
          </div>';
            return html;
        }
    }
    return false;
}

function unsetObject() {
    $('#label-objeto').html();
    $('#label-objeto').hide();
    itemSelected = null;
    // $('#bt-propriedade').hide();
}

function getObject() {
    return itemSelected;
}

function getObjectRel() {
    return relacionamento[relacionamentoClick];
}

function wfaddRequisito(){
    var idopcao = $('#wf-requisitos').val();
    var parmOpcoes = getOpcaoId(idopcao);
    var obj = getObjectRel();
    var dadosRel = getRelacionamentoId(obj.id);

    var dados = {};
    dados.idacao = newId();// (new Date).getTime().toString();//Math.floor(Math.random() * 999) + 111;
    dados.idacaotemp = newId();// (new Date).getTime().toString();//Math.floor(Math.random() * 999) + 111;
    dados.tipo = 'prerequisito';
    dados.idopcao = idopcao;
    dados.parametros = parmOpcoes.parametros;
    dadosRel.acoes.push(dados);

    workFlowModalRelacionamento();
}

function wfaddAcoes(){


    var idopcao = $('#wf-acoes').val();
    var parmOpcoes = getOpcaoId(idopcao);
    var obj = getObjectRel();
    var dadosRel = getRelacionamentoId(obj.id);

    var dados = {};
    dados.idacao = newId();// (new Date).getTime().toString();//Math.floor(Math.random() * 999) + 111;
    dados.idacaotemp = newId();// (new Date).getTime().toString();//Math.floor(Math.random() * 999) + 111;
    dados.tipo = 'acao';
    dados.idopcao = idopcao;
    dados.parametros = parmOpcoes.parametros;
    dadosRel.acoes.push(dados);
    workFlowModalRelacionamento();

    removeUndefined(dadosRel);



}

function setObjectDB(obj) {

    blocks[obj.idapp].db = itemSelected = obj;
    $('#label-objeto').hide();
    draw();

}

function exibirOrdem() {
    chaveOrdem = !chaveOrdem;
    for (var blk in blocks) {
        var item = blocks[blk];
        item.visibleOrder(chaveOrdem);
    }

    draw();
}

function trace(tx) {
    lg.html(tx);
}

function loop() {
    window.requestAnimationFrame(loop, cnv);
    update();
    render();
}

function update() {

    if (cam.x > 0) {
        cam.x = 0;
    }
    if (cam.x < -1 * (workflowStage.width - cnv.width)) {
        cam.x = -1 * (workflowStage.width - cnv.width);
    }
    if (cam.y > 0) {
        cam.y = 0;
    }
    if (cam.y < -1 * (workflowStage.height - cnv.height)) {
        cam.y = -1 * (workflowStage.height - cnv.height);
    }

}

function draw() {


    var ptrn = ctx.createPattern(background, 'repeat');
    ctx.fillStyle = ptrn;
    ctx.fillRect(0, 0, workflowStage.width, workflowStage.height);

    relacionamento = [];

    if (jsonfile.relacionamentos) {
        for (var i in jsonfile.relacionamentos) {
            var linha = jsonfile.relacionamentos[i];
            var posRelacionamento = createLine(linha.idrelacionamento, linha.idsituacao_de_app, linha.idsituacao_para_app);
            posRelacionamento.id = linha.idrelacionamento;
            relacionamento[linha.idrelacionamento] = posRelacionamento;
        }
    }

    for (var i in blocks) {
        var spr = blocks[i];
        spr.draw(ctx);
    }

    if (dragBall) {

        ctx.beginPath();
        ctx.arc(dragObj.difX, dragObj.difY, 6, 0, 2 * Math.PI, false);
        ctx.fillStyle = '#E46402';
        ctx.fill();
        ctx.lineWidth = 3;
        ctx.strokeStyle = '#FFF';
        ctx.stroke();
    }

}

function createLine(id, obj1, obj2) {


    var ptMarg = 15;
    var ptBezie = 100;
    var keyActive = true;

    var point1 = blocks[obj1];
    var point2 = blocks[obj2];
    var point1CenterY = blocks[obj1].centerY();
    var point2CenterY = blocks[obj2].centerY();

    var colorLineActive = '#515050';
    var colorLineInative = '#BDC1BC';

    var ballColor = '#E46402';


    ctx.lineWidth = 2;

    if (point1.clicked || point2.clicked) {

        keyActive = true;
    } else {
        keyActive = false;
    }

    if (keyActive) {
        ctx.strokeStyle = colorLineActive;
    } else {
        ctx.strokeStyle = colorLineInative;
    }

    var pt1X, pt1Y, pt2X, pt2Y, centerX, centerY, calcptX, calcptY = 0;


    pt1X = point1.posX;
    pt1Y = point1.posY;

    pt2X = point2.posX;
    pt2Y = point2.posY;


    var realpt1X = pt1X + ptMarg;
    var realpt2X = (point2.posX + point2.width - ptMarg);

    if (point1CenterY < point2CenterY) {
        realpt2X = pt2X + ptMarg;//(point2.posX+ point2.width - ptMarg);
        point1.setas.pb1 = true;
        point2.setas.pt1 = true;

    } else {
        // console.log('maior');
        point1.setas.pt2 = true;
        point2.setas.pb2 = true;
        realpt1X = (point1.posX + point1.width - ptMarg);
    }


    calcptX = Math.abs(realpt1X - realpt2X) / 2;
    centerX = realpt1X + calcptX;
    centerY = point1CenterY + calcptY;

    var newPt1Y = 0;
    var newPt2Y = 0;

    var newPt1Bezie = 0;
    var newPt2Bezie = 0;

    if (realpt1X < realpt2X) {
    } else {
        centerX = realpt1X - calcptX;
    }

    if (point1CenterY < point2CenterY) {
        centerY = pt1Y + point1.height;
        newPt1Y = point1.height;
        newPt1Bezie = point1.height + ptBezie;
        newPt2Bezie = -1 * ptBezie;
    } else {
        centerY = pt2Y + point2.height;
        newPt2Y = point2.height;
        newPt1Bezie = -1 * ptBezie;
        newPt2Bezie = ptBezie;
    }

    var pt1NewY = (pt1Y + newPt1Y);
    var pt2NewY = (point2.posY + newPt2Y);
    var newYDif = Math.abs(pt1NewY - pt2NewY) / 2;


    if (pt1NewY < pt2NewY) {
        centerY = pt1NewY + newYDif;
    } else {
        centerY = pt2NewY + newYDif;
    }


    //draw

    ctx.beginPath();
    ctx.moveTo(realpt1X, pt1Y + newPt1Y);
    ctx.bezierCurveTo(
        realpt1X,
        point1.posY + newPt1Bezie,

        realpt2X,
        point2.posY + newPt2Y + newPt2Bezie,

        realpt2X,
        point2.posY + newPt2Y
    );
    ctx.stroke();


    if (keyActive) {
        ctx.globalAlpha = 1;
    } else {
        ctx.globalAlpha = .5;
    }
    if (relacionamentoClick && relacionamentoClick == id) {
        ballColor = '#E43413'
        // relacionamentoClick = null;
        ctx.globalAlpha = 1;
    }

    ctx.beginPath();
    ctx.arc(centerX, centerY, 6, 0, 2 * Math.PI, false);
    ctx.fillStyle = ballColor;
    ctx.fill();
    ctx.globalAlpha = 1;

    return {'posX': centerX, 'posY': centerY};

}

function render() {
    // $(statusBar).html(countFPS());

    if (chaveLoop || dragBall || chaveDraw) {
        ctx.save();
        ctx.clearRect(0, 0, cnv.width, cnv.height);
        ctx.translate(cam.x, cam.y);
        draw();
        ctx.restore();
        chaveDraw = false;
    }
}

function newId() {
    var id = new IDGenerator();
    return id.generate();
}

function IDGenerator() {

    this.length = 8;
    this.timestamp = +new Date;

    var _getRandomInt = function (min, max) {
        return Math.floor(Math.random() * ( max - min + 1 )) + min;
    }

    this.generate = function () {
        var ts = this.timestamp.toString();
        var parts = ts.split("").reverse();
        var id = "";

        for (var i = 0; i < this.length; ++i) {
            var index = _getRandomInt(0, parts.length - 1);
            id += parts[index];
        }

        return id;
    }

}

function ampliarWorkflow(){
    $('#workflow').toggleClass('workflowZoom');
    organizar();
}

function removeUndefined(array)
{
    var i = 0;
    while (i < array.length){
        if (typeof array[i] === 'undefined'){
            array.splice(i, 1);
        }else{
            i++;
        }
    }
    return array;
}
