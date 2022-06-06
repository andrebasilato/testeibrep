<?php
@session_start();
if (!empty($_SERVER['SERVER_NAME']) && strpos($_SERVER['SERVER_NAME'], 'localhost') !== false) {
    #error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT ^ E_WARNING ^ E_DEPRECATED);
    #ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

$caminhoConfig = dirname(__DIR__);
if (session_status() == PHP_SESSION_NONE && !empty($_SERVER['REQUEST_URI'])) {
    $url = addslashes(strip_tags(rawurldecode($_SERVER['REQUEST_URI'])));
    $get_array = explode('?', $url);
    $url = explode('/', $get_array[0]);
    array_shift($url);
    session_set_cookie_params(356546426, '/' . $url[0]);
    @session_start();
    header("Set-Cookie: PHPSESSID=" . session_id() . "; httpOnly;");
}

// Comprimindo o Html para o browser
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
    ob_start("ob_gzhandler");
} else {
    ob_start();
}
define('DIR_APP', dirname(__DIR__));

header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("America/Recife");

$config["oraculo_versao"] = "10.0";
$config["link_manual"] = "https://manual.alfamaoraculo.com.br";
$config['logo_pesquisa'] = 'logo_empresa.png';

$config["workflow"] = true;

$config["emailSistema"] = "oraculo@alfamaweb.com.br";
$config["emailEsqueci"] = "oraculo@alfamaweb.com.br";
$config["emailLoja"] = "oraculo@alfamaweb.com.br";
$config['integrado_com_sms'] = false;

// Variável onde poderá ser colocado scripts de análise de acesso. Será impressa no rodapé das páginas do sistema.
$config["script_rodape_geral"] = NULL;
$config["script_rodape_gestor"] = NULL;
$config["script_rodape_imobiliaria"] = NULL;
$config["script_rodape_corretor"] = NULL;
$config["script_rodape_correspondente"] = NULL;
$config["script_rodape_web"] = NULL;
$config["script_rodape_advogado"] = NULL;

$config["script_cabecalho_geral"] = NULL;
$config["script_cabecalho_gestor"] = NULL;
$config["script_cabecalho_imobiliaria"] = NULL;
$config["script_cabecalho_corretor"] = NULL;
$config["script_cabecalho_correspondente"] = NULL;
$config["script_cabecalho_web"] = NULL;
$config["script_cabecalho_advogado"] = NULL;

$config["idiomas"] = array(
    "pt_br" => "Portugues",
    //"en" => "English"
);

$config["idioma_padrao"] = "pt_br";
$config["tamanho_upload_padrao"] = 1048576;
$config["tabela_monitoramento"] = "monitora_adm";
$config["tabela_monitoramento_log"] = "monitora_adm_log";

$config["telas"] = array(
    "desktop" => "Desktop",
    "mobile" => "Mobile",
);
$config["tela_padrao"] = "desktop";
if ($_GET["tela"]) {
    $config["tela_padrao"] = $_GET["tela"];
    $_SESSION["tela"] = $_GET["tela"];
} elseif ($_SESSION["tela"]) {
    $config["tela_padrao"] = $_SESSION["tela"];
}

require_once $caminhoConfig . '/classes/Mobile-Detect/Mobile_Detect.php';
$detect = new Mobile_Detect;

// FireBase
$arrayServer = explode('.', $_SERVER['HTTP_HOST']);
$config['link_integracao_firebase_pessoal'] = $arrayServer[0] . "_" . $arrayServer[1];

$config['firebase']['apiKey'] = "AIzaSyDDosVfX9dvhRhlu2t0WmAGs34a_6sfFt8";
$config['firebase']['authDomain'] = "oraculo-chat.firebaseapp.com";
$config['firebase']['databaseURL'] = "https://oraculo-chat.firebaseio.com";
$config['firebase']['projectId'] = "oraculo-chat";
$config['firebase']['storageBucket'] = "oraculo-chat.appspot.com";
$config['firebase']['messagingSenderId'] = "865408517677";

// Geolocalização
$config["geolocalizacao"]["latitude"] = "-15.79977494904839";
$config["geolocalizacao"]["longitude"] = "-47.86414342498779";
$config["geolocalizacao"]["zoom"] = "8";

$forma_pagamento_faturas['pt_br'] = array(
    'B' => 'Boleto',
    'CC' => 'Cartão de crédito'
);

//Formas pagamento que são enviadas para o pagar.me
$forma_pagamento_pagarme = array(
    'B' => 'boleto',
    'CC' => 'credit_card'
);

