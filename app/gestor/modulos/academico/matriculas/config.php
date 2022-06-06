<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "administrar";
$config["acoes"][4] = "dossie";
$config["acoes"][5] = "alterar_situacao_matricula";
$config["acoes"][6] = "alterar_dados_matricula";
$config["acoes"][7] = "alterar_campo_faturado";
/*$config["acoes"][7] = "adicionar_associados";
$config["acoes"][8] = "remover_associados";*/
$config["acoes"][9] = "modificar_financeiro_matrícula";
$config["acoes"][10] = "enviar_documentos";
$config["acoes"][11] = "alterar_situacao_documentos";
$config["acoes"][12] = "remover_documentos";
$config["acoes"][13] = "gerar_enviar_contratos";
$config["acoes"][14] = "informar_assinatura_contrato";
$config["acoes"][15] = "informar_validade_contrato";
$config["acoes"][16] = "cancelar_contrato";
$config["acoes"][17] = "aprovar_matricula";
$config["acoes"][18] = "gerar_enviar_declaracoes";
$config["acoes"][19] = "renegociar_parcelas";
$config["acoes"][20] = "alterar_data_registro";
$config["acoes"][21] = "alterar_escola_matricula";
$config["acoes"][22] = "lancar_nota";
$config["acoes"][23] = "remover_nota";
$config["acoes"][24] = "enviar_arquivos_pasta";
$config["acoes"][25] = "alterar_arquivos_pasta";
$config["acoes"][26] = "remover_arquivos_pasta";
$config["acoes"][27] = "modificar_nota";
$config["acoes"][28] = "transferir_parcelas";
$config["acoes"][29] = "transferir_turma";
$config["acoes"][30] = "porcentagem_aluno";
$config["acoes"][31] = "alterar_situacao_detran_aguardando_liberacao";
$config["acoes"][32] = "alterar_tentativas_prova";
$config['acoes'][33] = 'remover_reconhecimento_padrao';
$config['acoes'][34] = 'alterar_limite_datavalid';
$config['acoes'][35] = 'alterar_data_inicio_conclusao';

$config["monitoramento"]["onde"] = "79";
$config["monitoramento_pessoa"]["onde"] = "16";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
  "tabela" => "matriculas",
  "primaria" => "m.idmatricula",
  "campos_insert_fixo" => array(
    "data_cad" => "now()",
    "ativo" => "'S'"
  )
);

$config["banco_pessoas"] = array(
  "tabela" => "pessoas",
  "primaria" => "idpessoa",
  "campos_insert_fixo" => array(
    "data_cad" => "now()",
    "ativo" => "'S'"
  ),
  "campos_unicos" => array(
    /*array(
      "campo_banco" => "documento",
      "campo_form" => "documento",
      "erro_idioma" => "cpf_utilizado",
      "campo_php" => 'return str_replace(array(".", "-", "/"),"","%s")'
    ),*/
    array(
      "campo_banco" => "email",
      "campo_form" => "email",
      "erro_idioma" => "email_utilizado"
    )
  )
);

$config['remover_dados_tabelas_transferencias_alunos'] = array(
    'avas_enquetes_opcoes_votos',
    'matriculas_alunos_historicos',
    'matriculas_anotacoes',
    'matriculas_avaliacoes_historicos',
    'matriculas_notas',
    'matriculas_avaliacoes',
    'matriculas_avas_porcentagem',
    'matriculas_disciplinas_notas',
    'matriculas_exercicios',
    'matriculas_objetos_favoritos',
    'matriculas_rotas_aprendizagem_objetos',
);
