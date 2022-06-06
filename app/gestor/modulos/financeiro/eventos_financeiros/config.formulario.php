<?php
// Array de configuração para a formulario
$config["formulario"] = array(
 array(
  "fieldsetid"=>"dadosdoobjeto", // Titulo do formulario (referencia a variavel de idioma)
  "legendaidioma"=>"legendadadosdados", // Legenda do fomrulario (referencia a variavel de idioma)
  "campos"=>array( // Campos do formulario
   array(
    "id"=>"form_nome",
    "nome"=>"nome",
    "nomeidioma"=>"form_nome",
    "tipo"=>"input",
    "valor"=>"nome",
    "validacao"=>array(
     "required"=>"nome_vazio"
    ),
    "class"=>"span6",
    "banco"=>true,
    "banco_string"=>true
   ),
   array(
    "id"=>"form_mensalidade",
    "nome"=>"mensalidade",
    "nomeidioma"=>"form_mensalidade",
    "tipo"=>"select",
    "array"=>"sim_nao", // Array que alimenta o select
    "class"=>"span2",
    "valor"=>"mensalidade",
    "validacao"=>array(
     "required"=>"mensalidade_vazio"
    ),
    "ajudaidioma"=>"form_mensalidade_ajuda",
    "banco"=>true,
    "banco_string"=>true
   ),
   array(
    "id"=>"form_taxa_reativacao",
    "nome"=>"taxa_reativacao",
    "nomeidioma"=>"form_taxa_reativacao",
    "tipo"=>"select",
    "array"=>"sim_nao", // Array que alimenta o select
    "class"=>"span2",
    "valor"=>"taxa_reativacao",
    "validacao"=>array(
     "required"=>"taxa_reativacao_vazio"
    ),
    "ajudaidioma"=>"form_mensalidade_ajuda",
    "banco"=>true,
    "banco_string"=>true
   ),
   array(
    "id"=>"form_ativo_painel",
    "nome"=>"ativo_painel",
    "nomeidioma"=>"form_ativo_painel",
    "tipo"=>"select",
    "array"=>"ativo", // Array que alimenta o select
    "class"=>"span2",
    "valor"=>"ativo_painel",
    "validacao"=>array(
     "required"=>"ativo_vazio"
    ),
    "ajudaidioma"=>"form_ativo_ajuda",
    "banco"=>true,
    "banco_string"=>true
   )
  )
 )
);
?>