//Status das transações no pagarme
$statusTransacaoPagarme['pt_br'] = array(
    'processing' => 'Processando',
    'authorized' => 'Autorizada',
    'paid' => 'Paga',
    'refunded' => 'Estornada',
    'waiting_payment' => 'Aguardando pagamento',
    'pending_refund' => 'Aguardando para ser estornada',
    'refused' => 'Não autorizada',
    'chargedback' => 'Sofreu chargeback'
);
//Sigla status das transações no pagarme
$statusTransacaoPagarmeSigla['pt_br'] = array(
    'processing' => 'PR',
    'authorized' => 'AT',
    'paid' => 'PG',
    'refunded' => 'ES',
    'waiting_payment' => 'AP',
    'pending_refund' => 'AE',
    'refused' => 'NA',
    'chargedback' => 'CH'
);

$statusTransacaoPagarmeCor = array(
    'processing' => '#3babd7',
    'authorized' => '#ff9900',
    'paid' => '#3cb880',
    'refunded' => '#8977b6',
    'waiting_payment' => '#e5cc1e',
    'pending_refund' => '#8977b6',
    'refused' => '#f04c4c',
    'chargedback' => '#b7435c'
);

$combustivel['pt_br'] = array(
    'G' => 'Gasolina',
    'D' => 'Diesel',
    'E' => 'Etanol',
    'AL' => 'Álcool'
);

$escolaridade['pt_br'] = array(
    'FI' => 'Fundamental Incompleto',
    'MI' => 'Médio Incompleto',
    'MC' => 'Médio Completo',
    'SI' => 'Superior Incompleto',
    'SC' => 'Superior Completo',
    'E' => 'Especialista',
    'M' => 'Mestrado',
    'D' => 'Doutorado',
);

$dados_CNH["pt_br"] = array(
    1 => 'Carro',
    2 => 'Moto',
    3 => 'Carro e Moto',
    4 => 'Não Possui'
);

//Situações dos materiais didáticos das matrículas
$situacaoMateriaMatricula["pt_br"] = array(
    "P" => "Pendente",
    "E" => "Entregue",
    "C" => "Cancelado"
);

//Cores das situações dos materiais didáticos das matrículas
$situacaoMateriaMatriculaCor = array(
    "P" => "#999999",
    "E" => "#006600",
    "C" => "#FF0000"
);

//Estlio para botões maiores das situações dos materiais didáticos das matrículas
$situacaoMateriaMatriculaEstilo = array(
    "P" => "background-color:#999999; background-image:-webkit-linear-gradient(top,#666666,#999999); border-color:rgba(0,0,0,.1) rgba(0,0,0,.1) rgba(0,0,0,.25); background-repeat:repeat-x; color: #FFFFFF;",
    "E" => "background-color:#51a351; background-image:-webkit-linear-gradient(top,#62c462,#51a351); border-color:rgba(0,0,0,.1) rgba(0,0,0,.1) rgba(0,0,0,.25); background-repeat:repeat-x; color: #FFFFFF;",
    "C" => "background-color:#bd362f; background-image:-webkit-linear-gradient(top,#ee5f5b,#bd362f); border-color:rgba(0,0,0,.1) rgba(0,0,0,.1) rgba(0,0,0,.25); background-repeat:repeat-x; color: #FFFFFF;"
);

//BOLSAS FINANCEIRAS PARA MATRÍCULA
$bolsaMatricula["pt_br"] = array(
    "S" => "Bolsa Total",
    "BP" => "Bolsa Parcial",
    "N" => "Não Possui Bolsa"
);

//ATIVO
$ativo["pt_br"] = array(
    "N" => "Inativo",
    "S" => "Ativo"
);
$ativo["en"] = array(
    "N" => "Inactive",
    "S" => "Active"
);
//Aula on-line
// Tipo de repeticao para aula on-line
$tipo_repeticao["pt_br"] = array(
    1 => 'Diário',
    2 => 'Semanal',
    3 => 'Mensal',
    4 => 'Anual'
);
//Ativo boolean
$aula_online["pt_br"] = array(
    0 => "Inativo",
    1 => "Ativo"
);

$cupom_sim_nao["pt_br"] = array(
    "CUPOM" => "Cupom",
    "N" => "Não",
    "S" => "Sim"
);
$sim_nao["pt_br"] = array(
    "N" => "Não",
    "S" => "Sim"
);

$sim_nao["en"] = array(
    "N" => "No",
    "S" => "Yes"
);

$periodo_fatura["pt_br"] = array(
    "SEM" => "Semanal",
    "MEN" => "Mensal"
);

$periodo_fatura["en"] = array(
    "SEM" => "Week",
    "MEN" => "Month"
);

$igual_maior["pt_br"] = array(
    "> 0" => "Não",
    "is null" => "Sim"
);

$texto_blocos_arquivo["pt_br"] = array(
    "T" => "Editor de Texto",
    "B" => "Editor de Blocos",
    "A" => "Arquivo HTML"
);

$sim_nao_cor = array(
    "S" => "#339900",
    "N" => "#ff0000"
);

$tipo_quadro_aviso["pt_br"] = array(
    "cur" => "Curso",
    "ger" => "Geral"
);

$tipo_documento["pt_br"] = array(
    "cpf" => "CPF",
    "cnpj" => "CNPJ"
);

