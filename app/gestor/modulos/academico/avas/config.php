<?php
$config["funcionalidade"] = "funcionalidade";
$config["funcionalidade_icone_32"] = "/assets/icones/preto/32/menu_completo_32.png";
$config["acoes"][1] = "visualizar";
$config["acoes"][2] = "cadastrar_modificar";
$config["acoes"][3] = "remover";

$config["acoes"][4] = "rotas_visualizar";
$config["acoes"][5] = "rotas_modificar";
//$config["acoes"][6] = "rotas_remover";

$config["acoes"][7] = "conteudos_visualizar";
$config["acoes"][8] = "conteudos_cadastrar_modificar";
$config["acoes"][9] = "conteudos_remover";

$config["acoes"][10] = "videos_visualizar";
$config["acoes"][11] = "videos_cadastrar_modificar";
$config["acoes"][12] = "videos_remover";

$config["acoes"][13] = "audios_visualizar";
$config["acoes"][14] = "audios_cadastrar_modificar";
$config["acoes"][15] = "audios_remover";

$config["acoes"][16] = "downloads_visualizar";
$config["acoes"][17] = "downloads_cadastrar_modificar";
$config["acoes"][18] = "downloads_remover";

$config["acoes"][19] = "perguntas_visualizar";
$config["acoes"][20] = "perguntas_cadastrar_modificar";
$config["acoes"][21] = "perguntas_remover";

$config["acoes"][51] = "enquetes_visualizar";
$config["acoes"][52] = "enquetes_cadastrar_modificar";
$config["acoes"][53] = "enquetes_remover";

$config["acoes"][22] = "links_visualizar";
$config["acoes"][23] = "links_cadastrar_modificar";
$config["acoes"][24] = "links_remover";

$config["acoes"][25] = "foruns_visualizar";
$config["acoes"][26] = "foruns_cadastrar_modificar";
$config["acoes"][27] = "foruns_remover";
$config["acoes"][50] = "acessar_forum";
$config["acoes"][40] = "topicos_visualizar";
$config["acoes"][41] = "topicos_cadastrar_modificar";
$config["acoes"][42] = "topicos_responder";
$config["acoes"][43] = "topicos_moderar";
$config["acoes"][44] = "topicos_ocultar";
$config["acoes"][45] = "topicos_bloquear";
$config["acoes"][46] = "topicos_assinar";
$config["acoes"][47] = "topicos_mensagens_visualizar";
$config["acoes"][48] = "topicos_mensagens_moderar";
$config["acoes"][49] = "topicos_mensagens_ocultar";

$config["acoes"][54] = "exercicios_visualizar";
$config["acoes"][55] = "exercicios_cadastrar_modificar";
$config["acoes"][56] = "exercicios_remover";

$config["acoes"][28] = "avaliacoes_visualizar";
$config["acoes"][29] = "avaliacoes_cadastrar_modificar";
$config["acoes"][30] = "avaliacoes_remover";

$config["acoes"][31] = "simulados_visualizar";
$config["acoes"][32] = "simulados_cadastrar_modificar";
$config["acoes"][33] = "simulados_remover";

$config["acoes"][34] = "tira_duvidas_visualizar";
$config["acoes"][35] = "tira_duvidas_cadastrar_modificar";
$config["acoes"][36] = "tira_duvidas_remover";

$config["acoes"][37] = "chats_visualizar";
$config["acoes"][38] = "chats_cadastrar_modificar";
$config["acoes"][39] = "chats_remover";

$config["acoes"][40] = "objetos_divisores_visualizar";
$config["acoes"][41] = "objetos_divisores_cadastrar_modificar";
$config["acoes"][42] = "objetos_divisores_remover";

$config["acoes"][57] = "faqs_visualizar";
$config["acoes"][58] = "faqs_cadastrar_modificar";
$config["acoes"][59] = "faqs_remover";

$config["acoes"][60] = "clonar_ava";

$config["monitoramento"]["onde"] = "12";
$config["monitoramento"]["onde_rotas_aprendizagem"] = "22";
$config["monitoramento"]["onde_conteudos"] = "23";
$config["monitoramento"]["onde_videos"] = "24";
$config["monitoramento"]["onde_audios"] = "25";
$config["monitoramento"]["onde_downloads"] = "26";
$config["monitoramento"]["onde_perguntas"] = "27";
$config["monitoramento"]["onde_enquetes"] = "175";
$config["monitoramento"]["onde_links"] = "28";
$config["monitoramento"]["onde_foruns"] = "29";
$config["monitoramento"]["onde_foruns_topicos"] = "153";
$config["monitoramento"]["onde_exercicios"] = "183";
$config["monitoramento"]["onde_avaliacoes"] = "30";
$config["monitoramento"]["onde_simulados"] = "31";
$config["monitoramento"]["onde_tira_duvidas"] = "32";
$config["monitoramento"]["onde_chats"] = "41";
$config["monitoramento"]["onde_objetos_divisores"] = "182";
$config["monitoramento"]["onde_faqs"] = "206";

// Array de configuração de banco de dados (nome da tabela, chave primaria, campos com valores fixos, campos unicos)
$config["banco"] = array(
  "tabela" => "avas",
  "primaria" => "idava",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
	"ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "nome",
	  "campo_form" => "nome",
	  "erro_idioma" => "nome_utilizado"
	)
  ),
  'campos_sql_fixo' => array(
	'modulos' => 'return serialize($_POST["modulos"]);'
  )	  
);

$config["banco_conteudos"] = array(
  "tabela" => "avas_conteudos",
  "primaria" => "idconteudo",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
	"ativo" => "'S'"
  ),
  /*
  "campos_unicos" => array(
	array(
	  "campo_banco" => "nome",
	  "campo_form" => "nome||idava",
	  "erro_idioma" => "nome_utilizado"
	)
  ),
  */
);

