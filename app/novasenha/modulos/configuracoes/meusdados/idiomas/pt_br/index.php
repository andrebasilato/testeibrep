<?php
//if($url[3] == 'cfc') $url[3] = str_replace('cfc', 'escola', $url[3]);
if($url[3] == 'escola') $url[3] = str_replace('escola', 'cfc', $url[3]);
if($url[3] == 'vendedor') $url[3] = 'atendente';
$idioma["form_erros"] = "Por favor corrija o(s) erro(s) abaixo: ";
$idioma["solicitacao_nao_econtrada"] = "Solicitação de senha não econtrada.";
$idioma["solicitacao_usada"] = "Solicitação de senha já utilizada, para gerar uma nova solicitação <a href='http://".$_SERVER["SERVER_NAME"]."/".$url[3]."/esqueci'>clique aqui</a>.";
$idioma["solicitacao_inativa"] = "Solicitação de senha inativada, para gerar uma nova solicitação <a href='http://".$_SERVER["SERVER_NAME"]."/".$url[3]."/esqueci'>clique aqui</a>.";
$idioma["modificar_senha_sucesso"] = "Senha modificada com sucesso, para acessar com sua nova senha <a href='http://".$_SERVER["SERVER_NAME"]."/".$url[3]."'>clique aqui</a>.";
$idioma["erro_modificar_senha"] = "Erro ao modificar a senha.";
$idioma["solicitacao_expirada"] = "Essa solicitação de nova senha está expirada, para gerar uma nova solicitação <a href='http://".$_SERVER["SERVER_NAME"]."/".$url[3]."/esqueci'>clique aqui</a>.";
$idioma["form_erros"] = "Por favor corrija o(s) erro(s) abaixo: ";
$idioma["legendadadosusuarios"] = "Nova senha";
$idioma["form_senha"] = "Senha:";
$idioma["form_senha_ajuda"] = " A senha deve ter no mínimo 8 caracteres e no máximo 30 caracteres entre letras e números.";
$idioma["form_confirma"] = "Confirme:";
$idioma["form_confirma_ajuda"] = "Confirme a sua senha";
$idioma["senha_vazio"] = "Informe sua nova senha";
$idioma["confirmacao_invalida"] = "A confirmação da senha não confere";
$idioma["maximo_senha"] = "A senha deve conter no máximo 30 caracteres";
$idioma["minimo_senha"] = "A senha deve conter no minimo 8 caracteres";