$tipos_emails_automaticos["pt_br"] = array(
    "anive" => "Aniversário",
    "atifo" => "Atividade no fórum",
    "inadi" => "Inadimplência",
    "inati" => "Inatividade",
    "bemvi" => "Bem vindo ao Curso",
    "docup" => "Documentos pendentes",
    "achat" => "Aviso de chat",
    "propl" => "Prova presencial liberada",
    "provl" => "Prova virtual liberada",
    "dtinc" => "Data de inicio do curso",
    "ccurs" => "Conclusão do curso",
    "aforu" => "Data de abertura do fórum",
    "diame" => "Dia do mês",
    "notas" => "Nota do aluno",
    "senha" => "Esqueci minha senha",
    "ecurs" => "Data de expiração do curso",
    "chdev" => "Cheques devolvidos",
    "vparc" => "Vencimento de parcelas",
    "bvenc" => "Boleto vencido",
    'lprof' => 'Lembrete de prova final',
    'priac' => 'Primeiro acesso do aluno',
    'cadal' => 'Novo cadastro de aluno',
    "dobi" => 'Envio de documento para biometria'
);

$tipos_emails_automaticos_adm["pt_br"] = array(
    "qdmsa" => "Quantidade de dias da matrícula na situação",
);

//TIPO DE CONTAS DO SISTEMA (PAGAR E RECEBER)
$tipo_contas["pt_br"] = array(
    "despesa" => "A pagar",
    "receita" => "A receber"
);

//STATUS OFERTA
$status_oferta["pt_br"] = array(
    "NO" => "Nova oferta",
    "IL" => "Inscrição liberada",
    "CF" => "Concluída finalizada"
);

$tipo_biometria["pt_br"] = array(
    "AZURE" => "Azure",
    "DATAVALID" => "Datavalid"
);

$status_oferta["en"] = array(
    "NO" => "Nova oferta",
    "IL" => "Inscrição liberada",
    "CF" => "Concluída finalizada"
);

//ULTIMA INTERACAO
$interacao["pt_br"] = array(
    "I" => "Interna",
    "E" => "Externa"
);

//ATENDIMENTO LIDO
$leitura["pt_br"] = array(
    "S" => "Lido",
    "N" => "Não lido"
);

//SITUAÇÃO DE VISITAS DE VENDEDORES
$situacao_visita_vendedores["pt_br"] = array(
    "EMV" => "Em visita",
    "MAT" => "Matriculado",
    "SEI" => "Sem interesse"
);

$estadocivil["pt_br"] = array(
    1 => "Casado(a)", //"Casado(a) - Regime comunhão parcial de bens",
    //8 => "Casado(a) - Regime comunhão total de bens",
    //9 => "Casado(a) - Regime separação total de bens",
    2 => "Divorciado(a)",
    3 => "Separado(a)",
    4 => "Solteiro(a)",
    5 => "Viúvo(a)",
    7 => "União estável",
    6 => "Outro(s)"
);
$estadocivil["en"] = array(
    1 => "Married - Regime partial property",
    8 => "Married - Regime total communion of goods",
    9 => "Married - complete separation of property regime",
    2 => "Divorced",
    3 => "Separate",
    4 => "Single",
    5 => "Widowed",
    7 => "Union unstable",
    6 => "Other(s)"
);
$estadocivil_localizei = array(
    'a' => 6, //Amasiado
    'c' => 1, //Casado
    'd' => 2, //Divorciado
    's' => 4, //Solteiro
    'v' => 5, //Viúvo
    'i' => '' //Indeterminado
);

//TIPO DE PROFESSOR
$tipo_professor_config["pt_br"] = array(
    "P" => "Monitor online",//"Professor",
    //"TP" => "Tutor presencial",
    "TO" => "Tutor online"//"Tutor online",
);

//COR DO TIPO DE PROFESSOR NO AVA
$cor_ava_tipo_professor = array(
    "P" => "azul",
    "TP" => "verde",
    "TO" => "vermelho",
);

$sexo["pt_br"] = array(
    "F" => "Feminino",
    "M" => "Masculino"
);
$sexo["en"] = array(
    "F" => "Female",
    "M" => "Male"
);

$sexo_config["pt_br"] = array(
    "F" => "Feminino",
    "M" => "Masculino"
);
$sexo_config["en"] = array(
    "F" => "Female",
    "M" => "Male"
);

//TIPOS DE DESPESA
$tipos_despesas["pt_br"] = array(
    "S" => "Provisionadas ",
    "N" => "Não provisionadas"
);

//TIPO DE CONTA
$tipo_conta["pt_br"] = array(
    "C" => "Corrente",
    "P" => "Poupança"
);
$tipo_conta["en"] = array(
    "C" => "Checking account",
    "P" => "Savings account"
);

//TIPO DE META
$tipo_meta["pt_br"] = array(
    "QTD" => "Quantidade",
    "VAL" => "Valor"
);
$tipo_meta["en"] = array(
    "QTD" => "Quantidade",
    "VAL" => "Valor"
);

