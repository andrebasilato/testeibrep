<?php
// Includes gerais
include "../includes/config.php";
include "../includes/funcoes.php";

// Includes do adm
include "includes/config.php";
include "includes/funcoes.php";

// Classe PHPMailer (e-mail)
require_once '../classes/PHPMailer/PHPMailerAutoload.php';

// Login
include "includes/login.php";

// Verifica se a copia do painel permite a utilização do módulo
verificaModulos($url[1], $url[2]);

// Verifica a url[1](adm) e inclue o arquivo de acordo com a informação da url[1]
if ($url[1]) {
    $modulo = "modulos/" . $url[1];
    // Verifica se o arquivo existe
    if (file_exists($modulo)) {
        // Verifica a url[2](configuracoes, academico, financeiro) e inclue o arquivo de acordo com a informação da url[2]
        if ($url[2]) {
            $funcionalidade = $modulo . "/" . $url[2];
            // Verifica se o arquivo existe
            if (file_exists($funcionalidade)) {

                // Se a tela não existe (Desktop ou Mobile) imprimimos a lib Sem Tela
                if (!file_exists($funcionalidade . "/telas/" . $config["tela_padrao"])) {
                    incluirLib("sem_tela", $config, $usuario);
                }

                include $funcionalidade . "/index.php";
                // Se o arquivo não existir, mostra ERRO 404
            } else {
                incluirLib("404", $config, $usuario);
            }
            // Se o arquivo não existir, mostra ERRO 404
        } else {
            if (file_exists("modulos/" . $url[1] . "/index/index.php")) {
                include "modulos/" . $url[1] . "/index/index.php";
            } else {
                incluirLib("404", $config, $usuario);
            }
        }
        // Se o arquivo não existir, mostra ERRO 404
    } else {
        incluirLib("404", $config, $usuario);
    }
// Se o não tiver a url[1], inclue a home
} else {
    $orio_interfaces_label['pt_br'] = array();
    $orio_interfaces_descricoes['pt_br'] = array();
    foreach ($orio_interfaces as $id => $dados) {
        $orio_interfaces_label['pt_br'][$id] = $dados["nome"];
        $orio_interfaces_descricoes['pt_br'][$id] = $dados["descricao"];
    }

    include "modulos/index/index.php";
}
