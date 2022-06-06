<?php

$menuTopo = array();

$menuTopo[] = [
    "nome" => "inicio",
    "nome_idioma" => "inicio",
    "link" => "/" . $url[0],
    "icone" => "fa-home",
    "submenu" => false,
];

$menuTopo[] = array(
    "nome" => "especifico",
    "nome_idioma" => "/configuracoes/especifico",
    "link" => "/" . $url[0] . "configuracoes/especifico",
    "icone" => "fa-cog",
    "li_class" => "",
    "submenu" => false,
);
$menuTopo[] = array(
    "nome" => "detrans",
    "nome_idioma" => "/configuracoes/detrans",
    "link" => "/" . $url[0] . "configuracoes/detrans",
    "icone" => "fa-cog",
    "li_class" => "",
    "submenu" => false,
);

$menuTopo[] = array(
    "nome" => "logout",
    "nome_idioma" => "logout",
    "link" => "?opLogin=sair",
    "icone" => "fa-arrow-circle-o-right",
    "li_class" => "menu-bt-direita",
    "submenu" => false,
);