//SITUAÇÃO DA PESQUISA
$situacao_carteirinha_aluno["pt_br"] = array(
    1 => "Em confecção",
    2 => "Na sindicato",
    3 => "Entregue ao aluno",
    4 => "Cancelada"
);

//TIPO PESQUISA
$tipo_pesquisa["pt_br"] = array(
    "UA" => "Usuário Adm.",
    "MA" => "Matrícula",
    "PR" => "Professor",
    "PE" => "Pessoa",
);
$tipo_pesquisa["en"] = array(
    "UA" => "Usuário Adm.",
    "MA" => "Matrícula",
    "PR" => "Professor",
    "PE" => "Pessoa",
);

$tipo_mural["pt_br"] = array(
    "UA" => "Usuário Adm.",
    "PO" => "Professor",
    "VE" => "Atendente",
    "PE" => "Pessoa",
    "AT" => "Atendimento",
    "MA" => "Matrícula",
    "IN" => "Sindicato",
    "PL" => "Escola",
);

$tipo_mailing["pt_br"] = array(
    "UA" => "Usuário Adm.",
    "MA" => "Matrícula",
    "PR" => "Professor",
    "PE" => "Pessoa",
    "VE" => "Atendente",
    "VV" => "Visita",
    "ES" => "CFC",
    "SI" => "Sindicato",
);

$status_solicitacao_prova["pt_br"] = array(
    "E" => "Em Espera",
    "A" => "Agendada",
    "C" => "Cancelada",
);

$cor_status_solicitacao_prova = array(
    "E" => "#FF6600",
    "A" => "#339900",
    "C" => "#ff0000",
);

$status_solicitacao_declaracao["pt_br"] = array(
    "E" => "Em Espera",
    "D" => "Deferido",
    "I" => "Indeferido",
);

$cor_status_solicitacao_declaracao = array(
    "E" => "#FF6600",
    "D" => "#339900",
    "I" => "#ff0000",
);

//SITUAÇÃO DA PESQUISA
$situacao_pesquisa["pt_br"] = array(
    0 => "Aguardando Envio",
    1 => "Enviando",
    2 => "Enviada",
    3 => "Pausada"
);

$situacao_pesquisa["en"] = array(
    0 => "Aguardando Envio",
    1 => "Enviando",
    2 => "Enviada",
    3 => "Pausada"
);

$situacao_pesquisa_cor = array(
    0 => "#666666",
    1 => "#FF6600",
    2 => "#339900",
    3 => "#FF0000"
);

//SITUAÇÃO DO MAILING
$situacao_mailing["pt_br"] = array(
    0 => "Aguardando Envio",
    1 => "Enviando",
    2 => "Enviada",
    3 => "Pausada"
);
$situacao_mailing["en"] = array(
    0 => "Aguardando Envio",
    1 => "Enviando",
    2 => "Enviada",
    3 => "Pausada"
);

$situacao_mailing_cor = array(
    0 => "#666666",
    1 => "#FF6600",
    2 => "#339900",
    3 => "#FF0000"
);

//DIAS SEMANA
$dia_semana["pt_br"] = array(
    1 => "Segunda-feira",
    2 => "Terça-feira",
    3 => "Quarta-feira",
    4 => "Quinta-feira",
    5 => "Sexta-feira",
    6 => "Sábado",
    7 => "Domingo"
);
$dia_semana["en"] = array(
    1 => "Monday",
    2 => "Tuesday",
    3 => "Wednesday",
    4 => "Thursday",
    5 => "Friday",
    6 => "Saturday",
    7 => "Sunday"
);

//MESES
$meses_idioma["pt_br"] = array(
    "01" => "Janeiro",
    "02" => "Fevereiro",
    "03" => "Março",
    "04" => "Abril",
    "05" => "Maio",
    "06" => "Junho",
    "07" => "Julho",
    "08" => "Agosto",
    "09" => "Setembro",
    "10" => "Outubro",
    "11" => "Novembro",
    "12" => "Dezembro"
);
$meses_idioma["en"] = array(
    "01" => "January",
    "02" => "February",
    "03" => "March",
    "04" => "April",
    "05" => "May",
    "06" => "June",
    "07" => "July",
    "08" => "August",
    "09" => "September",
    "10" => "October",
    "11" => "November",
    "12" => "December"
);
$meses_min_idioma["pt_br"] = array(
    "01" => "Jan",
    "02" => "Fev",
    "03" => "Mar",
    "04" => "Abr",
    "05" => "Mai",
    "06" => "Jun",
    "07" => "Jul",
    "08" => "Ago",
    "09" => "Set",
    "10" => "Out",
    "11" => "Nov",
    "12" => "Dez"
);

$dia_semana_min["pt_br"] = array(
    "1" => "Seg",
    "2" => "Ter",
    "3" => "Qua",
    "4" => "Qui",
    "5" => "Sex",
    "6" => "Sab",
    "7" => "Dom"
);

