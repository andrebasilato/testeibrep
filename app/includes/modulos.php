<?php
$caminhoModulo = dirname(__DIR__);
require $caminhoModulo . '/especifico/inc/config.modulos.php';
$config['modulos']['url'] = array();
$config['modulos']['url']['configuracoes'] = array();
$config['modulos']['url']['cadastros'] = array();
$config['modulos']['url']['academico'] = array();
$config['modulos']['url']['financeiro'] = array();
$config['modulos']['url']['relacionamento'] = array();

// CONFIGURACOES
$config['modulos']['url']['configuracoes']['index'] = true;
$config['modulos']['url']['configuracoes']['meusdados'] = true;
$config['modulos']['url']['configuracoes']['valores_cursos'] = true;
$config['modulos']['url']['configuracoes']['usuariosadm'] = true;
$config['modulos']['url']['configuracoes']['perfisusuarioadm'] = true;
$config['modulos']['url']['configuracoes']['horariodeacesso'] = true;
$config['modulos']['url']['configuracoes']['feriados'] = true;
$config['modulos']['url']['configuracoes']['religioes'] = true;
$config['modulos']['url']['configuracoes']['racas'] = true;
$config['modulos']['url']['configuracoes']['chequesalineas'] = true;
$config['modulos']['url']['configuracoes']['declaracoes'] = true;
$config['modulos']['url']['configuracoes']['tiposcontratos'] = true;
$config['modulos']['url']['configuracoes']['tiposcontatos'] = true;
$config['modulos']['url']['configuracoes']['monitoramentos'] = true;
$config['modulos']['url']['configuracoes']['gruposusuariosadm'] = true;
$config['modulos']['url']['configuracoes']['tiposdocumentos'] = true;
$config['modulos']['url']['configuracoes']['tiposdeclaracoes'] = true;
$config['modulos']['url']['configuracoes']['logemails'] = true;
$config['modulos']['url']['configuracoes']['excecoes'] = true;
$config['modulos']['url']['configuracoes']['logsms'] = true;
$config['modulos']['url']['configuracoes']['logemailsarquivado'] = true;
$config['modulos']['url']['configuracoes']['logsmsarquivado'] = true;
$config['modulos']['url']['configuracoes']['monitoramentosarquivado'] = true;
$config["modulos"]["url"]["configuracoes"]["workflows"] = true;
$config["modulos"]["url"]["configuracoes"]["especifico"] = true;
$config["modulos"]["url"]["configuracoes"]["detrans"] = true;
$config['modulos']['workflow']['atendimentos'] = true;
$config['modulos']['workflow']['contas'] = true;
$config['modulos']['workflow']['matriculas'] = true;
$config['modulos']['workflow']['ofertas'] = true;


// CADASTROS
$config['modulos']['url']['cadastros']['index'] = true;
$config['modulos']['url']['cadastros']['mantenedoras'] = true;
$config['modulos']['url']['cadastros']['sindicatos'] = true;
$config['modulos']['url']['cadastros']['cfc'] = true;
$config['modulos']['url']['cadastros']['atendentes'] = true;
$config['modulos']['url']['cadastros']['gruposatendentes'] = true;
$config['modulos']['url']['cadastros']['pessoas'] = true;
$config['modulos']['url']['cadastros']['professores'] = true;
$config['modulos']['url']['cadastros']['unidades'] = true;

