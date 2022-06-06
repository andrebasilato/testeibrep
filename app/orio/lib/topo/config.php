<?php

$menuTopo = array();

$menuTopo[] = [
    "nome" => "inicio",
    "nome_idioma" => "inicio",
    "link" => "/" . $url[0],
    "icone" => "fa-home",
    "submenu" => false,
];

$menuTopo[] = [
    "nome" => "entrada",
    "nome_idioma" => "entrada",
    "link" => "/" . $url[0] . "/transacoes?q%5B1%7Ctipo%5D=E",
    "icone" => "fa-cloud-download",
    "submenu" => false,
];

$menuTopo[] = [
    "nome" => "saida",
    "nome_idioma" => "saida",
    "link" => "/" . $url[0] . "/transacoes?q%5B1%7Ctipo%5D=S",
    "icone" => "fa-cloud-upload",
    "submenu" => false,
];

$menuTopo[] = [
    "nome" => "pendente",
    "nome_idioma" => "pendente",
    "link" => "/" . $url[0] . "/transacoes?q%5B1%7Csituacao%5D=1",
    "icone" => "fa-refresh",
    "submenu" => false,
];

$menuTopo[] = array(
    "nome" => "logout",
    "nome_idioma" => "logout",
    "link" => "?opLogin=sair",
    "icone" => "fa-arrow-circle-o-right",
    "li_class" => "menu-bt-direita",
    "submenu" => false,
);