//PRIORIDADE DOS ATENDIMENTOS
$prioridades["pt_br"] = array(
    "A" => "Alta",
    "N" => "Normal",
    "B" => "Baixa"
);
$prioridades["en"] = array(
    "A" => "High",
    "N" => "Normal",
    "B" => "Low"
);

$dia_mes["pt_br"] = array(
    "01" => "01",
    "02" => "02",
    "03" => "03",
    "04" => "04",
    "05" => "05",
    "06" => "06",
    "07" => "07",
    "08" => "08",
    "09" => "09",
    "10" => "10",
    "11" => "11",
    "12" => "12",
    "13" => "13",
    "14" => "14",
    "15" => "15",
    "16" => "16",
    "17" => "17",
    "18" => "18",
    "19" => "19",
    "20" => "20",
    "21" => "21",
    "22" => "22",
    "23" => "23",
    "24" => "24",
    "25" => "25",
    "26" => "26",
    "27" => "27",
    "28" => "28",
    "29" => "29",
    "30" => "30",
    "31" => "31"
);
//VERIFICAR SE NO CORE PEGA O IDIOMA AUTOMATICAMENTE, PARA REMOVER UM DOS ARRAYS E DEIXAR SEM IDIOMA
$dia_mes["en"] = array(
    "01" => "01",
    "02" => "02",
    "03" => "03",
    "04" => "04",
    "05" => "05",
    "06" => "06",
    "07" => "07",
    "08" => "08",
    "09" => "09",
    "10" => "10",
    "11" => "11",
    "12" => "12",
    "13" => "13",
    "14" => "14",
    "15" => "15",
    "16" => "16",
    "17" => "17",
    "18" => "18",
    "19" => "19",
    "20" => "20",
    "21" => "21",
    "22" => "22",
    "23" => "23",
    "24" => "24",
    "25" => "25",
    "26" => "26",
    "27" => "27",
    "28" => "28",
    "29" => "29",
    "30" => "30",
    "31" => "31"
);

$tipo_pergunta["pt_br"] = array(
    "O" => "Objetiva",
    "S" => "Subjetiva"
);

$sentido_pergunta["pt_br"] = array(
    "H" => "Horizontal",
    "V" => "Vertical"
);

$dificuldade_pergunta["pt_br"] = array(
    "F" => "Fácil",
    "M" => "Média",
    "D" => "Difícil",
);

$tipo_disciplina["pt_br"] = array(
    "EAD" => "EAD",
    "PRE" => "Presencial"
);


$tipo_curso["pt_br"] = array(
    "EAD" => "EAD",
    "PRE" => "Presencial",
    "SEM" => "Semipresencial",
);

$tipo_oferta["pt_br"] = array(
    "EAD" => "EAD",
    "PRE" => "Presencial"
);

$forma_avaliacao["pt_br"] = array(
    "VIR" => "Virtual",
    "PRE" => "Presencial"
);

$tipo_video["pt_br"] = array(
    "videoteca" => "Videoteca",
    "youtube" => "You Tube"
);

$tipo_objetos_rota_apresizagem["pt_br"] = array(
    "audio" => "Áudio",
    "conteudo" => "Conteúdo",
    "objeto_divisor" => "Objeto divisor",
    "download" => "Download",
    "link" => "Link",
    "pergunta" => "Pergunta",
    "video" => "Vídeo",
    "simulado" => "Simulado",
    "enquete" => "Enquete",
    "exercicio" => "Exercício",
    'reconhecimento' => 'Reconhecimento',
    'aulaonline' => 'Aula on-line'
);

$tipo_avaliador["pt_br"] = array(
    "sistema" => "Sistema",
    "professor" => "Professor"
);

$tipo_avaliacao["pt_br"] = array(
    "1" => "1ª prova normal",
    "2" => "2ª prova normal",
    "3" => "1ª prova virtual",
    "4" => "2ª prova virtual",
);

$tipo_avaliacao_virtual["pt_br"] = array(
    "3" => "1ª prova virtual",
    "4" => "2ª prova virtual",
);

$tipo_avaliacao_presencial["pt_br"] = array(
    "1" => "1ª prova normal",
    "2" => "2ª prova normal",
);

$modelo_prova_presencial["pt_br"] = array(
    "1" => "Modelo 1",
    "2" => "Modelo 2",
    "3" => "Modelo 3",
    "4" => "Modelo 4",
    "5" => "Modelo 5",
    "6" => "Modelo 6",
    "7" => "Modelo 7",
    "8" => "Modelo 8",
    "9" => "Modelo 9",
    "10" => "Modelo 10",
);

$campo_peso_avaliacao = array(
    "1" => "peso_presencial_1",
    "2" => "peso_presencial_2",
    "3" => "peso_virtual_1",
    "4" => "peso_virtual_2",
);