// ACADÊMICO
$config['modulos']['url']['academico']['areas'] = true;
$config['modulos']['url']['academico']['aulaonline'] = true;
$config['modulos']['url']['academico']['cursos'] = true;
$config['modulos']['url']['academico']['disciplinas'] = true;
$config['modulos']['url']['academico']['bancodeperguntas'] = true;
$config['modulos']['url']['academico']['avas'] = true;
$config['modulos']['url']['academico']['ofertas'] = true;
//$config['modulos']['url']['academico']['diplomas'] = true;
$config['modulos']['url']['academico']['turmas'] = true;
$config['modulos']['url']['academico']['curriculos'] = true;
$config['modulos']['url']['academico']['matriculas'] = true;
$config['modulos']['url']['academico']['motivosinatividade'] = true;
$config['modulos']['url']['academico']['formulasnotas'] = true;
$config['modulos']['url']['academico']['videoteca'] = true;
$config['modulos']['url']['academico']['declaracoes'] = true;
$config['modulos']['url']['academico']['avaliacoes'] = true;
$config['modulos']['url']['academico']['bannersavaaluno'] = true;
$config['modulos']['url']['academico']['solicitacoesdeclaracoes'] = true;
$config['modulos']['url']['academico']['certificados'] = true;
$config['modulos']['url']['academico']['motivoscancelamentosolicitacaoprova'] = true;
$config['modulos']['url']['academico']['folhasregistrosdiplomas'] = true;
$config['modulos']['url']['academico']['relacionamentopedagogico'] = false;
$config['modulos']['url']['academico']['documentosmatriculas'] = true;
$config['modulos']['url']['academico']['contratosmatriculas'] = true;
$config['modulos']['url']['academico']['modelosprovas'] = true;
$config['modulos']['url']['academico']['calendario'] = true;
$config['modulos']['url']['academico']['matriculasaprovacao'] = true;
$config['modulos']['url']['academico']['tiposnotas'] = true;
$config['modulos']['url']['academico']['categoriastiraduvidas'] = true;
$config['modulos']['url']['academico']['historicos'] = true;

// COMERCIAL
$config['modulos']['url']['comercial']['index'] = true;
$config['modulos']['url']['comercial']['visitas'] = true;
$config['modulos']['url']['comercial']['metas'] = true;
$config['modulos']['url']['comercial']['cupons'] = false; // Nao estava sendo utilizado
$config['modulos']['url']['comercial']['midiasvisitas'] = true;
$config['modulos']['url']['comercial']['locaisvisitas'] = true;
$config['modulos']['url']['comercial']['motivosvisitas'] = true;
$config['modulos']['url']['comercial']['motivoscancelamento'] = true;
$config['modulos']['url']['comercial']['solicitantesbolsas'] = true;
$config['modulos']['url']['comercial']['mapadealcance'] = true;
$config['modulos']['url']['comercial']['empresas'] = true;
$config['modulos']['url']['comercial']['relacionamentocomercial'] = true;
$config['modulos']['url']['comercial']['metacursos'] = true;

// FINANCEIRO
$config['modulos']['url']['financeiro']['index'] = true;
$config['modulos']['url']['financeiro']['fornecedores'] = true;
$config['modulos']['url']['financeiro']['produtos'] = true;
$config['modulos']['url']['financeiro']['centrosdecustos'] = true;
$config['modulos']['url']['financeiro']['bancos'] = true;
$config['modulos']['url']['financeiro']['categorias'] = true;
$config['modulos']['url']['financeiro']['contascorrentes'] = true;
$config['modulos']['url']['financeiro']['cheques'] = true;
$config['modulos']['url']['financeiro']['contas'] = true;
$config['modulos']['url']['financeiro']['bandeirascartoes'] = true;
$config['modulos']['url']['financeiro']['cobrancas'] = true;
$config['modulos']['url']['financeiro']['regrascomissoes'] = true;
$config['modulos']['url']['financeiro']['competenciascomissoes'] = true;
$config['modulos']['url']['financeiro']['fechamento_caixa'] = true;
$config['modulos']['url']['financeiro']['extrato_caixa'] = true;
$config['modulos']['url']['financeiro']['pagamentos_compartilhados'] = true;
$config['modulos']['url']['financeiro']['eventos_financeiros'] = true;
$config['modulos']['url']['financeiro']['motivoscancelamentocontas'] = true;
$config['modulos']['url']['financeiro']['orcamentos_planejados'] = true;
$config['modulos']['url']['financeiro']['previsoes_gastos'] = true;
$config['modulos']['url']['financeiro']['cartoes'] = true;
$config['modulos']['url']['financeiro']['pagamentos_cartao'] = true;
$config['modulos']['url']['financeiro']['cupons'] = true;
$config['modulos']['url']['financeiro']['retornos'] = true;
$config['modulos']['url']['financeiro']['boletos_gerados'] = true;
$config['modulos']['url']['financeiro']['faturas'] = true;

// JURIDICO
$config['modulos']['url']['juridico']['index'] = true;
$config['modulos']['url']['juridico']['contratos'] = true;
$config['modulos']['url']['juridico']['gruposcontratos'] = true;

