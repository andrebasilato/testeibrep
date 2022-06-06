<?php

include("config.php");
include("config.formulario.php");
include("RelatorioDesempenhoIndividual.php");
include("../classes/relatorios.class.php");

$relatoriosObj = new Relatorios();
$relatoriosObj->Set("idusuario", $usuario["idusuario"]);

$relatorioDesempenhoObj = new RelatorioDesempenhoIndividual();
$relatorioDesempenhoObj->set("idusuario", $usuario["idusuario"])
    ->set("monitora_onde", 1);
$relatorioDesempenhoObj->verificaPermissao(
    $perfil["permissoes"],
    $url[2] . "|1"
);

switch ($_POST['acao']) {
    case 'salvar_relatorio':
        $relatoriosObj->set("post", $_POST);
        $salvar = $relatoriosObj->salvarRelatorio();

        $mensagem_sucesso = $salvar['sucesso']
            ? "salvar_relatorio_sucesso"
            : $salvar['erro_texto'];
        break;
}

switch ($url[3]) {
    case "ajax_alunos":
        if ($_GET['tag']) {
            $matriculasObj = new Matriculas();
            echo json_encode(
                $matriculasObj->retornarMatriculaPorNomeAluno($_GET['tag'])
            );
            exit();
        }
        break;
    case "ajax_alunos_cpf":
        if ($_GET['tag']) {
            $matriculasObj = new Matriculas();
            echo json_encode(
                $matriculasObj->retornarMatriculaPorCpfAluno($_GET['tag'])
            );
            exit();
        }
        break;
    case "html":
        $matriculasObj = new Matriculas();
        if (
            !empty($_GET['matricula_aluno'])
            && empty($_GET['q']['1|m.idmatricula'])
        ) {
            $_GET['q']['1|m.idmatricula'] = intval($_GET['matricula_aluno']);
        }

        if (
            !empty($_GET['matricula_aluno_cpf'])
            && empty($_GET['matricula_aluno'])
            && empty($_GET['q']['1|m.idmatricula'])
        ) {
            $_GET['q']['1|m.idmatricula'] = intval($_GET['matricula_aluno_cpf']);
        }

        $matricula['idmatricula'] = $_GET['q']['1|m.idmatricula'];
        #lista as avaliações e os acessos
        $acessos = $matriculasObj->matriculasRegistroAcessos($matricula['idmatricula']);
        $acessoTotal = $matriculasObj->matriculasSomatorioHorasAcessos($matricula['idmatricula']);
        $avaliacoes = $matriculasObj->avaliacoesConcluidas($matricula['idmatricula']);

        $dadosArray = $relatorioDesempenhoObj->gerarRelatorio('
            p.idpessoa,
            p.nome as aluno,
            p.documento,
            p.sexo,
            p.rg,
            p.rg_orgao_emissor,
            p.filiacao_mae,
            p.email,
            p.telefone,
            p.celular,
            p.cnh,
            esc.nome as estado_escola,
            p.categoria,
            p.data_nasc,
            c.nome as curso,
            m.data_matricula,
            m.idoferta,
            m.idcurso,
            m.idescola,
            DATE_FORMAT(m.data_cad, "%H:%i") as hora_matricula
        ');

        include("idiomas/" . $config["idioma_padrao"] . "/html.php");
        include("telas/" . $config["tela_padrao"] . "/html.php");
        break;
    default:
        include("idiomas/" . $config["idioma_padrao"] . "/index.php");
        include("telas/" . $config["tela_padrao"] . "/index.php");
}