$tipo_financeiro_matricula["pt_br"] = array(
    "1" => "Bolsa",
    "2" => "Mensalidade",
    "3" => "Taxa",
    "4" => "Outros"
);

$forma_pagamento_conta["pt_br"] = array(
    "1" => "Boleto",
    "2" => "Cartão de crédito",
    "3" => "Cartão de débito",
    "4" => "Cheque",
    "5" => "Dinheiro",
    "6" => "Depósito/Transferência Bancária",
    //"7" => "Transferência bancária",
    "8" => "Carnê",
    "10" => "PagSeguro",
    "11" => "FastConnect"
);

$cartao_pagamento_listagem["pt_br"] = array(
    "2" => "Cartão de crédito",
    "3" => "Cartão de débito"
);

$situacao_documento["pt_br"] = array(
    "aguardando" => "Aguardando conferência",
    "reprovado" => "Reprovado",
    "aprovado" => "Aprovado"
);

$situacao_duvida_ava["pt_br"] = array(
    "P" => "Pendente",
    "R" => "Respondida"
);

$situacao_documento_cores = array(
    "aguardando" => "#999999",
    "reprovado" => "#FF0000",
    "aprovado" => "#006600"
);

// Tipo de filtro - será usado nos relatórios para salvar os filtros gerados
$tipo_data_filtro['pt_br'] = array(
    'HOJ' => "Hoje",
    'ONT' => "Ontem",
    'SET' => "Última semana",
    'QUI' => "Última quinzena",
    'MAT' => "Mês atual",
    'MPR' => "Próximo mês",
    'MAN' => "Mês anterior",
    'PER' => "Periodo definido pelo usuário",
);

$icones_rota = array(
    "audio" => "p",
    "conteudo" => "g",
    "download" => "j",
    "link" => "k",
    "pergunta" => "t",
    "enquete" => "t",
    "video" => "o",
    "simulado" => "k",
    "exercicio" => "t",
);

$pastas_imagens_exibicao_ava = array(
    "audio" => "avas_audios_imagem_exibicao",
    "conteudo" => "avas_conteudos_imagem_exibicao",
    "download" => "avas_downloads_imagem_exibicao",
    "link" => "avas_links_imagem_exibicao",
    "pergunta" => "avas_perguntas_imagem_exibicao",
    "video" => "avas_videos_imagem_exibicao",
    "simulado" => "avas_simulados_imagem_exibicao",
);

$pastas_aquivos_ava = array(
    "audio" => "avas_audios_arquivo",
    "download" => "avas_downloads_arquivo",
);

$pastas_avatar = array(
    "aluno" => "pessoas_avatar",
    "gestor" => "usuariosadm_avatar",
    "professor" => "professores_avatar",
);

$contas_tipo_documento["pt_br"] = array(
    "NF" => "Nota fiscal",
    "BO" => "Boleto"
);

$modulos_ava["pt_br"] = array(
    'favoritos' => 'Favoritos',
    'tiraduvidas' => 'Tira-Dúvidas',
    'chats' => 'Chats',
    'foruns' => 'Fóruns',
    'simulado' => 'Simulado',
    'biblioteca' => 'Biblioteca',
    'anotacoes' => 'Anotações',
    'professores' => 'Monitores e Tutores',
    'colegas' => 'Colegas de Curso',
    'avaliacoes' => 'Avaliações',
    'faq' => 'Faq',
    //'mensagem_instantanea' => 'Mensagens Instantânea',
);

$modulos_sms["pt_br"] = array(
    "M" => "Mailing",
    "EA" => "Emails Automáticos",
    "EADM" => "Emails Automáticos ADM"
);

$bandeiras_cartoes['pt_br'] = array(
    'V' => 'Visa',
    'M' => 'MasterCard',
    'AE' => 'American Express',
    'E' => 'ELO',
    'DC' => 'Dinners Club',
    'D' => 'Discover',
    'J' => 'JCB',
    'A' => 'Aura'
);

$bandeiras_debito['pt_br'] = array(
    'MM' => 'MasterCard Maestro',
    'VE' => 'Visa Eletron'
);

$bandeiras_cartoes_classe_imagem['pt_br'] = array(
    'V' => 'visa',
    'M' => 'master',
    'AE' => 'amex',
    'E' => 'elo',
    'DC' => 'dinners',
    'D' => 'discover',
    'J' => 'jcb',
    'A' => 'aura',
    'MM' => 'maestro',
    'VE' => 'visaelectron'
);

$formas_pagamento_cartoes['pt_br'] = array(
    'D' => 'Débito',
    'C' => 'Crédito'
);

$tipo_parcelamento_cartoes['pt_br'] = array(
    'loja' => 'Loja',
    'admin' => 'Administradora'
);

$tipo_cupons['pt_br'] = array(
    'G' => 'Geral',
    'C' => 'CPF'
);

$tipo_desconto_cupons['pt_br'] = array(
    'P' => 'Porcentagem',
    'V' => 'Valor'
);