// RELACIONAMENTO
$config['modulos']['url']['relacionamento']['index'] = true;
$config['modulos']['url']['relacionamento']['assuntosatendimentos'] = true;
$config['modulos']['url']['relacionamento']['checklist'] = true;
$config['modulos']['url']['relacionamento']['respostasatendimentos'] = true;
$config['modulos']['url']['relacionamento']['atendimentos'] = true;
$config['modulos']['url']['relacionamento']['muraisadm'] = true;//Daiane
$config['modulos']['url']['relacionamento']['mural'] = true;//Daiane
$config['modulos']['url']['relacionamento']['etiquetas'] = true;
$config['modulos']['url']['relacionamento']['pesquisas'] = true;
$config['modulos']['url']['relacionamento']['perguntaspesquisas'] = true;
$config['modulos']['url']['relacionamento']['mailing'] = true;
$config['modulos']['url']['relacionamento']['emailsautomaticos'] = true;
$config['modulos']['url']['relacionamento']['emailsautomaticosadm'] = true;
$config['modulos']['url']['relacionamento']['quadrosavisos'] = true;


// RELATORIOS
$config['modulos']['url']['relatorios']['index'] = true;
$config['modulos']['url']['relatorios']['atendimentos_relatorio'] = true;
$config['modulos']['url']['relatorios']['biblioteca_virtual'] = true;
$config['modulos']['url']['relatorios']['contas_relatorio'] = true;
$config['modulos']['url']['relatorios']['extrato'] = true;
$config['modulos']['url']['relatorios']['extrato_caixa_relatorio'] = true;
$config['modulos']['url']['relatorios']['fechamento_caixa_relatorio'] = true;
$config['modulos']['url']['relatorios']['fornecedores_relatorio'] = true;
$config['modulos']['url']['relatorios']['matriculas_relatorio'] = true;
$config['modulos']['url']['relatorios']['matriculas_relatorio_notas'] = true;
$config['modulos']['url']['relatorios']['matriculas_relatorio_notas_simplificado'] = true;
$config['modulos']['url']['relatorios']['metas_relatorio'] = true;
$config['modulos']['url']['relatorios']['notas_por_turma'] = true;
$config['modulos']['url']['relatorios']['pessoas_relatorio'] = true;
$config['modulos']['url']['relatorios']['relatorio_chat'] = true;
$config['modulos']['url']['relatorios']['vendas_detalhado'] = true;
$config['modulos']['url']['relatorios']['vendas_faturamento'] = true;
$config['modulos']['url']['relatorios']['vendas_faturamento_novo'] = true;
$config['modulos']['url']['relatorios']['atendentes_relatorio'] = true;
$config['modulos']['url']['relatorios']['visitas_atendentes_por_dia'] = true;
$config['modulos']['url']['relatorios']['carteirinha_estudante'] = true;
$config['modulos']['url']['relatorios']['forum_relatorio'] = true;
$config['modulos']['url']['relatorios']['monitoramento_aluno'] = true;
$config['modulos']['url']['relatorios']['matriculas_relatorio_pedagogico'] = true;
$config['modulos']['url']['relatorios']['projecao_financeira'] = true;
$config['modulos']['url']['relatorios']['orcado_previsto'] = true;
$config['modulos']['url']['relatorios']['cobrancas_relatorio'] = true;
$config['modulos']['url']['relatorios']['gerencial_motivos_cancelamentos'] = true;
$config['modulos']['url']['relatorios']['relacionamento_comercial_relatorio'] = true;
$config['modulos']['url']['relatorios']['situacao_matricula_por_sindicato'] = true;
$config['modulos']['url']['relatorios']['documentos_protocolo'] = true;
$config['modulos']['url']['relatorios']['situacao_aluno'] = true;
$config['modulos']['url']['relatorios']['situacao_contratos'] = true;
$config['modulos']['url']['relatorios']['usuarios_adm_relatorio'] = true;
$config['modulos']['url']['relatorios']['relatorio_final_seed'] = true;
$config['modulos']['url']['relatorios']['cfc_relatorio'] = true;
$config['modulos']['url']['relatorios']['transacoes_faturas_pagarme'] = true;
$config['modulos']['url']['relatorios']['recebimento_pf'] = true;
$config['modulos']['url']['relatorios']['recebimento_pj'] = true;
$config['modulos']['url']['relatorios']['matriculas_cfc'] = true;
$config['modulos']['url']['relatorios']['pagamentos'] = true;
$config['modulos']['url']['relatorios']['desempenho_individual'] = true;
$config['modulos']['url']['relatorios']['detran_logs'] = true;