<?php
if ($url[5] == 'acessar_ava') {
    echo $linhaObj->ativarAcesso($_POST['ativo_login']);
}