//Status das transações no fastConnect
// $statusTransacaoFastConnect['pt_br'] = array(

// );

//Situações das transações no fastConnect
$situacoesTransacaoFastConnect['pt_br'] = array(
    1 => 'ATIVO',
    2 => 'Aberto',
    3 => 'Pago',
    4 => 'Pendente',
    5 => 'Cancelado',
    6 => 'Cancelado por estorno(Cartão)',
    7 => 'Cancelado por estorno(Banco)'
);

$situacoesTransacaoFastConnectSigla['pt_br'] = array(
    1 => 'AT',
    2 => 'AB',
    3 => 'PG',
    4 => 'PE',
    5 => 'CA',
    6 => 'CE',
    7 => 'CB'
);

$statusTransacaoFastConnectCor = array(
    1 => '#e5cc1e',
    2 => '#ff9900',
    3 => '#3cb880',
    4 => '#3babd7',
    5 => '#f04c4c',
    6 => '#8977b6',
    7 => '#8977b6'
);


//Status das transações no pagSeguro
$statusTransacaoPagSeguro['pt_br'] = array(
    1 => 'Aguardando pagamento',
    2 => 'Em análise',
    3 => 'Paga',
    4 => 'Disponível',
    5 => 'Em disputa',
    6 => 'Devolvida',
    7 => 'Cancelada',
    8 => 'Chargeback debitado',
    9 => 'Retenção temporária'
);

//Sigla status das transações no pagSeguro
$statusTransacaoPagSeguroSigla['pt_br'] = array(
    1 => 'AP',
    2 => 'EA',
    3 => 'PG',
    4 => 'DP',
    5 => 'ED',
    6 => 'DV',
    7 => 'CL',
    8 => 'CD',
    9 => 'RT'
);

$statusTransacaoPagSeguroCor = array(
    1 => '#e5cc1e',
    2 => '#ff9900',
    3 => '#3cb880',
    4 => '#3babd7',
    5 => '#8977b6',
    6 => '#8977b6',
    7 => '#f04c4c',
    8 => '#000000',
    9 => '#b7435c'
);

$tipoTransacaoPagSeguro['pt_br'] = array(
    1 => 'Pagamento',
    11 => 'Assinatura'
);

$origemCancelamentoPagSeguro['pt_br'] = array(
    'INTERNAL' => 'PagSeguro',
    'EXTERNAL' => 'Instituições Financeiras'
);

$tipoMeioPagamentoPagSeguro['pt_br'] = array(
    1 => 'Cartão de crédito',
    2 => 'Boleto',
    3 => 'Débito online',
    4 => 'Saldo PagSeguro',
    5 => 'Oi Paggo',
    7 => 'Depósito em conta'
);

$codigoIdentificadorMeioPagamentoPagSeguro['pt_br'] = array(
    101 => 'Cartão de crédito Visa',
    102 => 'Cartão de crédito MasterCard',
    103 => 'Cartão de crédito American Express',
    104 => 'Cartão de crédito Diners',
    105 => 'Cartão de crédito Hipercard',
    106 => 'Cartão de crédito Aura',
    107 => 'Cartão de crédito Elo',
    108 => 'Cartão de crédito PLENOCard. *',
    109 => 'Cartão de crédito PersonalCard',
    110 => 'Cartão de crédito JCB',
    111 => 'Cartão de crédito Discover',
    112 => 'Cartão de crédito BrasilCard',
    113 => 'Cartão de crédito FORTBRASIL',
    114 => 'Cartão de crédito CARDBAN. *',
    115 => 'Cartão de crédito VALECARD',
    116 => 'Cartão de crédito Cabal',
    117 => 'Cartão de crédito Mais!',
    118 => 'Cartão de crédito Avista',
    119 => 'Cartão de crédito GRANDCARD',
    120 => 'Cartão de crédito Sorocred',
    201 => 'Boleto Bradesco',
    202 => 'Boleto Santander',
    301 => 'Débito online Bradesco',
    302 => 'Débito online Itaú',
    303 => 'Débito online Unibanco',
    304 => 'Débito online Banco do Brasil',
    305 => 'Débito online Banco Real',
    306 => 'Débito online Banrisul',
    307 => 'Débito online HSBC',
    401 => 'Saldo PagSeguro',
    501 => 'Oi Paggo',
    701 => 'Depósito em conta - Banco do Brasil',
    702 => 'Depósito em conta - HSBC',
);

$tipoFretePagSeguro['pt_br'] = array(
    1 => 'Encomenda normal (PAC)',
    2 => 'SEDEX',
    3 => 'Tipo de frete não especificado'
);

