<?php
if ('ativar_desativar' == $url[5]) {
  $linhaObj->Set('id', (int) $url[3]);
  $linhaObj->Set('post', $_POST);
  echo $linhaObj->ModificarOpcoesAtivoPainel();
  exit;
} elseif ('correta_incorreta' == $url[5]) {
  $linhaObj->Set("id",intval($url[3]));
  $linhaObj->Set("post",$_POST);
  echo $linhaObj->ModificarRespostaCorreta();
  exit;
} elseif ('associar_disciplina' == $url[5]) {
	echo $linhaObj->BuscarDisciplina();
}