$config["banco_conteudos_frames"] = array(
    "tabela" => "avas_conteudos_frames",
    "primaria" => "idframe",
    "campos_insert_fixo" => array(
        "data_cad" => "now()",
        "ativo" => "'S'"
    )
);

$config["banco_rotas_aprendizagem"] = array(
  "tabela" => "avas_rotas_aprendizagem",
  "primaria" => "idrota_aprendizagem",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
	"ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "nome",
	  "campo_form" => "nome||idava",
	  "erro_idioma" => "nome_utilizado"
	)
  ),
);

$config["banco_videos"] = array(
  "tabela" => "avas_videotecas",
  "primaria" => "idvideo",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
	"ativo" => "'S'",
    "ativo_painel" => "'S'"
  )
);

$config["banco_audios"] = array("tabela" => "avas_audios",
  "primaria" => "idaudio",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
	"ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "nome",
	  "campo_form" => "nome||idava",
	  "erro_idioma" => "nome_utilizado"
	)
  ),
);

$config["banco_downloads"] = array(
  "tabela" => "avas_downloads",
  "primaria" => "iddownload",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
	"ativo" => "'S'"
  ),
  /*"campos_unicos" => array(
	array(
	  "campo_banco" => "nome",
	  "campo_form" => "nome||idava",
	  "erro_idioma" => "nome_utilizado"
	)
  ),*/
);

$config["banco_perguntas"] = array(
  "tabela" => "avas_perguntas",
  "primaria" => "idpergunta",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
	"ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "nome",
	  "campo_form" => "nome||idava",
	  "erro_idioma" => "nome_utilizado"
	)
  ),
);

$config["banco_enquetes"] = array(
  "tabela" => "avas_enquetes",
  "primaria" => "idenquete",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
	"ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "pergunta",
	  "campo_form" => "pergunta||idava",
	  "erro_idioma" => "pergunta_utilizada"
	)
  ),
);

$config["banco_links"] = array(
  "tabela" => "avas_links",
  "primaria" => "idlink",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
	"ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "nome",
	  "campo_form" => "nome||idava",
	  "erro_idioma" => "nome_utilizado"
	)
  ),
);

$config["banco_foruns"] = array(
  "tabela" => "avas_foruns",
  "primaria" => "idforum",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
	"ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "nome",
	  "campo_form" => "nome||idava",
	  "erro_idioma" => "nome_utilizado"
	)
  ),
  'campos_sql_fixo' => array(
	'permissoes' => 'return serialize($_POST["permissoes"]);'
  )
);

$config["banco_foruns_topicos"] = array(
  "tabela" => "avas_foruns_topicos",
  "primaria" => "idtopico",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
	"ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "nome",
	  "campo_form" => "nome||idforum",
	  "erro_idioma" => "nome_utilizado"
	)
  )
);

$config["banco_exercicios"] = array(
  "tabela" => "avas_exercicios",
  "primaria" => "idexercicio",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
	"ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "nome",
	  "campo_form" => "nome||idava",
	  "erro_idioma" => "nome_utilizado"
	)
  ),
);

$config["banco_avaliacoes"] = array(
  "tabela" => "avas_avaliacoes",
  "primaria" => "idavaliacao",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
	"ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "nome",
	  "campo_form" => "nome||idava",
	  "erro_idioma" => "nome_utilizado"
	)
  ),
);

$config["banco_simulados"] = array(
  "tabela" => "avas_simulados",
  "primaria" => "idsimulado",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
    "ativo" => "'S'"
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "nome",
	  "campo_form" => "nome||idava",
	  "erro_idioma" => "nome_utilizado"
	)
  ),
);

$config["banco_tira_duvidas"] = array(
  "tabela" => "avas_tira_duvidas",
  "primaria" => "idduvida",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
	"ativo" => "'S'",
  ),
  "campos_unicos" => array(
	array(
	  "campo_banco" => "nome",
	  "campo_form" => "nome||idava",
	  "erro_idioma" => "nome_utilizado"
	)
  ),
);

$config["banco_chats"] = array(
  "tabela" => "avas_chats",
  "primaria" => "idchat",
  "campos_insert_fixo" => array(
	"data_cad" => "now()",
	"ativo" => "'S'"
  ),
  /*"campos_unicos" => array(
	array(
	  "campo_banco" => "nome",
	  "campo_form" => "nome||idava",
	  "erro_idioma" => "nome_utilizado"
	)
  ),*/
);

$config["banco_objetos_divisores"] = array(
  "tabela" => "avas_objetos_divisores",
  "primaria" => "idobjeto_divisor",
  "campos_insert_fixo" => array(
    "data_cad" => "now()",
    "ativo" => "'S'"
  ),
  "campos_unicos" => array(
    array(
      "campo_banco" => "nome",
      "campo_form" => "nome||idava",
      "erro_idioma" => "nome_utilizado"
    )
  ),
);

$config["banco_linksacoes"] = array(
  "tabela" => "avas_conteudos_linksacoes",
  "primaria" => "idlinkacao",
  "campos_insert_fixo" => array(
    "data_cad" => "now()",
    "ativo" => "'S'"
  ),
  "campos_unicos" => array(
    array(
      "campo_banco" => "nome",
      "campo_form" => "nome||idava_conteudo",
      "erro_idioma" => "nome_utilizado"
    )
  ),
);

$config["banco_faqs"] = array(
                              "tabela" => "avas_faqs",
                              "primaria" => "idfaq",
                              "campos_insert_fixo" => array(
                                                            "data_cad" => "now()",
                                                            "ativo" => "'S'"
                                                            )
                            );
?>