$forma_pagamento_loja_select['pt_br'] = array(
    'Boleto' => array(
        'B' => 'Boleto'
    ),
    'Cartão crédito' => array(
        'V' => 'Visa',
        'M' => 'MasterCard',
        'AE' => 'American Express',
        'E' => 'ELO',
        'DC' => 'Dinners Club',
        'D' => 'Discover',
        'J' => 'JCB',
        'A' => 'Aura'
    ),
    'Cartão débito' => array(
        'MM' => 'MasterCard Maestro',
        'VE' => 'Visa Eletron'
    ),
    'Cheque' => array(
        'C' => 'Cheque'
    ),
    'Dinheiro' => array(
        'DI' => 'Dinheiro'
    )
);

$forma_pagamento_loja['pt_br'] = array(
    'B' => 'Boleto',
    'V' => 'Visa',
    'M' => 'MasterCard',
    'AE' => 'American Express',
    'E' => 'ELO',
    'DC' => 'Dinners Club',
    'D' => 'Discover',
    'J' => 'JCB',
    'A' => 'Aura',
    'MM' => 'MasterCard Maestro',
    'VE' => 'Visa Eletron',
    'C' => 'Cheque',
    'DI' => 'Dinheiro'
);

$tipo_pagamento_loja = array(
    'B' => 1,
    'V' => 2,
    'M' => 2,
    'AE' => 2,
    'E' => 2,
    'DC' => 2,
    'D' => 2,
    'J' => 2,
    'A' => 2,
    'MM' => 3,
    'VE' => 3,
    'C' => 4,
    'DI' => 5,
    'PS' => 10,//PagSeguro
    'FC' => 11//FastConnect
);

$bandeira_cartoes_loja = array(
    'B' => 'NULL',
    'V' => 8,
    'M' => 7,
    'AE' => 1,
    'E' => 11,
    'DC' => 5,
    'D' => 10001,
    'J' => 10002,
    'A' => 10003,
    'MM' => 7,
    'VE' => 8,
    'C' => 'NULL',
    'DI' => 'NULL'
);

$situacao_pedido_loja['pt_br'] = array(
    'A' => 'Aguardando pagamento',
    'P' => 'Pago',
    'C' => 'Cancelado'
);

$periodo['pt_br'] = array(
    'DIA' => 'Diário',
    'SEM' => 'Semanal',
    'MEN' => 'Mensal',
    'ANU' => 'Anual'
);

$area_atuacao['pt_br'] = array(
    2 => 'Alto padrão',
    9 => 'Área rural',
    5 => 'Grandes áreas',
    6 => 'Imóveis de terceiros',
    10 => 'Imóveis internacionais',
    1 => 'Lançamentos',
    7 => 'Locação',
    8 => 'Locação temporada',
    4 => 'Médio padrão',
    3 => 'Padrão popular',
);

$oraculo_modulos["pt_br"] = array(
    'gestor' => 'Gestor',
    'vendedor' => 'Atendente',
);

$linksacoes['pt_br'] = array(
    'L' => 'Link',
    'A' => 'Ação'
);

$numeroDetranPorestado['pt_br'] = array(
    'sergipe' => 26,
    'rio_grande_do_sul' => 21,
    'rio_de_janeiro' => 19,
    'alagoas' => 2
);

//Populando o array de meses (01 à 12)
for ($i = 1; $i <= 12; $i++) {
    $mesValidade['pt_br'][str_pad($i, 2, "0", STR_PAD_LEFT)] = str_pad($i, 2, "0", STR_PAD_LEFT);
}

//Populando o array de anos (Ano atual até próximos 9 anos. Ex: 2014 até 2023)
for ($i = 0; $i < 10; $i++) {
    $anoValidade['pt_br'][date('Y') + $i] = date('Y') + $i;
}

// ORIO
require_once 'config.interfaces.php';
$tipo_transacao["pt_br"] = array(
    "E" => "Entrada",
    "S" => "Saída",
);

$tipo_transacao_cores = array(
    "E" => "20b78a",
    "S" => "28b1e0",
);

$situacao_transacao["pt_br"] = array(
    1 => "Pendente",
    2 => "Concluída",
    3 => "Erro",
    4 => "Reprocessada",
    5 => "Erro de negócio"
);

$situacao_transacao_cores = array(
    1 => "FF9900",
    2 => "0c9770",
    3 => "dc314e",
    4 => '2f43cc',
    5 => 'dc314e'
);

$paineis_banner["pt_br"] = array(
    "aluno" => "Aluno",
    "cfc" => "CFC",
    "atendente" => "Atendente"
);


require_once(__DIR__."/sqlinjectionprotection.php");
require(__DIR__."/modulos.php");
$config["https"] = true;
$config['filtro_matricula_boleto_semana'] = false;
require_once $caminhoConfig . '/especifico/inc/config.especifico.php';
require_once $caminhoConfig . '/especifico/inc/config.banco.php';
require_once(__DIR__."/https.php");
require_once(__DIR__."/workflow.matriculas.php");
require_once(__DIR__."/workflow.contas.php");
require_once(__DIR__."/workflow.atendimentos.php");
require_once(__DIR__."/workflow.ofertas.php");
require_once(__DIR__."/workflow.ofertascursos.php");
