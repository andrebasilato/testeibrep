-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 05/04/2021 às 13:48
-- Versão do servidor: 10.2.36-MariaDB-log
-- Versão do PHP: 7.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ibreptran_oraculo`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `areas`
--

CREATE TABLE `areas` (
  `idarea` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos`
--

CREATE TABLE `atendimentos` (
  `idatendimento` int(10) UNSIGNED NOT NULL,
  `idpessoa` int(10) UNSIGNED DEFAULT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idassunto` int(10) UNSIGNED NOT NULL,
  `idsubassunto` int(10) UNSIGNED DEFAULT NULL,
  `idsituacao` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED DEFAULT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `idclone` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `protocolo` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `prioridade` char(1) NOT NULL DEFAULT 'N',
  `proxima_acao` date DEFAULT NULL,
  `avaliacao` int(1) UNSIGNED DEFAULT NULL,
  `tempo_resposta` int(10) UNSIGNED DEFAULT NULL COMMENT 'Media de tempo de resposta em minutos',
  `tempo_finalizado` int(10) UNSIGNED DEFAULT NULL COMMENT 'Tempo que demorou para finalizar em minutos',
  `cliente_visualiza` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_arquivos`
--

CREATE TABLE `atendimentos_arquivos` (
  `idarquivo` int(10) UNSIGNED NOT NULL,
  `idresposta` int(10) UNSIGNED DEFAULT NULL,
  `idatendimento` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `servidor` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `tamanho` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_assuntos`
--

CREATE TABLE `atendimentos_assuntos` (
  `idassunto` int(10) UNSIGNED NOT NULL,
  `idchecklist` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `sla` varchar(4) DEFAULT NULL,
  `subassunto_obrigatorio` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_assuntos_grupos`
--

CREATE TABLE `atendimentos_assuntos_grupos` (
  `idassunto_grupo` int(10) UNSIGNED NOT NULL,
  `idassunto` int(10) UNSIGNED NOT NULL,
  `idgrupo` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_assuntos_subassuntos`
--

CREATE TABLE `atendimentos_assuntos_subassuntos` (
  `idsubassunto` int(10) UNSIGNED NOT NULL,
  `idassunto` int(10) UNSIGNED NOT NULL,
  `idchecklist` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_assuntos_subassuntos_grupos`
--

CREATE TABLE `atendimentos_assuntos_subassuntos_grupos` (
  `idsubassunto_grupo` int(10) UNSIGNED NOT NULL,
  `idsubassunto` int(10) UNSIGNED NOT NULL,
  `idgrupo` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_checklists_opcoes_marcados`
--

CREATE TABLE `atendimentos_checklists_opcoes_marcados` (
  `idmarcada` int(10) UNSIGNED NOT NULL,
  `idatendimento` int(10) UNSIGNED NOT NULL,
  `idchecklist` int(10) UNSIGNED NOT NULL,
  `idopcao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_historicos`
--

CREATE TABLE `atendimentos_historicos` (
  `idhistorico` int(10) UNSIGNED NOT NULL,
  `idatendimento` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idpessoa` int(10) UNSIGNED DEFAULT NULL,
  `tipo` enum('E','D','IP','IA','CU','CAO','CAD','S','ES','A','CH','RA','UNI','ER','CI','CC','CL','CB','AOC','ROC') NOT NULL,
  `de` varchar(100) DEFAULT NULL,
  `para` varchar(100) DEFAULT NULL,
  `idusuario_convidado` int(10) UNSIGNED DEFAULT NULL,
  `idatendimento_clone` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_respostas`
--

CREATE TABLE `atendimentos_respostas` (
  `idresposta` int(10) UNSIGNED NOT NULL,
  `idatendimento` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idpessoa` int(10) UNSIGNED DEFAULT NULL,
  `idresposta_automatica` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `resposta` text DEFAULT NULL,
  `publica` enum('S','N') NOT NULL DEFAULT 'S',
  `tempo_resposta` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_respostas_automaticas`
--

CREATE TABLE `atendimentos_respostas_automaticas` (
  `idresposta` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `resposta` text DEFAULT NULL,
  `anexo` enum('S','N') NOT NULL,
  `todos` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_respostas_automaticas_assuntos`
--

CREATE TABLE `atendimentos_respostas_automaticas_assuntos` (
  `idresposta_assunto` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idresposta` int(10) UNSIGNED NOT NULL,
  `idassunto` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_sla`
--

CREATE TABLE `atendimentos_sla` (
  `idsla` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `horas` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_workflow`
--

CREATE TABLE `atendimentos_workflow` (
  `idsituacao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `inicio` enum('S','N') NOT NULL DEFAULT 'N',
  `fim` enum('S','N') NOT NULL DEFAULT 'N',
  `respondido_cliente` enum('S','N') NOT NULL DEFAULT 'N',
  `respondido_gestor` enum('S','N') NOT NULL DEFAULT 'N',
  `posicao_x` decimal(8,2) DEFAULT NULL,
  `posicao_y` decimal(8,2) DEFAULT NULL,
  `cor_bg` varchar(10) DEFAULT NULL,
  `cor_nome` varchar(10) DEFAULT NULL,
  `idapp` varchar(20) NOT NULL,
  `sla` int(10) UNSIGNED DEFAULT NULL,
  `ordem` int(10) UNSIGNED DEFAULT NULL,
  `sigla` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_workflow_acoes`
--

CREATE TABLE `atendimentos_workflow_acoes` (
  `idacao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsituacao` int(10) UNSIGNED DEFAULT NULL,
  `idrelacionamento` int(10) UNSIGNED DEFAULT NULL,
  `idopcao` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_workflow_acoes_parametros`
--

CREATE TABLE `atendimentos_workflow_acoes_parametros` (
  `idacaoparametro` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idacao` int(10) UNSIGNED NOT NULL,
  `idparametro` int(10) UNSIGNED NOT NULL,
  `valor` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos_workflow_relacionamentos`
--

CREATE TABLE `atendimentos_workflow_relacionamentos` (
  `idrelacionamento` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsituacao_de` int(10) UNSIGNED NOT NULL,
  `idsituacao_para` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `atentimentos_repostas_escolas`
--

CREATE TABLE `atentimentos_repostas_escolas` (
  `idassociacao` int(20) NOT NULL,
  `idresposta` int(10) NOT NULL,
  `idescola` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas`
--

CREATE TABLE `avas` (
  `idava` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `contabilizar_datas` enum('S','N') DEFAULT 'S',
  `carga_horaria_min` int(2) DEFAULT 0,
  `simulados_apartirde` date DEFAULT NULL,
  `simulados_link` varchar(255) DEFAULT NULL,
  `modulos` text NOT NULL,
  `pre_requisito` enum('S','N') NOT NULL DEFAULT 'S',
  `porcentagem_rota` decimal(4,1) UNSIGNED NOT NULL DEFAULT 0.0,
  `porcentagem_chat` decimal(4,1) UNSIGNED NOT NULL DEFAULT 0.0,
  `porcentagem_forum` decimal(4,1) UNSIGNED NOT NULL DEFAULT 0.0,
  `porcentagem_tira_duvida` decimal(4,1) UNSIGNED NOT NULL DEFAULT 0.0,
  `porcentagem_biblioteca` decimal(4,1) UNSIGNED NOT NULL DEFAULT 0.0,
  `porcentagem_simulado` decimal(4,1) UNSIGNED NOT NULL DEFAULT 0.0,
  `idava_clone` int(10) UNSIGNED DEFAULT NULL,
  `instrucoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_audios`
--

CREATE TABLE `avas_audios` (
  `idaudio` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_ava` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ordem` int(2) UNSIGNED DEFAULT NULL,
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `imagem_exibicao_nome` varchar(100) DEFAULT NULL,
  `imagem_exibicao_servidor` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tipo` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tamanho` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_avaliacoes`
--

CREATE TABLE `avas_avaliacoes` (
  `idavaliacao` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_ava` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL,
  `ordem` int(2) UNSIGNED DEFAULT NULL,
  `avaliador` enum('sistema','professor') NOT NULL DEFAULT 'sistema',
  `tempo` time DEFAULT NULL,
  `idtipo` int(10) UNSIGNED NOT NULL,
  `periode_de` date NOT NULL,
  `periode_ate` date NOT NULL,
  `objetivas_faceis` int(2) UNSIGNED NOT NULL,
  `objetivas_intermediarias` int(2) UNSIGNED NOT NULL,
  `objetivas_dificeis` int(2) UNSIGNED NOT NULL,
  `parcialmente` enum('S','N') NOT NULL DEFAULT 'S',
  `imagem_exibicao_nome` varchar(100) DEFAULT NULL,
  `imagem_exibicao_servidor` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tipo` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `qtde_tentativas` int(4) UNSIGNED DEFAULT NULL,
  `nota_minima` decimal(10,2) DEFAULT NULL,
  `subjetivas_faceis` int(2) UNSIGNED NOT NULL,
  `subjetivas_intermediarias` int(2) UNSIGNED NOT NULL,
  `subjetivas_dificeis` int(2) UNSIGNED NOT NULL,
  `periodo_correcao_de` date DEFAULT NULL,
  `periodo_correcao_ate` date DEFAULT NULL,
  `periodo_correcao_dias` int(2) UNSIGNED DEFAULT NULL,
  `tipo` int(10) DEFAULT NULL,
  `iddisciplina_nota` int(10) UNSIGNED NOT NULL,
  `intervalo_tentativas` time DEFAULT NULL,
  `tempo_alerta` time DEFAULT '00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_avaliacoes_disciplinas`
--

CREATE TABLE `avas_avaliacoes_disciplinas` (
  `idavaliacao_disciplina` int(10) UNSIGNED NOT NULL,
  `idavaliacao` int(10) UNSIGNED NOT NULL,
  `iddisciplina` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('N','S') DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_chats`
--

CREATE TABLE `avas_chats` (
  `idchat` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_ava` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `imagem_nome` varchar(100) DEFAULT NULL,
  `imagem_servidor` varchar(100) DEFAULT NULL,
  `imagem_tipo` varchar(100) DEFAULT NULL,
  `imagem_tamanho` int(10) DEFAULT NULL,
  `inicio_campanha` datetime DEFAULT NULL,
  `inicio_entrada_aluno` datetime DEFAULT NULL,
  `fim_entrada_aluno` datetime DEFAULT NULL,
  `limite_pessoas_online` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_conteudos`
--

CREATE TABLE `avas_conteudos` (
  `idconteudo` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_ava` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL,
  `conteudo` mediumtext NOT NULL,
  `conteudo_bkp` text NOT NULL,
  `ordem` int(2) UNSIGNED DEFAULT NULL,
  `imagem_exibicao_nome` varchar(100) DEFAULT NULL,
  `imagem_exibicao_servidor` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tipo` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `tipo_edicao` enum('T','B','A') DEFAULT 'T',
  `html_nome` varchar(100) DEFAULT NULL,
  `html_servidor` varchar(100) DEFAULT NULL,
  `html_tipo` varchar(100) DEFAULT NULL,
  `html_tamanho` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_conteudos_frames`
--

CREATE TABLE `avas_conteudos_frames` (
  `idframe` int(10) UNSIGNED NOT NULL,
  `idconteudo` int(10) UNSIGNED NOT NULL,
  `conteudo` mediumtext NOT NULL,
  `altura` int(4) NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `url_original` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_conteudos_linksacoes`
--

CREATE TABLE `avas_conteudos_linksacoes` (
  `idlinkacao` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE utf8_bin NOT NULL,
  `url` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `variavel` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `tipo` char(1) COLLATE utf8_bin NOT NULL,
  `ativo` enum('S','N') CHARACTER SET utf8 NOT NULL,
  `idava_conteudo` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_dicosvirtuais_pastas`
--

CREATE TABLE `avas_dicosvirtuais_pastas` (
  `id_pasta` int(10) NOT NULL,
  `idava` int(10) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `ativo` enum('S','N') DEFAULT 'S',
  `ativo_painel` enum('S','N') DEFAULT 'S',
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_disciplinas`
--

CREATE TABLE `avas_disciplinas` (
  `idava_disciplina` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `iddisciplina` int(10) UNSIGNED NOT NULL,
  `tempo_offline` time DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('N','S') DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_discosvirtuais`
--

CREATE TABLE `avas_discosvirtuais` (
  `id_discovirtual` int(10) NOT NULL,
  `ativo` enum('S','N') DEFAULT 'S',
  `ativo_painel` enum('S','N') DEFAULT 'S',
  `nome_do_arquivo` varchar(255) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `tamanho` int(20) DEFAULT NULL,
  `nome_no_disco` varchar(255) NOT NULL,
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp(),
  `idava` int(10) NOT NULL,
  `idpasta` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_downloads`
--

CREATE TABLE `avas_downloads` (
  `iddownload` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `idpasta` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_ava` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `arquivo_nome` varchar(100) NOT NULL,
  `arquivo_servidor` varchar(100) NOT NULL,
  `arquivo_tipo` varchar(100) NOT NULL,
  `arquivo_tamanho` int(10) UNSIGNED NOT NULL,
  `ordem` int(2) UNSIGNED DEFAULT NULL,
  `imagem_exibicao_nome` varchar(100) DEFAULT NULL,
  `imagem_exibicao_servidor` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tipo` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `ebook` enum('S','N') DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_downloads_pastas`
--

CREATE TABLE `avas_downloads_pastas` (
  `idpasta` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('N','S') DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_enquetes`
--

CREATE TABLE `avas_enquetes` (
  `idenquete` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_ava` enum('S','N') NOT NULL DEFAULT 'S',
  `pergunta` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_enquetes_opcoes`
--

CREATE TABLE `avas_enquetes_opcoes` (
  `idopcao` int(10) UNSIGNED NOT NULL,
  `idenquete` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `opcao` text NOT NULL,
  `ordem` int(2) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_enquetes_opcoes_votos`
--

CREATE TABLE `avas_enquetes_opcoes_votos` (
  `idvoto` int(10) UNSIGNED NOT NULL,
  `idopcao` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED DEFAULT NULL,
  `idbloco_disciplina` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_exercicios`
--

CREATE TABLE `avas_exercicios` (
  `idexercicio` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_ava` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL,
  `ordem` int(2) UNSIGNED DEFAULT NULL,
  `objetivas_faceis` int(2) UNSIGNED NOT NULL,
  `objetivas_intermediarias` int(2) UNSIGNED NOT NULL,
  `objetivas_dificeis` int(2) UNSIGNED NOT NULL,
  `imagem_exibicao_nome` varchar(100) DEFAULT NULL,
  `imagem_exibicao_servidor` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tipo` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `nota_minima` decimal(10,2) DEFAULT NULL,
  `iddisciplina_nota` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_exercicios_disciplinas`
--

CREATE TABLE `avas_exercicios_disciplinas` (
  `idexercicio_disciplina` int(10) UNSIGNED NOT NULL,
  `idexercicio` int(10) UNSIGNED NOT NULL,
  `iddisciplina` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('N','S') DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_faqs`
--

CREATE TABLE `avas_faqs` (
  `idfaq` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_ava` enum('S','N') NOT NULL DEFAULT 'S',
  `pergunta` text NOT NULL,
  `resposta` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_foruns`
--

CREATE TABLE `avas_foruns` (
  `idforum` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_ava` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `periode_de` date DEFAULT NULL,
  `periode_ate` date DEFAULT NULL,
  `ordem` int(2) UNSIGNED DEFAULT NULL,
  `imagem_exibicao_nome` varchar(100) DEFAULT NULL,
  `imagem_exibicao_servidor` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tipo` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `permissoes` text DEFAULT NULL,
  `iddisciplina` int(10) DEFAULT NULL,
  `enviar_email_automatico` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_foruns_topicos`
--

CREATE TABLE `avas_foruns_topicos` (
  `idtopico` int(10) UNSIGNED NOT NULL,
  `idforum` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `nome` varchar(100) NOT NULL,
  `mensagem` text NOT NULL,
  `periode_de` date DEFAULT NULL,
  `periode_ate` date DEFAULT NULL,
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `arquivo_downloads` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `visualizacoes` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_curtiu` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_nao_curtiu` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `bloqueado` enum('desbloqueado','bloqueado','bloqueado_topico','bloqueado_url') NOT NULL DEFAULT 'desbloqueado',
  `bloqueado_idusuario` int(10) UNSIGNED DEFAULT NULL,
  `bloqueado_idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `bloqueado_idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `bloqueado_quando` datetime DEFAULT NULL,
  `bloqueado_mensagem` text DEFAULT NULL,
  `bloqueado_idtopico` int(10) UNSIGNED DEFAULT NULL,
  `bloqueado_url` varchar(200) DEFAULT NULL,
  `moderado` enum('S','N') NOT NULL DEFAULT 'N',
  `moderado_idusuario` int(10) UNSIGNED DEFAULT NULL,
  `moderado_idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `moderado_idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `moderado_quando` datetime DEFAULT NULL,
  `moderado_mensagem` text DEFAULT NULL,
  `oculto` enum('S','N') NOT NULL DEFAULT 'N',
  `oculto_idusuario` int(10) UNSIGNED DEFAULT NULL,
  `oculto_idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `oculto_idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `oculto_quando` datetime DEFAULT NULL,
  `total_mensagens` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `ultima_mensagem_data` datetime DEFAULT NULL,
  `ultima_mensagem_idusuario` int(10) UNSIGNED DEFAULT NULL,
  `ultima_mensagem_idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `ultima_mensagem_idmatricula` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_foruns_topicos_assinantes`
--

CREATE TABLE `avas_foruns_topicos_assinantes` (
  `idassinatura` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idtopico` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_foruns_topicos_assinantes_mensagens`
--

CREATE TABLE `avas_foruns_topicos_assinantes_mensagens` (
  `idassinatura_mensagem` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idtopico` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `code` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_foruns_topicos_curtidas`
--

CREATE TABLE `avas_foruns_topicos_curtidas` (
  `idcurtida` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `tipo` enum('curtiu','nao_curtiu') NOT NULL DEFAULT 'curtiu',
  `idtopico` int(10) UNSIGNED DEFAULT NULL,
  `idmensagem` int(10) UNSIGNED DEFAULT NULL,
  `ip` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_foruns_topicos_mensagens`
--

CREATE TABLE `avas_foruns_topicos_mensagens` (
  `idmensagem` int(10) UNSIGNED NOT NULL,
  `idtopico` int(10) UNSIGNED NOT NULL,
  `idmensagem_associada` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `mensagem` text NOT NULL,
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `arquivo_downloads` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_curtiu` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_nao_curtiu` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `moderado` enum('S','N') NOT NULL DEFAULT 'N',
  `moderado_idusuario` int(10) UNSIGNED DEFAULT NULL,
  `moderado_idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `moderado_idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `moderado_quando` datetime DEFAULT NULL,
  `moderado_mensagem` text DEFAULT NULL,
  `oculto` enum('S','N') NOT NULL DEFAULT 'N',
  `oculto_idusuario` int(10) UNSIGNED DEFAULT NULL,
  `oculto_idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `oculto_idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `oculto_quando` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_links`
--

CREATE TABLE `avas_links` (
  `idlink` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_ava` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL,
  `link` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ordem` int(2) UNSIGNED DEFAULT NULL,
  `imagem_exibicao_nome` varchar(100) DEFAULT NULL,
  `imagem_exibicao_servidor` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tipo` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tamanho` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_mensagem_instantanea`
--

CREATE TABLE `avas_mensagem_instantanea` (
  `idmensagem_instantanea` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idava` int(10) UNSIGNED NOT NULL,
  `ultima_interacao` datetime NOT NULL,
  `idcategoria` int(7) UNSIGNED DEFAULT NULL,
  `sinalizador_professor` enum('S','N') CHARACTER SET utf8 NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_mensagem_instantanea_conversas`
--

CREATE TABLE `avas_mensagem_instantanea_conversas` (
  `idmensagem_instantanea_conversa` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `idmensagem_instantanea` int(10) UNSIGNED NOT NULL,
  `idmensagem_instantanea_integrante` int(10) UNSIGNED NOT NULL,
  `mensagem` text NOT NULL,
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `liberada` enum('S','N') NOT NULL DEFAULT 'S' COMMENT 'Flag para indicar se será exibida a mensagem. Criada para o envio de arquivo. Caso tenha arquivo, só ficará liberada após terminar o upload do mesmo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela com as conversas das mensagens instantâneas';

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_mensagem_instantanea_conversas_visualizar`
--

CREATE TABLE `avas_mensagem_instantanea_conversas_visualizar` (
  `idmensagem_instantanea_conversas_visualizar` int(10) UNSIGNED NOT NULL,
  `idmensagem_instantanea_conversa` int(10) UNSIGNED NOT NULL,
  `idmensagem_instantanea_integrante` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela com as converesas que os integrantes não viram';

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_mensagem_instantanea_integrantes`
--

CREATE TABLE `avas_mensagem_instantanea_integrantes` (
  `idmensagem_instantanea_integrante` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idmensagem_instantanea` int(10) UNSIGNED NOT NULL,
  `idpessoa` int(10) UNSIGNED DEFAULT NULL,
  `idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `criador` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'Indica se foi a pessoa que criou a conversa',
  `data_reativado` datetime DEFAULT NULL COMMENT 'Indica a data que o integrante foi inserido novamente na conversa'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela com os integrantes das mensagens instantâneas';

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_mensagens`
--

CREATE TABLE `avas_mensagens` (
  `idmensagem` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idmatricula_de` int(10) UNSIGNED NOT NULL,
  `idmatricula_para` int(10) UNSIGNED NOT NULL,
  `idbloco_disciplina` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_mensagens_texto`
--

CREATE TABLE `avas_mensagens_texto` (
  `idmensagem_texto` int(10) UNSIGNED NOT NULL,
  `idmensagem` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `mensagem` text NOT NULL,
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_objetos_divisores`
--

CREATE TABLE `avas_objetos_divisores` (
  `idobjeto_divisor` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_ava` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL,
  `ordem` int(2) UNSIGNED DEFAULT NULL,
  `cor_bg` varchar(7) NOT NULL DEFAULT '0B89BD',
  `cor_letra` varchar(7) NOT NULL DEFAULT 'FFFFFF',
  `agrupado` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_perguntas`
--

CREATE TABLE `avas_perguntas` (
  `idpergunta` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_ava` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL,
  `pergunta` text NOT NULL,
  `resposta` text NOT NULL,
  `ordem` int(2) UNSIGNED DEFAULT NULL,
  `imagem_exibicao_nome` varchar(100) DEFAULT NULL,
  `imagem_exibicao_servidor` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tipo` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tamanho` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_rotas_aprendizagem`
--

CREATE TABLE `avas_rotas_aprendizagem` (
  `idrota_aprendizagem` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_ava` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_rotas_aprendizagem_objetos`
--

CREATE TABLE `avas_rotas_aprendizagem_objetos` (
  `idobjeto` int(10) UNSIGNED NOT NULL,
  `idrota_aprendizagem` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ordem` int(3) UNSIGNED DEFAULT NULL,
  `tipo` enum('audio','conteudo','download','link','pergunta','video','simulado','enquete','objeto_divisor','exercicio','reconhecimento') NOT NULL,
  `idaudio` int(10) UNSIGNED DEFAULT NULL,
  `idconteudo` int(10) UNSIGNED DEFAULT NULL,
  `iddownload` int(10) UNSIGNED DEFAULT NULL,
  `idlink` int(10) UNSIGNED DEFAULT NULL,
  `idpergunta` int(10) UNSIGNED DEFAULT NULL,
  `idvideo` int(10) UNSIGNED DEFAULT NULL,
  `tempo` time DEFAULT NULL,
  `porcentagem` decimal(5,2) UNSIGNED DEFAULT NULL,
  `idsimulado` int(10) UNSIGNED DEFAULT NULL,
  `vencimento` date DEFAULT NULL,
  `idenquete` int(10) UNSIGNED DEFAULT NULL,
  `idobjeto_divisor` int(10) UNSIGNED DEFAULT NULL,
  `idexercicio` int(10) UNSIGNED DEFAULT NULL,
  `idobjeto_pre_requisito` int(10) UNSIGNED DEFAULT NULL,
  `idreconhecimento` int(11) DEFAULT NULL,
  `dias` int(10) UNSIGNED DEFAULT NULL,
  `gerar_data_final` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_simulados`
--

CREATE TABLE `avas_simulados` (
  `idsimulado` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_ava` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL,
  `ordem` int(2) UNSIGNED DEFAULT NULL,
  `tempo` time DEFAULT NULL,
  `periode_de` date NOT NULL,
  `periode_ate` date NOT NULL,
  `objetivas_faceis` int(2) UNSIGNED NOT NULL,
  `objetivas_intermediarias` int(2) UNSIGNED NOT NULL,
  `objetivas_dificeis` int(2) UNSIGNED NOT NULL,
  `imagem_exibicao_nome` varchar(100) DEFAULT NULL,
  `imagem_exibicao_servidor` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tipo` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tamanho` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_simulados_disciplinas`
--

CREATE TABLE `avas_simulados_disciplinas` (
  `idsimulado_disciplina` int(10) UNSIGNED NOT NULL,
  `idsimulado` int(10) UNSIGNED NOT NULL,
  `iddisciplina` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('N','S') DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_tiraduvidas`
--

CREATE TABLE `avas_tiraduvidas` (
  `idtiraduvida` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idprofessor` int(10) UNSIGNED NOT NULL,
  `idbloco_disciplina` int(10) UNSIGNED NOT NULL,
  `sinalizador_professor` enum('N','S') NOT NULL DEFAULT 'N',
  `sinalizador_aluno` enum('N','S') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_tiraduvidas_categorias`
--

CREATE TABLE `avas_tiraduvidas_categorias` (
  `idcategoria` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_tiraduvidas_categorias_professores`
--

CREATE TABLE `avas_tiraduvidas_categorias_professores` (
  `idcategoria_professor` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idcategoria` int(10) UNSIGNED NOT NULL,
  `idprofessor` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabela com associações dos professores com as categorias de ';

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_tiraduvidas_mensagens`
--

CREATE TABLE `avas_tiraduvidas_mensagens` (
  `idmensagem` int(10) UNSIGNED NOT NULL,
  `idtiraduvida` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `mensagem` text NOT NULL,
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_tira_duvidas`
--

CREATE TABLE `avas_tira_duvidas` (
  `idduvida` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL,
  `autoriza_exibir` enum('S','N') NOT NULL DEFAULT 'N',
  `interesse_exibir` enum('S','N') NOT NULL DEFAULT 'N',
  `resposta` text DEFAULT NULL,
  `data_resposta` datetime DEFAULT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `exibir_ava` enum('S','N') NOT NULL DEFAULT 'S',
  `pergunta` text NOT NULL,
  `idusuario_cad` int(10) UNSIGNED DEFAULT NULL,
  `idprofessor_cad` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avas_videotecas`
--

CREATE TABLE `avas_videotecas` (
  `idvideo` int(10) NOT NULL,
  `idava` int(5) NOT NULL,
  `idvideoteca` int(10) NOT NULL,
  `ativo` enum('S','N') DEFAULT 'S',
  `ativo_painel` enum('S','N') DEFAULT NULL,
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `bancos`
--

CREATE TABLE `bancos` (
  `idbanco` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `codigo_banco` varchar(4) DEFAULT NULL,
  `pagina` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `bandeiras_cartoes`
--

CREATE TABLE `bandeiras_cartoes` (
  `idbandeira` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `banners_ava_aluno`
--

CREATE TABLE `banners_ava_aluno` (
  `idbanner` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `periodo_exibicao_de` date DEFAULT NULL,
  `periodo_exibicao_ate` date DEFAULT NULL,
  `hora_de` time DEFAULT NULL,
  `hora_ate` time DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `imagem_nome` varchar(100) DEFAULT NULL,
  `imagem_servidor` varchar(100) DEFAULT NULL,
  `imagem_tipo` varchar(100) DEFAULT NULL,
  `imagem_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `cor_background` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `banners_ava_aluno_dias`
--

CREATE TABLE `banners_ava_aluno_dias` (
  `idbanner_dia` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `idbanner` int(10) UNSIGNED NOT NULL,
  `dia` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `banners_escolas`
--

CREATE TABLE `banners_escolas` (
  `idbanner_escola` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idescola` int(10) UNSIGNED NOT NULL,
  `idbanner` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `banners_sindicatos`
--

CREATE TABLE `banners_sindicatos` (
  `idbanner_sindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `idbanner` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cartoes`
--

CREATE TABLE `cartoes` (
  `idcartao` int(11) NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `ativo_painel` enum('S','N') NOT NULL,
  `homologado` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'Indica se o cartão já está homologado pela cielo. S=Sim, N=Não',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) CHARACTER SET utf8 NOT NULL,
  `numero_estabelecimento` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `token` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `bandeiras` varchar(200) CHARACTER SET utf8 NOT NULL,
  `formas_pagamento` varchar(100) CHARACTER SET utf8 NOT NULL,
  `qtd_parcela` int(2) UNSIGNED NOT NULL,
  `parcelamento` enum('loja','admin') CHARACTER SET utf8 NOT NULL,
  `observacoes` text CHARACTER SET utf8 DEFAULT NULL,
  `slug` varchar(20) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cartoes_sindicatos`
--

CREATE TABLE `cartoes_sindicatos` (
  `idcartao_sindicato` int(10) UNSIGNED NOT NULL,
  `idcartao` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `idcategoria` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `subcategoria_obrigatoria` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias_subcategorias`
--

CREATE TABLE `categorias_subcategorias` (
  `idsubcategoria` int(10) UNSIGNED NOT NULL,
  `idcategoria` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `idunidade` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias_subcategorias_sindicatos`
--

CREATE TABLE `categorias_subcategorias_sindicatos` (
  `idassociacao` int(20) NOT NULL,
  `idsubcategoria` int(10) NOT NULL,
  `idsindicato` int(10) NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `centros_custos`
--

CREATE TABLE `centros_custos` (
  `idcentro_custo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `centros_custos_sindicatos`
--

CREATE TABLE `centros_custos_sindicatos` (
  `idcentro_custo_sindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `idcentro_custo` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `certificados`
--

CREATE TABLE `certificados` (
  `idcertificado` int(20) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `certificados_escolas`
--

CREATE TABLE `certificados_escolas` (
  `idcertificado_escola` int(20) NOT NULL,
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `certificados_midias`
--

CREATE TABLE `certificados_midias` (
  `idcertificado_midia` int(10) NOT NULL,
  `idcertificado` int(20) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(255) NOT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) DEFAULT NULL,
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `certificados_paginas`
--

CREATE TABLE `certificados_paginas` (
  `certificados_paginas` int(10) NOT NULL,
  `idcertificado` int(20) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(255) NOT NULL,
  `arquivo_tipo` varchar(45) DEFAULT NULL,
  `arquivo_tamanho` int(10) DEFAULT NULL,
  `ordem` int(10) DEFAULT NULL,
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cfcs_valores_cursos`
--

CREATE TABLE `cfcs_valores_cursos` (
  `idvalor_curso` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'N',
  `idcfc` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `avista` decimal(10,2) DEFAULT NULL,
  `aprazo` decimal(10,2) DEFAULT NULL,
  `parcelas` int(3) DEFAULT NULL,
  `disponivel_cfc` enum('S','N') DEFAULT NULL,
  `valor_por_matricula` decimal(10,2) DEFAULT NULL,
  `quantidade_faturas_ciclo` int(2) DEFAULT NULL,
  `qtd_parcelas` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cfc_mensagens`
--

CREATE TABLE `cfc_mensagens` (
  `idmensagem` int(10) NOT NULL,
  `idusuario` int(10) DEFAULT NULL,
  `idescola` int(10) NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `mensagem` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `chats`
--

CREATE TABLE `chats` (
  `idchat` int(10) NOT NULL,
  `idava` int(20) NOT NULL,
  `idinstrutor` int(10) NOT NULL COMMENT 'idpessoa',
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_agendamento` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Inicio do chat',
  `data_encerramento` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'data de encerramento do chat',
  `foto_chamada` varchar(255) DEFAULT NULL,
  `arquivo` varchar(255) DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `chats_acoes`
--

CREATE TABLE `chats_acoes` (
  `idchat_acao` int(10) NOT NULL,
  `acao` int(10) DEFAULT NULL,
  `icone` varchar(255) DEFAULT NULL,
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `chats_mensagens`
--

CREATE TABLE `chats_mensagens` (
  `idchat_mensagem` int(10) NOT NULL,
  `idchat` int(10) NOT NULL,
  `idpessoa` int(10) NOT NULL,
  `acao` int(10) DEFAULT NULL,
  `mensagem` text NOT NULL,
  `arquivo` varchar(255) DEFAULT NULL,
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `usuario_tipo` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `chats_pessoas`
--

CREATE TABLE `chats_pessoas` (
  `idchat_pessoa` int(10) NOT NULL,
  `idchat` int(10) NOT NULL,
  `idpessoa` int(10) NOT NULL,
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `checklists`
--

CREATE TABLE `checklists` (
  `idchecklist` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `obrigatorio` enum('S','N') NOT NULL DEFAULT 'N',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `checklists_opcoes`
--

CREATE TABLE `checklists_opcoes` (
  `idopcao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idchecklist` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cheques`
--

CREATE TABLE `cheques` (
  `idcheque` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `agencia` varchar(20) DEFAULT NULL,
  `numero_cheque` varchar(20) DEFAULT NULL,
  `titular` varchar(100) DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `data_vencimento` date DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `data_baixa` date DEFAULT NULL,
  `situacao` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cheques_alineas`
--

CREATE TABLE `cheques_alineas` (
  `idcheque_alinea` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cidades`
--

CREATE TABLE `cidades` (
  `idcidade` int(10) UNSIGNED NOT NULL,
  `idestado` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `longitude` decimal(8,4) DEFAULT NULL,
  `latitude` decimal(8,4) DEFAULT NULL,
  `capital` int(1) DEFAULT NULL,
  `codigo_uf` int(10) UNSIGNED DEFAULT NULL,
  `codigo` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cobrancas_log`
--

CREATE TABLE `cobrancas_log` (
  `idcobranca` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `mensagem` text DEFAULT NULL,
  `proxima_acao` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cofeci_log`
--

CREATE TABLE `cofeci_log` (
  `idcofecilog` int(10) NOT NULL,
  `idmatricula` int(10) NOT NULL,
  `situacao` int(1) NOT NULL DEFAULT 0,
  `data_cad` datetime NOT NULL,
  `data_processado` datetime DEFAULT NULL,
  `response` int(1) UNSIGNED DEFAULT NULL,
  `acao` int(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `comissoes_competencias`
--

CREATE TABLE `comissoes_competencias` (
  `idcompetencia` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `mes` date NOT NULL,
  `de` date DEFAULT NULL,
  `ate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `comissoes_competencias_cursos`
--

CREATE TABLE `comissoes_competencias_cursos` (
  `idcompetencia_curso` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idcompetencia` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `idregra` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `comissoes_competencias_sindicatos_cursos`
--

CREATE TABLE `comissoes_competencias_sindicatos_cursos` (
  `idcompetencia_oferta_curso` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idcompetencia` int(10) UNSIGNED NOT NULL,
  `idcurso_sindicato` int(10) UNSIGNED NOT NULL,
  `idregra` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `comissoes_regras`
--

CREATE TABLE `comissoes_regras` (
  `idregra` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `todas_sindicatos` enum('S','N') NOT NULL DEFAULT 'S',
  `todos_cursos` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `comissoes_regras_cursos`
--

CREATE TABLE `comissoes_regras_cursos` (
  `idregra_curso` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idregra` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `comissoes_regras_sindicatos`
--

CREATE TABLE `comissoes_regras_sindicatos` (
  `idregra_sindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idregra` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `comissoes_regras_valores`
--

CREATE TABLE `comissoes_regras_valores` (
  `idvalor` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idregra` int(10) UNSIGNED NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `porcentagem` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas`
--

CREATE TABLE `contas` (
  `idconta` int(10) UNSIGNED NOT NULL,
  `idmantenedora` int(10) UNSIGNED DEFAULT NULL,
  `idsindicato` int(10) UNSIGNED DEFAULT NULL,
  `idsituacao` int(10) UNSIGNED NOT NULL,
  `idcategoria` int(10) UNSIGNED DEFAULT NULL,
  `idsubcategoria` int(10) UNSIGNED DEFAULT NULL,
  `idfornecedor` int(10) UNSIGNED DEFAULT NULL,
  `idpessoa` int(10) UNSIGNED DEFAULT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `idproduto` int(10) UNSIGNED DEFAULT NULL,
  `idescola` int(10) UNSIGNED DEFAULT NULL,
  `idconta_corrente` int(10) UNSIGNED DEFAULT NULL,
  `idrelacao` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `parcela` int(2) NOT NULL,
  `total_parcelas` int(2) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `numero_documento` varchar(20) DEFAULT NULL,
  `tipo` enum('despesa','receita') NOT NULL,
  `forma_pagamento` int(1) UNSIGNED DEFAULT NULL COMMENT '1 = Boleto, 2 = Cartão de crédito, 3 = Cartão de débito, 4 = Cheque, 5 = Dinheiro, 6 = Depósito/Transferência Bancária, 8 = Carnê, 10 = PagSeguro',
  `valor` decimal(10,2) NOT NULL,
  `valor_juros` decimal(10,2) DEFAULT NULL,
  `valor_outro` decimal(10,2) DEFAULT NULL,
  `valor_multa` decimal(10,2) DEFAULT NULL,
  `valor_desconto` decimal(10,2) DEFAULT NULL,
  `data_vencimento` date NOT NULL,
  `valor_pago` decimal(10,2) DEFAULT NULL,
  `data_pagamento` date DEFAULT NULL,
  `tipo_documento` int(2) UNSIGNED DEFAULT NULL,
  `documento` varchar(100) DEFAULT NULL,
  `idevento` int(10) UNSIGNED DEFAULT NULL,
  `idbandeira` int(10) UNSIGNED DEFAULT NULL COMMENT 'Bandeira do cartao',
  `autorizacao_cartao` varchar(40) DEFAULT NULL,
  `idbanco` int(10) UNSIGNED DEFAULT NULL COMMENT 'Banco do cheque',
  `agencia_cheque` varchar(20) DEFAULT NULL,
  `cc_cheque` varchar(20) DEFAULT NULL,
  `numero_cheque` varchar(20) DEFAULT NULL,
  `emitente_cheque` varchar(100) DEFAULT NULL,
  `idfechamento` int(10) UNSIGNED DEFAULT NULL,
  `idfechamento_cfc` int(10) UNSIGNED DEFAULT NULL,
  `idpagamento_compartilhado` int(10) UNSIGNED DEFAULT NULL,
  `renegociada` enum('S','N') NOT NULL DEFAULT 'N',
  `parcelas_renegociadas` varchar(200) DEFAULT NULL,
  `idcheque` int(10) UNSIGNED DEFAULT NULL,
  `valor_liquido` decimal(10,2) DEFAULT NULL,
  `data1_cheque_alinea` date DEFAULT NULL,
  `id1_cheque_alinea` int(10) UNSIGNED DEFAULT NULL,
  `data2_cheque_alinea` date DEFAULT NULL,
  `id2_cheque_alinea` int(10) UNSIGNED DEFAULT NULL,
  `data3_cheque_alinea` date DEFAULT NULL,
  `id3_cheque_alinea` int(10) UNSIGNED DEFAULT NULL,
  `idcentro_custo` int(10) UNSIGNED DEFAULT NULL,
  `idmotivo` int(10) UNSIGNED DEFAULT NULL,
  `idconta_transferida` int(10) UNSIGNED DEFAULT NULL,
  `transferida` enum('S','N') NOT NULL DEFAULT 'N',
  `tipo_pagamento` enum('B','V','M','AE','E','DC','D','J','A','MM','VE','DI','C') DEFAULT NULL COMMENT 'B = Boleto, V = Visa, M = MasterCard, AE = American Express, E = ELO, DC = Dinners Club, D = Discover, J = JCB, A = Aura, MM = MasterCard Maestro, VE = Visa Eletron , DI = Dinheiro, C = Cheque',
  `idordemdecompra` int(10) DEFAULT NULL,
  `fatura` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'Identifica se essa conta é uma fatura',
  `qnt_matriculas` int(5) DEFAULT NULL,
  `data_modificacao_fatura` datetime DEFAULT NULL,
  `fastconnect_nu_link` int(10) DEFAULT NULL,
  `fastconnect_url_link` varchar(255) DEFAULT NULL,
  `idcliente` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_arquivos`
--

CREATE TABLE `contas_arquivos` (
  `idarquivo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idconta` int(10) UNSIGNED NOT NULL,
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `idmatricula` int(10) DEFAULT NULL,
  `protocolo` varchar(20) DEFAULT NULL,
  `nome_arquivo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_boletos_gerado`
--

CREATE TABLE `contas_boletos_gerado` (
  `idboletogerado` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `idconta` int(10) UNSIGNED NOT NULL,
  `data_vencimento` date NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `idconta_corrente` int(10) UNSIGNED NOT NULL,
  `idbanco` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_centros_custos`
--

CREATE TABLE `contas_centros_custos` (
  `idconta_centro_custo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idconta` int(10) UNSIGNED NOT NULL,
  `idcentro_custo` int(10) UNSIGNED NOT NULL,
  `porcentagem` decimal(5,2) NOT NULL,
  `valor` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_correntes`
--

CREATE TABLE `contas_correntes` (
  `idconta_corrente` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `idbanco` int(10) UNSIGNED DEFAULT NULL,
  `tipo` int(10) UNSIGNED DEFAULT NULL COMMENT '1 = Conta corrente | 2 = Investimento | 3 = Poupança',
  `agencia` varchar(120) DEFAULT NULL,
  `agencia_dig` int(2) UNSIGNED NOT NULL,
  `conta` varchar(30) DEFAULT NULL,
  `conta_dig` int(2) UNSIGNED NOT NULL,
  `data_abertura` datetime DEFAULT NULL,
  `carteira` varchar(10) NOT NULL,
  `empresa` varchar(100) DEFAULT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `boleto` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_correntes_fechamentos`
--

CREATE TABLE `contas_correntes_fechamentos` (
  `idconta_corrente_fechamento` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime DEFAULT NULL,
  `idconta` int(10) UNSIGNED DEFAULT NULL,
  `idconta_corrente` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_correntes_fechamentos_cfc`
--

CREATE TABLE `contas_correntes_fechamentos_cfc` (
  `idconta_corrente_fechamento` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime DEFAULT NULL,
  `idconta` int(10) UNSIGNED DEFAULT NULL,
  `idconta_corrente` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_correntes_sindicatos`
--

CREATE TABLE `contas_correntes_sindicatos` (
  `idconta_corrente_sindicato` int(10) UNSIGNED NOT NULL,
  `idconta_corrente` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_historicos`
--

CREATE TABLE `contas_historicos` (
  `idhistorico` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `idconta` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idvendedor` int(10) UNSIGNED DEFAULT NULL,
  `idescola` int(10) UNSIGNED DEFAULT NULL,
  `tipo` enum('conta','situacao','forma_pagamento','valor','valor_juros','valor_outro','valor_multa','valor_desconto','data_vencimento','valor_pago','data_pagamento','nome','idmantenedora','idsindicato','idcategoria','idfornecedor','idpessoa','idproduto','idconta_corrente','idevento','idbandeira','autorizacao_cartao','idbanco','agencia_cheque','cc_cheque','numero_cheque','emitente_cheque','idcentro_custo','idmotivo','conta_centro_custo','documento','arquivo','numero_documento') NOT NULL,
  `acao` enum('cadastrou','modificou','removeu','modificou_porcentagem') NOT NULL,
  `de` varchar(200) DEFAULT NULL,
  `para` varchar(200) DEFAULT NULL,
  `id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_matriculas`
--

CREATE TABLE `contas_matriculas` (
  `idconta_matricula` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idconta` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `valor_fatura` decimal(10,2) NOT NULL COMMENT 'Valor que essa matrícula representa apenas nessa fatura',
  `valor_total` decimal(10,2) NOT NULL COMMENT 'Valor total que essa matrícula representa em todas as faturas',
  `parcela` int(2) UNSIGNED NOT NULL,
  `total_parcelas` int(2) UNSIGNED NOT NULL,
  `qtd_parcelas` int(10) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_orcamentos`
--

CREATE TABLE `contas_orcamentos` (
  `idorcamento` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idcategoria` int(10) UNSIGNED DEFAULT NULL,
  `mes` date NOT NULL,
  `valor` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_pagamentos`
--

CREATE TABLE `contas_pagamentos` (
  `idpagamento` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `idconta` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `data_pagamento` datetime DEFAULT NULL,
  `valor` decimal(12,2) DEFAULT NULL,
  `bandeira` enum('V','M','AE','E','DC','D','J','A','MM','VE') DEFAULT NULL COMMENT 'V = Visa, M = MasterCard, AE = American Express, E = ELO, DC = Dinners Club, D = Discover, J = JCB, A = Aura, MM = MasterCard Maestro, VE = Visa Eletron',
  `tipo_operacao` enum('D','CV','CPL','CPA') DEFAULT NULL COMMENT 'D = Débito, CV = Crédito à vista, CPL = Crédito Loja, CPA = Crédito Administradora. A opção Débito só é permitido para as bandeiras Visa e MaterCard. A Bandeira Discover só aceita Crédito à vista',
  `qnt_parcelas` int(2) DEFAULT NULL COMMENT 'Quantidade de vezes que foi parcelado',
  `status_transacao` enum('CRI','EAN','AUT','NAUT','ATZ','NATZ','CAP','CAN','EAT','ECAN') DEFAULT NULL COMMENT 'CRI = Criada, EAN = Em Andamento, AUT = Autenticada, NAUT = Não Autenticada, ATZ = Autorizada, NATZ = Não Autorizada, CAP = Capturada, CAN = Cancelada, EAT = Em Autenticação, ECAN = Em Cancelamento',
  `tid` varchar(40) DEFAULT NULL,
  `nsu` int(10) DEFAULT NULL,
  `arp` int(6) UNSIGNED DEFAULT NULL COMMENT 'Código da autorização caso a transação tenha sido autorizada com sucesso',
  `pan` varchar(50) DEFAULT NULL,
  `cron` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'indica se o cron do retorno de pagamento da locaweb já foi rodado',
  `idmantenedora` int(10) UNSIGNED DEFAULT NULL,
  `idsindicato` int(10) UNSIGNED DEFAULT NULL,
  `idpessoa` int(10) UNSIGNED DEFAULT NULL,
  `idvendedor` int(10) UNSIGNED DEFAULT NULL,
  `idevento` int(10) UNSIGNED DEFAULT NULL,
  `forma_pagamento` int(1) UNSIGNED DEFAULT NULL,
  `idbandeira` int(10) UNSIGNED DEFAULT NULL,
  `vencimento` date DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_previsoes`
--

CREATE TABLE `contas_previsoes` (
  `idprevisao` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idcategoria` int(10) UNSIGNED DEFAULT NULL,
  `mes` date NOT NULL,
  `valor` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_relacoes`
--

CREATE TABLE `contas_relacoes` (
  `idrelacao` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_workflow`
--

CREATE TABLE `contas_workflow` (
  `idsituacao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime DEFAULT current_timestamp(),
  `nome` varchar(100) NOT NULL,
  `emaberto` enum('S','N') NOT NULL DEFAULT 'N',
  `faturar` enum('S','N') NOT NULL,
  `pago` enum('S','N') NOT NULL DEFAULT 'N',
  `renegociada` enum('S','N') NOT NULL DEFAULT 'N',
  `transferida` enum('S','N') NOT NULL DEFAULT 'N',
  `cancelada` enum('S','N') NOT NULL DEFAULT 'N',
  `pagseguro` enum('S','N') NOT NULL DEFAULT 'N',
  `posicao_x` decimal(8,2) DEFAULT NULL,
  `posicao_y` decimal(8,2) DEFAULT NULL,
  `cor_bg` varchar(10) DEFAULT NULL,
  `cor_nome` varchar(10) DEFAULT NULL,
  `idapp` varchar(20) NOT NULL,
  `sla` int(10) UNSIGNED DEFAULT NULL,
  `ordem` int(10) UNSIGNED DEFAULT NULL,
  `sigla` varchar(3) DEFAULT NULL,
  `fastconnect` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_workflow_acoes`
--

CREATE TABLE `contas_workflow_acoes` (
  `idacao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsituacao` int(10) UNSIGNED DEFAULT NULL,
  `idrelacionamento` int(10) UNSIGNED DEFAULT NULL,
  `idopcao` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_workflow_acoes_parametros`
--

CREATE TABLE `contas_workflow_acoes_parametros` (
  `idacaoparametro` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idacao` int(10) UNSIGNED NOT NULL,
  `idparametro` int(10) UNSIGNED NOT NULL,
  `valor` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_workflow_relacionamentos`
--

CREATE TABLE `contas_workflow_relacionamentos` (
  `idrelacionamento` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsituacao_de` int(10) UNSIGNED NOT NULL,
  `idsituacao_para` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contratos`
--

CREATE TABLE `contratos` (
  `idcontrato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idtipo` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `contrato` longtext DEFAULT NULL,
  `visualizacao` enum('S','N') NOT NULL DEFAULT 'S' COMMENT 'S - Exibir | N - Nao exibir',
  `background_nome` varchar(100) DEFAULT NULL,
  `background_servidor` varchar(100) DEFAULT NULL,
  `background_tipo` varchar(100) DEFAULT NULL,
  `background_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `margem_top` double(7,4) DEFAULT NULL COMMENT 'Margem do topo do PDF',
  `margem_bottom` double(7,4) DEFAULT NULL COMMENT 'Margem do rodapé do PDF',
  `margem_left` double(7,4) DEFAULT NULL COMMENT 'Margem do esquerda do PDF',
  `margem_right` double(7,4) DEFAULT NULL COMMENT 'Margem do direita do PDF',
  `gerar_cfc` enum('S','N') DEFAULT 'N',
  `gerar_aluno` enum('S','N') DEFAULT 'N',
  `gerar_proximo_acesso` enum('S','N') DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contratos_cursos`
--

CREATE TABLE `contratos_cursos` (
  `idcontrato_curso` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `idcontrato` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contratos_grupos`
--

CREATE TABLE `contratos_grupos` (
  `idgrupo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contratos_grupos_contratos`
--

CREATE TABLE `contratos_grupos_contratos` (
  `idgrupo_contrato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idgrupo` int(10) UNSIGNED NOT NULL,
  `idcontrato` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contratos_imagens`
--

CREATE TABLE `contratos_imagens` (
  `idimagem` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idcontrato` int(10) UNSIGNED NOT NULL,
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contratos_sindicatos`
--

CREATE TABLE `contratos_sindicatos` (
  `idcontrato_sindicato` int(10) UNSIGNED NOT NULL,
  `idcontrato` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contratos_tipos`
--

CREATE TABLE `contratos_tipos` (
  `idtipo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cupons`
--

CREATE TABLE `cupons` (
  `idcupom` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') CHARACTER SET utf8 NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') CHARACTER SET utf8 NOT NULL DEFAULT 'S',
  `nome` varchar(100) CHARACTER SET utf8 NOT NULL,
  `codigo` varchar(40) CHARACTER SET utf8 NOT NULL,
  `tipo` enum('G','C') CHARACTER SET utf8 NOT NULL DEFAULT 'G',
  `tipo_desconto` enum('P','V') CHARACTER SET utf8 NOT NULL DEFAULT 'P',
  `porcentagem` decimal(10,2) UNSIGNED DEFAULT NULL,
  `valor` decimal(10,2) UNSIGNED DEFAULT NULL,
  `quantidade` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `utilizado` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `validade` datetime DEFAULT NULL,
  `descricao` text CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cupons_cursos`
--

CREATE TABLE `cupons_cursos` (
  `idcupom_curso` int(10) UNSIGNED NOT NULL,
  `idcupom` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cupons_escolas`
--

CREATE TABLE `cupons_escolas` (
  `idcupom_escola` int(10) UNSIGNED NOT NULL,
  `idcupom` int(10) UNSIGNED NOT NULL,
  `idescola` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curriculos`
--

CREATE TABLE `curriculos` (
  `idcurriculo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `carga_horaria` varchar(80) DEFAULT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `media` decimal(3,1) DEFAULT NULL,
  `dias_minimo` int(10) DEFAULT NULL,
  `dias_maximo` int(11) DEFAULT NULL,
  `porcentagem_ava` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curriculos_arquivos`
--

CREATE TABLE `curriculos_arquivos` (
  `idarquivo` int(10) UNSIGNED NOT NULL,
  `idcurriculo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'N',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `servidor` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `tamanho` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curriculos_avaliacoes`
--

CREATE TABLE `curriculos_avaliacoes` (
  `idavaliacao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL,
  `idcurriculo` int(10) UNSIGNED NOT NULL,
  `avaliacao` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curriculos_blocos`
--

CREATE TABLE `curriculos_blocos` (
  `idbloco` int(10) UNSIGNED NOT NULL,
  `idcurriculo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `ordem` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curriculos_blocos_disciplinas`
--

CREATE TABLE `curriculos_blocos_disciplinas` (
  `idbloco_disciplina` int(10) UNSIGNED NOT NULL,
  `idbloco` int(10) UNSIGNED NOT NULL,
  `iddisciplina` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL,
  `ordem` int(2) UNSIGNED NOT NULL,
  `horas` int(4) UNSIGNED DEFAULT NULL,
  `carga_horaria` int(5) DEFAULT NULL,
  `idformula` int(20) DEFAULT NULL,
  `ignorar_historico` enum('S','N') NOT NULL DEFAULT 'N',
  `contabilizar_media` enum('S','N') NOT NULL DEFAULT 'N',
  `exibir_aptidao` enum('S','N') NOT NULL DEFAULT 'N',
  `nota_conceito` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'Se a nota for conceito irá exibir em letra (A à E) ao invés de número'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curriculos_notas_tipos`
--

CREATE TABLE `curriculos_notas_tipos` (
  `idcurriculo_tipo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idcurriculo` int(10) UNSIGNED NOT NULL,
  `idtipo` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curriculos_sindicatos`
--

CREATE TABLE `curriculos_sindicatos` (
  `idcurriculo_sindicatos` int(10) UNSIGNED NOT NULL,
  `idcurriculo` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos`
--

CREATE TABLE `cursos` (
  `idcurso` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `dias_acesso_ava` int(4) DEFAULT NULL,
  `percentual_ideal_ava` decimal(5,2) DEFAULT NULL,
  `tipo` enum('EAD','PRE','SEM') DEFAULT NULL,
  `carga_horaria_presencial` int(10) UNSIGNED DEFAULT NULL,
  `carga_horaria_distancia` int(10) UNSIGNED DEFAULT NULL,
  `carga_horaria_total` int(10) UNSIGNED DEFAULT NULL,
  `fundamentacao` varchar(100) DEFAULT NULL,
  `fundamentacao_legal` text DEFAULT NULL,
  `autorizacao` varchar(100) DEFAULT NULL,
  `perfil` text DEFAULT NULL,
  `regulamento` text DEFAULT NULL,
  `email_boas_vindas` text DEFAULT NULL,
  `sms_boas_vindas` text DEFAULT NULL,
  `imagem_exibicao_nome` varchar(100) DEFAULT NULL,
  `imagem_exibicao_servidor` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tipo` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `abreviacao` varchar(20) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `cofeci` enum('S','N') NOT NULL DEFAULT 'N',
  `se_quilometragem` varchar(45) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `ordem` int(2) UNSIGNED DEFAULT NULL,
  `gestaoacessos` enum('S','N') DEFAULT 'N',
  `usar_datavalid` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos_areas`
--

CREATE TABLE `cursos_areas` (
  `idcurso_area` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `idarea` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos_sindicatos`
--

CREATE TABLE `cursos_sindicatos` (
  `idcurso_sindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `idcertificado` int(10) UNSIGNED DEFAULT NULL,
  `idhistorico_escolar` int(10) DEFAULT NULL,
  `fundamentacao` text DEFAULT NULL,
  `fundamentacao_legal` text DEFAULT NULL,
  `autorizacao` text DEFAULT NULL,
  `perfil` text DEFAULT NULL,
  `regulamento` text DEFAULT NULL,
  `email_boas_vindas_sindicato` text DEFAULT NULL,
  `sms_boas_vindas_sindicato` text DEFAULT NULL,
  `certificado_ava` enum('S','N') NOT NULL DEFAULT 'N',
  `codigo_diploma_obrigatorio` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'Indica se o código do diploma para a matrícula é obrigatório',
  `homologar_certificado` enum('S','N') DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `declaracoes`
--

CREATE TABLE `declaracoes` (
  `iddeclaracao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idtipo` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `declaracao` longtext DEFAULT NULL,
  `visualizacao` enum('S','N') NOT NULL DEFAULT 'S' COMMENT 'S - Exibir | N - Nao exibir',
  `background_nome` varchar(100) DEFAULT NULL,
  `background_servidor` varchar(100) DEFAULT NULL,
  `background_tipo` varchar(100) DEFAULT NULL,
  `background_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `margem_top` double(7,4) DEFAULT NULL COMMENT 'Margem do topo do PDF',
  `margem_bottom` double(7,4) DEFAULT NULL COMMENT 'Margem do rodapé do PDF',
  `margem_left` double(7,4) DEFAULT NULL COMMENT 'Margem do esquerda do PDF',
  `margem_right` double(7,4) DEFAULT NULL COMMENT 'Margem do direita do PDF',
  `aluno_solicita` enum('S','N') NOT NULL DEFAULT 'N',
  `difere_automatico` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `declaracoes_cursos`
--

CREATE TABLE `declaracoes_cursos` (
  `iddeclaracao_curso` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `iddeclaracao` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `declaracoes_grupos`
--

CREATE TABLE `declaracoes_grupos` (
  `idgrupo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `declaracoes_grupos_declaracoes`
--

CREATE TABLE `declaracoes_grupos_declaracoes` (
  `idgrupo_declaracao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idgrupo` int(10) UNSIGNED NOT NULL,
  `iddeclaracao` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `declaracoes_imagens`
--

CREATE TABLE `declaracoes_imagens` (
  `iddeclaracao_imagem` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL,
  `iddeclaracao` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `servidor` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `tamanho` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `declaracoes_sindicatos`
--

CREATE TABLE `declaracoes_sindicatos` (
  `iddeclaracao_sindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `iddeclaracao` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `declaracoes_tipos`
--

CREATE TABLE `declaracoes_tipos` (
  `idtipo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `detran_logs`
--

CREATE TABLE `detran_logs` (
  `idlog` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET utf8 NOT NULL,
  `data_cad` datetime NOT NULL,
  `cod_transacao` enum('424','427','431','20','10') COLLATE utf8_bin DEFAULT NULL COMMENT '424 = Envio de créditos de aula, 427 = Cadastro certificado, 431 = Consulta Processo do aluno, 20 = Liberação RJ, 10 = Certicação RJ',
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `retorno` text CHARACTER SET utf8 NOT NULL,
  `string_envio` text CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura para tabela `detran_matriculas_disciplinas_enviadas`
--

CREATE TABLE `detran_matriculas_disciplinas_enviadas` (
  `idenviado` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET utf8 NOT NULL,
  `data_cad` datetime NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `iddisciplina` int(10) UNSIGNED NOT NULL,
  `data_aula` date NOT NULL,
  `hora_inicio_aula` time NOT NULL,
  `hora_fim_aula` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura para tabela `diplomas`
--

CREATE TABLE `diplomas` (
  `iddiploma` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `disciplinas`
--

CREATE TABLE `disciplinas` (
  `iddisciplina` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(300) NOT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `tipo` enum('EAD','PRE') DEFAULT 'EAD',
  `avaliacao_presencial` enum('S','N') NOT NULL DEFAULT 'N',
  `carga_horaria` int(10) UNSIGNED DEFAULT NULL,
  `pratica` int(10) UNSIGNED DEFAULT NULL,
  `teorica` int(10) UNSIGNED DEFAULT NULL,
  `laboratorio` int(10) UNSIGNED DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `idformula` int(10) DEFAULT NULL,
  `carga_horaria_distancia` int(10) DEFAULT NULL,
  `carga_horaria_presencial` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `disciplinas_cursos`
--

CREATE TABLE `disciplinas_cursos` (
  `iddisciplina_curso` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `iddisciplina` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `disciplinas_perguntas`
--

CREATE TABLE `disciplinas_perguntas` (
  `iddisciplina_pergunta` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `iddisciplina` int(10) UNSIGNED NOT NULL,
  `idpergunta` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `emails_automaticos`
--

CREATE TABLE `emails_automaticos` (
  `idemail` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `texto` longtext DEFAULT NULL,
  `dia` int(10) DEFAULT NULL,
  `porcentagem` decimal(5,1) DEFAULT NULL,
  `dia_semanal` int(1) DEFAULT NULL,
  `dia_mensal` int(2) DEFAULT NULL,
  `idsituacao_conta` int(10) UNSIGNED DEFAULT NULL,
  `corpo_sms` varchar(160) DEFAULT NULL,
  `salvar_log` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `emails_automaticos_adm`
--

CREATE TABLE `emails_automaticos_adm` (
  `idemail` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `texto` longtext DEFAULT NULL,
  `dia` int(10) DEFAULT NULL,
  `dia_semanal` int(1) DEFAULT NULL,
  `dia_mensal` int(2) DEFAULT NULL,
  `idsituacao_matricula` int(10) UNSIGNED DEFAULT NULL,
  `corpo_sms` varchar(160) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `emails_automaticos_cursos`
--

CREATE TABLE `emails_automaticos_cursos` (
  `idemail_curso` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idemail` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `emails_automaticos_cursos_adm`
--

CREATE TABLE `emails_automaticos_cursos_adm` (
  `idemail_curso` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idemail` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `emails_automaticos_log`
--

CREATE TABLE `emails_automaticos_log` (
  `idemail_log` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idemail` int(10) UNSIGNED DEFAULT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `tipo` varchar(50) NOT NULL,
  `idpessoa` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED DEFAULT NULL,
  `idoferta` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `emails_automaticos_log_adm`
--

CREATE TABLE `emails_automaticos_log_adm` (
  `idemail_log` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `idusuario` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `emails_automaticos_ofertas`
--

CREATE TABLE `emails_automaticos_ofertas` (
  `idemail_oferta` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idemail` int(10) UNSIGNED NOT NULL,
  `idoferta` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `emails_automaticos_sindicatos`
--

CREATE TABLE `emails_automaticos_sindicatos` (
  `idemail_sindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET utf8 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idemail` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `emails_log`
--

CREATE TABLE `emails_log` (
  `idemail` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `de_nome` varchar(200) NOT NULL,
  `de_email` varchar(200) NOT NULL,
  `para_nome` varchar(200) NOT NULL,
  `para_email` varchar(200) NOT NULL,
  `assunto` varchar(200) NOT NULL,
  `layout` varchar(45) NOT NULL,
  `mensagem` text NOT NULL,
  `cabecalho` text DEFAULT NULL,
  `data_leitura` datetime DEFAULT NULL,
  `qnt_reenvio` int(4) NOT NULL DEFAULT 0 COMMENT 'Quantidade de vezes que foi reenviado o email',
  `enviado` enum('S','N') NOT NULL DEFAULT 'S',
  `erro` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `emails_newsletter`
--

CREATE TABLE `emails_newsletter` (
  `idemail` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresas`
--

CREATE TABLE `empresas` (
  `idempresa` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `documento_tipo` enum('cpf','cnpj') NOT NULL DEFAULT 'cpf',
  `documento` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `cep` int(8) UNSIGNED DEFAULT NULL,
  `idlogradouro` int(10) UNSIGNED DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `idestado` int(10) UNSIGNED DEFAULT NULL,
  `idcidade` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `escolas`
--

CREATE TABLE `escolas` (
  `idescola` int(10) UNSIGNED NOT NULL,
  `idescola_aux` int(10) UNSIGNED DEFAULT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_login` enum('S','N') NOT NULL DEFAULT 'S',
  `relogin` enum('S','N') NOT NULL DEFAULT 'N',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idioma` enum('pt_br') NOT NULL DEFAULT 'pt_br',
  `documento_tipo` enum('cpf','cnpj') DEFAULT 'cpf',
  `documento` varchar(20) DEFAULT NULL,
  `razao_social` varchar(100) NOT NULL,
  `nome_fantasia` varchar(100) NOT NULL,
  `inscricao_estadual` varchar(100) DEFAULT NULL,
  `inscricao_municipal` varchar(100) DEFAULT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(128) NOT NULL,
  `cnh` varchar(20) DEFAULT NULL,
  `site` varchar(50) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `cep` int(8) UNSIGNED DEFAULT NULL,
  `idlogradouro` int(10) UNSIGNED DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `idestado` int(10) UNSIGNED DEFAULT NULL,
  `idcidade` int(10) UNSIGNED DEFAULT NULL,
  `informacoes` text DEFAULT NULL,
  `idcertificado` int(10) DEFAULT NULL,
  `avatar_nome` varchar(100) DEFAULT NULL,
  `avatar_servidor` varchar(100) DEFAULT NULL,
  `avatar_tipo` varchar(100) DEFAULT NULL,
  `avatar_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `ultimo_acesso` datetime DEFAULT NULL,
  `ultimo_view` datetime DEFAULT NULL,
  `ultima_senha` datetime DEFAULT NULL,
  `slug` varchar(30) DEFAULT NULL,
  `matriculas_por_mes` int(10) UNSIGNED DEFAULT NULL,
  `gerente_nome` varchar(100) DEFAULT NULL,
  `gerente_email` varchar(100) DEFAULT NULL,
  `gerente_telefone` varchar(15) DEFAULT NULL,
  `gerente_celular` varchar(15) DEFAULT NULL,
  `ciretran` varchar(100) DEFAULT NULL,
  `ciretran_numero` varchar(10) DEFAULT NULL,
  `ciretran_cidade` varchar(100) DEFAULT NULL,
  `valor_por_matricula` decimal(10,2) DEFAULT NULL,
  `quantidade_faturas_ciclo` int(2) UNSIGNED DEFAULT NULL,
  `qtd_parcelas` int(2) UNSIGNED DEFAULT NULL,
  `gerente_cpf` varchar(11) DEFAULT NULL,
  `gerente_data_nasc` date DEFAULT NULL,
  `gerente_profissao` varchar(100) DEFAULT NULL,
  `gerente_cep` int(8) DEFAULT NULL,
  `gerente_idlogradouro` int(10) DEFAULT NULL,
  `gerente_endereco` varchar(100) DEFAULT NULL,
  `gerente_bairro` varchar(100) DEFAULT NULL,
  `gerente_numero` varchar(10) DEFAULT NULL,
  `gerente_complemento` varchar(100) DEFAULT NULL,
  `gerente_idestado` int(10) DEFAULT NULL,
  `gerente_idcidade` int(10) DEFAULT NULL,
  `gerente_assinatura_nome` varchar(100) DEFAULT NULL,
  `gerente_assinatura_servidor` varchar(100) DEFAULT NULL,
  `gerente_assinatura_tipo` varchar(100) DEFAULT NULL,
  `gerente_assinatura_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `responsavel_legal_nome` varchar(100) DEFAULT NULL,
  `responsavel_legal_cpf` varchar(11) DEFAULT NULL,
  `responsavel_legal_data_nasc` date DEFAULT NULL,
  `responsavel_legal_email` varchar(100) DEFAULT NULL,
  `responsavel_legal_telefone` varchar(15) DEFAULT NULL,
  `responsavel_legal_celular` varchar(15) DEFAULT NULL,
  `responsavel_legal_profissao` varchar(100) DEFAULT NULL,
  `responsavel_legal_cep` int(8) DEFAULT NULL,
  `responsavel_legal_idlogradouro` int(10) DEFAULT NULL,
  `responsavel_legal_endereco` varchar(100) DEFAULT NULL,
  `responsavel_legal_bairro` varchar(100) DEFAULT NULL,
  `responsavel_legal_numero` varchar(10) DEFAULT NULL,
  `responsavel_legal_complemento` varchar(100) DEFAULT NULL,
  `responsavel_legal_idestado` int(10) DEFAULT NULL,
  `responsavel_legal_idcidade` int(10) DEFAULT NULL,
  `responsavel_legal_assinatura_nome` varchar(100) DEFAULT NULL,
  `responsavel_legal_assinatura_servidor` varchar(100) DEFAULT NULL,
  `responsavel_legal_assinatura_tipo` varchar(100) DEFAULT NULL,
  `exibir_campos` char(1) DEFAULT 'N',
  `criar_matricula` enum('S','N') DEFAULT 'S',
  `bloquear_mudanca_turma` char(1) DEFAULT 'N',
  `criar_modificar_matricula` char(1) DEFAULT 'S',
  `fastconnect` enum('S','N') DEFAULT 'N',
  `fastconnect_client_code` varchar(100) DEFAULT NULL,
  `fastconnect_client_key` varchar(32) DEFAULT NULL,
  `parceiro` enum('N','S') DEFAULT 'S',
  `modificar_matricula` enum('S','N') DEFAULT 'S',
  `contratos_aceitos` enum('S','N') DEFAULT 'N',
  `responsavel_legal_assinatura_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `diretor_ensino_nome` varchar(100) DEFAULT NULL,
  `diretor_ensino_cpf` varchar(11) DEFAULT NULL,
  `diretor_ensino_data_nasc` date DEFAULT NULL,
  `diretor_ensino_email` varchar(100) DEFAULT NULL,
  `diretor_ensino_telefone` varchar(15) DEFAULT NULL,
  `diretor_ensino_celular` varchar(15) DEFAULT NULL,
  `diretor_ensino_assinatura_nome` varchar(100) DEFAULT NULL,
  `diretor_ensino_assinatura_servidor` varchar(100) DEFAULT NULL,
  `diretor_ensino_assinatura_tipo` varchar(100) DEFAULT NULL,
  `diretor_ensino_assinatura_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `acesso_bloqueado` enum('S','N') DEFAULT NULL,
  `pagseguro` enum('S','N') DEFAULT 'N',
  `pagseguro_email` varchar(100) DEFAULT NULL,
  `pagseguro_token` varchar(32) DEFAULT NULL,
  `diretor_ensino_profissao` varchar(100) DEFAULT NULL,
  `diretor_ensino_skype` varchar(100) DEFAULT NULL,
  `diretor_ensino_portaria` varchar(100) DEFAULT NULL,
  `detran_codigo` varchar(8) DEFAULT NULL COMMENT 'Código do CFC',
  `receber_email` char(1) DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `escolas_arquivos`
--

CREATE TABLE `escolas_arquivos` (
  `idarquivo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idescola` int(10) UNSIGNED NOT NULL,
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `escolas_aux`
--

CREATE TABLE `escolas_aux` (
  `idescola` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `documento_tipo` enum('cpf','cnpj') NOT NULL DEFAULT 'cpf',
  `documento` varchar(20) NOT NULL,
  `razao_social` varchar(100) NOT NULL,
  `nome_fantasia` varchar(100) DEFAULT NULL,
  `inscricao_estadual` varchar(100) DEFAULT NULL,
  `inscricao_municipal` varchar(100) DEFAULT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `site` varchar(50) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `cep` int(8) UNSIGNED DEFAULT NULL,
  `idlogradouro` int(10) UNSIGNED DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `idestado` int(10) UNSIGNED DEFAULT NULL,
  `idcidade` int(10) UNSIGNED DEFAULT NULL,
  `gerente_nome` varchar(100) DEFAULT NULL,
  `gerente_telefone` varchar(15) DEFAULT NULL,
  `gerente_celular` varchar(15) DEFAULT NULL,
  `gerente_email` varchar(100) DEFAULT NULL,
  `informacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `escolas_contatos`
--

CREATE TABLE `escolas_contatos` (
  `idcontato` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'N',
  `idescola` int(10) UNSIGNED NOT NULL,
  `idtipo` int(10) UNSIGNED NOT NULL,
  `valor` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `escolas_contratos`
--

CREATE TABLE `escolas_contratos` (
  `idescola_contrato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idcontrato` int(10) UNSIGNED NOT NULL,
  `idescola` int(10) UNSIGNED NOT NULL,
  `gerado` enum('S','N') DEFAULT 'N',
  `aceito` enum('S','N') DEFAULT 'N',
  `ip` varchar(20) DEFAULT NULL,
  `navegador` varchar(100) DEFAULT NULL,
  `sistema_operacional` varchar(100) DEFAULT NULL,
  `navegador_versao` varchar(100) DEFAULT NULL,
  `user_agent` varchar(100) DEFAULT NULL,
  `idcontrato_gerado` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `escolas_contratos_gerados`
--

CREATE TABLE `escolas_contratos_gerados` (
  `idescola_contrato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idescola` int(10) UNSIGNED NOT NULL,
  `idcontrato` int(10) UNSIGNED DEFAULT NULL,
  `idtipo` int(10) UNSIGNED DEFAULT NULL,
  `idusuario_validou` int(10) DEFAULT NULL,
  `idusuario_assinou` int(10) DEFAULT NULL,
  `idpessoa_assinou` int(10) UNSIGNED DEFAULT NULL,
  `iddevedor_assinou` int(10) UNSIGNED DEFAULT NULL,
  `idusuario_cancelou` int(10) UNSIGNED DEFAULT NULL,
  `assinado` datetime DEFAULT NULL,
  `assinado_devedor` datetime DEFAULT NULL,
  `validado` datetime DEFAULT NULL,
  `cancelado` datetime DEFAULT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `justificativa` text DEFAULT NULL COMMENT 'Justificativa do cancelamento do contrato',
  `nao_assinado` datetime DEFAULT NULL,
  `nao_validado` datetime DEFAULT NULL,
  `arquivo` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_pasta` varchar(10) DEFAULT NULL,
  `aceito_cfc` enum('S','N') DEFAULT 'N',
  `aceito_cfc_data` datetime DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `navegador` varchar(100) DEFAULT NULL,
  `sistema_operacional` varchar(100) DEFAULT NULL,
  `navegador_versao` varchar(100) DEFAULT NULL,
  `user_agent` varchar(100) DEFAULT NULL,
  `script` int(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `escolas_estados_cidades`
--

CREATE TABLE `escolas_estados_cidades` (
  `idescola_estado_cidade` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idescola` int(10) UNSIGNED NOT NULL,
  `idestado` int(10) UNSIGNED NOT NULL,
  `idcidade` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `escolas_formas_pagamento`
--

CREATE TABLE `escolas_formas_pagamento` (
  `idescola_forma_pagamento` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idescola` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) DEFAULT NULL,
  `forma_pagamento` enum('B','CC') NOT NULL COMMENT 'B = Boleto, CC = Cartão de crédito'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `escolas_historico`
--

CREATE TABLE `escolas_historico` (
  `idhistorico` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `acesso_bloqueado` char(1) DEFAULT 'S' COMMENT 'Opções: S, N',
  `idescola` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estados`
--

CREATE TABLE `estados` (
  `idestado` int(10) UNSIGNED NOT NULL,
  `idpais` int(10) UNSIGNED NOT NULL,
  `idregiao` int(11) DEFAULT NULL,
  `sigla` char(2) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `codigo` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `estados`
--

INSERT INTO `estados` (`idestado`, `idpais`, `idregiao`, `sigla`, `nome`, `codigo`) VALUES
(1, 33, 1, 'AC', 'Acre', 12),
(2, 33, 2, 'AL', 'Alagoas', 27),
(3, 33, 1, 'AP', 'Amapá', 16),
(4, 33, 1, 'AM', 'Amazonas', 13),
(5, 33, 2, 'BA', 'Bahia', 29),
(6, 33, 2, 'CE', 'Ceará', 23),
(7, 33, 3, 'DF', 'Distrito Federal', 53),
(8, 33, 4, 'ES', 'Espírito Santo', 32),
(9, 33, 3, 'GO', 'Goiás', 52),
(10, 33, 2, 'MA', 'Maranhão', 21),
(11, 33, 3, 'MT', 'Mato Grosso', 51),
(12, 33, 3, 'MS', 'Mato Grosso do Sul', 50),
(13, 33, 4, 'MG', 'Minas Gerais', 31),
(14, 33, 1, 'PA', 'Pará', 15),
(15, 33, 2, 'PB', 'Paraíba', 25),
(16, 33, 5, 'PR', 'Paraná', 41),
(17, 33, 2, 'PE', 'Pernambuco', 26),
(18, 33, 2, 'PI', 'Piauí', 22),
(19, 33, 4, 'RJ', 'Rio de Janeiro', 33),
(20, 33, 2, 'RN', 'Rio Grande do Norte', 24),
(21, 33, 5, 'RS', 'Rio Grande do Sul', 43),
(22, 33, 1, 'RO', 'Rondônia', 11),
(23, 33, 1, 'RR', 'Roraima', 14),
(24, 33, 5, 'SC', 'Santa Catarina', 42),
(25, 33, 4, 'SP', 'São Paulo', 35),
(26, 33, 2, 'SE', 'Sergipe', 28),
(27, 33, 1, 'TO', 'Tocantins', 17);

-- --------------------------------------------------------

--
-- Estrutura para tabela `etiquetas`
--

CREATE TABLE `etiquetas` (
  `idetiqueta` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `etiqueta` longtext DEFAULT NULL,
  `margem_top` double(7,4) DEFAULT NULL COMMENT 'Margem do topo do PDF',
  `margem_bottom` double(7,4) DEFAULT NULL COMMENT 'Margem do rodapé do PDF',
  `margem_left` double(7,4) DEFAULT NULL COMMENT 'Margem do esquerda do PDF',
  `margem_right` double(7,4) DEFAULT NULL COMMENT 'Margem do direita do PDF',
  `espaco_linhas` double(7,4) DEFAULT NULL,
  `espaco_colunas` double(7,4) DEFAULT NULL,
  `altura` double(7,4) DEFAULT NULL COMMENT 'altura de cada etiqueta',
  `largura` double(7,4) DEFAULT NULL COMMENT 'largura de cada etiqueta',
  `linhas` int(11) DEFAULT NULL,
  `colunas` int(11) DEFAULT NULL,
  `linha_a_partir` int(11) DEFAULT NULL,
  `coluna_a_partir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `eventos_financeiros`
--

CREATE TABLE `eventos_financeiros` (
  `idevento` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `mensalidade` enum('S','N') NOT NULL DEFAULT 'N',
  `taxa_reativacao` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `excecoes`
--

CREATE TABLE `excecoes` (
  `idexcecao` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL,
  `url` varchar(50) NOT NULL,
  `logo_nome` varchar(100) DEFAULT NULL,
  `logo_servidor` varchar(100) DEFAULT NULL,
  `logo_tipo` varchar(100) DEFAULT NULL,
  `logo_tamanho` int(10) DEFAULT NULL,
  `logo_pequena_nome` varchar(100) DEFAULT NULL,
  `logo_pequena_servidor` varchar(100) DEFAULT NULL,
  `logo_pequena_tipo` varchar(100) DEFAULT NULL,
  `logo_pequena_tamanho` int(10) DEFAULT NULL,
  `titulo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fastconnect`
--

CREATE TABLE `fastconnect` (
  `idfastconnect` int(11) NOT NULL,
  `fid` varchar(50) DEFAULT NULL COMMENT 'Código que identifica unicamente uma transação efetuada na FastConnect.',
  `nm_cliente` varchar(200) NOT NULL COMMENT 'Nome completo do comprador',
  `url_retorno` varchar(255) DEFAULT NULL COMMENT 'Url de callback, Sempre que houver mudança de status no pagamento, será enviada uma requisição POST com os dados da transação. Ex.: https://callback.meusite.com"',
  `nu_documento` varchar(20) NOT NULL COMMENT 'CPF ou CNPJ do comprador',
  `ds_email` varchar(255) NOT NULL COMMENT 'Email do comprador',
  `nu_telefone` varchar(20) NOT NULL COMMENT 'Número do celular do comprador. Formato: 48999999999',
  `vl_total` decimal(10,2) NOT NULL COMMENT 'Valor total da compra. Formato: 1250.22',
  `dt_vencimento` datetime DEFAULT NULL COMMENT 'Data de vencimento da compra. Formato: YYYY-MM-DD',
  `dt_cobranca` datetime DEFAULT NULL COMMENT 'Caso queira agendar o pagamento para outro dia. Formato: YYYY-MM-DD',
  `dt_pagamento` datetime DEFAULT NULL COMMENT 'Formato: YYYY-MM-DD H:mm:ii.',
  `nu_referencia` varchar(100) DEFAULT NULL COMMENT 'Número aleatório gerado pelo sistema. Número de referência da sua compra.',
  `nu_parcelas` int(11) NOT NULL COMMENT 'Quantidade de parcelas dessa compra.',
  `nu_parcela` int(11) DEFAULT NULL COMMENT 'Número da parcela atual.',
  `tipo_venda` char(2) NOT NULL COMMENT 'Tipo de venda efetuada. Os tipos são: AV = (A Vista), PB = (Parcelado pelo banco), PL = (Parcelado pela loja), AS = (Assinatura)',
  `dia_cobranca` int(11) DEFAULT NULL COMMENT 'Caso o tipo _venda for "AS", você pode indicar o melhor dia de cobrança.',
  `ds_cep` varchar(100) DEFAULT NULL COMMENT 'Cep do comprador. Formato: 88888888',
  `ds_endereco` varchar(100) DEFAULT NULL COMMENT 'Endereço do comprador.',
  `ds_bairro` varchar(100) DEFAULT NULL COMMENT 'Bairro do comprador.',
  `ds_complemento` varchar(100) DEFAULT NULL COMMENT 'Complemento do endereço do comprador.',
  `ds_numero` int(11) DEFAULT NULL COMMENT 'Número do endereço do comprador.',
  `nm_cidade` varchar(150) DEFAULT NULL COMMENT 'Cidade do comprador.',
  `nm_estado` char(2) DEFAULT NULL COMMENT 'Sigla do estado do comprador',
  `ds_softdescriptor` varchar(13) DEFAULT NULL COMMENT 'Descrição que irá aparecer na fatura do cartão de crédito do comprador. Somente letras maiúsculas.',
  `ds_cartao_token` varchar(100) DEFAULT NULL COMMENT 'Você pode passar o token criptografado do cartão, caso já tenha feito uma compra com esse cartão.',
  `nm_bandeira` varchar(10) DEFAULT NULL COMMENT 'Obrigatório, (se ds _cartao _token não for informado). Bandeira do cartão. As bandeiras disponíveis são: visa, master, diners, elo, amex, aura, hipercard, jcb, discover. Somente letras minúsculas.',
  `nu_cartao` varchar(16) DEFAULT NULL COMMENT 'Obrigatório, (se ds _cartao _token não for informado). Número do cartão.',
  `nm_titular` varchar(200) DEFAULT NULL COMMENT 'Obrigatório, (se ds _cartao _token não for informado). Nome do titular do cartão.',
  `dt_validade` varchar(10) DEFAULT NULL COMMENT 'Obrigatório, (se ds _cartao _token não for informado). Validade do cartão. Formato: MM/YY.',
  `tp_capturar` tinyint(1) DEFAULT 1 COMMENT 'Obrigatório, (se ds _cartao _token não for informado). Efetua a cobrança na hora da transação.',
  `tp_antecipacao` int(11) DEFAULT NULL COMMENT 'Efetuar antecipação desta transação. Padrão: 1.',
  `nu_venda` varchar(50) DEFAULT NULL COMMENT 'Código alfanumérico.',
  `tipo` varchar(20) DEFAULT NULL COMMENT 'Exemplo: credito.',
  `situacao` varchar(100) DEFAULT NULL COMMENT 'Exemplo: CANCELADO POR ESTORNO(CARTÃO)',
  `vl_parcela` decimal(10,2) DEFAULT NULL,
  `vl_venda` decimal(10,2) DEFAULT NULL,
  `ds_mascara_cartao` varchar(50) DEFAULT NULL COMMENT 'Exemplo: Visa - **** 2221.',
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idconta` int(11) DEFAULT NULL,
  `ativo` enum('S','N') DEFAULT NULL,
  `id_venda` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `dt_venda` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_pdf` varchar(50) DEFAULT NULL,
  `link_pagamento` varchar(150) DEFAULT NULL,
  `cron` enum('S','N') NOT NULL DEFAULT 'N',
  `idsituacao` int(11) DEFAULT NULL COMMENT 'Código criado (config) a partir das situações retornadas do pagamento. Situações: 1 (ATIVO), 2 (Aberto), 3 (Pago), 4 (Pendente), 5 (Cancelado), 6 (Cancelado por estorno)',
  `dt_atualizacao` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `linha_digitavel` varchar(60) DEFAULT NULL,
  `codigo_barra` varchar(60) DEFAULT NULL,
  `nu_link` int(10) DEFAULT NULL,
  `idescola` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fastconnect_logs`
--

CREATE TABLE `fastconnect_logs` (
  `idlog` int(11) NOT NULL,
  `ativo` enum('S','N') DEFAULT NULL,
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `metodo` varchar(50) DEFAULT NULL COMMENT 'Método usado',
  `retorno` varchar(2000) DEFAULT NULL COMMENT 'Todo o texto retornado pelo método',
  `idfastconnect` int(11) DEFAULT NULL,
  `fid` varchar(50) DEFAULT NULL COMMENT 'Código que identifica unicamente uma transação efetuada na FastConnect.',
  `envio` varchar(2000) DEFAULT NULL COMMENT 'Todo o texto do envio pelo método'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fechamentos_caixa`
--

CREATE TABLE `fechamentos_caixa` (
  `idfechamento` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `idusuario` int(10) UNSIGNED NOT NULL,
  `credito_valor` decimal(10,2) DEFAULT NULL,
  `credito_quantidade` int(11) DEFAULT NULL,
  `debito_valor` decimal(10,2) DEFAULT NULL,
  `debito_quantidade` int(11) DEFAULT NULL,
  `periodo_tipo_pagar` varchar(3) DEFAULT NULL,
  `periodo_de_pagar` date DEFAULT NULL,
  `periodo_ate_pagar` date DEFAULT NULL,
  `periodo_tipo_receber` varchar(3) DEFAULT NULL,
  `periodo_de_receber` date DEFAULT NULL,
  `periodo_ate_receber` date DEFAULT NULL,
  `forma_pagamento_receber` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fechamentos_caixa_cfc`
--

CREATE TABLE `fechamentos_caixa_cfc` (
  `idfechamento` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `idescola` int(10) UNSIGNED NOT NULL,
  `credito_valor` decimal(10,2) DEFAULT NULL,
  `credito_quantidade` int(11) DEFAULT NULL,
  `debito_valor` decimal(10,2) DEFAULT NULL,
  `debito_quantidade` int(11) DEFAULT NULL,
  `periodo_tipo_pagar` varchar(3) DEFAULT NULL,
  `periodo_de_pagar` date DEFAULT NULL,
  `periodo_ate_pagar` date DEFAULT NULL,
  `periodo_tipo_receber` varchar(3) DEFAULT NULL,
  `periodo_de_receber` date DEFAULT NULL,
  `periodo_ate_receber` date DEFAULT NULL,
  `forma_pagamento_receber` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fechamentos_caixa_cfc_sindicatos`
--

CREATE TABLE `fechamentos_caixa_cfc_sindicatos` (
  `idfechamento_sindicato` int(10) UNSIGNED NOT NULL,
  `idfechamento` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fechamentos_caixa_sindicatos`
--

CREATE TABLE `fechamentos_caixa_sindicatos` (
  `idfechamento_sindicato` int(10) UNSIGNED NOT NULL,
  `idfechamento` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `feriados`
--

CREATE TABLE `feriados` (
  `idferiado` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `feriados_cidades`
--

CREATE TABLE `feriados_cidades` (
  `idferiado_cidade` int(10) UNSIGNED NOT NULL,
  `idferiado` int(10) UNSIGNED NOT NULL,
  `idcidade` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `feriados_escolas`
--

CREATE TABLE `feriados_escolas` (
  `idferiado_escola` int(10) UNSIGNED NOT NULL,
  `idferiado` int(10) UNSIGNED NOT NULL,
  `idescola` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `feriados_estados`
--

CREATE TABLE `feriados_estados` (
  `idferiado_estado` int(10) UNSIGNED NOT NULL,
  `idferiado` int(10) UNSIGNED NOT NULL,
  `idestado` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `feriados_sindicatos`
--

CREATE TABLE `feriados_sindicatos` (
  `idferiado_sindicato` int(10) UNSIGNED NOT NULL,
  `idferiado` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `folhas_registros_diplomas`
--

CREATE TABLE `folhas_registros_diplomas` (
  `idfolha` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `numero_livro` int(10) UNSIGNED NOT NULL,
  `numero_ordem` int(11) DEFAULT NULL,
  `numero_registro` int(11) DEFAULT NULL,
  `numero_relacao` int(11) DEFAULT NULL,
  `numero_folha` int(10) UNSIGNED DEFAULT NULL,
  `limite_matriculas` int(4) UNSIGNED DEFAULT NULL,
  `data_expedicao` date NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `observacoes` text DEFAULT NULL,
  `idcurso_sindicato` int(10) DEFAULT NULL,
  `idfolha_clone` int(10) UNSIGNED DEFAULT NULL,
  `numero_oficio` int(11) UNSIGNED DEFAULT NULL,
  `clone` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `folhas_registros_diplomas_matriculas`
--

CREATE TABLE `folhas_registros_diplomas_matriculas` (
  `idfolha_matricula` int(10) UNSIGNED NOT NULL,
  `idfolha` int(10) NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `cancelado` enum('N','S') DEFAULT 'N',
  `numero_ordem` int(11) DEFAULT NULL,
  `numero_registro` int(11) DEFAULT NULL,
  `cod_validacao` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `formulas_notas`
--

CREATE TABLE `formulas_notas` (
  `idformula` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `formula` text NOT NULL,
  `tipo` int(10) DEFAULT NULL COMMENT '1 = Coneito | 2 = Coneito por maior nota | 3 = Média | 4 = Resultado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `formulas_notas_sindicatos`
--

CREATE TABLE `formulas_notas_sindicatos` (
  `idformula_sindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `idformula` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `idfornecedor` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `documento_tipo` enum('cpf','cnpj') NOT NULL DEFAULT 'cpf',
  `documento` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(15) NOT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `cep` int(8) UNSIGNED DEFAULT NULL,
  `idlogradouro` int(10) UNSIGNED DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `idestado` int(10) UNSIGNED DEFAULT NULL,
  `idcidade` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios_arquivos`
--

CREATE TABLE `funcionarios_arquivos` (
  `idarquivo` int(10) UNSIGNED NOT NULL,
  `idfuncionario` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL,
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `grupos_usuarios_adm`
--

CREATE TABLE `grupos_usuarios_adm` (
  `idgrupo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `grupos_usuarios_adm_usuarios`
--

CREATE TABLE `grupos_usuarios_adm_usuarios` (
  `idgrupo_usuario` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idgrupo` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `grupos_vendedores_vendedores`
--

CREATE TABLE `grupos_vendedores_vendedores` (
  `idgrupo_vendedor` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idvendedor` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_escolar`
--

CREATE TABLE `historico_escolar` (
  `idhistorico_escolar` int(10) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_escolar_midias`
--

CREATE TABLE `historico_escolar_midias` (
  `idhistorico_escolar_midia` int(10) NOT NULL,
  `idhistorico_escolar` int(10) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `arquivo` varchar(255) NOT NULL,
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_escolar_paginas`
--

CREATE TABLE `historico_escolar_paginas` (
  `idhistorico_escolar_paginas` int(10) NOT NULL,
  `idhistorico_escolar` int(10) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `arquivo` varchar(255) NOT NULL,
  `ordem` int(10) DEFAULT NULL,
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `interesses_mensagens_arquivos`
--

CREATE TABLE `interesses_mensagens_arquivos` (
  `idarquivo` int(10) NOT NULL,
  `idmensagem` int(10) NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(60) DEFAULT NULL,
  `arquivo_tamanho` varchar(60) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `locais_provas`
--

CREATE TABLE `locais_provas` (
  `idlocal` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `idescola` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `cep` int(8) UNSIGNED DEFAULT NULL,
  `idlogradouro` int(10) UNSIGNED DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `idestado` int(10) UNSIGNED DEFAULT NULL,
  `idcidade` int(10) UNSIGNED DEFAULT NULL,
  `gerente_nome` varchar(100) DEFAULT NULL,
  `gerente_telefone` varchar(15) DEFAULT NULL,
  `gerente_celular` varchar(15) DEFAULT NULL,
  `gerente_email` varchar(100) DEFAULT NULL,
  `informacoes` text DEFAULT NULL,
  `quantidade_pessoas_comportadas` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `locais_visitas`
--

CREATE TABLE `locais_visitas` (
  `idlocal` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `logradouros`
--

CREATE TABLE `logradouros` (
  `idlogradouro` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `log_sms`
--

CREATE TABLE `log_sms` (
  `idlog_sms` int(11) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL,
  `enviado` enum('S','N') NOT NULL DEFAULT 'N',
  `data_envio` datetime DEFAULT NULL,
  `celular` varchar(20) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `mensagem` text NOT NULL,
  `idapi` int(11) DEFAULT NULL COMMENT 'Numero que representa o ID na API SMS',
  `origem` enum('M','EA','EADM') NOT NULL,
  `idchave` int(10) UNSIGNED DEFAULT NULL,
  `cron` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'Indica se o cron status_sms.php já rodou no mesmo.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `loja_pedidos`
--

CREATE TABLE `loja_pedidos` (
  `idpedido` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idpessoa` int(10) UNSIGNED NOT NULL,
  `idoferta` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `idescola` int(10) UNSIGNED NOT NULL,
  `idturma` int(10) UNSIGNED NOT NULL,
  `valor_final` decimal(10,2) UNSIGNED NOT NULL,
  `situacao` enum('P','A','C') NOT NULL DEFAULT 'A',
  `ip` varchar(20) DEFAULT NULL,
  `navegador` varchar(100) DEFAULT NULL,
  `sistema_operacional` varchar(100) DEFAULT NULL,
  `navegador_versao` varchar(100) DEFAULT NULL,
  `user_agent` varchar(200) DEFAULT NULL,
  `modulo` enum('gestor','web') DEFAULT 'web'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mailings`
--

CREATE TABLE `mailings` (
  `idemail` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `layout` text DEFAULT NULL,
  `situacao` int(10) NOT NULL DEFAULT 0,
  `idemail_pai` int(11) DEFAULT NULL COMMENT 'caso tenha sido clonado, aqui fica o id do email referencia',
  `total_reenvio` int(10) NOT NULL DEFAULT 0,
  `corpo_email` text DEFAULT NULL,
  `corpo_sms` varchar(145) DEFAULT NULL,
  `salvar_log` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mailings_fila`
--

CREATE TABLE `mailings_fila` (
  `idemail_pessoa` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL,
  `idemail` int(10) UNSIGNED NOT NULL,
  `tipo` enum('UA','MA','PR','PE','VE','VV','ES','SI') NOT NULL,
  `idpessoa` int(10) UNSIGNED DEFAULT NULL,
  `data_abertura_email` datetime DEFAULT NULL,
  `data_abertura_web` datetime DEFAULT NULL,
  `hash` varchar(32) DEFAULT NULL,
  `enviado` enum('S','N') NOT NULL DEFAULT 'N',
  `data_envio` datetime DEFAULT NULL,
  `ip` varchar(40) DEFAULT NULL,
  `browser` varchar(100) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `nome` varchar(200) NOT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `idusuario_gestor` int(10) UNSIGNED DEFAULT NULL,
  `enviar_email` enum('S','N') NOT NULL DEFAULT 'S',
  `idfiltro` int(10) UNSIGNED DEFAULT NULL,
  `paraemail` enum('S','N') NOT NULL DEFAULT 'S' COMMENT 'Coluna para avisar que essa fila enviara para o EMAIL',
  `parasms` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'Coluna para avisar que essa fila enviara para o SMS',
  `idvisita` int(10) UNSIGNED DEFAULT NULL,
  `idvendedor` int(10) UNSIGNED DEFAULT NULL,
  `idescola` int(10) UNSIGNED DEFAULT NULL,
  `idsindicato` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mailings_fila_reenvio_historico`
--

CREATE TABLE `mailings_fila_reenvio_historico` (
  `id` int(11) NOT NULL,
  `idemail_pessoa` int(10) UNSIGNED NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mailings_filtros`
--

CREATE TABLE `mailings_filtros` (
  `idfiltro` int(10) UNSIGNED NOT NULL,
  `idemail` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `filtro` text DEFAULT NULL,
  `busca` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mailings_imagens`
--

CREATE TABLE `mailings_imagens` (
  `idemail_imagem` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL,
  `idemail` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `servidor` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `tamanho` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mantenedoras`
--

CREATE TABLE `mantenedoras` (
  `idmantenedora` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `razao_social` varchar(100) NOT NULL,
  `nome_fantasia` varchar(100) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `inscricao_estadual` varchar(100) DEFAULT NULL,
  `inscricao_municipal` varchar(100) DEFAULT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `site` varchar(50) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `cep` int(8) UNSIGNED ZEROFILL DEFAULT NULL,
  `idlogradouro` int(10) UNSIGNED DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `idestado` int(10) UNSIGNED DEFAULT NULL,
  `idcidade` int(10) UNSIGNED DEFAULT NULL,
  `logo_nome` varchar(100) DEFAULT NULL,
  `logo_servidor` varchar(100) DEFAULT NULL,
  `logo_tipo` varchar(100) DEFAULT NULL,
  `logo_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `gerente_nome` varchar(100) DEFAULT NULL,
  `gerente_telefone` varchar(15) DEFAULT NULL,
  `gerente_celular` varchar(15) DEFAULT NULL,
  `gerente_email` varchar(100) DEFAULT NULL,
  `codigo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas`
--

CREATE TABLE `matriculas` (
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idmantenedora` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `idoferta` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `idescola` int(10) UNSIGNED NOT NULL,
  `idturma` int(10) UNSIGNED NOT NULL,
  `idpessoa` int(10) UNSIGNED DEFAULT NULL,
  `idsituacao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `data_matricula` date NOT NULL,
  `data_comissao` date DEFAULT NULL,
  `data_expedicao` date DEFAULT NULL,
  `data_inicio_certificado` date DEFAULT NULL,
  `data_final_certificado` date DEFAULT NULL,
  `data_prolongada` date DEFAULT NULL,
  `aprovado_comercial` enum('S','N') NOT NULL DEFAULT 'N',
  `data_aprovado_comercial` datetime DEFAULT NULL,
  `idusuario_aprovado_comercial` int(10) UNSIGNED DEFAULT NULL,
  `financeiro_lancado` enum('S','N') NOT NULL DEFAULT 'N',
  `modulo` enum('gestor','aluno','vendedor','web','escola') NOT NULL DEFAULT 'gestor',
  `idusuario` int(10) UNSIGNED DEFAULT NULL COMMENT 'idusuario que realizou a matricula',
  `idvendedor` int(10) UNSIGNED DEFAULT NULL,
  `numero_contrato` varchar(20) DEFAULT NULL,
  `valor_contrato` decimal(10,2) DEFAULT NULL,
  `bolsa` enum('S','BP','N') NOT NULL DEFAULT 'N' COMMENT 'S = "Bolsa Total", BP = "Bolsa Parcial", N = "Não Possui Bolsa"',
  `idsolicitante` int(10) UNSIGNED DEFAULT NULL,
  `quantidade_parcelas` int(10) UNSIGNED DEFAULT NULL,
  `observacao` text DEFAULT NULL,
  `idmotivo_cancelamento` int(10) UNSIGNED DEFAULT NULL,
  `idmotivo_inativo` int(10) UNSIGNED DEFAULT NULL,
  `idempresa` int(10) UNSIGNED DEFAULT NULL,
  `ultimo_acesso_ava` datetime DEFAULT NULL,
  `total_acessos_ava` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `valor_desconto` decimal(10,2) DEFAULT NULL,
  `informacao_pagamento` varchar(100) DEFAULT NULL,
  `cancelada` enum('S','N') DEFAULT NULL,
  `desistente` enum('S','N') DEFAULT NULL,
  `trancada` enum('S','N') DEFAULT NULL,
  `tranferido` enum('S','N') DEFAULT NULL,
  `motivo` varchar(100) DEFAULT NULL,
  `curso_consluido` enum('S','N') DEFAULT NULL,
  `data_conclusao` date DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `caracteristicas` varchar(100) DEFAULT NULL,
  `data_registro` date DEFAULT NULL,
  `forma_pagamento` int(1) UNSIGNED DEFAULT NULL COMMENT '1 = Boleto, 2 = Cartão de crédito, 3 = Cartão de débito, 4 = Cheque, 5 = Dinheiro, 6 = Depósito/Transferência Bancária, 7 = Transferência bancária, 8 = Carnê',
  `idbandeira` int(10) UNSIGNED DEFAULT NULL,
  `autorizacao_cartao` varchar(40) DEFAULT NULL,
  `porcentagem` decimal(5,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `codigo_sistec` varchar(20) DEFAULT NULL,
  `codigo_diploma` varchar(30) DEFAULT NULL,
  `data_solicitacao_carteirinha` date DEFAULT NULL,
  `situacao_carteirinha` int(10) DEFAULT NULL,
  `data_negativacao` date DEFAULT NULL,
  `negativada` enum('S','N') NOT NULL DEFAULT 'N',
  `pode_aprovar` enum('S','N') DEFAULT NULL,
  `combo` enum('S','N') NOT NULL DEFAULT 'N',
  `combo_matricula` int(10) DEFAULT NULL,
  `porcentagem_manual` decimal(10,2) DEFAULT NULL,
  `cupom_nota_fiscal` varchar(30) DEFAULT NULL,
  `idpedido` int(10) UNSIGNED DEFAULT NULL,
  `rodou_script` enum('S','N','E') NOT NULL DEFAULT 'N' COMMENT 'Criado para indicar se rodou o script transferencia_oferta.php. ''E'' = Erro ao transferir de oferta',
  `faturada` enum('S','N','CUPOM') NOT NULL DEFAULT 'N' COMMENT 'Indica se essa matrícula já está em alguma fatura da escola.',
  `data_cron_lembrete_prova_final` date DEFAULT NULL COMMENT 'Data da última vez que o cron de 24_horas\\emails_automaticos\\lembrete_prova_final.php enviou e-mail para essa matrícula',
  `detran_situacao` enum('LI','NL','AL') NOT NULL DEFAULT 'LI' COMMENT 'LI = Liberado, NL = Não liberado, AL = Aguardando liberação. Situação no detran para fazer o curso',
  `detran_creditos` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'Indica se já enviou os créditos das disciplinas que serão cursadas',
  `detran_finalizar` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'DETRAN PE: Indica que foi atulizada a conclusão do curso com sucesso',
  `detran_numero` varchar(30) DEFAULT NULL COMMENT 'DETRAN SE: Indica que o aluno foi cadastro no site do DETRAN',
  `detran_certificado` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'Indiica se já enviou a liberação para gerar o certificado',
  `cod_ticket` varchar(40) DEFAULT NULL,
  `data_primeiro_acesso` datetime DEFAULT NULL,
  `limite_datavalid` int(11) NOT NULL DEFAULT 3,
  `contratos_aceitos` enum('S','N') DEFAULT 'N',
  `matricula_liberada_azure` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_alunos_historicos`
--

CREATE TABLE `matriculas_alunos_historicos` (
  `idhistorico` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED DEFAULT NULL,
  `idbloco_disciplina` int(10) UNSIGNED DEFAULT NULL,
  `acao` enum('visualizou','download','cadastrou','removeu','respondeu') NOT NULL,
  `oque` enum('objeto_rota','material','anotacao','favorito','arquivo','multimidia','video','audio','forum','tira_duvidas','avaliacoes','colegas','chat','exercicio','simulado','mensagem_instantanea','professores','enquete') NOT NULL,
  `id` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_anotacoes`
--

CREATE TABLE `matriculas_anotacoes` (
  `idanotacao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED DEFAULT NULL,
  `idbloco_disciplina` int(10) UNSIGNED NOT NULL,
  `idrota_aprendizagem` int(10) UNSIGNED NOT NULL,
  `idobjeto` int(10) UNSIGNED NOT NULL,
  `anotacao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_arquivos`
--

CREATE TABLE `matriculas_arquivos` (
  `idarquivo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `arquivo_pasta` varchar(10) DEFAULT NULL,
  `flag` varchar(100) DEFAULT NULL,
  `script` int(1) UNSIGNED NOT NULL DEFAULT 0,
  `protocolo` varchar(20) DEFAULT NULL,
  `nome_arquivo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_associados`
--

CREATE TABLE `matriculas_associados` (
  `idassociado` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idpessoa` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_avaliacoes`
--

CREATE TABLE `matriculas_avaliacoes` (
  `idprova` int(10) UNSIGNED NOT NULL,
  `idavaliacao` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `inicio` datetime NOT NULL,
  `fim` datetime DEFAULT NULL,
  `nota` decimal(3,1) UNSIGNED DEFAULT 0.0,
  `prova_corrigida` enum('S','N') NOT NULL DEFAULT 'N',
  `data_correcao` datetime DEFAULT NULL,
  `idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_avaliacoes_historicos`
--

CREATE TABLE `matriculas_avaliacoes_historicos` (
  `idhistorico` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `idprova` int(10) UNSIGNED NOT NULL,
  `idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `tipo` enum('prova','nota','observacao') NOT NULL,
  `acao` enum('abriu','respondeu','corrigiu','recorrigiu') NOT NULL,
  `de` varchar(200) DEFAULT NULL,
  `para` varchar(200) DEFAULT NULL,
  `id` int(10) UNSIGNED DEFAULT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_avaliacoes_perguntas`
--

CREATE TABLE `matriculas_avaliacoes_perguntas` (
  `id_prova_pergunta` int(10) UNSIGNED NOT NULL,
  `idprova` int(10) UNSIGNED NOT NULL,
  `idpergunta` int(10) UNSIGNED NOT NULL,
  `resposta` text DEFAULT NULL,
  `observacao` text DEFAULT NULL,
  `nota_questao` decimal(4,2) NOT NULL DEFAULT 0.00,
  `arquivo` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(50) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `arquivo_professor` varchar(100) DEFAULT NULL,
  `arquivo_professor_servidor` varchar(50) DEFAULT NULL,
  `arquivo_professor_tipo` varchar(100) DEFAULT NULL,
  `arquivo_professor_tamanho` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_avaliacoes_perguntas_opcoes_marcadas`
--

CREATE TABLE `matriculas_avaliacoes_perguntas_opcoes_marcadas` (
  `id_prova_pergunta_opcao` int(10) UNSIGNED NOT NULL,
  `id_prova_pergunta` int(10) UNSIGNED NOT NULL,
  `idopcao` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_avas_porcentagem`
--

CREATE TABLE `matriculas_avas_porcentagem` (
  `idmatricula_ava_porcentagem` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `data_ini` datetime DEFAULT NULL,
  `data_fim` datetime DEFAULT NULL,
  `porcentagem` decimal(5,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `porcentagem_manual` decimal(10,2) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_comparacoes_fotos`
--

CREATE TABLE `matriculas_comparacoes_fotos` (
  `idfoto` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `foto` varchar(100) NOT NULL,
  `tamanho` varchar(15) NOT NULL,
  `extensao` varchar(15) NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `probabilidade_datavalid` decimal(9,2) DEFAULT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  `ativo_painel` char(1) NOT NULL DEFAULT 'S',
  `json` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_contratos`
--

CREATE TABLE `matriculas_contratos` (
  `idmatricula_contrato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idcontrato` int(10) UNSIGNED DEFAULT NULL,
  `idtipo` int(10) UNSIGNED DEFAULT NULL,
  `idusuario_validou` int(10) DEFAULT NULL,
  `idusuario_assinou` int(10) DEFAULT NULL,
  `idpessoa_assinou` int(10) UNSIGNED DEFAULT NULL,
  `iddevedor_assinou` int(10) UNSIGNED DEFAULT NULL,
  `idusuario_cancelou` int(10) UNSIGNED DEFAULT NULL,
  `assinado` datetime DEFAULT NULL,
  `assinado_devedor` datetime DEFAULT NULL,
  `validado` datetime DEFAULT NULL,
  `cancelado` datetime DEFAULT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `justificativa` text DEFAULT NULL COMMENT 'Justificativa do cancelamento do contrato',
  `nao_assinado` datetime DEFAULT NULL,
  `nao_validado` datetime DEFAULT NULL,
  `arquivo` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_pasta` varchar(10) DEFAULT NULL,
  `aceito_aluno` enum('S','N') DEFAULT 'N',
  `aceito_aluno_data` datetime DEFAULT NULL,
  `script` int(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_contratos_gerados`
--

CREATE TABLE `matriculas_contratos_gerados` (
  `idmatricula_contrato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idcontrato` int(10) UNSIGNED DEFAULT NULL,
  `arquivo_pasta` varchar(10) DEFAULT NULL,
  `aceito` enum('S','N') DEFAULT 'N',
  `aceito_data` datetime DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `navegador` varchar(100) DEFAULT NULL,
  `sistema_operacional` varchar(100) DEFAULT NULL,
  `navegador_versao` varchar(100) DEFAULT NULL,
  `user_agent` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_declaracoes`
--

CREATE TABLE `matriculas_declaracoes` (
  `idmatriculadeclaracao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `iddeclaracao` int(10) UNSIGNED DEFAULT NULL,
  `idtipo` int(10) UNSIGNED DEFAULT NULL,
  `visualizacao` int(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 - Permissao da declaracao | 2 - Exibir | 3 - Nao exibir',
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `arquivo` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_pasta` varchar(10) DEFAULT NULL,
  `cod_validacao` varchar(128) NOT NULL,
  `aluno_visualiza` enum('S','N') NOT NULL DEFAULT 'N',
  `script` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_disciplinas_notas`
--

CREATE TABLE `matriculas_disciplinas_notas` (
  `idmatricula_disciplina_nota` int(10) NOT NULL,
  `idcurriculo_bloco_disciplina` int(10) NOT NULL,
  `idmatricula` int(10) NOT NULL,
  `idavaliacao` int(10) NOT NULL,
  `nota` varchar(10) NOT NULL,
  `informacoes_adicionais` int(11) NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_documentos`
--

CREATE TABLE `matriculas_documentos` (
  `iddocumento` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idtipo` int(10) UNSIGNED NOT NULL,
  `idtipo_associacao` int(10) UNSIGNED DEFAULT NULL,
  `situacao` enum('aguardando','reprovado','aprovado') NOT NULL DEFAULT 'aguardando',
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `arquivo_pasta` varchar(10) DEFAULT NULL,
  `idmotivo_reprovacao` int(10) UNSIGNED DEFAULT NULL,
  `descricao_motivo_reprovacao` text DEFAULT NULL,
  `script` int(1) NOT NULL DEFAULT 0,
  `protocolo` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_exercicios`
--

CREATE TABLE `matriculas_exercicios` (
  `idmatricula_exercicio` int(10) UNSIGNED NOT NULL,
  `idexercicio` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `inicio` datetime NOT NULL,
  `fim` datetime DEFAULT NULL,
  `nota` decimal(3,1) UNSIGNED DEFAULT 0.0,
  `corretas` int(2) UNSIGNED NOT NULL DEFAULT 0,
  `erradas` int(2) UNSIGNED NOT NULL DEFAULT 0,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_exercicios_perguntas`
--

CREATE TABLE `matriculas_exercicios_perguntas` (
  `idmatricula_exercicio_pergunta` int(10) UNSIGNED NOT NULL,
  `idmatricula_exercicio` int(10) UNSIGNED NOT NULL,
  `idpergunta` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_exercicios_perguntas_opcoes_marcadas`
--

CREATE TABLE `matriculas_exercicios_perguntas_opcoes_marcadas` (
  `idmatricula_exercicio_opcao` int(10) UNSIGNED NOT NULL,
  `idmatricula_exercicio_pergunta` int(10) UNSIGNED NOT NULL,
  `idopcao` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_historico`
--

CREATE TABLE `matriculas_historico` (
  `idmatricula_historico` int(10) NOT NULL,
  `idmatricula` int(10) NOT NULL,
  `idhistorico` int(10) NOT NULL,
  `cod_validacao` varchar(100) DEFAULT NULL,
  `data_cad` date NOT NULL,
  `ativo` enum('S','N') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_historicos`
--

CREATE TABLE `matriculas_historicos` (
  `idhistorico` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idpessoa` int(10) UNSIGNED DEFAULT NULL,
  `idvendedor` int(10) UNSIGNED DEFAULT NULL,
  `idescola` int(10) UNSIGNED DEFAULT NULL,
  `iddevedor` int(10) UNSIGNED DEFAULT NULL,
  `tipo` enum('matricula','situacao','data_matricula','data_expedicao','associado','parcela','documento','contrato','mensagem','declaracao','numero_contrato','bolsa','solicitante','valor_contrato','quantidade_parcelas','observacao','empresa','prova','notas','faturada','parcela_situacao','codigo_sistec','nome_aluno','email_aluno','cep_aluno','logradouro_aluno','endereco_aluno','bairro_aluno','numero_aluno','complemento_aluno','estado_aluno','cidade_aluno','sexo_aluno','telefone_aluno','celular_aluno','estado_civil_aluno','data_nasc_aluno','nacionalidade_aluno','naturalidade_aluno','rg_aluno','rg_orgao_emissor_aluno','rg_data_emissao_aluno','rne_aluno','filiacao_mae_aluno','filiacao_pai_aluno','situacao_carteirinha','data_solicitacao_carteirinha','data_negativacao','arquivo','data_prolongada','data_conclusao','permissao_aprovacao','oferta','escola','turma','data_registro','vendedor','forma_pagamento','idbandeira','autorizacao_cartao','data_comissao','sindicato','porcentagem_manual','cupom_nota_fiscal','combo_matricula','combo','codigo_diploma','material_didatico','detran_situacao','detran_creditos','detran_certificado','detran_finalizar','detran_numero','data_inicio_certificado','data_final_certificado','tentativas_prova','data_primeiro_acesso','zerar_tentativas_prova','cancelar_tentativa_prova','matricula_liberada_azure') NOT NULL,
  `acao` enum('cadastrou','modificou','removeu','aprovou','reprovou','validou','desvalidou','assinou','desassinou','cancelou','descancelou','enviou','aprovou_comercial','visualizou','respondeu','renegociou','transferiu','negativou','desnegativou','modificou_situacao','zerou','detran_nao_respondeu','desaprovou_comercial') NOT NULL,
  `de` varchar(200) DEFAULT NULL,
  `para` varchar(200) DEFAULT NULL,
  `id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_linksacoes_cliques`
--

CREATE TABLE `matriculas_linksacoes_cliques` (
  `idclique` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idlinkacao` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_mensagens`
--

CREATE TABLE `matriculas_mensagens` (
  `idmensagem` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idescola` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `mensagem` text DEFAULT NULL,
  `exibir_diploma` enum('S','N') DEFAULT 'N' COMMENT 'Define se uma mensagem irá aparecer no diploma do aluno ou não',
  `enviar_email` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_mensagens_arquivos`
--

CREATE TABLE `matriculas_mensagens_arquivos` (
  `idarquivo` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idmensagem` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `arquivo_nome` varchar(100) NOT NULL,
  `arquivo_servidor` varchar(100) NOT NULL,
  `arquivo_tipo` varchar(100) NOT NULL,
  `arquivo_tamanho` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_notas`
--

CREATE TABLE `matriculas_notas` (
  `idmatricula_nota` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idprova` int(10) UNSIGNED DEFAULT NULL,
  `id_solicitacao_prova` int(10) UNSIGNED DEFAULT NULL,
  `iddisciplina` int(10) UNSIGNED NOT NULL,
  `nota` decimal(4,2) UNSIGNED DEFAULT 0.00,
  `nota_eliceu` varchar(80) DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idtipo` int(10) UNSIGNED DEFAULT NULL,
  `idmodelo` int(10) UNSIGNED DEFAULT NULL,
  `flag` varchar(20) DEFAULT NULL,
  `aproveitamento_estudo` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_notas_tipos`
--

CREATE TABLE `matriculas_notas_tipos` (
  `idtipo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `sigla` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_objetos_favoritos`
--

CREATE TABLE `matriculas_objetos_favoritos` (
  `idfavorito` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idava` int(10) DEFAULT NULL,
  `idbloco_disciplina` int(10) UNSIGNED NOT NULL,
  `idrota_aprendizagem` int(10) UNSIGNED NOT NULL,
  `idobjeto` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_reconhecimentos`
--

CREATE TABLE `matriculas_reconhecimentos` (
  `idfoto` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL DEFAULT current_timestamp(),
  `foto` varchar(100) NOT NULL,
  `tamanho` varchar(15) NOT NULL,
  `extensao` varchar(15) NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `probabilidade_datavalid` decimal(9,2) DEFAULT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  `ativo_painel` char(1) NOT NULL DEFAULT 'S',
  `json` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_rotas_aprendizagem_objetos`
--

CREATE TABLE `matriculas_rotas_aprendizagem_objetos` (
  `idmatricula_rota_objeto` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `idobjeto` int(10) UNSIGNED DEFAULT NULL,
  `porcentagem` decimal(5,2) NOT NULL,
  `idchat` int(10) UNSIGNED DEFAULT NULL,
  `idforum` int(10) UNSIGNED DEFAULT NULL,
  `iddownload` int(10) UNSIGNED DEFAULT NULL,
  `idtiraduvida` int(10) UNSIGNED DEFAULT NULL,
  `idsimulado` int(10) UNSIGNED DEFAULT NULL,
  `idmensagem_instantanea` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_simulados`
--

CREATE TABLE `matriculas_simulados` (
  `idmatricula_simulado` int(10) UNSIGNED NOT NULL,
  `idsimulado` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `inicio` datetime NOT NULL,
  `fim` datetime DEFAULT NULL,
  `total_perguntas` int(11) DEFAULT NULL,
  `total_perguntas_corretas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_simulados_perguntas`
--

CREATE TABLE `matriculas_simulados_perguntas` (
  `idmatricula_simulado_pergunta` int(10) UNSIGNED NOT NULL,
  `idmatricula_simulado` int(10) UNSIGNED NOT NULL,
  `idpergunta` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_simulados_perguntas_opcoes_marcadas`
--

CREATE TABLE `matriculas_simulados_perguntas_opcoes_marcadas` (
  `idmatricula_simulado_pergunta_opcao_marcada` int(10) UNSIGNED NOT NULL,
  `idmatricula_simulado_pergunta` int(10) UNSIGNED NOT NULL,
  `idopcao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_solicitacoes_declaracoes`
--

CREATE TABLE `matriculas_solicitacoes_declaracoes` (
  `idsolicitacao_declaracao` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idmatriculadeclaracao` int(10) UNSIGNED DEFAULT NULL,
  `data_solicitacao` datetime NOT NULL,
  `data_geracao` datetime DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `situacao` enum('E','D','I') NOT NULL DEFAULT 'E' COMMENT 'E - EM ESPERA, D - DEFERIDO, I - INDEFERIDO',
  `data_cad` datetime NOT NULL,
  `iddeclaracao` int(10) UNSIGNED DEFAULT NULL,
  `motivo_cancelamento` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_workflow`
--

CREATE TABLE `matriculas_workflow` (
  `idsituacao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime DEFAULT current_timestamp(),
  `nome` varchar(100) NOT NULL,
  `inicio` enum('S','N') NOT NULL DEFAULT 'N',
  `aprovado_comercial` enum('S','N') DEFAULT NULL,
  `fim` enum('S','N') DEFAULT 'N',
  `ativa` enum('S','N') NOT NULL DEFAULT 'N',
  `aprovado` enum('N','S') NOT NULL DEFAULT 'N',
  `aprovado_pendencias` enum('N','S') NOT NULL DEFAULT 'N',
  `diploma` enum('N','S') NOT NULL DEFAULT 'N',
  `diploma_expedido` enum('S','N') NOT NULL DEFAULT 'N',
  `inativa` enum('S','N') NOT NULL DEFAULT 'N',
  `cancelada` enum('S','N') NOT NULL DEFAULT 'N',
  `posicao_x` decimal(8,2) DEFAULT NULL,
  `posicao_y` decimal(8,2) DEFAULT NULL,
  `cor_bg` varchar(10) DEFAULT NULL,
  `cor_nome` varchar(10) DEFAULT NULL,
  `idapp` varchar(20) NOT NULL,
  `sla` int(10) UNSIGNED DEFAULT NULL,
  `ordem` int(10) UNSIGNED DEFAULT NULL,
  `sigla` varchar(3) DEFAULT NULL,
  `homologar_certificado` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_workflow_acoes`
--

CREATE TABLE `matriculas_workflow_acoes` (
  `idacao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsituacao` int(10) UNSIGNED DEFAULT NULL,
  `idrelacionamento` int(10) UNSIGNED DEFAULT NULL,
  `idopcao` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_workflow_acoes_parametros`
--

CREATE TABLE `matriculas_workflow_acoes_parametros` (
  `idacaoparametro` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idacao` int(10) UNSIGNED NOT NULL,
  `idparametro` int(10) UNSIGNED NOT NULL,
  `valor` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `matriculas_workflow_relacionamentos`
--

CREATE TABLE `matriculas_workflow_relacionamentos` (
  `idrelacionamento` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsituacao_de` int(10) UNSIGNED NOT NULL,
  `idsituacao_para` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens_alerta`
--

CREATE TABLE `mensagens_alerta` (
  `idmensagem` int(10) UNSIGNED NOT NULL,
  `tipo_alerta` enum('atendimento','forum','tiraduvidas','documentospedagogicos','agendamento') CHARACTER SET utf8 DEFAULT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `idtopico` int(10) UNSIGNED DEFAULT NULL,
  `situacao_documento` enum('E','D','I') CHARACTER SET utf8 DEFAULT NULL COMMENT 'E - EM ESPERA, D - DEFERIDO, I - INDEFERIDO',
  `iddocumento` int(10) UNSIGNED DEFAULT NULL,
  `idatendimento` int(10) UNSIGNED DEFAULT NULL,
  `idsituacao_atendimento` int(10) UNSIGNED DEFAULT NULL,
  `idmensagem_instantanea` int(10) UNSIGNED DEFAULT NULL,
  `id_solicitacao_prova` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `metas`
--

CREATE TABLE `metas` (
  `idmeta` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `periodo_inicio` date DEFAULT NULL,
  `periodo_final` date DEFAULT NULL,
  `tipo` enum('QTD','VAL') NOT NULL,
  `ratio` decimal(10,2) DEFAULT NULL,
  `idusuario_processou` int(10) UNSIGNED DEFAULT NULL,
  `data_processou` datetime DEFAULT NULL,
  `exibir_curso` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_vendedor` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_ranking_vendedor` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `metas_cursos`
--

CREATE TABLE `metas_cursos` (
  `idmeta` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `mes` date NOT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `quantidade` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `metas_sindicatos`
--

CREATE TABLE `metas_sindicatos` (
  `idmeta` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `mes` date NOT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `quantidade` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `metas_vendedores`
--

CREATE TABLE `metas_vendedores` (
  `idmeta_vendedor` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL,
  `idmeta` int(10) UNSIGNED DEFAULT NULL,
  `idvendedor` int(10) UNSIGNED NOT NULL,
  `valor_qtd` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `midias_visitas`
--

CREATE TABLE `midias_visitas` (
  `idmidia` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `modelos_prova`
--

CREATE TABLE `modelos_prova` (
  `idmodelo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `monitora_adm`
--

CREATE TABLE `monitora_adm` (
  `idmonitora` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED NOT NULL,
  `idacao` int(10) UNSIGNED NOT NULL,
  `idonde` int(10) UNSIGNED NOT NULL,
  `id` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `monitora_adm_log`
--

CREATE TABLE `monitora_adm_log` (
  `idlog` int(10) UNSIGNED NOT NULL,
  `idmonitora` int(10) UNSIGNED NOT NULL,
  `campo` varchar(50) NOT NULL,
  `de` text DEFAULT NULL,
  `para` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `monitora_escola`
--

CREATE TABLE `monitora_escola` (
  `idmonitora` int(10) UNSIGNED NOT NULL,
  `idescola` int(10) UNSIGNED NOT NULL,
  `idacao` int(10) UNSIGNED NOT NULL,
  `idonde` int(10) UNSIGNED NOT NULL,
  `id` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `monitora_escola_log`
--

CREATE TABLE `monitora_escola_log` (
  `idlog` int(10) UNSIGNED NOT NULL,
  `idmonitora` int(10) UNSIGNED NOT NULL,
  `campo` varchar(50) NOT NULL,
  `de` text DEFAULT NULL,
  `para` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `monitora_pessoa`
--

CREATE TABLE `monitora_pessoa` (
  `idmonitora` int(10) UNSIGNED NOT NULL,
  `idpessoa` int(10) UNSIGNED NOT NULL,
  `idacao` int(10) UNSIGNED NOT NULL,
  `idonde` int(10) UNSIGNED NOT NULL,
  `id` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `monitora_pessoa_log`
--

CREATE TABLE `monitora_pessoa_log` (
  `idlog` int(10) UNSIGNED NOT NULL,
  `idmonitora` int(10) UNSIGNED NOT NULL,
  `campo` varchar(50) NOT NULL,
  `de` text DEFAULT NULL,
  `para` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `monitora_professor`
--

CREATE TABLE `monitora_professor` (
  `idmonitora` int(10) UNSIGNED NOT NULL,
  `idprofessor` int(10) UNSIGNED NOT NULL,
  `idacao` int(10) UNSIGNED NOT NULL,
  `idonde` int(10) UNSIGNED NOT NULL,
  `id` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `monitora_professor_log`
--

CREATE TABLE `monitora_professor_log` (
  `idlog` int(10) UNSIGNED NOT NULL,
  `idmonitora` int(10) UNSIGNED NOT NULL,
  `campo` varchar(50) NOT NULL,
  `de` text DEFAULT NULL,
  `para` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `monitora_vendedor`
--

CREATE TABLE `monitora_vendedor` (
  `idmonitora` int(10) UNSIGNED NOT NULL,
  `idvendedor` int(10) UNSIGNED NOT NULL,
  `idacao` int(10) UNSIGNED NOT NULL,
  `idonde` int(10) UNSIGNED NOT NULL,
  `id` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `monitora_vendedor_log`
--

CREATE TABLE `monitora_vendedor_log` (
  `idlog` int(10) UNSIGNED NOT NULL,
  `idmonitora` int(10) UNSIGNED NOT NULL,
  `campo` varchar(50) NOT NULL,
  `de` text DEFAULT NULL,
  `para` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `motivos_cancelamento`
--

CREATE TABLE `motivos_cancelamento` (
  `idmotivo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `retirar_comissao` enum('S','N') NOT NULL DEFAULT 'N',
  `anular_parcelas` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'Se o motivo estiver com S ao cancelar uma matricula e esse for o motivo escolhido, ira cancelar todas as parcelas em aberto',
  `cancela_automatico` enum('S','N') NOT NULL DEFAULT 'N',
  `padrao` enum('S','N') DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `motivos_cancelamento_conta`
--

CREATE TABLE `motivos_cancelamento_conta` (
  `idmotivo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `motivos_cancelamento_solicitacao_prova`
--

CREATE TABLE `motivos_cancelamento_solicitacao_prova` (
  `idmotivo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `exibir_aluno` enum('S','N') NOT NULL DEFAULT 'S',
  `descricao` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `motivos_inatividade`
--

CREATE TABLE `motivos_inatividade` (
  `idmotivo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `motivos_visitas`
--

CREATE TABLE `motivos_visitas` (
  `idmotivo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `habilitar_midia` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `murais`
--

CREATE TABLE `murais` (
  `idmural` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `resumo` text NOT NULL,
  `descricao` mediumtext NOT NULL,
  `data_de` date NOT NULL,
  `data_ate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `murais_arquivos`
--

CREATE TABLE `murais_arquivos` (
  `idmural_arquivo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL,
  `idmural` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `servidor` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `tamanho` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `murais_filas`
--

CREATE TABLE `murais_filas` (
  `idfila` int(10) UNSIGNED NOT NULL,
  `idusuario_adm` int(10) UNSIGNED DEFAULT NULL,
  `idpessoa` int(10) UNSIGNED DEFAULT NULL,
  `idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `idvendedor` int(10) UNSIGNED DEFAULT NULL,
  `idatendimento` int(10) UNSIGNED DEFAULT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `idmural` int(10) UNSIGNED NOT NULL,
  `tipo` enum('UA','PE','PO','AT','MA','VE','PL','IN') NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `data_enviado` datetime DEFAULT NULL,
  `data_lido` datetime DEFAULT NULL,
  `data_avisado` datetime DEFAULT NULL,
  `filtro` mediumtext DEFAULT NULL,
  `idsindicato` int(10) DEFAULT NULL,
  `idescola` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `murais_imagens`
--

CREATE TABLE `murais_imagens` (
  `idmural_imagem` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL,
  `idmural` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `servidor` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `tamanho` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ofertas`
--

CREATE TABLE `ofertas` (
  `idoferta` int(10) UNSIGNED NOT NULL,
  `idsituacao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `data_inicio_matricula` date NOT NULL,
  `data_fim_matricula` date NOT NULL,
  `modalidade` enum('EAD','PRE') NOT NULL DEFAULT 'EAD',
  `data_limite` date DEFAULT NULL,
  `situacao_turma` int(11) DEFAULT NULL,
  `alunos_ilimitados` enum('S','N') DEFAULT NULL,
  `data_inicio_acesso_ava` date DEFAULT NULL,
  `data_fim_acesso_ava` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ofertas_curriculos_avas`
--

CREATE TABLE `ofertas_curriculos_avas` (
  `idoferta_curriculo_ava` int(10) UNSIGNED NOT NULL,
  `idoferta` int(10) UNSIGNED NOT NULL,
  `idcurriculo` int(10) UNSIGNED NOT NULL,
  `iddisciplina` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ofertas_cursos`
--

CREATE TABLE `ofertas_cursos` (
  `idoferta_curso` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idoferta` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `matricula_liberada` enum('S','N') NOT NULL DEFAULT 'S',
  `data_inicio_aula` date DEFAULT NULL,
  `certificado` enum('S','N') DEFAULT NULL,
  `porcentagem_minima` decimal(10,2) DEFAULT NULL,
  `qtde_minima_dias` int(10) UNSIGNED DEFAULT NULL,
  `porcentagem_minima_virtual` decimal(10,2) DEFAULT NULL,
  `dias_para_prova` int(10) DEFAULT NULL,
  `idfolha` int(10) UNSIGNED DEFAULT NULL,
  `porcentagem_minima_disciplinas` int(2) UNSIGNED DEFAULT NULL,
  `possui_financeiro` enum('S','N') NOT NULL DEFAULT 'S',
  `gerar_quantidade_dias` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ofertas_cursos_escolas`
--

CREATE TABLE `ofertas_cursos_escolas` (
  `idoferta_curso_escola` int(10) UNSIGNED NOT NULL,
  `idoferta` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `idescola` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idcurriculo` int(10) UNSIGNED DEFAULT NULL,
  `dias_para_ava` int(10) UNSIGNED DEFAULT NULL,
  `data_inicio_ava` date DEFAULT NULL,
  `data_limite_ava` date DEFAULT NULL,
  `dias_para_contrato` int(10) UNSIGNED DEFAULT NULL,
  `ignorar` enum('S','N') NOT NULL DEFAULT 'N',
  `dias_para_prova` int(10) DEFAULT NULL,
  `ordem` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ofertas_cursos_sindicatos`
--

CREATE TABLE `ofertas_cursos_sindicatos` (
  `idoferta_curso_sindicato` int(10) UNSIGNED NOT NULL,
  `idoferta` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `limite` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ofertas_cursos_workflow`
--

CREATE TABLE `ofertas_cursos_workflow` (
  `idsituacao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `inicio` enum('S','N') NOT NULL DEFAULT 'N',
  `cancelada` enum('S','N') NOT NULL DEFAULT 'N',
  `posicao_x` decimal(8,2) DEFAULT NULL,
  `posicao_y` decimal(8,2) DEFAULT NULL,
  `cor_bg` varchar(10) DEFAULT NULL,
  `cor_nome` varchar(10) DEFAULT NULL,
  `idapp` varchar(20) NOT NULL,
  `sla` int(10) UNSIGNED DEFAULT NULL,
  `ordem` int(10) UNSIGNED DEFAULT NULL,
  `sigla` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ofertas_cursos_workflow_acoes`
--

CREATE TABLE `ofertas_cursos_workflow_acoes` (
  `idacao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsituacao` int(10) UNSIGNED DEFAULT NULL,
  `idrelacionamento` int(10) UNSIGNED DEFAULT NULL,
  `idopcao` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ofertas_cursos_workflow_acoes_parametros`
--

CREATE TABLE `ofertas_cursos_workflow_acoes_parametros` (
  `idacaoparametro` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idacao` int(10) UNSIGNED NOT NULL,
  `idparametro` int(10) UNSIGNED NOT NULL,
  `valor` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ofertas_cursos_workflow_relacionamentos`
--

CREATE TABLE `ofertas_cursos_workflow_relacionamentos` (
  `idrelacionamento` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsituacao_de` int(10) UNSIGNED NOT NULL,
  `idsituacao_para` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ofertas_escolas`
--

CREATE TABLE `ofertas_escolas` (
  `idoferta_escola` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idoferta` int(10) UNSIGNED NOT NULL,
  `idescola` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ofertas_turmas`
--

CREATE TABLE `ofertas_turmas` (
  `idturma` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('N','S') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idoferta` int(10) UNSIGNED NOT NULL,
  `nome` varchar(80) NOT NULL,
  `situacao_turma` int(1) UNSIGNED NOT NULL,
  `alunos_ilimitados` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ofertas_turmas_sindicatos`
--

CREATE TABLE `ofertas_turmas_sindicatos` (
  `idoferta_turma_sindicato` int(10) UNSIGNED NOT NULL,
  `idoferta` int(10) UNSIGNED NOT NULL,
  `idturma` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `ignorar` enum('S','N') DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ofertas_workflow`
--

CREATE TABLE `ofertas_workflow` (
  `idsituacao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `inicio` enum('S','N') NOT NULL DEFAULT 'N',
  `cancelada` enum('S','N') NOT NULL DEFAULT 'N',
  `posicao_x` decimal(8,2) DEFAULT NULL,
  `posicao_y` decimal(8,2) DEFAULT NULL,
  `cor_bg` varchar(10) DEFAULT NULL,
  `cor_nome` varchar(10) DEFAULT NULL,
  `idapp` varchar(20) NOT NULL,
  `sla` int(10) UNSIGNED DEFAULT NULL,
  `ordem` int(10) UNSIGNED DEFAULT NULL,
  `sigla` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ofertas_workflow_acoes`
--

CREATE TABLE `ofertas_workflow_acoes` (
  `idacao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsituacao` int(10) UNSIGNED DEFAULT NULL,
  `idrelacionamento` int(10) UNSIGNED DEFAULT NULL,
  `idopcao` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ofertas_workflow_acoes_parametros`
--

CREATE TABLE `ofertas_workflow_acoes_parametros` (
  `idacaoparametro` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idacao` int(10) UNSIGNED NOT NULL,
  `idparametro` int(10) UNSIGNED NOT NULL,
  `valor` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ofertas_workflow_relacionamentos`
--

CREATE TABLE `ofertas_workflow_relacionamentos` (
  `idrelacionamento` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsituacao_de` int(10) UNSIGNED NOT NULL,
  `idsituacao_para` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamentos_compartilhados`
--

CREATE TABLE `pagamentos_compartilhados` (
  `idpagamento` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamentos_compartilhados_matriculas`
--

CREATE TABLE `pagamentos_compartilhados_matriculas` (
  `idpagamento_matricula` int(10) UNSIGNED NOT NULL,
  `idpagamento` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagarme`
--

CREATE TABLE `pagarme` (
  `idpagarme` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idconta` int(10) UNSIGNED NOT NULL,
  `object` varchar(30) DEFAULT NULL,
  `id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('processing','authorized','paid','refunded','waiting_payment','pending_refund','refused','chargedback') NOT NULL COMMENT 'processing: transação sendo processada, authorized: transação autorizada, paid: transação paga (autorizada e capturada), refunded: transação estornada, waiting_payment: transação aguardando pagamento (status para transações criadas com boleto bancário), pending_refund: transação paga com boleto aguardando para ser estornada, refused: transação não autorizada, chargedback: transação sofreu chargeback.',
  `date_created` varchar(24) DEFAULT NULL,
  `date_updated` varchar(24) DEFAULT NULL,
  `payment_method` varchar(11) DEFAULT NULL,
  `installments` int(3) UNSIGNED DEFAULT NULL,
  `amount` int(10) UNSIGNED DEFAULT NULL,
  `authorized_amount` int(10) UNSIGNED DEFAULT NULL,
  `paid_amount` int(10) UNSIGNED DEFAULT NULL,
  `refunded_amount` int(10) UNSIGNED DEFAULT NULL,
  `authorization_code` varchar(40) DEFAULT NULL,
  `tid` varchar(40) DEFAULT NULL,
  `nsu` varchar(10) DEFAULT NULL,
  `refuse_reason` varchar(50) DEFAULT NULL,
  `status_reason` varchar(50) DEFAULT NULL,
  `acquirer_response_code` varchar(5) DEFAULT NULL,
  `acquirer_name` varchar(20) DEFAULT NULL,
  `soft_descriptor` varchar(13) DEFAULT NULL,
  `cost` int(10) UNSIGNED DEFAULT NULL,
  `postback_url` text DEFAULT NULL,
  `capture_method` varchar(10) DEFAULT NULL,
  `antifraud_score` varchar(10) DEFAULT NULL,
  `boleto_url` text DEFAULT NULL,
  `boleto_barcode` text DEFAULT NULL,
  `boleto_expiration_date` varchar(24) DEFAULT NULL,
  `referer` varchar(14) DEFAULT NULL COMMENT 'api_key = transação criada utilizando a API Key, encryption_key = transação criada utilizando a Encryption Key',
  `ip` varchar(15) DEFAULT NULL,
  `subscription_id` int(10) UNSIGNED DEFAULT NULL,
  `phone_object` varchar(30) DEFAULT NULL,
  `phone_id` int(10) UNSIGNED DEFAULT NULL,
  `phone_ddi` int(3) UNSIGNED DEFAULT NULL,
  `phone_ddd` int(3) UNSIGNED DEFAULT NULL,
  `phone_number` int(9) UNSIGNED DEFAULT NULL,
  `address_object` varchar(30) DEFAULT NULL,
  `address_id` int(10) UNSIGNED DEFAULT NULL,
  `address_street` varchar(100) DEFAULT NULL,
  `address_street_number` varchar(10) DEFAULT NULL,
  `address_neighborhood` varchar(100) DEFAULT NULL,
  `address_complementary` varchar(100) DEFAULT NULL,
  `address_zipcode` int(8) DEFAULT NULL,
  `address_country` varchar(100) DEFAULT NULL,
  `address_state` varchar(100) DEFAULT NULL,
  `address_city` varchar(100) DEFAULT NULL,
  `customer_object` varchar(30) DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_date_created` varchar(24) DEFAULT NULL,
  `customer_document_type` varchar(4) DEFAULT NULL,
  `customer_document_number` varchar(14) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `customer_born_at` varchar(10) DEFAULT NULL,
  `customer_gender` varchar(1) DEFAULT NULL,
  `card_object` varchar(30) DEFAULT NULL,
  `card_id` varchar(50) DEFAULT NULL,
  `card_date_created` varchar(24) DEFAULT NULL,
  `card_date_updated` varchar(24) DEFAULT NULL,
  `card_brand` varchar(10) DEFAULT NULL,
  `card_holder_name` varchar(100) DEFAULT NULL,
  `card_first_digits` varchar(6) DEFAULT NULL,
  `card_last_digits` varchar(4) DEFAULT NULL,
  `card_valid` varchar(5) DEFAULT NULL,
  `card_expiration_date` varchar(4) DEFAULT NULL,
  `card_country` varchar(100) DEFAULT NULL,
  `card_fingerprint` varchar(20) DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  `antifraud_metadata` text DEFAULT NULL,
  `retorno_pagarme` text DEFAULT NULL COMMENT 'Contém o retorno inteiro',
  `cron` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagarme_monitora`
--

CREATE TABLE `pagarme_monitora` (
  `idmonitora` int(11) NOT NULL,
  `data_cad` datetime NOT NULL,
  `idpagarme` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagarme_monitora_log`
--

CREATE TABLE `pagarme_monitora_log` (
  `idlog` int(10) UNSIGNED NOT NULL,
  `idmonitora` int(10) UNSIGNED NOT NULL,
  `campo` varchar(50) NOT NULL,
  `de` text DEFAULT NULL,
  `para` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagseguro`
--

CREATE TABLE `pagseguro` (
  `idpagseguro` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idconta` int(10) UNSIGNED NOT NULL,
  `date` varchar(29) DEFAULT NULL COMMENT 'Data da criação da transação',
  `code` varchar(36) DEFAULT NULL COMMENT 'Código identificador da transação',
  `reference` varchar(200) DEFAULT NULL COMMENT 'Código de referência da transação',
  `type` int(10) DEFAULT NULL COMMENT 'Tipo da transação',
  `status` int(1) NOT NULL DEFAULT 0 COMMENT 'Status da transação. 1: Aguardando pagamento, 2: Em análise, 3: Paga, 4: Disponível, 5: Em disputa, 6: Devolvida, 7: Cancelada, 8: Chargeback debitado, 9: Retenção temporária',
  `cancellationSource` enum('INTERNAL','EXTERNAL') DEFAULT NULL COMMENT 'Origem do cancelamento. INTERNAL: PagSeguro, EXTERNAL: Instituições Financeiras',
  `lastEventDate` varchar(29) DEFAULT NULL COMMENT 'Data do último evento',
  `paymentMethod_type` int(1) DEFAULT NULL COMMENT 'Tipo do meio de pagamento. 1: Cartão de crédito, 2: Boleto, 3: Débito online (TEF), 4: Saldo PagSeguro, 5: Oi Paggo, 7: Depósito em conta',
  `paymentMethod_code` int(3) DEFAULT NULL COMMENT 'Código identificador do meio de pagamento. 101: Cartão de crédito Visa, 102: Cartão de crédito MasterCard, 103: Cartão de crédito American Express, 104: Cartão de crédito Diners, 105: Cartão de crédito Hipercard, 106: Cartão de crédito Aura, 107: Cartão de crédito Elo, 108: Cartão de crédito PLENOCard, 109: Cartão de crédito PersonalCard, 110: Cartão de crédito JCB, 111: Cartão de crédito Discover, 112: Cartão de crédito BrasilCard, 113: Cartão de crédito FORTBRASIL, 114: Cartão de crédito CARDBAN, 115: Cartão de crédito VALECARD, 116: Cartão de crédito Cabal, 117: Cartão de crédito Mais!, 118: Cartão de crédito Avista, 119: Cartão de crédito GRANDCARD, 120: Cartão de crédito Sorocred, 201: Boleto Bradesco, 202: Boleto Santander, 301: Débito online Bradesco, 302: Débito online Itaú, 303: Débito online Unibanco, 304: Débito online Banco do Brasil, 305: Débito online Banco Real, 306: Débito online Banrisul, 307: Débito online HSBC, 401: Saldo PagSeguro, 501: Oi Paggo, 701: Depósito em conta - Banco do Brasil',
  `paymentLink` varchar(255) DEFAULT NULL,
  `grossAmount` decimal(9,0) DEFAULT NULL COMMENT 'Valor bruto da transação',
  `discountAmount` decimal(9,0) DEFAULT NULL COMMENT 'Valor do desconto dado',
  `feeAmount` decimal(9,0) DEFAULT NULL,
  `netAmount` decimal(9,0) DEFAULT NULL COMMENT 'Valor líquido da transação',
  `escrowEndDate` varchar(29) DEFAULT NULL COMMENT 'Data de crédito',
  `extraAmount` decimal(9,0) DEFAULT NULL COMMENT 'Valor extra',
  `installmentCount` int(3) DEFAULT NULL COMMENT 'Número de parcelas',
  `creditorFees` text DEFAULT NULL COMMENT 'Dados dos custos cobrados',
  `installmentFeeAmount` decimal(9,0) DEFAULT NULL COMMENT 'Taxa de parcelamento',
  `operationalFeeAmount` decimal(9,0) DEFAULT NULL COMMENT 'Taxa de operação',
  `intermediationRateAmount` decimal(9,0) DEFAULT NULL COMMENT 'Tarifa de intermediação',
  `intermediationFeeAmount` decimal(9,0) DEFAULT NULL COMMENT 'Tarifa de intermediação',
  `itemCount` int(9) DEFAULT NULL COMMENT 'Número de itens da transação',
  `items_item_id` varchar(100) DEFAULT NULL COMMENT 'Identificador do item',
  `items_item_description` varchar(100) DEFAULT NULL COMMENT 'Descrição do item',
  `items_item_quantity` int(3) DEFAULT NULL COMMENT 'Quantidade do item',
  `items_item_amount` decimal(9,0) DEFAULT NULL COMMENT 'Valor unitário do item',
  `sender_name` varchar(50) DEFAULT NULL COMMENT 'E-mail do comprador',
  `sender_email` varchar(60) DEFAULT NULL COMMENT 'Nome completo do comprador',
  `sender_phone_areaCode` int(2) DEFAULT NULL COMMENT 'DDD do comprador',
  `sender_phone_number` int(9) DEFAULT NULL COMMENT 'Número de telefone do comprador',
  `shipping_type` int(1) DEFAULT NULL COMMENT 'Tipo de frete. 1: Encomenda normal (PAC), 2: SEDEX, 3: Tipo de frete não especificado',
  `shipping_cost` decimal(9,0) DEFAULT NULL COMMENT 'Custo total do frete',
  `shipping_address_street` varchar(80) DEFAULT NULL COMMENT 'Nome da rua do endereço de envio',
  `shipping_address_number` varchar(20) DEFAULT NULL COMMENT 'Número do endereço de envio',
  `shipping_address_complement` varchar(40) DEFAULT NULL COMMENT 'Complemento do endereço de envio',
  `shipping_address_district` varchar(60) DEFAULT NULL COMMENT 'Bairro do endereço de envio',
  `shipping_address_city` varchar(60) DEFAULT NULL COMMENT 'Cidade do endereço de envio',
  `shipping_address_state` varchar(2) DEFAULT NULL COMMENT 'Estado do endereço de envio',
  `shipping_address_country` varchar(3) DEFAULT NULL COMMENT 'País do endereço de envio',
  `shipping_address_postalCode` int(8) DEFAULT NULL COMMENT 'CEP do endereço de envio',
  `gatewaySystem` text DEFAULT NULL COMMENT 'Contém informações do pagamento com cartão',
  `retorno_pagseguro` text DEFAULT NULL COMMENT 'Contém o retorno inteiro do PagSeguro (XML)',
  `cron` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagseguro_logs`
--

CREATE TABLE `pagseguro_logs` (
  `idlog` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `metodo_gerado` varchar(30) NOT NULL,
  `retorno` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagseguro_monitora`
--

CREATE TABLE `pagseguro_monitora` (
  `idmonitora` int(11) NOT NULL,
  `data_cad` datetime NOT NULL,
  `idpagseguro` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagseguro_monitora_log`
--

CREATE TABLE `pagseguro_monitora_log` (
  `idlog` int(10) UNSIGNED NOT NULL,
  `idmonitora` int(10) UNSIGNED NOT NULL,
  `campo` varchar(50) NOT NULL,
  `de` text DEFAULT NULL,
  `para` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `paises`
--

CREATE TABLE `paises` (
  `idpais` int(10) UNSIGNED NOT NULL,
  `sigla_iso` char(2) NOT NULL,
  `sigla_iso3` char(3) NOT NULL,
  `codigo_iso` int(3) UNSIGNED ZEROFILL NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `paises`
--

INSERT INTO `paises` (`idpais`, `sigla_iso`, `sigla_iso3`, `codigo_iso`, `nome`) VALUES
(1, 'AF', 'AFG', 004, 'Afeganistão'),
(2, 'ZA', 'ZAF', 710, 'África do Sul'),
(3, 'AX', 'ALA', 248, 'Åland, Ilhas'),
(4, 'AL', 'ALB', 008, 'Albânia'),
(5, 'DE', 'DEU', 276, 'Alemanha'),
(6, 'AD', 'AND', 020, 'Andorra'),
(7, 'AO', 'AGO', 024, 'Angola'),
(8, 'AI', 'AIA', 660, 'Anguilla'),
(9, 'AQ', 'ATA', 010, 'Antárctida'),
(10, 'AG', 'ATG', 028, 'Antigua e Barbuda'),
(11, 'AN', 'ANT', 530, 'Antilhas Holandesas'),
(12, 'SA', 'SAU', 682, 'Arábia Saudita'),
(13, 'DZ', 'DZA', 012, 'Argélia'),
(14, 'AR', 'ARG', 032, 'Argentina'),
(15, 'AM', 'ARM', 051, 'Arménia'),
(16, 'AW', 'ABW', 533, 'Aruba'),
(17, 'AU', 'AUS', 036, 'Austrália'),
(18, 'AT', 'AUT', 040, 'Áustria'),
(19, 'AZ', 'AZE', 031, 'Azerbeijão'),
(20, 'BS', 'BHS', 044, 'Bahamas'),
(21, 'BH', 'BHR', 048, 'Bahrain'),
(22, 'BD', 'BGD', 050, 'Bangladesh'),
(23, 'BB', 'BRB', 052, 'Barbados'),
(24, 'BE', 'BEL', 056, 'Bélgica'),
(25, 'BZ', 'BLZ', 084, 'Belize'),
(26, 'BJ', 'BEN', 204, 'Benin'),
(27, 'BM', 'BMU', 060, 'Bermuda'),
(28, 'BY', 'BLR', 112, 'Bielo-Rússia'),
(29, 'BO', 'BOL', 068, 'Bolívia'),
(30, 'BA', 'BIH', 070, 'Bósnia-Herzegovina'),
(31, 'BW', 'BWA', 072, 'Botswana'),
(32, 'BV', 'BVT', 074, 'Bouvet, Ilha'),
(33, 'BR', 'BRA', 076, 'Brasil'),
(34, 'BN', 'BRN', 096, 'Brunei'),
(35, 'BG', 'BGR', 100, 'Bulgária'),
(36, 'BF', 'BFA', 854, 'Burkina Faso'),
(37, 'BI', 'BDI', 108, 'Burundi'),
(38, 'BT', 'BTN', 064, 'Butão'),
(39, 'CV', 'CPV', 132, 'Cabo Verde'),
(40, 'KH', 'KHM', 116, 'Cambodja'),
(41, 'CM', 'CMR', 120, 'Camarões'),
(42, 'CA', 'CAN', 124, 'Canadá'),
(43, 'KY', 'CYM', 136, 'Cayman, Ilhas'),
(44, 'KZ', 'KAZ', 398, 'Cazaquistão'),
(45, 'CF', 'CAF', 140, 'Centro-africana, República'),
(46, 'TD', 'TCD', 148, 'Chade'),
(47, 'CZ', 'CZE', 203, 'Checa, República'),
(48, 'CL', 'CHL', 152, 'Chile'),
(49, 'CN', 'CHN', 156, 'China'),
(50, 'CY', 'CYP', 196, 'Chipre'),
(51, 'CX', 'CXR', 162, 'Christmas, Ilha'),
(52, 'CC', 'CCK', 166, 'Cocos, Ilhas'),
(53, 'CO', 'COL', 170, 'Colômbia'),
(54, 'KM', 'COM', 174, 'Comores'),
(55, 'CG', 'COG', 178, 'Congo, República do'),
(56, 'CD', 'COD', 180, 'Congo, República Democrática do (antigo Zaire)'),
(57, 'CK', 'COK', 184, 'Cook, Ilhas'),
(58, 'KR', 'KOR', 410, 'Coreia do Sul'),
(59, 'KP', 'PRK', 408, 'Coreia, República Democrática da (Coreia do Norte)'),
(60, 'CI', 'CIV', 384, 'Costa do Marfim'),
(61, 'CR', 'CRI', 188, 'Costa Rica'),
(62, 'HR', 'HRV', 191, 'Croácia'),
(63, 'CU', 'CUB', 192, 'Cuba'),
(64, 'DK', 'DNK', 208, 'Dinamarca'),
(65, 'DJ', 'DJI', 262, 'Djibouti'),
(66, 'DM', 'DMA', 212, 'Dominica'),
(67, 'DO', 'DOM', 214, 'Dominicana, República'),
(68, 'EG', 'EGY', 818, 'Egipto'),
(69, 'SV', 'SLV', 222, 'El Salvador'),
(70, 'AE', 'ARE', 784, 'Emiratos Árabes Unidos'),
(71, 'EC', 'ECU', 218, 'Equador'),
(72, 'ER', 'ERI', 232, 'Eritreia'),
(73, 'SK', 'SVK', 703, 'Eslováquia'),
(74, 'SI', 'SVN', 705, 'Eslovénia'),
(75, 'ES', 'ESP', 724, 'Espanha'),
(76, 'US', 'USA', 840, 'Estados Unidos da América'),
(77, 'EE', 'EST', 233, 'Estónia'),
(78, 'ET', 'ETH', 231, 'Etiópia'),
(79, 'FO', 'FRO', 234, 'Faroe, Ilhas'),
(80, 'FJ', 'FJI', 242, 'Fiji'),
(81, 'PH', 'PHL', 608, 'Filipinas'),
(82, 'FI', 'FIN', 246, 'Finlândia'),
(83, 'FR', 'FRA', 250, 'França'),
(84, 'GA', 'GAB', 266, 'Gabão'),
(85, 'GM', 'GMB', 270, 'Gâmbia'),
(86, 'GH', 'GHA', 288, 'Gana'),
(87, 'GE', 'GEO', 268, 'Geórgia'),
(88, 'GS', 'SGS', 239, 'Geórgia do Sul e Sandwich do Sul, Ilhas'),
(89, 'GI', 'GIB', 292, 'Gibraltar'),
(90, 'GR', 'GRC', 300, 'Grécia'),
(91, 'GD', 'GRD', 308, 'Grenada'),
(92, 'GL', 'GRL', 304, 'Gronelândia'),
(93, 'GP', 'GLP', 312, 'Guadeloupe'),
(94, 'GU', 'GUM', 316, 'Guam'),
(95, 'GT', 'GTM', 320, 'Guatemala'),
(96, 'GG', 'GGY', 831, 'Guernsey'),
(97, 'GY', 'GUY', 328, 'Guiana'),
(98, 'GF', 'GUF', 254, 'Guiana Francesa'),
(99, 'GW', 'GNB', 624, 'Guiné-Bissau'),
(100, 'GN', 'GIN', 324, 'Guiné-Conacri'),
(101, 'GQ', 'GNQ', 226, 'Guiné Equatorial'),
(102, 'HT', 'HTI', 332, 'Haiti'),
(103, 'HM', 'HMD', 334, 'Heard e Ilhas McDonald, Ilha'),
(104, 'HN', 'HND', 340, 'Honduras'),
(105, 'HK', 'HKG', 344, 'Hong Kong'),
(106, 'HU', 'HUN', 348, 'Hungria'),
(107, 'YE', 'YEM', 887, 'Iémen'),
(108, 'IN', 'IND', 356, 'Índia'),
(109, 'ID', 'IDN', 360, 'Indonésia'),
(110, 'IQ', 'IRQ', 368, 'Iraque'),
(111, 'IR', 'IRN', 364, 'Irão'),
(112, 'IE', 'IRL', 372, 'Irlanda'),
(113, 'IS', 'ISL', 352, 'Islândia'),
(114, 'IL', 'ISR', 376, 'Israel'),
(115, 'IT', 'ITA', 380, 'Itália'),
(116, 'JM', 'JAM', 388, 'Jamaica'),
(117, 'JP', 'JPN', 392, 'Japão'),
(118, 'JE', 'JEY', 832, 'Jersey'),
(119, 'JO', 'JOR', 400, 'Jordânia'),
(120, 'KI', 'KIR', 296, 'Kiribati'),
(121, 'KW', 'KWT', 414, 'Kuwait'),
(122, 'LA', 'LAO', 418, 'Laos'),
(123, 'LS', 'LSO', 426, 'Lesoto'),
(124, 'LV', 'LVA', 428, 'Letónia'),
(125, 'LB', 'LBN', 422, 'Líbano'),
(126, 'LR', 'LBR', 430, 'Libéria'),
(127, 'LY', 'LBY', 434, 'Líbia'),
(128, 'LI', 'LIE', 438, 'Liechtenstein'),
(129, 'LT', 'LTU', 440, 'Lituânia'),
(130, 'LU', 'LUX', 442, 'Luxemburgo'),
(131, 'MO', 'MAC', 446, 'Macau'),
(132, 'MK', 'MKD', 807, 'Macedónia, República da'),
(133, 'MG', 'MDG', 450, 'Madagáscar'),
(134, 'MY', 'MYS', 458, 'Malásia'),
(135, 'MW', 'MWI', 454, 'Malawi'),
(136, 'MV', 'MDV', 462, 'Maldivas'),
(137, 'ML', 'MLI', 466, 'Mali'),
(138, 'MT', 'MLT', 470, 'Malta'),
(139, 'FK', 'FLK', 238, 'Malvinas, Ilhas (Falkland)'),
(140, 'IM', 'IMN', 833, 'Man, Ilha de'),
(141, 'MP', 'MNP', 580, 'Marianas Setentrionais'),
(142, 'MA', 'MAR', 504, 'Marrocos'),
(143, 'MH', 'MHL', 584, 'Marshall, Ilhas'),
(144, 'MQ', 'MTQ', 474, 'Martinica'),
(145, 'MU', 'MUS', 480, 'Maurícia'),
(146, 'MR', 'MRT', 478, 'Mauritânia'),
(147, 'YT', 'MYT', 175, 'Mayotte'),
(148, 'UM', 'UMI', 581, 'Menores Distantes dos Estados Unidos, Ilhas'),
(149, 'MX', 'MEX', 484, 'México'),
(150, 'MM', 'MMR', 104, 'Myanmar (antiga Birmânia)'),
(151, 'FM', 'FSM', 583, 'Micronésia, Estados Federados da'),
(152, 'MZ', 'MOZ', 508, 'Moçambique'),
(153, 'MD', 'MDA', 498, 'Moldávia'),
(154, 'MC', 'MCO', 492, 'Mónaco'),
(155, 'MN', 'MNG', 496, 'Mongólia'),
(156, 'ME', 'MNE', 499, 'Montenegro'),
(157, 'MS', 'MSR', 500, 'Montserrat'),
(158, 'NA', 'NAM', 516, 'Namíbia'),
(159, 'NR', 'NRU', 520, 'Nauru'),
(160, 'NP', 'NPL', 524, 'Nepal'),
(161, 'NI', 'NIC', 558, 'Nicarágua'),
(162, 'NE', 'NER', 562, 'Níger'),
(163, 'NG', 'NGA', 566, 'Nigéria'),
(164, 'NU', 'NIU', 570, 'Niue'),
(165, 'NF', 'NFK', 574, 'Norfolk, Ilha'),
(166, 'NO', 'NOR', 578, 'Noruega'),
(167, 'NC', 'NCL', 540, 'Nova Caledónia'),
(168, 'NZ', 'NZL', 554, 'Nova Zelândia (Aotearoa)'),
(169, 'OM', 'OMN', 512, 'Oman'),
(170, 'NL', 'NLD', 528, 'Países Baixos (Holanda)'),
(171, 'PW', 'PLW', 585, 'Palau'),
(172, 'PS', 'PSE', 275, 'Palestina'),
(173, 'PA', 'PAN', 591, 'Panamá'),
(174, 'PG', 'PNG', 598, 'Papua-Nova Guiné'),
(175, 'PK', 'PAK', 586, 'Paquistão'),
(176, 'PY', 'PRY', 600, 'Paraguai'),
(177, 'PE', 'PER', 604, 'Peru'),
(178, 'PN', 'PCN', 612, 'Pitcairn'),
(179, 'PF', 'PYF', 258, 'Polinésia Francesa'),
(180, 'PL', 'POL', 616, 'Polónia'),
(181, 'PR', 'PRI', 630, 'Porto Rico'),
(182, 'PT', 'PRT', 620, 'Portugal'),
(183, 'QA', 'QAT', 634, 'Qatar'),
(184, 'KE', 'KEN', 404, 'Quénia'),
(185, 'KG', 'KGZ', 417, 'Quirguistão'),
(186, 'GB', 'GBR', 826, 'Reino Unido da Grã-Bretanha e Irlanda do Norte'),
(187, 'RE', 'REU', 638, 'Reunião'),
(188, 'RO', 'ROU', 642, 'Roménia'),
(189, 'RW', 'RWA', 646, 'Ruanda'),
(190, 'RU', 'RUS', 643, 'Rússia'),
(191, 'EH', 'ESH', 732, 'Saara Ocidental'),
(192, 'AS', 'ASM', 016, 'Samoa Americana'),
(193, 'WS', 'WSM', 882, 'Samoa (Samoa Ocidental)'),
(194, 'PM', 'SPM', 666, 'Saint Pierre et Miquelon'),
(195, 'SB', 'SLB', 090, 'Salomão, Ilhas'),
(196, 'KN', 'KNA', 659, 'São Cristóvão e Névis (Saint Kitts e Nevis)'),
(197, 'SM', 'SMR', 674, 'San Marino'),
(198, 'ST', 'STP', 678, 'São Tomé e Príncipe'),
(199, 'VC', 'VCT', 670, 'São Vicente e Granadinas'),
(200, 'SH', 'SHN', 654, 'Santa Helena'),
(201, 'LC', 'LCA', 662, 'Santa Lúcia'),
(202, 'SN', 'SEN', 686, 'Senegal'),
(203, 'SL', 'SLE', 694, 'Serra Leoa'),
(204, 'RS', 'SRB', 688, 'Sérvia'),
(205, 'SC', 'SYC', 690, 'Seychelles'),
(206, 'SG', 'SGP', 702, 'Singapura'),
(207, 'SY', 'SYR', 760, 'Síria'),
(208, 'SO', 'SOM', 706, 'Somália'),
(209, 'LK', 'LKA', 144, 'Sri Lanka'),
(210, 'SZ', 'SWZ', 748, 'Suazilândia'),
(211, 'SD', 'SDN', 736, 'Sudão'),
(212, 'SE', 'SWE', 752, 'Suécia'),
(213, 'CH', 'CHE', 756, 'Suíça'),
(214, 'SR', 'SUR', 740, 'Suriname'),
(215, 'SJ', 'SJM', 744, 'Svalbard e Jan Mayen'),
(216, 'TH', 'THA', 764, 'Tailândia'),
(217, 'TW', 'TWN', 158, 'Taiwan'),
(218, 'TJ', 'TJK', 762, 'Tajiquistão'),
(219, 'TZ', 'TZA', 834, 'Tanzânia'),
(220, 'TF', 'ATF', 260, 'Terras Austrais e Antárticas Francesas (TAAF)'),
(221, 'IO', 'IOT', 086, 'Território Britânico do Oceano Índico'),
(222, 'TL', 'TLS', 626, 'Timor-Leste'),
(223, 'TG', 'TGO', 768, 'Togo'),
(224, 'TK', 'TKL', 772, 'Toquelau'),
(225, 'TO', 'TON', 776, 'Tonga'),
(226, 'TT', 'TTO', 780, 'Trindade e Tobago'),
(227, 'TN', 'TUN', 788, 'Tunísia'),
(228, 'TC', 'TCA', 796, 'Turks e Caicos'),
(229, 'TM', 'TKM', 795, 'Turquemenistão'),
(230, 'TR', 'TUR', 792, 'Turquia'),
(231, 'TV', 'TUV', 798, 'Tuvalu'),
(232, 'UA', 'UKR', 804, 'Ucrânia'),
(233, 'UG', 'UGA', 800, 'Uganda'),
(234, 'UY', 'URY', 858, 'Uruguai'),
(235, 'UZ', 'UZB', 860, 'Usbequistão'),
(236, 'VU', 'VUT', 548, 'Vanuatu'),
(237, 'VA', 'VAT', 336, 'Vaticano'),
(238, 'VE', 'VEN', 862, 'Venezuela'),
(239, 'VN', 'VNM', 704, 'Vietname'),
(240, 'VI', 'VIR', 850, 'Virgens Americanas, Ilhas'),
(241, 'VG', 'VGB', 092, 'Virgens Britânicas, Ilhas'),
(242, 'WF', 'WLF', 876, 'Wallis e Futuna'),
(243, 'ZM', 'ZMB', 894, 'Zâmbia'),
(244, 'ZW', 'ZWE', 716, 'Zimbabwe');

-- --------------------------------------------------------

--
-- Estrutura para tabela `perguntas`
--

CREATE TABLE `perguntas` (
  `idpergunta` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` text NOT NULL,
  `critica` text DEFAULT NULL,
  `tipo` enum('O','S') NOT NULL DEFAULT 'S',
  `multipla_escolha` enum('S','N') DEFAULT NULL,
  `sentido` enum('V','H') DEFAULT NULL,
  `quantidade_colunas` int(10) UNSIGNED DEFAULT NULL,
  `espacamento_esquerda` int(10) DEFAULT NULL,
  `exercicio` enum('S','N') NOT NULL DEFAULT 'N',
  `simulado` enum('S','N') NOT NULL DEFAULT 'N',
  `avaliacao_virtual` enum('S','N') NOT NULL DEFAULT 'N',
  `avaliacao_presencial` enum('S','N') NOT NULL DEFAULT 'N',
  `dificuldade` enum('F','M','D') DEFAULT NULL,
  `iddisciplina` int(10) NOT NULL,
  `permite_anexo_resposta` enum('S','N') NOT NULL DEFAULT 'N',
  `imagem_nome` varchar(100) DEFAULT NULL,
  `imagem_servidor` varchar(100) DEFAULT NULL,
  `imagem_tipo` varchar(100) DEFAULT NULL,
  `imagem_tamanho` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `perguntas_clonar`
--

CREATE TABLE `perguntas_clonar` (
  `idpergunta_clonar` int(10) UNSIGNED NOT NULL,
  `idpergunta` int(10) UNSIGNED NOT NULL,
  `iddisciplina_de` int(10) UNSIGNED NOT NULL,
  `iddisciplina_para` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `clonada` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `perguntas_opcoes`
--

CREATE TABLE `perguntas_opcoes` (
  `idopcao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idpergunta` int(10) UNSIGNED NOT NULL,
  `nome` text NOT NULL,
  `ordem` int(2) UNSIGNED DEFAULT NULL,
  `correta` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `perguntas_pesquisas`
--

CREATE TABLE `perguntas_pesquisas` (
  `idpergunta` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(250) NOT NULL,
  `tipo` enum('O','S') NOT NULL DEFAULT 'O',
  `multipla_escolha` enum('S','N') NOT NULL DEFAULT 'N',
  `sentido` enum('V','H') DEFAULT 'V',
  `quantidade_colunas` int(10) UNSIGNED DEFAULT NULL,
  `espacamento_esquerda` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pesquisas`
--

CREATE TABLE `pesquisas` (
  `idpesquisa` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_cliente` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `layout` text DEFAULT NULL,
  `de` date DEFAULT NULL,
  `ate` date DEFAULT NULL,
  `situacao` int(10) NOT NULL DEFAULT 0,
  `idpesquisa_pai` int(11) DEFAULT NULL COMMENT 'caso tenha sido clonada, aqui fica o id da pesquisa referencia',
  `total_reenvio` int(10) NOT NULL DEFAULT 0,
  `corpo_email` text DEFAULT NULL,
  `email_padrao` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pesquisas_fila`
--

CREATE TABLE `pesquisas_fila` (
  `idpesquisa_pessoa` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL,
  `idpesquisa` int(10) UNSIGNED NOT NULL,
  `idfiltro` int(10) UNSIGNED DEFAULT NULL,
  `tipo` enum('UA','MA','PR','PE') NOT NULL,
  `idpessoa` int(10) UNSIGNED DEFAULT NULL,
  `data_abertura_email` datetime DEFAULT NULL,
  `data_abertura_web` datetime DEFAULT NULL,
  `hash` varchar(32) DEFAULT NULL,
  `enviado` enum('S','N') NOT NULL DEFAULT 'N',
  `data_envio` datetime DEFAULT NULL,
  `data_resposta` datetime DEFAULT NULL,
  `ip` varchar(40) DEFAULT NULL,
  `browser` varchar(100) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `idprofessor` int(10) UNSIGNED DEFAULT NULL,
  `idusuario_gestor` int(10) UNSIGNED DEFAULT NULL,
  `respondido_gestor` int(10) UNSIGNED DEFAULT NULL,
  `enviar_email` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pesquisas_fila_reenvio_historico`
--

CREATE TABLE `pesquisas_fila_reenvio_historico` (
  `id` int(11) NOT NULL,
  `idpesquisa_pessoa` int(10) UNSIGNED NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pesquisas_filtros`
--

CREATE TABLE `pesquisas_filtros` (
  `idfiltro` int(10) UNSIGNED NOT NULL,
  `idpesquisa` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `filtro` text DEFAULT NULL,
  `busca` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pesquisas_imagens`
--

CREATE TABLE `pesquisas_imagens` (
  `idpesquisa_imagem` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL,
  `idpesquisa` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `servidor` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `tamanho` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pesquisas_perguntas`
--

CREATE TABLE `pesquisas_perguntas` (
  `idpesquisa_pergunta` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL,
  `idpesquisa` int(10) UNSIGNED NOT NULL,
  `idpergunta` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pesquisas_perguntas_opcoes`
--

CREATE TABLE `pesquisas_perguntas_opcoes` (
  `idopcao` int(10) UNSIGNED NOT NULL,
  `numero` int(10) UNSIGNED NOT NULL,
  `titulo` text NOT NULL,
  `idpergunta` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pesquisas_respostas`
--

CREATE TABLE `pesquisas_respostas` (
  `idpesquisa_resposta` int(10) UNSIGNED NOT NULL,
  `idpesquisa_pessoa` int(10) UNSIGNED NOT NULL,
  `idpergunta` int(10) UNSIGNED NOT NULL,
  `idopcao` int(10) UNSIGNED DEFAULT NULL,
  `resposta` text DEFAULT NULL,
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pessoas`
--

CREATE TABLE `pessoas` (
  `idpessoa` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_login` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `sexo` enum('F','M') DEFAULT 'M',
  `estado_civil` int(1) UNSIGNED DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `idpais` int(10) UNSIGNED DEFAULT NULL,
  `naturalidade` varchar(100) DEFAULT NULL,
  `documento_tipo` enum('cpf','cnpj') NOT NULL DEFAULT 'cpf',
  `documento` varchar(20) DEFAULT NULL,
  `rg` varchar(21) DEFAULT NULL,
  `rg_orgao_emissor` varchar(20) DEFAULT NULL,
  `rg_data_emissao` date DEFAULT NULL,
  `rne` varchar(30) DEFAULT NULL,
  `filiacao_mae` varchar(100) DEFAULT NULL,
  `filiacao_pai` varchar(100) DEFAULT NULL,
  `cep` int(8) UNSIGNED ZEROFILL DEFAULT NULL,
  `idlogradouro` int(10) UNSIGNED DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `idestado` int(10) UNSIGNED DEFAULT NULL,
  `idcidade` int(10) UNSIGNED DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `renda_familiar` decimal(10,2) UNSIGNED DEFAULT NULL,
  `profissao` varchar(100) DEFAULT NULL,
  `senha` varchar(128) NOT NULL,
  `ultimo_acesso` datetime DEFAULT NULL,
  `ultimo_view` datetime DEFAULT NULL,
  `ultima_senha` datetime DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `banco_nome` varchar(100) DEFAULT NULL,
  `banco_agencia` varchar(20) DEFAULT NULL,
  `banco_conta` varchar(20) DEFAULT NULL,
  `banco_nome_titular` varchar(100) DEFAULT NULL,
  `banco_cpf_titular` varchar(11) DEFAULT NULL,
  `banco_observacoes` text DEFAULT NULL,
  `avatar_nome` varchar(100) DEFAULT NULL,
  `avatar_servidor` varchar(100) DEFAULT NULL,
  `avatar_tipo` varchar(100) DEFAULT NULL,
  `avatar_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `facebook` varchar(100) DEFAULT NULL,
  `disponivel_interacao` enum('S','N') NOT NULL DEFAULT 'S',
  `nacionalidade` varchar(100) DEFAULT NULL,
  `zona_residencial` int(10) DEFAULT NULL,
  `idreligiao` int(10) DEFAULT NULL,
  `raca` int(10) DEFAULT NULL,
  `empresa` varchar(100) DEFAULT NULL,
  `cep_comercial` varchar(15) DEFAULT NULL,
  `endereco_comercial` varchar(100) DEFAULT NULL,
  `numero_comercial` varchar(10) DEFAULT NULL,
  `complemento_comercial` varchar(100) DEFAULT NULL,
  `bairro_comercial` varchar(100) DEFAULT NULL,
  `idcidade_comercial` int(10) DEFAULT NULL,
  `idestado_comercial` int(10) DEFAULT NULL,
  `telefone_comercial` varchar(20) DEFAULT NULL,
  `curso_anterior_sindicato` varchar(100) DEFAULT NULL,
  `curso_anterior_idcidade` int(10) DEFAULT NULL,
  `curso_anterior_idestado` int(10) DEFAULT NULL,
  `curso_anterior_estado` varchar(100) DEFAULT NULL,
  `curso_anterior_cidade` varchar(100) DEFAULT NULL,
  `curso_anterior_pais` varchar(100) DEFAULT NULL,
  `curso_anterior` varchar(100) DEFAULT NULL,
  `curso_anterior_nome` varchar(100) DEFAULT NULL,
  `curso_anterior_ano_conclusao` int(4) DEFAULT NULL,
  `curso_anterior_carga_horaria` varchar(100) DEFAULT NULL,
  `estrangeiro` enum('S','N') DEFAULT NULL,
  `funcao` varchar(100) DEFAULT NULL,
  `biometria` enum('S','N') DEFAULT 'N',
  `biometria_arquivo` varchar(200) DEFAULT NULL,
  `razao_social` varchar(200) DEFAULT NULL,
  `nome_fantasia` varchar(100) DEFAULT NULL,
  `representante` varchar(100) DEFAULT NULL,
  `escolaridade` enum('FI','MI','MC','SI','SC','E','M','D') DEFAULT NULL COMMENT 'FI = Fundamental Incompleto, MI = Médio Incompleto, MC = Médio Completo, SI = Superior Incompleto, SC = Superior Completo, E = Especialista, M = Mestrado, D = Doutorado,',
  `cnh` varchar(100) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `data_primeira_habilitacao` date DEFAULT NULL,
  `cnh_data_emissao` date DEFAULT NULL,
  `data_validade` date DEFAULT NULL,
  `ato_punitivo` varchar(50) DEFAULT NULL,
  `receber_email` char(1) DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pessoas_acessos`
--

CREATE TABLE `pessoas_acessos` (
  `idacesso` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `idpessoa` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pessoas_acessos_matriculas`
--

CREATE TABLE `pessoas_acessos_matriculas` (
  `idacessomatricula` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `idacesso` int(10) UNSIGNED NOT NULL,
  `idpessoa` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED NOT NULL,
  `inicio` datetime NOT NULL,
  `fim` datetime NOT NULL,
  `duracao` time NOT NULL,
  `data_competencia` date DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `navegador` varchar(100) DEFAULT NULL,
  `sistema_operacional` varchar(100) DEFAULT NULL,
  `navegador_versao` varchar(100) DEFAULT NULL,
  `user_agent` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pessoas_associacoes`
--

CREATE TABLE `pessoas_associacoes` (
  `idpessoa_associacao` int(10) UNSIGNED NOT NULL,
  `idpessoa` int(10) UNSIGNED NOT NULL,
  `idpessoa_associada` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pessoas_contatos`
--

CREATE TABLE `pessoas_contatos` (
  `idcontato` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'N',
  `idpessoa` int(10) UNSIGNED NOT NULL,
  `idtipo` int(10) UNSIGNED NOT NULL,
  `valor` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pessoas_sindicatos`
--

CREATE TABLE `pessoas_sindicatos` (
  `idpessoa_sindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `idpessoa` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pessoas_validacoes`
--

CREATE TABLE `pessoas_validacoes` (
  `idvalidacao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idpessoa` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp(),
  `onde` enum('G','P','V','A') NOT NULL COMMENT 'G - Painel Gestor | P - Painel Professor | V - Painel Vendedor | A - Painel Aluno',
  `descricao` text DEFAULT NULL,
  `idpessoa_solicitou` int(10) UNSIGNED DEFAULT NULL,
  `idusuario_solicitou` int(10) UNSIGNED DEFAULT NULL,
  `idcorretor` int(10) UNSIGNED DEFAULT NULL,
  `idusuario_imobiliaria` int(10) UNSIGNED DEFAULT NULL,
  `validacao` datetime DEFAULT NULL,
  `situacao` enum('V','R','P') NOT NULL DEFAULT 'P' COMMENT 'V - Validada | R - Recusada | P - Pendente',
  `diferenca` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pessoas_validacoes_campos`
--

CREATE TABLE `pessoas_validacoes_campos` (
  `idvalidacaocampo` int(10) UNSIGNED NOT NULL,
  `idvalidacao` int(10) UNSIGNED NOT NULL,
  `campo` varchar(50) NOT NULL,
  `de` text DEFAULT NULL,
  `para` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `previsoes_gastos`
--

CREATE TABLE `previsoes_gastos` (
  `idprevisao` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED DEFAULT NULL,
  `idcategoria` int(10) UNSIGNED DEFAULT NULL,
  `idsubcategoria` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data` date DEFAULT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `idproduto` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos_fornecedores`
--

CREATE TABLE `produtos_fornecedores` (
  `idproduto_fornecedor` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idproduto` int(10) UNSIGNED NOT NULL,
  `idfornecedor` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `professores`
--

CREATE TABLE `professores` (
  `idprofessor` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_login` enum('S','N') NOT NULL DEFAULT 'S',
  `tipo` enum('P','TP','TO') NOT NULL DEFAULT 'P',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `estado_civil` int(1) UNSIGNED DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `idpais` int(10) UNSIGNED DEFAULT NULL,
  `naturalidade` varchar(100) DEFAULT NULL,
  `documento_tipo` enum('cpf','cnpj') DEFAULT 'cpf',
  `documento` varchar(20) NOT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `rg_orgao_emissor` varchar(20) DEFAULT NULL,
  `rg_data_emissao` date DEFAULT NULL,
  `cep` int(8) UNSIGNED DEFAULT NULL,
  `idlogradouro` int(10) UNSIGNED DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `idestado` int(10) UNSIGNED DEFAULT NULL,
  `idcidade` int(10) UNSIGNED DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `senha` varchar(128) NOT NULL,
  `ultimo_acesso` datetime DEFAULT NULL,
  `ultimo_view` datetime DEFAULT NULL,
  `ultima_senha` datetime NOT NULL,
  `observacoes` text DEFAULT NULL,
  `diretor_pedagogico` enum('S','N') DEFAULT NULL,
  `diretor_geral` enum('S','N') DEFAULT NULL,
  `secretario_escolar` enum('S','N') DEFAULT NULL,
  `portaria` varchar(20) DEFAULT NULL,
  `avatar_nome` varchar(100) DEFAULT NULL,
  `avatar_servidor` varchar(100) DEFAULT NULL,
  `avatar_tipo` varchar(100) DEFAULT NULL,
  `avatar_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `ativo_painel_aluno` enum('S','N') DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `professores_arquivos`
--

CREATE TABLE `professores_arquivos` (
  `idarquivo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idprofessor` int(10) UNSIGNED NOT NULL,
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `professores_avas`
--

CREATE TABLE `professores_avas` (
  `idprofessor_ava` int(10) UNSIGNED NOT NULL,
  `idprofessor` int(10) UNSIGNED NOT NULL,
  `idava` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `professores_cursos`
--

CREATE TABLE `professores_cursos` (
  `idprofessor_curso` int(10) UNSIGNED NOT NULL,
  `idprofessor` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `professores_disciplinas`
--

CREATE TABLE `professores_disciplinas` (
  `idprofessor_disciplina` int(10) UNSIGNED NOT NULL,
  `idprofessor` int(10) UNSIGNED NOT NULL,
  `iddisciplina` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `professores_ofertas`
--

CREATE TABLE `professores_ofertas` (
  `idprofessor_oferta` int(10) UNSIGNED NOT NULL,
  `idprofessor` int(10) UNSIGNED NOT NULL,
  `idoferta` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `provas_impressas`
--

CREATE TABLE `provas_impressas` (
  `idprova_impressa` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `nome` varchar(100) NOT NULL,
  `objetivas_faceis` int(2) UNSIGNED NOT NULL,
  `objetivas_intermediarias` int(2) UNSIGNED NOT NULL,
  `objetivas_dificeis` int(2) UNSIGNED NOT NULL,
  `subjetivas_faceis` int(2) UNSIGNED NOT NULL,
  `subjetivas_intermediarias` int(2) UNSIGNED NOT NULL,
  `subjetivas_dificeis` int(2) UNSIGNED NOT NULL,
  `imagem_exibicao_nome` varchar(100) DEFAULT NULL,
  `imagem_exibicao_servidor` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tipo` varchar(100) DEFAULT NULL,
  `imagem_exibicao_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `data_geracao` datetime DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `informacoes` text DEFAULT NULL,
  `idcurso` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `provas_impressas_disciplinas`
--

CREATE TABLE `provas_impressas_disciplinas` (
  `idprova_impressa_disciplina` int(10) UNSIGNED NOT NULL,
  `idprova_impressa` int(10) UNSIGNED NOT NULL,
  `iddisciplina` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('N','S') DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `provas_impressas_perguntas`
--

CREATE TABLE `provas_impressas_perguntas` (
  `idprova_impressa_pergunta` int(10) UNSIGNED NOT NULL,
  `idprova_impressa` int(10) UNSIGNED NOT NULL,
  `idpergunta` int(10) UNSIGNED NOT NULL,
  `arquivo` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(50) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('N','S') DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `provas_presenciais`
--

CREATE TABLE `provas_presenciais` (
  `id_prova_presencial` int(10) UNSIGNED NOT NULL,
  `data_realizacao` date NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `hora_realizacao_de` time DEFAULT NULL,
  `idtipo` int(2) NOT NULL,
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `hora_realizacao_ate` time DEFAULT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `provas_presenciais_escolas`
--

CREATE TABLE `provas_presenciais_escolas` (
  `id_prova_escola` int(10) UNSIGNED NOT NULL,
  `id_prova_presencial` int(10) UNSIGNED NOT NULL,
  `idescola` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `provas_presenciais_locais_provas`
--

CREATE TABLE `provas_presenciais_locais_provas` (
  `id_prova_local` int(10) UNSIGNED NOT NULL,
  `id_prova_presencial` int(10) UNSIGNED NOT NULL,
  `idlocal` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `provas_solicitadas`
--

CREATE TABLE `provas_solicitadas` (
  `id_solicitacao_prova` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `idescola` int(10) UNSIGNED DEFAULT NULL,
  `id_prova_presencial` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `situacao` enum('E','A','C') NOT NULL DEFAULT 'E' COMMENT 'E - EM ESPERA, A - AGENDADO, C - CANCELADO',
  `compareceu` enum('S','N') NOT NULL DEFAULT 'N',
  `data_cad` datetime NOT NULL,
  `idmotivo` int(10) UNSIGNED DEFAULT NULL,
  `nota` decimal(4,2) UNSIGNED DEFAULT 0.00,
  `modelo` int(2) DEFAULT NULL,
  `iddisciplina` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idlocal` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `provas_solicitadas_disciplinas`
--

CREATE TABLE `provas_solicitadas_disciplinas` (
  `id_solicitacao_prova_disciplina` int(10) UNSIGNED NOT NULL,
  `id_solicitacao_prova` int(10) UNSIGNED NOT NULL,
  `iddisciplina` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `quadros_avisos`
--

CREATE TABLE `quadros_avisos` (
  `idquadro` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `resumo` text NOT NULL,
  `descricao` mediumtext NOT NULL,
  `data_de` date NOT NULL,
  `data_ate` date DEFAULT NULL,
  `tipo_aviso` enum('cur','ger') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `quadros_avisos_cursos`
--

CREATE TABLE `quadros_avisos_cursos` (
  `idquadro_curso` int(10) UNSIGNED NOT NULL,
  `idquadro` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `quadros_avisos_escolas`
--

CREATE TABLE `quadros_avisos_escolas` (
  `idquadro_escola` int(10) UNSIGNED NOT NULL,
  `idquadro` int(10) UNSIGNED NOT NULL,
  `idescola` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `quadros_avisos_imagens`
--

CREATE TABLE `quadros_avisos_imagens` (
  `idquadro_imagem` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL,
  `idquadro` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `servidor` varchar(100) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `tamanho` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `quadros_avisos_ofertas`
--

CREATE TABLE `quadros_avisos_ofertas` (
  `idquadro_oferta` int(10) UNSIGNED NOT NULL,
  `idquadro` int(10) UNSIGNED NOT NULL,
  `idoferta` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `racas`
--

CREATE TABLE `racas` (
  `idraca` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `reconhecimento_fotos`
--

CREATE TABLE `reconhecimento_fotos` (
  `idreconhecimento` int(11) NOT NULL,
  `data_cad` datetime NOT NULL,
  `face_id` varchar(40) DEFAULT NULL,
  `face_att_age` int(11) DEFAULT NULL,
  `face_att_gender` varchar(10) DEFAULT NULL,
  `idobjetorota` int(10) UNSIGNED NOT NULL,
  `idmatricula` int(10) UNSIGNED NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'S',
  `ativo_painel` char(1) NOT NULL DEFAULT 'S',
  `idfoto_principal` int(10) UNSIGNED NOT NULL,
  `isIdentical` char(1) DEFAULT NULL,
  `idfoto_comparacao` int(10) UNSIGNED NOT NULL,
  `sucesso` char(1) NOT NULL,
  `resultado` char(1) NOT NULL,
  `confidence` decimal(1,1) DEFAULT NULL,
  `json` text NOT NULL,
  `foto_comparada_azure` varchar(200) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `regioes`
--

CREATE TABLE `regioes` (
  `idregiao` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `relacionamentos_comerciais`
--

CREATE TABLE `relacionamentos_comerciais` (
  `idrelacionamento` int(10) UNSIGNED NOT NULL,
  `email_pessoa` varchar(100) DEFAULT NULL,
  `nome_pessoa` varchar(100) DEFAULT NULL,
  `idvisita` int(10) DEFAULT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idvendedor` int(10) DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `idpessoa` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `relacionamentos_comerciais_historicos`
--

CREATE TABLE `relacionamentos_comerciais_historicos` (
  `idhistorico` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `idrelacionamento` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idvendedor` int(10) DEFAULT NULL,
  `tipo` enum('relacionamento','email_pessoa','nome_pessoa','ativo_painel','mensagem') NOT NULL,
  `acao` enum('cadastrou','modificou','removeu') NOT NULL,
  `de` varchar(200) DEFAULT NULL,
  `para` varchar(200) DEFAULT NULL,
  `id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `relacionamentos_comerciais_mensagens`
--

CREATE TABLE `relacionamentos_comerciais_mensagens` (
  `idmensagem` int(10) UNSIGNED NOT NULL,
  `idrelacionamento` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idvendedor` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `mensagem` text DEFAULT NULL,
  `proxima_acao` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `relacionamentos_comercial`
--

CREATE TABLE `relacionamentos_comercial` (
  `idmensagem` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idpessoa` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `mensagem` text DEFAULT NULL,
  `proxima_acao` date DEFAULT NULL,
  `idvendedor` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `relacionamentos_pedagogico`
--

CREATE TABLE `relacionamentos_pedagogico` (
  `idmensagem` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idpessoa` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `mensagem` text DEFAULT NULL,
  `proxima_acao` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorios`
--

CREATE TABLE `relatorios` (
  `idrelatorio` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(200) NOT NULL,
  `uri` text NOT NULL,
  `idusuario` int(10) NOT NULL,
  `modulo` varchar(25) NOT NULL,
  `ultimo_view` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `religioes`
--

CREATE TABLE `religioes` (
  `idreligiao` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `retornos`
--

CREATE TABLE `retornos` (
  `idretorno` int(10) UNSIGNED NOT NULL,
  `datacad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `processado` enum('S','N') NOT NULL DEFAULT 'N',
  `idusuario` int(10) UNSIGNED NOT NULL,
  `arquivo_servidor` varchar(80) NOT NULL,
  `arquivo_nome` varchar(120) NOT NULL,
  `arquivo_tamanho` bigint(20) NOT NULL,
  `arquivo_tipo` varchar(120) NOT NULL,
  `banco` int(5) NOT NULL COMMENT 'Banco do Retorno. 341=ITAU',
  `quantidade_processado` int(10) NOT NULL DEFAULT 0 COMMENT 'quantidade de contas processadas',
  `idfechamento` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `retornos_contas`
--

CREATE TABLE `retornos_contas` (
  `idretorno_conta` int(11) UNSIGNED NOT NULL,
  `idretorno` int(10) UNSIGNED NOT NULL,
  `idconta` int(10) UNSIGNED NOT NULL,
  `linha` tinytext CHARACTER SET latin1 NOT NULL,
  `data_ocorrencia` date DEFAULT NULL,
  `data_debito` date DEFAULT NULL,
  `mo_nosso_numero` int(10) DEFAULT NULL COMMENT 'Modalidade Nosso Número',
  `valorboleto` decimal(10,2) DEFAULT NULL,
  `valorpago` decimal(10,2) DEFAULT NULL,
  `juros` decimal(8,2) DEFAULT NULL,
  `multa` decimal(8,2) DEFAULT NULL,
  `status` enum('P','AV','PJ','PM','PA') DEFAULT 'P' COMMENT 'P para Pago, AV para abaixo do valor, PJ para Pago com Juros, PM para pagamento maior que o valor, PA para pago anteriormente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `retornos_sindicatos`
--

CREATE TABLE `retornos_sindicatos` (
  `idretorno_sindicato` int(10) UNSIGNED NOT NULL,
  `idretorno` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `sindicatos`
--

CREATE TABLE `sindicatos` (
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `idmantenedora` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `acesso_ava` enum('S','N') NOT NULL DEFAULT 'S' COMMENT 'Indica se o acesso ao AVA estará liberado para os alunos',
  `nome` varchar(100) NOT NULL,
  `nome_abreviado` varchar(20) DEFAULT NULL,
  `documento` varchar(20) NOT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `site` varchar(50) DEFAULT NULL,
  `nre` varchar(20) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `cep` int(8) UNSIGNED ZEROFILL DEFAULT NULL,
  `idlogradouro` int(10) UNSIGNED DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `idestado` int(10) UNSIGNED DEFAULT NULL,
  `idcidade` int(10) UNSIGNED DEFAULT NULL,
  `logo_nome` varchar(100) DEFAULT NULL,
  `logo_servidor` varchar(100) DEFAULT NULL,
  `logo_tipo` varchar(100) DEFAULT NULL,
  `logo_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `brasao_nome` varchar(100) DEFAULT NULL,
  `brasao_servidor` varchar(100) DEFAULT NULL,
  `brasao_tipo` varchar(100) DEFAULT NULL,
  `brasao_tamanho` int(10) DEFAULT NULL,
  `gerente_nome` varchar(100) DEFAULT NULL,
  `gerente_telefone` varchar(15) DEFAULT NULL,
  `gerente_celular` varchar(15) DEFAULT NULL,
  `gerente_email` varchar(100) DEFAULT NULL,
  `gerente_cpf` varchar(14) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `validade_mandato` date DEFAULT NULL,
  `cofesi_login` varchar(255) DEFAULT NULL,
  `cofesi_senha` varchar(255) DEFAULT NULL,
  `cofesi_hash` varchar(255) DEFAULT NULL,
  `idestado_competencia` int(11) UNSIGNED DEFAULT NULL,
  `max_parcelas` int(10) DEFAULT NULL,
  `max_boletos` int(10) DEFAULT NULL,
  `ativ_federal` int(10) DEFAULT NULL,
  `gerente_data_nasc` date DEFAULT NULL,
  `gerente_skype` varchar(100) DEFAULT NULL,
  `vice_presidente_sind_nome` varchar(100) DEFAULT NULL,
  `vice_presidente_sind_cpf` varchar(14) DEFAULT NULL,
  `vice_presidente_sind_data_nasc` date DEFAULT NULL,
  `vice_presidente_sind_email` varchar(30) DEFAULT NULL,
  `vice_presidente_sind_telefone` varchar(15) DEFAULT NULL,
  `vice_presidente_sind_celular` varchar(15) DEFAULT NULL,
  `vice_presidente_sind_inicio_mandato` date DEFAULT NULL,
  `vice_presidente_sind_termino_mandato` date DEFAULT NULL,
  `presidente_sind_nome` varchar(100) DEFAULT NULL,
  `presidente_sind_cpf` varchar(14) DEFAULT NULL,
  `presidente_sind_data_nasc` date DEFAULT NULL,
  `presidente_sind_email` varchar(30) DEFAULT NULL,
  `presidente_sind_telefone` varchar(15) DEFAULT NULL,
  `presidente_sind_celular` varchar(15) DEFAULT NULL,
  `presidente_sind_inicio_mandato` date DEFAULT NULL,
  `presidente_sind_termino_mandato` date DEFAULT NULL,
  `usar_datavalid` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `sindicatos_arquivos`
--

CREATE TABLE `sindicatos_arquivos` (
  `idarquivo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `arquivo_nome` varchar(100) DEFAULT NULL,
  `arquivo_servidor` varchar(100) DEFAULT NULL,
  `arquivo_tipo` varchar(100) DEFAULT NULL,
  `arquivo_tamanho` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `sindicatos_formas_pagamento`
--

CREATE TABLE `sindicatos_formas_pagamento` (
  `idsindicato_forma_pagamento` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) DEFAULT NULL,
  `forma_pagamento` enum('B','CC') NOT NULL COMMENT 'B = Boleto, CC = Cartão de crédito'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `sindicatos_valores_cursos`
--

CREATE TABLE `sindicatos_valores_cursos` (
  `idvalor_curso` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'N',
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `avista` decimal(10,2) DEFAULT NULL,
  `aprazo` decimal(10,2) DEFAULT NULL,
  `parcelas` int(3) DEFAULT NULL,
  `max_parcelas` int(10) DEFAULT 1,
  `max_boletos` int(10) DEFAULT NULL,
  `valor_por_matricula` decimal(10,2) DEFAULT NULL,
  `quantidade_faturas_ciclo` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `sms_automaticos_log`
--

CREATE TABLE `sms_automaticos_log` (
  `idsms_log` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idemail` int(10) UNSIGNED DEFAULT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `tipo` varchar(50) NOT NULL,
  `idpessoa` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED DEFAULT NULL,
  `idoferta` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacoes_cadastros_portal`
--

CREATE TABLE `solicitacoes_cadastros_portal` (
  `idsolicitacao` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `hash` varchar(32) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_modificacao` datetime NOT NULL,
  `tipo` enum('corretor','imobiliaria') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacoes_senhas`
--

CREATE TABLE `solicitacoes_senhas` (
  `idsolicitacao_senha` int(10) UNSIGNED NOT NULL,
  `id` int(10) UNSIGNED NOT NULL,
  `modulo` enum('gestor','professor','vendedor','aluno','escola') NOT NULL,
  `data_cad` datetime NOT NULL,
  `hash` varchar(32) NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_modificacao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitantes_bolsas`
--

CREATE TABLE `solicitantes_bolsas` (
  `idsolicitante` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `referencia` varchar(60) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `cep` int(8) UNSIGNED DEFAULT NULL,
  `idlogradouro` int(10) UNSIGNED DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `idestado` int(10) UNSIGNED DEFAULT NULL,
  `idcidade` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_contatos`
--

CREATE TABLE `tipos_contatos` (
  `idtipo` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'N',
  `nome` varchar(100) NOT NULL,
  `mascara` varchar(100) DEFAULT NULL,
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_contratos`
--

CREATE TABLE `tipos_contratos` (
  `idtipo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_documentos`
--

CREATE TABLE `tipos_documentos` (
  `idtipo` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `exibir_ava` enum('S','N') NOT NULL DEFAULT 'S' COMMENT 'Indica se será exibido para o aluno',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `obrigatorio_workflow` enum('S','N') NOT NULL DEFAULT 'N',
  `todos_cursos_obrigatorio` enum('S','N') NOT NULL DEFAULT 'N',
  `todas_sindicatos_obrigatorio` enum('S','N') NOT NULL DEFAULT 'N',
  `todas_sindicatos_obrigatorio_agendamento` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_documentos_cursos`
--

CREATE TABLE `tipos_documentos_cursos` (
  `idtipo_curso` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') DEFAULT 'S',
  `idtipo` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_documentos_sindicatos`
--

CREATE TABLE `tipos_documentos_sindicatos` (
  `idtipo_sindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') DEFAULT 'S',
  `idtipo` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_documentos_sindicatos_agendamento`
--

CREATE TABLE `tipos_documentos_sindicatos_agendamento` (
  `idtipo_sindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') DEFAULT 'S',
  `idtipo` int(10) UNSIGNED NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmas`
--

CREATE TABLE `turmas` (
  `idturma` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_adm`
--

CREATE TABLE `usuarios_adm` (
  `idusuario` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_login` enum('S','N') NOT NULL DEFAULT 'S',
  `relogin` enum('S','N') NOT NULL DEFAULT 'N',
  `idperfil` int(10) UNSIGNED DEFAULT NULL,
  `idestado` int(10) UNSIGNED DEFAULT NULL,
  `idcidade` int(10) UNSIGNED DEFAULT NULL,
  `data_cad` datetime NOT NULL,
  `idioma` enum('pt_br') NOT NULL DEFAULT 'pt_br',
  `email` varchar(100) NOT NULL,
  `senha` varchar(128) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `documento_tipo` enum('cpf','cnpj') NOT NULL DEFAULT 'cpf',
  `documento` varchar(20) NOT NULL,
  `rg` varchar(21) DEFAULT NULL,
  `rg_orgao_emissor` varchar(20) DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `departamento` varchar(50) DEFAULT NULL,
  `funcao` varchar(50) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `avatar_nome` varchar(100) DEFAULT NULL,
  `avatar_servidor` varchar(100) DEFAULT NULL,
  `avatar_tipo` varchar(100) DEFAULT NULL,
  `avatar_tamanho` int(10) UNSIGNED DEFAULT NULL,
  `ultimo_acesso` datetime DEFAULT NULL,
  `ultimo_view` datetime DEFAULT NULL,
  `ultima_senha` datetime NOT NULL,
  `validade` date DEFAULT NULL,
  `gestor_sindicato` enum('S','N') NOT NULL DEFAULT 'N',
  `idexcecao` int(10) UNSIGNED DEFAULT NULL,
  `receber_email_matricula_situacao` enum('S','N') NOT NULL DEFAULT 'N',
  `receber_email_relatorio_gerencial_segunda` enum('S','N') NOT NULL DEFAULT 'N',
  `receber_email_relatorio_gerencial_terca` enum('S','N') NOT NULL DEFAULT 'N',
  `receber_email_relatorio_gerencial_quarta` enum('S','N') NOT NULL DEFAULT 'N',
  `receber_email_relatorio_gerencial_quinta` enum('S','N') NOT NULL DEFAULT 'N',
  `receber_email_relatorio_gerencial_sexta` enum('S','N') NOT NULL DEFAULT 'N',
  `receber_email_relatorio_gerencial_sabado` enum('S','N') NOT NULL DEFAULT 'N',
  `receber_email_relatorio_gerencial_domingo` enum('S','N') NOT NULL DEFAULT 'N',
  `receber_email` char(1) DEFAULT 'S',
  `recebe_email_homologacao` enum('S','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_adm_perfis`
--

CREATE TABLE `usuarios_adm_perfis` (
  `idperfil` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `observacoes` text DEFAULT NULL,
  `permissoes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_adm_sindicatos`
--

CREATE TABLE `usuarios_adm_sindicatos` (
  `idusuario_sindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendedores`
--

CREATE TABLE `vendedores` (
  `idvendedor` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_login` enum('S','N') NOT NULL DEFAULT 'S',
  `venda_bloqueada` enum('S','N') DEFAULT 'N',
  `data_cad` datetime NOT NULL,
  `nome` varchar(100) NOT NULL,
  `documento_tipo` enum('cpf','cnpj') DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `documento` varchar(20) DEFAULT NULL,
  `cep` int(8) UNSIGNED DEFAULT NULL,
  `idlogradouro` int(10) UNSIGNED DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `idestado` int(10) UNSIGNED DEFAULT NULL,
  `idcidade` int(10) UNSIGNED DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `senha` varchar(128) DEFAULT NULL,
  `ultimo_acesso` datetime DEFAULT NULL,
  `ultimo_view` datetime DEFAULT NULL,
  `ultima_senha` datetime NOT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `rg_orgao_emissor` varchar(20) DEFAULT NULL,
  `rg_data_emissao` date DEFAULT NULL,
  `rne` varchar(300) DEFAULT NULL,
  `relogin` enum('S','N') NOT NULL DEFAULT 'N',
  `avatar_nome` varchar(100) DEFAULT NULL,
  `avatar_servidor` varchar(100) DEFAULT NULL,
  `avatar_tipo` varchar(100) DEFAULT NULL,
  `avatar_tamanho` int(10) DEFAULT NULL,
  `atendente_padrao` enum('S','N') NOT NULL DEFAULT 'N',
  `receber_email` char(1) DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendedores_contatos`
--

CREATE TABLE `vendedores_contatos` (
  `idcontato` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'N',
  `idvendedor` int(10) UNSIGNED NOT NULL,
  `idtipo` int(10) UNSIGNED NOT NULL,
  `valor` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendedores_escolas`
--

CREATE TABLE `vendedores_escolas` (
  `idvendedor_escola` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') DEFAULT NULL,
  `data_cad` datetime DEFAULT NULL,
  `idescola` int(10) UNSIGNED DEFAULT NULL,
  `idvendedor` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendedores_sindicatos`
--

CREATE TABLE `vendedores_sindicatos` (
  `idvendedor_sindicato` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `idsindicato` int(10) UNSIGNED NOT NULL,
  `idvendedor` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `videotecas`
--

CREATE TABLE `videotecas` (
  `idvideo` int(20) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `duracao` varchar(15) DEFAULT NULL,
  `tamanho` varchar(20) DEFAULT NULL,
  `arquivo` varchar(255) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `variavel` varchar(255) DEFAULT NULL,
  `pasta` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `ativo_painel` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp(),
  `idpasta` int(20) NOT NULL,
  `video_nome` varchar(245) DEFAULT NULL,
  `video_imagem` varchar(245) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `videotecas_pastas`
--

CREATE TABLE `videotecas_pastas` (
  `idpasta` int(20) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `caminho` varchar(255) DEFAULT NULL,
  `data_cad` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo` enum('N','S') DEFAULT 'S',
  `ativo_painel` enum('N','S') DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `videotecas_tags`
--

CREATE TABLE `videotecas_tags` (
  `idtag` int(20) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `data_cad` timestamp NULL DEFAULT current_timestamp(),
  `ativo` enum('N','S') DEFAULT 'S',
  `ativo_painel` enum('N','S') DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `videotecas_tags_videos`
--

CREATE TABLE `videotecas_tags_videos` (
  `idtagvideo` int(20) NOT NULL,
  `idvideo` int(20) NOT NULL,
  `idtag` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `visitas_mensagens`
--

CREATE TABLE `visitas_mensagens` (
  `idmensagem` int(10) UNSIGNED NOT NULL,
  `idvisita` int(10) UNSIGNED NOT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `idvendedor` int(10) UNSIGNED DEFAULT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `data_cad` datetime NOT NULL,
  `mensagem` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `visitas_vendedores`
--

CREATE TABLE `visitas_vendedores` (
  `idvisita` int(10) UNSIGNED NOT NULL,
  `ativo` enum('S','N') NOT NULL,
  `data_cad` datetime NOT NULL,
  `situacao` enum('EMV','MAT','SEI') NOT NULL DEFAULT 'EMV' COMMENT 'EMV = Em visita, MAT = Matriculado, SEI = Sem interesse',
  `idvendedor` int(10) UNSIGNED DEFAULT NULL,
  `idpessoa` int(10) UNSIGNED DEFAULT NULL,
  `idmidia` int(10) UNSIGNED DEFAULT NULL,
  `idlocal` int(10) UNSIGNED DEFAULT NULL,
  `idmotivo` int(10) UNSIGNED DEFAULT NULL,
  `idusuario` int(10) UNSIGNED DEFAULT NULL,
  `documento` varchar(20) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `geolocation` varchar(255) DEFAULT NULL,
  `estabelecimento` varchar(100) DEFAULT NULL,
  `funcao` varchar(100) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `idcidade` int(10) UNSIGNED DEFAULT NULL,
  `idestado` int(10) UNSIGNED DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `data_visita` date DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `motivo_visita` text DEFAULT NULL,
  `geolocalizacao_endereco` varchar(400) DEFAULT NULL,
  `geolocalizacao_cep` varchar(20) DEFAULT NULL,
  `idmatricula` int(10) UNSIGNED DEFAULT NULL,
  `email_secundario` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `visitas_vendedores_cursos`
--

CREATE TABLE `visitas_vendedores_cursos` (
  `idvisita_curso` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'S',
  `idvisita` int(10) UNSIGNED NOT NULL,
  `idcurso` int(10) UNSIGNED NOT NULL,
  `ofertado` enum('S','N') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `visitas_vendedores_iteracoes`
--

CREATE TABLE `visitas_vendedores_iteracoes` (
  `iditeracao` int(10) UNSIGNED NOT NULL,
  `data_cad` datetime NOT NULL,
  `ativo` enum('S','N') NOT NULL DEFAULT 'N',
  `idvisita` int(10) UNSIGNED NOT NULL,
  `numero` int(2) UNSIGNED NOT NULL,
  `data_visita` date NOT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`idarea`),
  ADD KEY `areas_index_ativo` (`ativo`),
  ADD KEY `areas_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `atendimentos`
--
ALTER TABLE `atendimentos`
  ADD PRIMARY KEY (`idatendimento`),
  ADD KEY `atendimentos_idpessoa` (`idpessoa`),
  ADD KEY `atendimentos_idassunto` (`idassunto`),
  ADD KEY `atendimentos_idsubassunto` (`idsubassunto`),
  ADD KEY `atendimentos_idsituacao` (`idsituacao`),
  ADD KEY `atendimentos_idcurso` (`idcurso`),
  ADD KEY `atendimentos_idmatricula` (`idmatricula`),
  ADD KEY `atendimentos_idclone` (`idclone`),
  ADD KEY `atendimentos_ativo` (`ativo`),
  ADD KEY `atendimentos_ativo_painel` (`ativo_painel`),
  ADD KEY `atendimentos_idusuario` (`idusuario`);

--
-- Índices de tabela `atendimentos_arquivos`
--
ALTER TABLE `atendimentos_arquivos`
  ADD PRIMARY KEY (`idarquivo`),
  ADD KEY `atendimentos_respostas_arquivos_idresposta` (`idresposta`);

--
-- Índices de tabela `atendimentos_assuntos`
--
ALTER TABLE `atendimentos_assuntos`
  ADD PRIMARY KEY (`idassunto`),
  ADD KEY `atendimentos_assuntos_idcheclist` (`idchecklist`),
  ADD KEY `atenimentos_assuntos_index_ativo` (`ativo`),
  ADD KEY `atenimentos_assuntos_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `atendimentos_assuntos_grupos`
--
ALTER TABLE `atendimentos_assuntos_grupos`
  ADD PRIMARY KEY (`idassunto_grupo`),
  ADD KEY `atendimentos_assuntos_grupos_idassunto` (`idassunto`),
  ADD KEY `atendimentos_assuntos_grupos_idgrupo` (`idgrupo`);

--
-- Índices de tabela `atendimentos_assuntos_subassuntos`
--
ALTER TABLE `atendimentos_assuntos_subassuntos`
  ADD PRIMARY KEY (`idsubassunto`),
  ADD KEY `atenimentos_assuntos_subassuntos_index_ativo` (`ativo`),
  ADD KEY `atenimentos_assuntos_subassuntos_index_ativo_painel` (`ativo_painel`),
  ADD KEY `atendimentos_assuntos_subassuntos_idcheclist` (`idchecklist`),
  ADD KEY `atendimentos_assuntos_subassuntos_idassunto` (`idassunto`);

--
-- Índices de tabela `atendimentos_assuntos_subassuntos_grupos`
--
ALTER TABLE `atendimentos_assuntos_subassuntos_grupos`
  ADD PRIMARY KEY (`idsubassunto_grupo`),
  ADD KEY `atendimentos_assuntos_subassuntos_grupos_idsubassunto` (`idsubassunto`),
  ADD KEY `atendimentos_assuntos_subassuntos_grupos_idgrupo` (`idgrupo`);

--
-- Índices de tabela `atendimentos_checklists_opcoes_marcados`
--
ALTER TABLE `atendimentos_checklists_opcoes_marcados`
  ADD PRIMARY KEY (`idmarcada`),
  ADD KEY `atendimentos_checklists_opcoes_marcados_idchecklist` (`idchecklist`),
  ADD KEY `atendimentos_checklists_opcoes_marcados_idopcao` (`idopcao`),
  ADD KEY `atendimentos_checklists_opcoes_marcados_idatendimento` (`idatendimento`);

--
-- Índices de tabela `atendimentos_historicos`
--
ALTER TABLE `atendimentos_historicos`
  ADD PRIMARY KEY (`idhistorico`),
  ADD KEY `atendimentos_historicos_idatendimento` (`idatendimento`),
  ADD KEY `atendimentos_historicos_idusuario` (`idusuario`),
  ADD KEY `FK_atendimentos_historicos_idpessoa` (`idpessoa`);

--
-- Índices de tabela `atendimentos_respostas`
--
ALTER TABLE `atendimentos_respostas`
  ADD PRIMARY KEY (`idresposta`),
  ADD KEY `atendimentos_respostas_idatendimento` (`idatendimento`),
  ADD KEY `atendimentos_respostas_idusuario` (`idusuario`),
  ADD KEY `atendimentos_respostas_idpessoa` (`idpessoa`),
  ADD KEY `atendimentos_respostas_idresposta_automatica` (`idresposta_automatica`),
  ADD KEY `atendimentos_ativo` (`ativo`);

--
-- Índices de tabela `atendimentos_respostas_automaticas`
--
ALTER TABLE `atendimentos_respostas_automaticas`
  ADD PRIMARY KEY (`idresposta`),
  ADD KEY `atendimentos_respostas_index_ativo` (`ativo`),
  ADD KEY `atendimentos_respostas_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `atendimentos_respostas_automaticas_assuntos`
--
ALTER TABLE `atendimentos_respostas_automaticas_assuntos`
  ADD PRIMARY KEY (`idresposta_assunto`),
  ADD KEY `atendimentos_respostas_subassuntos_index_ativo` (`ativo`),
  ADD KEY `atendimentos_respostas_subassuntos_idgrupo` (`idresposta`),
  ADD KEY `atendimentos_respostas_subassuntos_index_idresposta_idassunto` (`idresposta`,`idassunto`),
  ADD KEY `atendimentos_respostas_subassuntos_idusuario` (`idassunto`);

--
-- Índices de tabela `atendimentos_sla`
--
ALTER TABLE `atendimentos_sla`
  ADD PRIMARY KEY (`idsla`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `index_atendimentos` (`ativo_painel`);

--
-- Índices de tabela `atendimentos_workflow`
--
ALTER TABLE `atendimentos_workflow`
  ADD PRIMARY KEY (`idsituacao`),
  ADD KEY `atenimentos_assuntos_index_ativo` (`ativo`),
  ADD KEY `Index_Idapp` (`idapp`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `atendimentos_workflow_acoes`
--
ALTER TABLE `atendimentos_workflow_acoes`
  ADD PRIMARY KEY (`idacao`),
  ADD KEY `FK_atendimentos_workflow_acoes_idsituacao` (`idsituacao`),
  ADD KEY `FK_atendimentos_workflow_acoes_relacionamento` (`idrelacionamento`),
  ADD KEY `FK_atendimentos_workflow_acoes_opcao` (`idopcao`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `atendimentos_workflow_acoes_parametros`
--
ALTER TABLE `atendimentos_workflow_acoes_parametros`
  ADD PRIMARY KEY (`idacaoparametro`),
  ADD KEY `FK_atendimentos_workflow_acoes_parametros_idacao` (`idacao`),
  ADD KEY `FK_atendimentos_workflow_acoes_parametros_idparametro` (`idparametro`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `atendimentos_workflow_relacionamentos`
--
ALTER TABLE `atendimentos_workflow_relacionamentos`
  ADD PRIMARY KEY (`idrelacionamento`),
  ADD KEY `FK_atendimentos_workflow_relacionamentos_de` (`idsituacao_de`),
  ADD KEY `FK_atendimentos_workflow_relacionamentos_para` (`idsituacao_para`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `atentimentos_repostas_escolas`
--
ALTER TABLE `atentimentos_repostas_escolas`
  ADD PRIMARY KEY (`idassociacao`);

--
-- Índices de tabela `avas`
--
ALTER TABLE `avas`
  ADD PRIMARY KEY (`idava`),
  ADD KEY `avas_index_ativo` (`ativo`),
  ADD KEY `avas_index_ativo_painel` (`ativo_painel`),
  ADD KEY `idava_clone` (`idava_clone`);

--
-- Índices de tabela `avas_audios`
--
ALTER TABLE `avas_audios`
  ADD PRIMARY KEY (`idaudio`),
  ADD KEY `avas_audios_idava_idx` (`idava`);

--
-- Índices de tabela `avas_avaliacoes`
--
ALTER TABLE `avas_avaliacoes`
  ADD PRIMARY KEY (`idavaliacao`),
  ADD KEY `avas_avaliacoes_idava_idx` (`idava`);

--
-- Índices de tabela `avas_avaliacoes_disciplinas`
--
ALTER TABLE `avas_avaliacoes_disciplinas`
  ADD PRIMARY KEY (`idavaliacao_disciplina`),
  ADD KEY `avas_avaliacoes_disciplinas_idavaliacao` (`idavaliacao`),
  ADD KEY `avas_avaliacoes_disciplinas_iddisciplina` (`iddisciplina`);

--
-- Índices de tabela `avas_chats`
--
ALTER TABLE `avas_chats`
  ADD PRIMARY KEY (`idchat`),
  ADD KEY `avas_chats_idava_idx` (`idava`);

--
-- Índices de tabela `avas_conteudos`
--
ALTER TABLE `avas_conteudos`
  ADD PRIMARY KEY (`idconteudo`),
  ADD KEY `avas_conteudos_idava_idx` (`idava`);

--
-- Índices de tabela `avas_conteudos_frames`
--
ALTER TABLE `avas_conteudos_frames`
  ADD PRIMARY KEY (`idframe`),
  ADD KEY `index_idconteudo` (`idconteudo`);

--
-- Índices de tabela `avas_conteudos_linksacoes`
--
ALTER TABLE `avas_conteudos_linksacoes`
  ADD PRIMARY KEY (`idlinkacao`),
  ADD KEY `ix_idava_conteudo` (`idava_conteudo`);

--
-- Índices de tabela `avas_dicosvirtuais_pastas`
--
ALTER TABLE `avas_dicosvirtuais_pastas`
  ADD PRIMARY KEY (`id_pasta`);

--
-- Índices de tabela `avas_disciplinas`
--
ALTER TABLE `avas_disciplinas`
  ADD PRIMARY KEY (`idava_disciplina`);

--
-- Índices de tabela `avas_discosvirtuais`
--
ALTER TABLE `avas_discosvirtuais`
  ADD PRIMARY KEY (`id_discovirtual`);

--
-- Índices de tabela `avas_downloads`
--
ALTER TABLE `avas_downloads`
  ADD PRIMARY KEY (`iddownload`),
  ADD KEY `avas_downloads_idava_idx` (`idava`);

--
-- Índices de tabela `avas_downloads_pastas`
--
ALTER TABLE `avas_downloads_pastas`
  ADD PRIMARY KEY (`idpasta`);

--
-- Índices de tabela `avas_enquetes`
--
ALTER TABLE `avas_enquetes`
  ADD PRIMARY KEY (`idenquete`);

--
-- Índices de tabela `avas_enquetes_opcoes`
--
ALTER TABLE `avas_enquetes_opcoes`
  ADD PRIMARY KEY (`idopcao`);

--
-- Índices de tabela `avas_enquetes_opcoes_votos`
--
ALTER TABLE `avas_enquetes_opcoes_votos`
  ADD PRIMARY KEY (`idvoto`);

--
-- Índices de tabela `avas_exercicios`
--
ALTER TABLE `avas_exercicios`
  ADD PRIMARY KEY (`idexercicio`),
  ADD KEY `avas_avaliacoes_idava_idx` (`idava`),
  ADD KEY `FK_avas_exercicios_2` (`iddisciplina_nota`);

--
-- Índices de tabela `avas_exercicios_disciplinas`
--
ALTER TABLE `avas_exercicios_disciplinas`
  ADD PRIMARY KEY (`idexercicio_disciplina`),
  ADD KEY `avas_avaliacoes_disciplinas_iddisciplina` (`iddisciplina`),
  ADD KEY `avas_avaliacoes_disciplinas_idavaliacao` (`idexercicio`);

--
-- Índices de tabela `avas_faqs`
--
ALTER TABLE `avas_faqs`
  ADD PRIMARY KEY (`idfaq`),
  ADD KEY `avas_faqs_idava_idx` (`idava`);

--
-- Índices de tabela `avas_foruns`
--
ALTER TABLE `avas_foruns`
  ADD PRIMARY KEY (`idforum`),
  ADD KEY `avas_foruns_idava_idx` (`idava`),
  ADD KEY `FK_avas_foruns_iddisciplina` (`iddisciplina`);

--
-- Índices de tabela `avas_foruns_topicos`
--
ALTER TABLE `avas_foruns_topicos`
  ADD PRIMARY KEY (`idtopico`);

--
-- Índices de tabela `avas_foruns_topicos_assinantes`
--
ALTER TABLE `avas_foruns_topicos_assinantes`
  ADD PRIMARY KEY (`idassinatura`);

--
-- Índices de tabela `avas_foruns_topicos_assinantes_mensagens`
--
ALTER TABLE `avas_foruns_topicos_assinantes_mensagens`
  ADD PRIMARY KEY (`idassinatura_mensagem`),
  ADD KEY `idtopico` (`idtopico`),
  ADD KEY `idmatricula` (`idmatricula`),
  ADD KEY `idprofessor` (`idprofessor`);

--
-- Índices de tabela `avas_foruns_topicos_curtidas`
--
ALTER TABLE `avas_foruns_topicos_curtidas`
  ADD PRIMARY KEY (`idcurtida`);

--
-- Índices de tabela `avas_foruns_topicos_mensagens`
--
ALTER TABLE `avas_foruns_topicos_mensagens`
  ADD PRIMARY KEY (`idmensagem`);

--
-- Índices de tabela `avas_links`
--
ALTER TABLE `avas_links`
  ADD PRIMARY KEY (`idlink`),
  ADD KEY `avas_links_idava_idx` (`idava`);

--
-- Índices de tabela `avas_mensagem_instantanea`
--
ALTER TABLE `avas_mensagem_instantanea`
  ADD PRIMARY KEY (`idmensagem_instantanea`),
  ADD KEY `idava` (`idava`),
  ADD KEY `ativo` (`ativo`),
  ADD KEY `ultima_interacao` (`ultima_interacao`);

--
-- Índices de tabela `avas_mensagem_instantanea_conversas`
--
ALTER TABLE `avas_mensagem_instantanea_conversas`
  ADD PRIMARY KEY (`idmensagem_instantanea_conversa`),
  ADD KEY `idmensagem_instantanea` (`idmensagem_instantanea`),
  ADD KEY `idmensagem_instantanea_integrante` (`idmensagem_instantanea_integrante`);

--
-- Índices de tabela `avas_mensagem_instantanea_conversas_visualizar`
--
ALTER TABLE `avas_mensagem_instantanea_conversas_visualizar`
  ADD PRIMARY KEY (`idmensagem_instantanea_conversas_visualizar`),
  ADD KEY `idmensagem_instantanea_dialogo` (`idmensagem_instantanea_conversa`),
  ADD KEY `idmensagem_instantanea_integrante` (`idmensagem_instantanea_integrante`);

--
-- Índices de tabela `avas_mensagem_instantanea_integrantes`
--
ALTER TABLE `avas_mensagem_instantanea_integrantes`
  ADD PRIMARY KEY (`idmensagem_instantanea_integrante`),
  ADD KEY `ativo` (`ativo`),
  ADD KEY `idmensagem_instantanea` (`idmensagem_instantanea`),
  ADD KEY `idpessoa` (`idpessoa`),
  ADD KEY `idprofessor` (`idprofessor`);

--
-- Índices de tabela `avas_mensagens`
--
ALTER TABLE `avas_mensagens`
  ADD PRIMARY KEY (`idmensagem`);

--
-- Índices de tabela `avas_mensagens_texto`
--
ALTER TABLE `avas_mensagens_texto`
  ADD PRIMARY KEY (`idmensagem_texto`);

--
-- Índices de tabela `avas_objetos_divisores`
--
ALTER TABLE `avas_objetos_divisores`
  ADD PRIMARY KEY (`idobjeto_divisor`),
  ADD KEY `FK_avas_objetos_divisores_idava` (`idava`);

--
-- Índices de tabela `avas_perguntas`
--
ALTER TABLE `avas_perguntas`
  ADD PRIMARY KEY (`idpergunta`),
  ADD KEY `avas_perguntas_idava_idx` (`idava`);

--
-- Índices de tabela `avas_rotas_aprendizagem`
--
ALTER TABLE `avas_rotas_aprendizagem`
  ADD PRIMARY KEY (`idrota_aprendizagem`),
  ADD UNIQUE KEY `index_idava` (`idava`),
  ADD KEY `avas_rotas_apredizagem_idava_idx` (`idava`);

--
-- Índices de tabela `avas_rotas_aprendizagem_objetos`
--
ALTER TABLE `avas_rotas_aprendizagem_objetos`
  ADD PRIMARY KEY (`idobjeto`),
  ADD KEY `avas_rotas_aprendizagem_objetos_idrota_aprendizagem` (`idrota_aprendizagem`),
  ADD KEY `avas_rotas_aprendizagem_objetos_idaudio` (`idaudio`),
  ADD KEY `avas_rotas_aprendizagem_objetos_idconteudo` (`idconteudo`),
  ADD KEY `avas_rotas_aprendizagem_objetos_iddownload` (`iddownload`),
  ADD KEY `avas_rotas_aprendizagem_objetos_idlink` (`idlink`),
  ADD KEY `avas_rotas_aprendizagem_objetos_idpergunta` (`idpergunta`),
  ADD KEY `avas_rotas_aprendizagem_objetos_idvideo` (`idvideo`),
  ADD KEY `avas_rotas_aprendizagem_objetos_idquiz` (`idsimulado`),
  ADD KEY `avas_rotas_aprendizagem_objetos_idobjeto_divisor` (`idobjeto_divisor`);

--
-- Índices de tabela `avas_simulados`
--
ALTER TABLE `avas_simulados`
  ADD PRIMARY KEY (`idsimulado`),
  ADD KEY `avas_avaliacoes_idava_idx` (`idava`);

--
-- Índices de tabela `avas_simulados_disciplinas`
--
ALTER TABLE `avas_simulados_disciplinas`
  ADD PRIMARY KEY (`idsimulado_disciplina`),
  ADD KEY `avas_avaliacoes_disciplinas_idavaliacao` (`idsimulado`),
  ADD KEY `avas_avaliacoes_disciplinas_iddisciplina` (`iddisciplina`);

--
-- Índices de tabela `avas_tiraduvidas`
--
ALTER TABLE `avas_tiraduvidas`
  ADD PRIMARY KEY (`idtiraduvida`),
  ADD KEY `sinalizador_professor` (`sinalizador_professor`,`sinalizador_aluno`);

--
-- Índices de tabela `avas_tiraduvidas_categorias`
--
ALTER TABLE `avas_tiraduvidas_categorias`
  ADD PRIMARY KEY (`idcategoria`),
  ADD KEY `avas_tiraduvidas_categoria_index_ativo` (`ativo`),
  ADD KEY `avas_tiraduvidas_categoria_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `avas_tiraduvidas_categorias_professores`
--
ALTER TABLE `avas_tiraduvidas_categorias_professores`
  ADD PRIMARY KEY (`idcategoria_professor`),
  ADD KEY `idcategoria` (`idcategoria`),
  ADD KEY `idprofessor` (`idprofessor`);

--
-- Índices de tabela `avas_tiraduvidas_mensagens`
--
ALTER TABLE `avas_tiraduvidas_mensagens`
  ADD PRIMARY KEY (`idmensagem`);

--
-- Índices de tabela `avas_tira_duvidas`
--
ALTER TABLE `avas_tira_duvidas`
  ADD PRIMARY KEY (`idduvida`),
  ADD KEY `avas_comunicadores_idava_idx` (`idava`);

--
-- Índices de tabela `avas_videotecas`
--
ALTER TABLE `avas_videotecas`
  ADD PRIMARY KEY (`idvideo`);

--
-- Índices de tabela `bancos`
--
ALTER TABLE `bancos`
  ADD PRIMARY KEY (`idbanco`),
  ADD KEY `bancos_index_ativo` (`ativo`),
  ADD KEY `bancos_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `bandeiras_cartoes`
--
ALTER TABLE `bandeiras_cartoes`
  ADD PRIMARY KEY (`idbandeira`),
  ADD KEY `bandeiras_cartoes_index_ativo` (`ativo`),
  ADD KEY `bandeiras_cartoes_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `banners_ava_aluno`
--
ALTER TABLE `banners_ava_aluno`
  ADD PRIMARY KEY (`idbanner`);

--
-- Índices de tabela `banners_ava_aluno_dias`
--
ALTER TABLE `banners_ava_aluno_dias`
  ADD PRIMARY KEY (`idbanner_dia`),
  ADD KEY `idbanner` (`idbanner`);

--
-- Índices de tabela `banners_escolas`
--
ALTER TABLE `banners_escolas`
  ADD PRIMARY KEY (`idbanner_escola`),
  ADD KEY `banners_escolas_idbanner` (`idbanner`),
  ADD KEY `banners_escolas_idescola` (`idescola`);

--
-- Índices de tabela `banners_sindicatos`
--
ALTER TABLE `banners_sindicatos`
  ADD PRIMARY KEY (`idbanner_sindicato`),
  ADD KEY `banners_sindicatos_idbanner` (`idbanner`),
  ADD KEY `banners_sindicatos_idsindicato` (`idsindicato`);

--
-- Índices de tabela `cartoes`
--
ALTER TABLE `cartoes`
  ADD PRIMARY KEY (`idcartao`);

--
-- Índices de tabela `cartoes_sindicatos`
--
ALTER TABLE `cartoes_sindicatos`
  ADD PRIMARY KEY (`idcartao_sindicato`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `contas_correntes_sindicatos_idconta_corrente` (`idcartao`),
  ADD KEY `contas_correntes_sindicatos_idsindicato` (`idsindicato`);

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`idcategoria`),
  ADD KEY `categorias_index_ativo` (`ativo`),
  ADD KEY `categorias_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `categorias_subcategorias`
--
ALTER TABLE `categorias_subcategorias`
  ADD PRIMARY KEY (`idsubcategoria`),
  ADD KEY `categorias_subcategorias_index_ativo` (`ativo`),
  ADD KEY `categorias_subcategorias_index_ativo_painel` (`ativo_painel`),
  ADD KEY `categorias_subcategorias_idcategoria` (`idcategoria`);

--
-- Índices de tabela `categorias_subcategorias_sindicatos`
--
ALTER TABLE `categorias_subcategorias_sindicatos`
  ADD PRIMARY KEY (`idassociacao`),
  ADD KEY `ativo` (`ativo`);

--
-- Índices de tabela `centros_custos`
--
ALTER TABLE `centros_custos`
  ADD PRIMARY KEY (`idcentro_custo`),
  ADD KEY `centros_custos_index_ativo` (`ativo`),
  ADD KEY `centros_custos_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `centros_custos_sindicatos`
--
ALTER TABLE `centros_custos_sindicatos`
  ADD PRIMARY KEY (`idcentro_custo_sindicato`),
  ADD KEY `centros_custos_sindicatos_idsindicato` (`idsindicato`),
  ADD KEY `centros_custos_sindicatos_idcentro_custo` (`idcentro_custo`);

--
-- Índices de tabela `certificados`
--
ALTER TABLE `certificados`
  ADD PRIMARY KEY (`idcertificado`);

--
-- Índices de tabela `certificados_escolas`
--
ALTER TABLE `certificados_escolas`
  ADD PRIMARY KEY (`idcertificado_escola`);

--
-- Índices de tabela `certificados_midias`
--
ALTER TABLE `certificados_midias`
  ADD PRIMARY KEY (`idcertificado_midia`);

--
-- Índices de tabela `certificados_paginas`
--
ALTER TABLE `certificados_paginas`
  ADD PRIMARY KEY (`certificados_paginas`);

--
-- Índices de tabela `cfcs_valores_cursos`
--
ALTER TABLE `cfcs_valores_cursos`
  ADD PRIMARY KEY (`idvalor_curso`),
  ADD KEY `FK_escolas_valores_cursos_escolas` (`idcfc`),
  ADD KEY `Index_valores_cursos_ativo` (`ativo`),
  ADD KEY `FK_escolas_valores_cursos_idcurso` (`idcurso`);

--
-- Índices de tabela `cfc_mensagens`
--
ALTER TABLE `cfc_mensagens`
  ADD PRIMARY KEY (`idmensagem`);

--
-- Índices de tabela `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`idchat`);

--
-- Índices de tabela `chats_acoes`
--
ALTER TABLE `chats_acoes`
  ADD PRIMARY KEY (`idchat_acao`);

--
-- Índices de tabela `chats_mensagens`
--
ALTER TABLE `chats_mensagens`
  ADD PRIMARY KEY (`idchat_mensagem`);

--
-- Índices de tabela `chats_pessoas`
--
ALTER TABLE `chats_pessoas`
  ADD PRIMARY KEY (`idchat_pessoa`);

--
-- Índices de tabela `checklists`
--
ALTER TABLE `checklists`
  ADD PRIMARY KEY (`idchecklist`),
  ADD KEY `checklists_index_ativo_ativo_painel` (`ativo`,`ativo_painel`),
  ADD KEY `checklists_index_ativo` (`ativo`),
  ADD KEY `checklists_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `checklists_opcoes`
--
ALTER TABLE `checklists_opcoes`
  ADD PRIMARY KEY (`idopcao`),
  ADD KEY `checklists_opcoes_ativo` (`ativo`),
  ADD KEY `checklists_opcoes_idchecklist` (`idchecklist`);

--
-- Índices de tabela `cheques`
--
ALTER TABLE `cheques`
  ADD PRIMARY KEY (`idcheque`),
  ADD KEY `cheques_index_ativo` (`ativo`),
  ADD KEY `cheques_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `cheques_alineas`
--
ALTER TABLE `cheques_alineas`
  ADD PRIMARY KEY (`idcheque_alinea`),
  ADD KEY `cheques_alineas_index_ativo` (`ativo`),
  ADD KEY `cheques_alineas_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `cidades`
--
ALTER TABLE `cidades`
  ADD PRIMARY KEY (`idcidade`),
  ADD KEY `cidades_idestado` (`idestado`),
  ADD KEY `codigo_uf` (`codigo_uf`);

--
-- Índices de tabela `cobrancas_log`
--
ALTER TABLE `cobrancas_log`
  ADD PRIMARY KEY (`idcobranca`),
  ADD KEY `cobrancas_idusuario` (`idusuario`),
  ADD KEY `cobrancas_idmatricula` (`idmatricula`);

--
-- Índices de tabela `cofeci_log`
--
ALTER TABLE `cofeci_log`
  ADD PRIMARY KEY (`idcofecilog`);

--
-- Índices de tabela `comissoes_competencias`
--
ALTER TABLE `comissoes_competencias`
  ADD PRIMARY KEY (`idcompetencia`),
  ADD KEY `comissoes_competencias_idsindicato` (`idsindicato`);

--
-- Índices de tabela `comissoes_competencias_cursos`
--
ALTER TABLE `comissoes_competencias_cursos`
  ADD PRIMARY KEY (`idcompetencia_curso`),
  ADD KEY `comissoes_competencias_cursos_idcompetencia` (`idcompetencia`),
  ADD KEY `comissoes_competencias_cursos_idregra` (`idregra`),
  ADD KEY `comissoes_competencias_cursos_idcurso` (`idcurso`);

--
-- Índices de tabela `comissoes_competencias_sindicatos_cursos`
--
ALTER TABLE `comissoes_competencias_sindicatos_cursos`
  ADD PRIMARY KEY (`idcompetencia_oferta_curso`),
  ADD KEY `comissoes_competencias_cursos_idcompetencia` (`idcompetencia`),
  ADD KEY `comissoes_competencias_cursos_idcurso_sindicato` (`idcurso_sindicato`),
  ADD KEY `comissoes_competencias_cursos_idregra` (`idregra`);

--
-- Índices de tabela `comissoes_regras`
--
ALTER TABLE `comissoes_regras`
  ADD PRIMARY KEY (`idregra`),
  ADD KEY `comissoes_regras_index_ativo` (`ativo`),
  ADD KEY `comissoes_regras_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `comissoes_regras_cursos`
--
ALTER TABLE `comissoes_regras_cursos`
  ADD PRIMARY KEY (`idregra_curso`),
  ADD KEY `comissoes_regras_cursos_idregra` (`idregra`),
  ADD KEY `comissoes_regras_cursos_idcurso` (`idcurso`);

--
-- Índices de tabela `comissoes_regras_sindicatos`
--
ALTER TABLE `comissoes_regras_sindicatos`
  ADD PRIMARY KEY (`idregra_sindicato`),
  ADD KEY `comissoes_regras_sindicatos_idregra` (`idregra`),
  ADD KEY `comissoes_regras_sindicatos_idsindicato` (`idsindicato`);

--
-- Índices de tabela `comissoes_regras_valores`
--
ALTER TABLE `comissoes_regras_valores`
  ADD PRIMARY KEY (`idvalor`);

--
-- Índices de tabela `contas`
--
ALTER TABLE `contas`
  ADD PRIMARY KEY (`idconta`),
  ADD KEY `contas_index_ativo` (`ativo`),
  ADD KEY `contas_index_ativo_painel` (`ativo_painel`),
  ADD KEY `contas_idsituacao` (`idsituacao`),
  ADD KEY `contas_idconta_corrente` (`idconta_corrente`),
  ADD KEY `contas_idrelacao` (`idrelacao`),
  ADD KEY `contas_idbanco` (`idbanco`),
  ADD KEY `contas_idbandeira` (`idbandeira`),
  ADD KEY `contas_idmatricula` (`idmatricula`),
  ADD KEY `idfechamento` (`idfechamento`),
  ADD KEY `contas_idevento` (`idevento`),
  ADD KEY `id1_cheque_alinea` (`id1_cheque_alinea`,`id2_cheque_alinea`,`id3_cheque_alinea`),
  ADD KEY `idcheque` (`idcheque`),
  ADD KEY `idpagamento_compartilhado` (`idpagamento_compartilhado`),
  ADD KEY `idsubcategoria` (`idsubcategoria`),
  ADD KEY `contas_vencimento` (`data_vencimento`),
  ADD KEY `contas_ibfk_2` (`idpessoa`),
  ADD KEY `contas_ibfk_3` (`idfornecedor`),
  ADD KEY `contas_ibfk_4` (`idcategoria`),
  ADD KEY `contas_ibfk_6` (`idmantenedora`),
  ADD KEY `idmatricula_transferida` (`idconta_transferida`),
  ADD KEY `contas_ibfk_5` (`idsindicato`),
  ADD KEY `idescola` (`idescola`);

--
-- Índices de tabela `contas_arquivos`
--
ALTER TABLE `contas_arquivos`
  ADD PRIMARY KEY (`idarquivo`),
  ADD KEY `indice_idconta` (`idconta`);

--
-- Índices de tabela `contas_boletos_gerado`
--
ALTER TABLE `contas_boletos_gerado`
  ADD PRIMARY KEY (`idboletogerado`),
  ADD KEY `idbanco` (`idbanco`),
  ADD KEY `idconta` (`idconta`),
  ADD KEY `idconta_corrente` (`idconta_corrente`);

--
-- Índices de tabela `contas_centros_custos`
--
ALTER TABLE `contas_centros_custos`
  ADD PRIMARY KEY (`idconta_centro_custo`),
  ADD KEY `idconta` (`idconta`,`idcentro_custo`);

--
-- Índices de tabela `contas_correntes`
--
ALTER TABLE `contas_correntes`
  ADD PRIMARY KEY (`idconta_corrente`),
  ADD KEY `contas_correntes_index_ativo` (`ativo`),
  ADD KEY `contas_correntes_index_ativo_painel` (`ativo_painel`),
  ADD KEY `idbanco` (`idbanco`);

--
-- Índices de tabela `contas_correntes_fechamentos`
--
ALTER TABLE `contas_correntes_fechamentos`
  ADD PRIMARY KEY (`idconta_corrente_fechamento`);

--
-- Índices de tabela `contas_correntes_fechamentos_cfc`
--
ALTER TABLE `contas_correntes_fechamentos_cfc`
  ADD PRIMARY KEY (`idconta_corrente_fechamento`);

--
-- Índices de tabela `contas_correntes_sindicatos`
--
ALTER TABLE `contas_correntes_sindicatos`
  ADD PRIMARY KEY (`idconta_corrente_sindicato`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `contas_correntes_sindicatos_idconta_corrente` (`idconta_corrente`),
  ADD KEY `contas_correntes_sindicatos_idsindicato` (`idsindicato`);

--
-- Índices de tabela `contas_historicos`
--
ALTER TABLE `contas_historicos`
  ADD PRIMARY KEY (`idhistorico`),
  ADD KEY `contas_historicos_idmatricula` (`idconta`),
  ADD KEY `contas_historicos_idusuario` (`idusuario`),
  ADD KEY `contas_historicos_tipo_id` (`tipo`,`id`);

--
-- Índices de tabela `contas_matriculas`
--
ALTER TABLE `contas_matriculas`
  ADD PRIMARY KEY (`idconta_matricula`),
  ADD KEY `fk_contas_matriculas_1` (`idconta`),
  ADD KEY `fk_contas_matriculas_2` (`idmatricula`);

--
-- Índices de tabela `contas_orcamentos`
--
ALTER TABLE `contas_orcamentos`
  ADD PRIMARY KEY (`idorcamento`),
  ADD KEY `contas_previsoes_idcategoria` (`idcategoria`),
  ADD KEY `contas_previsoes_idsindicato` (`idsindicato`);

--
-- Índices de tabela `contas_pagamentos`
--
ALTER TABLE `contas_pagamentos`
  ADD PRIMARY KEY (`idpagamento`),
  ADD KEY `idconta` (`idconta`);

--
-- Índices de tabela `contas_previsoes`
--
ALTER TABLE `contas_previsoes`
  ADD PRIMARY KEY (`idprevisao`),
  ADD KEY `contas_previsoes_idcategoria` (`idcategoria`),
  ADD KEY `contas_previsoes_idsindicato` (`idsindicato`);

--
-- Índices de tabela `contas_relacoes`
--
ALTER TABLE `contas_relacoes`
  ADD PRIMARY KEY (`idrelacao`);

--
-- Índices de tabela `contas_workflow`
--
ALTER TABLE `contas_workflow`
  ADD PRIMARY KEY (`idsituacao`),
  ADD KEY `atenimentos_assuntos_index_ativo` (`ativo`),
  ADD KEY `Index_Idapp` (`idapp`),
  ADD KEY `Index_Ativo` (`ativo`),
  ADD KEY `Index_emaberto` (`emaberto`),
  ADD KEY `Index_pago` (`pago`),
  ADD KEY `Index_renegociada` (`renegociada`),
  ADD KEY `Index_transferida` (`transferida`),
  ADD KEY `Index_cancelada` (`cancelada`),
  ADD KEY `index_pagseguro` (`pagseguro`);

--
-- Índices de tabela `contas_workflow_acoes`
--
ALTER TABLE `contas_workflow_acoes`
  ADD PRIMARY KEY (`idacao`),
  ADD KEY `FK_atendimentos_workflow_acoes_idsituacao` (`idsituacao`),
  ADD KEY `FK_atendimentos_workflow_acoes_relacionamento` (`idrelacionamento`),
  ADD KEY `FK_atendimentos_workflow_acoes_opcao` (`idopcao`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `contas_workflow_acoes_parametros`
--
ALTER TABLE `contas_workflow_acoes_parametros`
  ADD PRIMARY KEY (`idacaoparametro`),
  ADD KEY `FK_atendimentos_workflow_acoes_parametros_idacao` (`idacao`),
  ADD KEY `FK_atendimentos_workflow_acoes_parametros_idparametro` (`idparametro`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `contas_workflow_relacionamentos`
--
ALTER TABLE `contas_workflow_relacionamentos`
  ADD PRIMARY KEY (`idrelacionamento`),
  ADD KEY `FK_atendimentos_workflow_relacionamentos_de` (`idsituacao_de`),
  ADD KEY `FK_atendimentos_workflow_relacionamentos_para` (`idsituacao_para`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`idcontrato`),
  ADD KEY `contratos_index_ativo` (`ativo`),
  ADD KEY `contratos_index_ativo_painel` (`ativo_painel`),
  ADD KEY `FK_contratos_comercial_1` (`idtipo`);

--
-- Índices de tabela `contratos_cursos`
--
ALTER TABLE `contratos_cursos`
  ADD PRIMARY KEY (`idcontrato_curso`),
  ADD KEY `contratos_cursos_idcontrato` (`idcontrato`),
  ADD KEY `contratos_cursos_idcurso` (`idcurso`);

--
-- Índices de tabela `contratos_grupos`
--
ALTER TABLE `contratos_grupos`
  ADD PRIMARY KEY (`idgrupo`),
  ADD KEY `contratos_grupos_index_ativo` (`ativo`),
  ADD KEY `contratos_grupos_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `contratos_grupos_contratos`
--
ALTER TABLE `contratos_grupos_contratos`
  ADD PRIMARY KEY (`idgrupo_contrato`),
  ADD KEY `contratos_grupos_index_ativo` (`ativo`),
  ADD KEY `index_idgrupo_contrato` (`idgrupo`,`idcontrato`),
  ADD KEY ` contratos_grupos_contratos_idgrupo` (`idgrupo`),
  ADD KEY `contratos_grupos_contratos_idcontrato` (`idcontrato`);

--
-- Índices de tabela `contratos_imagens`
--
ALTER TABLE `contratos_imagens`
  ADD PRIMARY KEY (`idimagem`);

--
-- Índices de tabela `contratos_sindicatos`
--
ALTER TABLE `contratos_sindicatos`
  ADD PRIMARY KEY (`idcontrato_sindicato`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `contratos_sindicatos_idcontrato` (`idcontrato`),
  ADD KEY `contratos_sindicatos_idsindicato` (`idsindicato`);

--
-- Índices de tabela `contratos_tipos`
--
ALTER TABLE `contratos_tipos`
  ADD PRIMARY KEY (`idtipo`),
  ADD KEY `contratos_tipos_index_ativo` (`ativo`),
  ADD KEY `contratos_tipos_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `cupons`
--
ALTER TABLE `cupons`
  ADD PRIMARY KEY (`idcupom`);

--
-- Índices de tabela `cupons_cursos`
--
ALTER TABLE `cupons_cursos`
  ADD PRIMARY KEY (`idcupom_curso`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `contas_correntes_sindicatos_idconta_corrente` (`idcupom`),
  ADD KEY `contas_correntes_sindicatos_idsindicato` (`idcurso`);

--
-- Índices de tabela `cupons_escolas`
--
ALTER TABLE `cupons_escolas`
  ADD PRIMARY KEY (`idcupom_escola`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `contas_correntes_sindicatos_idconta_corrente` (`idcupom`),
  ADD KEY `contas_correntes_sindicatos_idsindicato` (`idescola`);

--
-- Índices de tabela `curriculos`
--
ALTER TABLE `curriculos`
  ADD PRIMARY KEY (`idcurriculo`),
  ADD KEY `curriculos_idcurso` (`idcurso`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `curriculos_arquivos`
--
ALTER TABLE `curriculos_arquivos`
  ADD PRIMARY KEY (`idarquivo`),
  ADD KEY `curriculos_arquivos_idcurriculo` (`idcurriculo`),
  ADD KEY `index_ativo` (`ativo`);

--
-- Índices de tabela `curriculos_avaliacoes`
--
ALTER TABLE `curriculos_avaliacoes`
  ADD PRIMARY KEY (`idavaliacao`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `curriculos_avaliacoes_idcurriculo` (`idcurriculo`);

--
-- Índices de tabela `curriculos_blocos`
--
ALTER TABLE `curriculos_blocos`
  ADD PRIMARY KEY (`idbloco`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `curriculos_blocos_idcurriculo` (`idcurriculo`);

--
-- Índices de tabela `curriculos_blocos_disciplinas`
--
ALTER TABLE `curriculos_blocos_disciplinas`
  ADD PRIMARY KEY (`idbloco_disciplina`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `curriculos_blocos_disciplinas_idbloco` (`idbloco`),
  ADD KEY `curriculos_blocos_disciplinas_iddisciplina` (`iddisciplina`),
  ADD KEY `curriculos_blocos_disciplinas_idava` (`idava`);

--
-- Índices de tabela `curriculos_notas_tipos`
--
ALTER TABLE `curriculos_notas_tipos`
  ADD PRIMARY KEY (`idcurriculo_tipo`),
  ADD KEY `curriculos_notas_tipos_idcurriculo` (`idcurriculo`),
  ADD KEY `curriculos_notas_tipos_idtipo` (`idtipo`);

--
-- Índices de tabela `curriculos_sindicatos`
--
ALTER TABLE `curriculos_sindicatos`
  ADD PRIMARY KEY (`idcurriculo_sindicatos`);

--
-- Índices de tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`idcurso`),
  ADD KEY `cursos_index_ativo` (`ativo`),
  ADD KEY `cursos_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `cursos_areas`
--
ALTER TABLE `cursos_areas`
  ADD PRIMARY KEY (`idcurso_area`),
  ADD KEY `cursos_areas_idcurso` (`idcurso`),
  ADD KEY `cursos_areas_idarea` (`idarea`);

--
-- Índices de tabela `cursos_sindicatos`
--
ALTER TABLE `cursos_sindicatos`
  ADD PRIMARY KEY (`idcurso_sindicato`),
  ADD KEY `cursos_sindicatos_idcurso` (`idcurso`),
  ADD KEY `cursos_sindicatos_idsindicato` (`idsindicato`),
  ADD KEY `idhistorico_escolar` (`idhistorico_escolar`);

--
-- Índices de tabela `declaracoes`
--
ALTER TABLE `declaracoes`
  ADD PRIMARY KEY (`iddeclaracao`),
  ADD KEY `declaracoes_index_ativo` (`ativo`),
  ADD KEY `declaracoes_index_ativo_painel` (`ativo_painel`),
  ADD KEY `FK_declaracoes_comercial_1` (`idtipo`);

--
-- Índices de tabela `declaracoes_cursos`
--
ALTER TABLE `declaracoes_cursos`
  ADD PRIMARY KEY (`iddeclaracao_curso`),
  ADD KEY `declaracoes_cursos_idcurso` (`idcurso`),
  ADD KEY `declaracoes_cursos_iddeclaracao` (`iddeclaracao`);

--
-- Índices de tabela `declaracoes_grupos`
--
ALTER TABLE `declaracoes_grupos`
  ADD PRIMARY KEY (`idgrupo`),
  ADD KEY `declaracoes_grupos_index_ativo` (`ativo`),
  ADD KEY `declaracoes_grupos_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `declaracoes_grupos_declaracoes`
--
ALTER TABLE `declaracoes_grupos_declaracoes`
  ADD PRIMARY KEY (`idgrupo_declaracao`),
  ADD KEY `declaracoes_grupos_index_ativo` (`ativo`),
  ADD KEY `index_idgrupo_declaracao` (`idgrupo`,`iddeclaracao`),
  ADD KEY ` declaracoes_grupos_declaracoes_idgrupo` (`idgrupo`),
  ADD KEY `declaracoes_grupos_declaracoes_iddeclaracao` (`iddeclaracao`);

--
-- Índices de tabela `declaracoes_imagens`
--
ALTER TABLE `declaracoes_imagens`
  ADD PRIMARY KEY (`iddeclaracao_imagem`),
  ADD KEY `FK_declaracoes_imagens_iddeclaracao` (`iddeclaracao`);

--
-- Índices de tabela `declaracoes_sindicatos`
--
ALTER TABLE `declaracoes_sindicatos`
  ADD PRIMARY KEY (`iddeclaracao_sindicato`),
  ADD KEY `vendedores_sindicatos_idsindicato` (`idsindicato`),
  ADD KEY `vendedores_sindicatos_iddeclaracao` (`iddeclaracao`);

--
-- Índices de tabela `declaracoes_tipos`
--
ALTER TABLE `declaracoes_tipos`
  ADD PRIMARY KEY (`idtipo`),
  ADD KEY `declaracoes_tipos_index_ativo` (`ativo`),
  ADD KEY `declaracoes_tipos_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `detran_logs`
--
ALTER TABLE `detran_logs`
  ADD PRIMARY KEY (`idlog`),
  ADD KEY `index_idmatricula` (`idmatricula`);

--
-- Índices de tabela `detran_matriculas_disciplinas_enviadas`
--
ALTER TABLE `detran_matriculas_disciplinas_enviadas`
  ADD PRIMARY KEY (`idenviado`),
  ADD KEY `index_idmatricula` (`idmatricula`),
  ADD KEY `index_iddisciplina` (`iddisciplina`);

--
-- Índices de tabela `diplomas`
--
ALTER TABLE `diplomas`
  ADD PRIMARY KEY (`iddiploma`),
  ADD KEY `diplomas_index_ativo` (`ativo`),
  ADD KEY `diplomas_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `disciplinas`
--
ALTER TABLE `disciplinas`
  ADD PRIMARY KEY (`iddisciplina`),
  ADD KEY `disciplinas_index_ativo` (`ativo`),
  ADD KEY `disciplinas_index_ativo_painel` (`ativo_painel`),
  ADD KEY `idformula` (`idformula`);

--
-- Índices de tabela `disciplinas_cursos`
--
ALTER TABLE `disciplinas_cursos`
  ADD PRIMARY KEY (`iddisciplina_curso`),
  ADD KEY `disciplinas_cursos_iddisciplina` (`iddisciplina`),
  ADD KEY `disciplinas_cursos_idcurso` (`idcurso`);

--
-- Índices de tabela `disciplinas_perguntas`
--
ALTER TABLE `disciplinas_perguntas`
  ADD PRIMARY KEY (`iddisciplina_pergunta`),
  ADD KEY `disciplinas_perguntas_iddisciplina` (`iddisciplina`),
  ADD KEY `disciplinas_perguntas_idpergunta` (`idpergunta`);

--
-- Índices de tabela `emails_automaticos`
--
ALTER TABLE `emails_automaticos`
  ADD PRIMARY KEY (`idemail`),
  ADD KEY `emails_automaticos_index_ativo` (`ativo`),
  ADD KEY `emails_automaticos_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `emails_automaticos_adm`
--
ALTER TABLE `emails_automaticos_adm`
  ADD PRIMARY KEY (`idemail`),
  ADD KEY `emails_automaticos_adm_index_ativo` (`ativo`),
  ADD KEY `emails_automaticos_adm_index_ativo_painel` (`ativo_painel`),
  ADD KEY `idsituacao_matricula` (`idsituacao_matricula`);

--
-- Índices de tabela `emails_automaticos_cursos`
--
ALTER TABLE `emails_automaticos_cursos`
  ADD PRIMARY KEY (`idemail_curso`),
  ADD KEY `emails_automaticos_cursos_idemail` (`idemail`),
  ADD KEY `emails_automaticos_cursos_idcurso` (`idcurso`);

--
-- Índices de tabela `emails_automaticos_cursos_adm`
--
ALTER TABLE `emails_automaticos_cursos_adm`
  ADD PRIMARY KEY (`idemail_curso`),
  ADD KEY `emails_automaticos_cursos_adm_idemail` (`idemail`),
  ADD KEY `emails_automaticos_cursos_adm_idcurso` (`idcurso`);

--
-- Índices de tabela `emails_automaticos_log`
--
ALTER TABLE `emails_automaticos_log`
  ADD PRIMARY KEY (`idemail_log`),
  ADD KEY `emails_automaticos_log_index_ativo` (`ativo`);

--
-- Índices de tabela `emails_automaticos_log_adm`
--
ALTER TABLE `emails_automaticos_log_adm`
  ADD PRIMARY KEY (`idemail_log`),
  ADD KEY `emails_automaticos_log_adm_index_ativo` (`ativo`);

--
-- Índices de tabela `emails_automaticos_ofertas`
--
ALTER TABLE `emails_automaticos_ofertas`
  ADD PRIMARY KEY (`idemail_oferta`),
  ADD KEY `emails_automaticos_ofertas_idemail` (`idemail`),
  ADD KEY `emails_automaticos_ofertas_idoferta` (`idoferta`);

--
-- Índices de tabela `emails_automaticos_sindicatos`
--
ALTER TABLE `emails_automaticos_sindicatos`
  ADD PRIMARY KEY (`idemail_sindicato`);

--
-- Índices de tabela `emails_log`
--
ALTER TABLE `emails_log`
  ADD PRIMARY KEY (`idemail`),
  ADD KEY `assunto` (`assunto`),
  ADD KEY `para_email` (`para_email`),
  ADD KEY `para_nome` (`para_nome`),
  ADD KEY `de_email` (`de_email`),
  ADD KEY `de_nome` (`de_nome`),
  ADD KEY `enviado` (`enviado`);

--
-- Índices de tabela `emails_newsletter`
--
ALTER TABLE `emails_newsletter`
  ADD PRIMARY KEY (`idemail`);

--
-- Índices de tabela `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`idempresa`),
  ADD KEY `fornecedores_index_ativo` (`ativo`),
  ADD KEY `fornecedores_index_ativo_painel` (`ativo_painel`),
  ADD KEY `idlogradouro` (`idlogradouro`,`idestado`,`idcidade`),
  ADD KEY `fornecedores_idsindicato` (`idsindicato`);

--
-- Índices de tabela `escolas`
--
ALTER TABLE `escolas`
  ADD PRIMARY KEY (`idescola`),
  ADD KEY `escolas_index_ativo` (`ativo`),
  ADD KEY `escolas_index_ativo_painel` (`ativo_painel`),
  ADD KEY `escolas_idcidade_idx` (`idcidade`),
  ADD KEY `escolas_idestado_idx` (`idestado`),
  ADD KEY `escolas_idlogradouro_idx` (`idlogradouro`),
  ADD KEY `escolas_idsindicato_idx` (`idsindicato`),
  ADD KEY `escolas_gerente_idcidade` (`gerente_idcidade`),
  ADD KEY `escolas_gerente_idestado` (`gerente_idestado`),
  ADD KEY `escolas_gerente_idlogradouro` (`gerente_idlogradouro`),
  ADD KEY `escolas_responsavel_legal_idcidade` (`responsavel_legal_idcidade`),
  ADD KEY `escolas_responsavel_legal_idestado` (`responsavel_legal_idestado`),
  ADD KEY `escolas_responsavel_legal_idlogradouro` (`responsavel_legal_idlogradouro`);

--
-- Índices de tabela `escolas_arquivos`
--
ALTER TABLE `escolas_arquivos`
  ADD PRIMARY KEY (`idarquivo`),
  ADD KEY `indice_idescola` (`idescola`);

--
-- Índices de tabela `escolas_aux`
--
ALTER TABLE `escolas_aux`
  ADD PRIMARY KEY (`idescola`),
  ADD KEY `escolas_index_ativo` (`ativo`),
  ADD KEY `escolas_index_ativo_painel` (`ativo_painel`),
  ADD KEY `escolas_idcidade_idx` (`idcidade`),
  ADD KEY `escolas_idestado_idx` (`idestado`),
  ADD KEY `escolas_idlogradouro_idx` (`idlogradouro`),
  ADD KEY `escolas_idsindicato_idx` (`idsindicato`);

--
-- Índices de tabela `escolas_contatos`
--
ALTER TABLE `escolas_contatos`
  ADD PRIMARY KEY (`idcontato`),
  ADD KEY `FK_escolas_contatos_escolas` (`idescola`),
  ADD KEY `Index_Contatos_ativo` (`ativo`),
  ADD KEY `FK_escolas_contatos_tipo` (`idtipo`);

--
-- Índices de tabela `escolas_contratos`
--
ALTER TABLE `escolas_contratos`
  ADD PRIMARY KEY (`idescola_contrato`),
  ADD KEY `escolas_contratos_idescola` (`idescola`),
  ADD KEY `escolas_contratos_idcontrato` (`idcontrato`);

--
-- Índices de tabela `escolas_contratos_gerados`
--
ALTER TABLE `escolas_contratos_gerados`
  ADD PRIMARY KEY (`idescola_contrato`);

--
-- Índices de tabela `escolas_estados_cidades`
--
ALTER TABLE `escolas_estados_cidades`
  ADD PRIMARY KEY (`idescola_estado_cidade`),
  ADD KEY `index_idescola` (`idescola`),
  ADD KEY `index_idestado` (`idestado`),
  ADD KEY `index_idcidade` (`idcidade`);

--
-- Índices de tabela `escolas_formas_pagamento`
--
ALTER TABLE `escolas_formas_pagamento`
  ADD PRIMARY KEY (`idescola_forma_pagamento`),
  ADD KEY `index2` (`idescola`),
  ADD KEY `index3` (`ativo`);

--
-- Índices de tabela `escolas_historico`
--
ALTER TABLE `escolas_historico`
  ADD PRIMARY KEY (`idhistorico`);

--
-- Índices de tabela `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`idestado`),
  ADD KEY `estados_idpais` (`idpais`),
  ADD KEY `codigo` (`codigo`);

--
-- Índices de tabela `etiquetas`
--
ALTER TABLE `etiquetas`
  ADD PRIMARY KEY (`idetiqueta`),
  ADD KEY `etiquetas_index_ativo` (`ativo`),
  ADD KEY `etiquetas_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `eventos_financeiros`
--
ALTER TABLE `eventos_financeiros`
  ADD PRIMARY KEY (`idevento`),
  ADD KEY `eventos_financeiros_index_ativo` (`ativo`),
  ADD KEY `eventos_financeiros_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `excecoes`
--
ALTER TABLE `excecoes`
  ADD PRIMARY KEY (`idexcecao`);

--
-- Índices de tabela `fastconnect`
--
ALTER TABLE `fastconnect`
  ADD PRIMARY KEY (`idfastconnect`);

--
-- Índices de tabela `fastconnect_logs`
--
ALTER TABLE `fastconnect_logs`
  ADD PRIMARY KEY (`idlog`);

--
-- Índices de tabela `fechamentos_caixa`
--
ALTER TABLE `fechamentos_caixa`
  ADD PRIMARY KEY (`idfechamento`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Índices de tabela `fechamentos_caixa_cfc`
--
ALTER TABLE `fechamentos_caixa_cfc`
  ADD PRIMARY KEY (`idfechamento`),
  ADD KEY `idescola` (`idescola`);

--
-- Índices de tabela `fechamentos_caixa_cfc_sindicatos`
--
ALTER TABLE `fechamentos_caixa_cfc_sindicatos`
  ADD PRIMARY KEY (`idfechamento_sindicato`),
  ADD KEY `idfechamento` (`idfechamento`,`idsindicato`);

--
-- Índices de tabela `fechamentos_caixa_sindicatos`
--
ALTER TABLE `fechamentos_caixa_sindicatos`
  ADD PRIMARY KEY (`idfechamento_sindicato`),
  ADD KEY `idfechamento` (`idfechamento`,`idsindicato`);

--
-- Índices de tabela `feriados`
--
ALTER TABLE `feriados`
  ADD PRIMARY KEY (`idferiado`),
  ADD KEY `feriados_index_ativo` (`ativo`),
  ADD KEY `feriados_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `feriados_cidades`
--
ALTER TABLE `feriados_cidades`
  ADD PRIMARY KEY (`idferiado_cidade`),
  ADD KEY `feriados_cidades_idferiado` (`idferiado`),
  ADD KEY `feriados_cidades_idcidade` (`idcidade`),
  ADD KEY `index_ativo` (`ativo`);

--
-- Índices de tabela `feriados_escolas`
--
ALTER TABLE `feriados_escolas`
  ADD PRIMARY KEY (`idferiado_escola`),
  ADD KEY `feriados_escolas_idferiado` (`idferiado`),
  ADD KEY `feriados_escolas_idescola` (`idescola`),
  ADD KEY `index_ativo` (`ativo`);

--
-- Índices de tabela `feriados_estados`
--
ALTER TABLE `feriados_estados`
  ADD PRIMARY KEY (`idferiado_estado`),
  ADD KEY `feriados_estados_idferiado` (`idferiado`),
  ADD KEY `feriados_estados_idestado` (`idestado`),
  ADD KEY `index_ativo` (`ativo`);

--
-- Índices de tabela `feriados_sindicatos`
--
ALTER TABLE `feriados_sindicatos`
  ADD PRIMARY KEY (`idferiado_sindicato`),
  ADD KEY `feriados_sindicatos_idferiado` (`idferiado`),
  ADD KEY `feriados_sindicatos_idsindicato` (`idsindicato`),
  ADD KEY `index_ativo` (`ativo`);

--
-- Índices de tabela `folhas_registros_diplomas`
--
ALTER TABLE `folhas_registros_diplomas`
  ADD PRIMARY KEY (`idfolha`),
  ADD KEY `folhas_registros_diplomas_index_ativo` (`ativo`),
  ADD KEY `idoferta` (`idsindicato`);

--
-- Índices de tabela `folhas_registros_diplomas_matriculas`
--
ALTER TABLE `folhas_registros_diplomas_matriculas`
  ADD PRIMARY KEY (`idfolha_matricula`),
  ADD KEY `folhas_registros_diplomas_alunos_index_ativo` (`ativo`),
  ADD KEY `idmatricula` (`idmatricula`);

--
-- Índices de tabela `formulas_notas`
--
ALTER TABLE `formulas_notas`
  ADD PRIMARY KEY (`idformula`),
  ADD KEY `formulas_notas_index_ativo` (`ativo`),
  ADD KEY `formulas_notas_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `formulas_notas_sindicatos`
--
ALTER TABLE `formulas_notas_sindicatos`
  ADD PRIMARY KEY (`idformula_sindicato`),
  ADD KEY `formulas_notas_sindicatos_idsindicato` (`idsindicato`),
  ADD KEY `formulas_notas_sindicatos_idformula` (`idformula`);

--
-- Índices de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`idfornecedor`),
  ADD KEY `fornecedores_index_ativo` (`ativo`),
  ADD KEY `fornecedores_index_ativo_painel` (`ativo_painel`),
  ADD KEY `idlogradouro` (`idlogradouro`,`idestado`,`idcidade`),
  ADD KEY `fornecedores_idsindicato` (`idsindicato`);

--
-- Índices de tabela `funcionarios_arquivos`
--
ALTER TABLE `funcionarios_arquivos`
  ADD PRIMARY KEY (`idarquivo`),
  ADD KEY `fk_funcionarios_arquivos_1_idx` (`idfuncionario`);

--
-- Índices de tabela `grupos_usuarios_adm`
--
ALTER TABLE `grupos_usuarios_adm`
  ADD PRIMARY KEY (`idgrupo`),
  ADD KEY `atendimentos_grupos_index_ativo` (`ativo`),
  ADD KEY `atendimentos_grupos_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `grupos_usuarios_adm_usuarios`
--
ALTER TABLE `grupos_usuarios_adm_usuarios`
  ADD PRIMARY KEY (`idgrupo_usuario`),
  ADD KEY `grupos_usuarios_adm_usuarios_index_ativo` (`ativo`),
  ADD KEY `grupos_usuarios_adm_usuarios_index_idgrupo_idusuario` (`idgrupo`,`idusuario`),
  ADD KEY `grupos_usuarios_adm_usuarios_idgrupo` (`idgrupo`),
  ADD KEY `grupos_usuarios_adm_usuarios_idusuario` (`idusuario`);

--
-- Índices de tabela `grupos_vendedores_vendedores`
--
ALTER TABLE `grupos_vendedores_vendedores`
  ADD PRIMARY KEY (`idgrupo_vendedor`),
  ADD KEY `grupos_vendedores_vendedores_idvendedor` (`idvendedor`);

--
-- Índices de tabela `historico_escolar`
--
ALTER TABLE `historico_escolar`
  ADD PRIMARY KEY (`idhistorico_escolar`);

--
-- Índices de tabela `historico_escolar_midias`
--
ALTER TABLE `historico_escolar_midias`
  ADD PRIMARY KEY (`idhistorico_escolar_midia`);

--
-- Índices de tabela `historico_escolar_paginas`
--
ALTER TABLE `historico_escolar_paginas`
  ADD PRIMARY KEY (`idhistorico_escolar_paginas`);

--
-- Índices de tabela `interesses_mensagens_arquivos`
--
ALTER TABLE `interesses_mensagens_arquivos`
  ADD PRIMARY KEY (`idarquivo`);

--
-- Índices de tabela `locais_provas`
--
ALTER TABLE `locais_provas`
  ADD PRIMARY KEY (`idlocal`),
  ADD KEY `locais_provas_index_ativo` (`ativo`),
  ADD KEY `locais_provas_index_ativo_painel` (`ativo_painel`),
  ADD KEY `locais_provas_idcidade_idx` (`idcidade`),
  ADD KEY `locais_provas_idestado_idx` (`idestado`),
  ADD KEY `locais_provas_idlogradouro_idx` (`idlogradouro`),
  ADD KEY `locais_provas_idsindicato_idx` (`idsindicato`);

--
-- Índices de tabela `locais_visitas`
--
ALTER TABLE `locais_visitas`
  ADD PRIMARY KEY (`idlocal`),
  ADD KEY `locais_visitas_index_ativo` (`ativo`),
  ADD KEY `locais_visitas_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `logradouros`
--
ALTER TABLE `logradouros`
  ADD PRIMARY KEY (`idlogradouro`),
  ADD KEY `logradouros_index_ativo` (`ativo`),
  ADD KEY `logradouros_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `log_sms`
--
ALTER TABLE `log_sms`
  ADD PRIMARY KEY (`idlog_sms`);

--
-- Índices de tabela `loja_pedidos`
--
ALTER TABLE `loja_pedidos`
  ADD PRIMARY KEY (`idpedido`),
  ADD KEY `index_idpessoa` (`idpessoa`),
  ADD KEY `index_idoferta` (`idoferta`),
  ADD KEY `index_idcurso` (`idcurso`),
  ADD KEY `index_idescola` (`idescola`),
  ADD KEY `index_idturma` (`idturma`),
  ADD KEY `index_situacao` (`situacao`);

--
-- Índices de tabela `mailings`
--
ALTER TABLE `mailings`
  ADD PRIMARY KEY (`idemail`),
  ADD KEY `mailing_index_ativo` (`ativo`),
  ADD KEY `mailing_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `mailings_fila`
--
ALTER TABLE `mailings_fila`
  ADD PRIMARY KEY (`idemail_pessoa`),
  ADD KEY `FK_mailing_fila_gestor_idusuario` (`idusuario_gestor`),
  ADD KEY `FK_mailing_fila_idmatricula` (`idmatricula`),
  ADD KEY `FK_mailing_fila_idprofessor` (`idprofessor`),
  ADD KEY `FK_mailing_fila_idemail` (`idemail`),
  ADD KEY `mailings_fila_idfiltro` (`idfiltro`),
  ADD KEY `paraemail` (`paraemail`,`parasms`);

--
-- Índices de tabela `mailings_fila_reenvio_historico`
--
ALTER TABLE `mailings_fila_reenvio_historico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mailings_fila_reenvio_historico_idemail_pessoa` (`idemail_pessoa`);

--
-- Índices de tabela `mailings_filtros`
--
ALTER TABLE `mailings_filtros`
  ADD PRIMARY KEY (`idfiltro`),
  ADD KEY `mailings_filtros_idemail` (`idemail`),
  ADD KEY `mailings_filtros_idusuario` (`idusuario`);

--
-- Índices de tabela `mailings_imagens`
--
ALTER TABLE `mailings_imagens`
  ADD PRIMARY KEY (`idemail_imagem`),
  ADD KEY `FK_mailing_imagens_idemail` (`idemail`);

--
-- Índices de tabela `mantenedoras`
--
ALTER TABLE `mantenedoras`
  ADD PRIMARY KEY (`idmantenedora`),
  ADD KEY `mantenedoras_index_ativo` (`ativo`),
  ADD KEY `mantenedoras_index_ativo_painel` (`ativo_painel`),
  ADD KEY `mantenedoras_idlogradouro_idx` (`idlogradouro`),
  ADD KEY `mantenedoras_idestado_idx` (`idestado`),
  ADD KEY `mantenedoras_idcidade_idx` (`idcidade`);

--
-- Índices de tabela `matriculas`
--
ALTER TABLE `matriculas`
  ADD PRIMARY KEY (`idmatricula`),
  ADD KEY `matriculas_idsituacao` (`idsituacao`),
  ADD KEY `matriculas_idpessoa` (`idpessoa`),
  ADD KEY `matriculas_idusuario_matriculou` (`idusuario`),
  ADD KEY `matriculas_idvendedor` (`idvendedor`),
  ADD KEY `matriculas_idmotivo_cancelamento` (`idmotivo_cancelamento`),
  ADD KEY `matriculas_idmotivo_inativo` (`idmotivo_inativo`),
  ADD KEY `matriculas_idsolicitante` (`idsolicitante`),
  ADD KEY `matriculas_idusuario_aprovado_comercial` (`idusuario_aprovado_comercial`),
  ADD KEY `matriculas_idempresa` (`idempresa`),
  ADD KEY `idcurso` (`idcurso`),
  ADD KEY `idturma` (`idturma`),
  ADD KEY `idmantenedora` (`idmantenedora`),
  ADD KEY `idbandeira` (`idbandeira`),
  ADD KEY `idescola` (`idescola`),
  ADD KEY `idsindicato` (`idsindicato`),
  ADD KEY `idoferta` (`idoferta`,`idcurso`,`idescola`,`idturma`),
  ADD KEY `matricula_faturada` (`faturada`);

--
-- Índices de tabela `matriculas_alunos_historicos`
--
ALTER TABLE `matriculas_alunos_historicos`
  ADD PRIMARY KEY (`idhistorico`);

--
-- Índices de tabela `matriculas_anotacoes`
--
ALTER TABLE `matriculas_anotacoes`
  ADD PRIMARY KEY (`idanotacao`);

--
-- Índices de tabela `matriculas_arquivos`
--
ALTER TABLE `matriculas_arquivos`
  ADD PRIMARY KEY (`idarquivo`);

--
-- Índices de tabela `matriculas_associados`
--
ALTER TABLE `matriculas_associados`
  ADD PRIMARY KEY (`idassociado`),
  ADD KEY `matriculas_associados_idmatricula` (`idmatricula`),
  ADD KEY `matriculas_associados_idpessoa` (`idpessoa`);

--
-- Índices de tabela `matriculas_avaliacoes`
--
ALTER TABLE `matriculas_avaliacoes`
  ADD PRIMARY KEY (`idprova`),
  ADD KEY `FK_cbd_avaliacoes_matriculas_bloco_disciplina` (`idavaliacao`),
  ADD KEY `FK_cbd_avaliacoes_matriculas_matricula` (`idmatricula`),
  ADD KEY `FK_cbd_avaliacoes_matriculas_professor` (`idprofessor`);

--
-- Índices de tabela `matriculas_avaliacoes_historicos`
--
ALTER TABLE `matriculas_avaliacoes_historicos`
  ADD PRIMARY KEY (`idhistorico`),
  ADD KEY `index_tipo_acao` (`tipo`,`acao`),
  ADD KEY `index_para` (`para`),
  ADD KEY `index_data_cad` (`data_cad`),
  ADD KEY `matriculas_avaliacoes_historicos_idprova` (`idprova`);

--
-- Índices de tabela `matriculas_avaliacoes_perguntas`
--
ALTER TABLE `matriculas_avaliacoes_perguntas`
  ADD PRIMARY KEY (`id_prova_pergunta`),
  ADD KEY `FK_cbd_avaliacoes_matriculas_perguntas_prova` (`idprova`),
  ADD KEY `FK_cbd_avaliacoes_matriculas_perguntas_pergunta` (`idpergunta`);

--
-- Índices de tabela `matriculas_avaliacoes_perguntas_opcoes_marcadas`
--
ALTER TABLE `matriculas_avaliacoes_perguntas_opcoes_marcadas`
  ADD PRIMARY KEY (`id_prova_pergunta_opcao`),
  ADD KEY `FK_cbd_avaliacoes_matriculas_perguntas_opcoes_prova_pergunta` (`id_prova_pergunta`),
  ADD KEY `FK_cbd_avaliacoes_matriculas_perguntas_opcoes_opcao` (`idopcao`);

--
-- Índices de tabela `matriculas_avas_porcentagem`
--
ALTER TABLE `matriculas_avas_porcentagem`
  ADD PRIMARY KEY (`idmatricula_ava_porcentagem`),
  ADD KEY `FK_matriculas_avas_porcentagem_1` (`idmatricula`),
  ADD KEY `FK_matriculas_avas_porcentagem_2` (`idava`);

--
-- Índices de tabela `matriculas_comparacoes_fotos`
--
ALTER TABLE `matriculas_comparacoes_fotos`
  ADD PRIMARY KEY (`idfoto`);

--
-- Índices de tabela `matriculas_contratos`
--
ALTER TABLE `matriculas_contratos`
  ADD PRIMARY KEY (`idmatricula_contrato`),
  ADD KEY `index_mat_cont_idmatricula` (`idmatricula`);

--
-- Índices de tabela `matriculas_contratos_gerados`
--
ALTER TABLE `matriculas_contratos_gerados`
  ADD PRIMARY KEY (`idmatricula_contrato`);

--
-- Índices de tabela `matriculas_declaracoes`
--
ALTER TABLE `matriculas_declaracoes`
  ADD PRIMARY KEY (`idmatriculadeclaracao`),
  ADD KEY `FK_matriculas_declaracoes_idusuario` (`idusuario`),
  ADD KEY `FK_matriculas_declaracoes_idmatricula` (`idmatricula`),
  ADD KEY `FK_matriculas_declaracoes_iddeclaracao` (`iddeclaracao`),
  ADD KEY `Index_Ativo` (`ativo`),
  ADD KEY `matriculas_declaracoes_idtipo` (`idtipo`);

--
-- Índices de tabela `matriculas_disciplinas_notas`
--
ALTER TABLE `matriculas_disciplinas_notas`
  ADD PRIMARY KEY (`idmatricula_disciplina_nota`),
  ADD KEY `idcurriculo_bloco_disciplina` (`idcurriculo_bloco_disciplina`,`idmatricula`,`idavaliacao`);

--
-- Índices de tabela `matriculas_documentos`
--
ALTER TABLE `matriculas_documentos`
  ADD PRIMARY KEY (`iddocumento`);

--
-- Índices de tabela `matriculas_exercicios`
--
ALTER TABLE `matriculas_exercicios`
  ADD PRIMARY KEY (`idmatricula_exercicio`),
  ADD KEY `FK_cbd_avaliacoes_matriculas_matricula` (`idmatricula`),
  ADD KEY `FK_cbd_avaliacoes_matriculas_bloco_disciplina` (`idexercicio`);

--
-- Índices de tabela `matriculas_exercicios_perguntas`
--
ALTER TABLE `matriculas_exercicios_perguntas`
  ADD PRIMARY KEY (`idmatricula_exercicio_pergunta`),
  ADD KEY `FK_cbd_avaliacoes_matriculas_perguntas_pergunta` (`idpergunta`),
  ADD KEY `FK_cbd_avaliacoes_matriculas_perguntas_prova` (`idmatricula_exercicio`);

--
-- Índices de tabela `matriculas_exercicios_perguntas_opcoes_marcadas`
--
ALTER TABLE `matriculas_exercicios_perguntas_opcoes_marcadas`
  ADD PRIMARY KEY (`idmatricula_exercicio_opcao`),
  ADD KEY `FK_cbd_avaliacoes_matriculas_perguntas_opcoes_opcao` (`idopcao`),
  ADD KEY `FK_cbd_avaliacoes_matriculas_perguntas_opcoes_prova_pergunta` (`idmatricula_exercicio_pergunta`);

--
-- Índices de tabela `matriculas_historico`
--
ALTER TABLE `matriculas_historico`
  ADD PRIMARY KEY (`idmatricula_historico`);

--
-- Índices de tabela `matriculas_historicos`
--
ALTER TABLE `matriculas_historicos`
  ADD PRIMARY KEY (`idhistorico`),
  ADD KEY `matriculas_historicos_idmatricula` (`idmatricula`),
  ADD KEY `matriculas_historicos_idusuario` (`idusuario`),
  ADD KEY `matriculas_historicos_tipo_id` (`tipo`,`id`);

--
-- Índices de tabela `matriculas_linksacoes_cliques`
--
ALTER TABLE `matriculas_linksacoes_cliques`
  ADD PRIMARY KEY (`idclique`),
  ADD KEY `ix_idmatricula` (`idmatricula`),
  ADD KEY `ix_idlinkacao` (`idlinkacao`);

--
-- Índices de tabela `matriculas_mensagens`
--
ALTER TABLE `matriculas_mensagens`
  ADD PRIMARY KEY (`idmensagem`);

--
-- Índices de tabela `matriculas_mensagens_arquivos`
--
ALTER TABLE `matriculas_mensagens_arquivos`
  ADD PRIMARY KEY (`idarquivo`),
  ADD KEY `matriculas_mensagens_idmatricula` (`idmatricula`),
  ADD KEY `matriculas_mensagens_idmensagem` (`idmensagem`);

--
-- Índices de tabela `matriculas_notas`
--
ALTER TABLE `matriculas_notas`
  ADD PRIMARY KEY (`idmatricula_nota`),
  ADD KEY `FK_matriculas_notas_idprova` (`idprova`),
  ADD KEY `FK_matriculas_notas_id_solicitacao_prova` (`id_solicitacao_prova`),
  ADD KEY `FK_matriculas_notas_iddisciplina` (`iddisciplina`),
  ADD KEY `idmatricula` (`idmatricula`),
  ADD KEY `idmodelo` (`idmodelo`),
  ADD KEY `idtipo` (`idtipo`);

--
-- Índices de tabela `matriculas_notas_tipos`
--
ALTER TABLE `matriculas_notas_tipos`
  ADD PRIMARY KEY (`idtipo`),
  ADD KEY `modelos_prova_index_ativo` (`ativo`),
  ADD KEY `modelos_prova_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `matriculas_objetos_favoritos`
--
ALTER TABLE `matriculas_objetos_favoritos`
  ADD PRIMARY KEY (`idfavorito`);

--
-- Índices de tabela `matriculas_reconhecimentos`
--
ALTER TABLE `matriculas_reconhecimentos`
  ADD PRIMARY KEY (`idfoto`),
  ADD KEY `matriculas_reconhecimentos_FK` (`idmatricula`);

--
-- Índices de tabela `matriculas_rotas_aprendizagem_objetos`
--
ALTER TABLE `matriculas_rotas_aprendizagem_objetos`
  ADD PRIMARY KEY (`idmatricula_rota_objeto`),
  ADD KEY `matriculas_rotas_aprendizagem_objetos_idmatricula` (`idmatricula`),
  ADD KEY `matriculas_rotas_aprendizagem_objetos_idobjeto` (`idobjeto`);

--
-- Índices de tabela `matriculas_simulados`
--
ALTER TABLE `matriculas_simulados`
  ADD PRIMARY KEY (`idmatricula_simulado`),
  ADD KEY `IDSIMULADO` (`idsimulado`),
  ADD KEY `IDMATRICULA` (`idmatricula`);

--
-- Índices de tabela `matriculas_simulados_perguntas`
--
ALTER TABLE `matriculas_simulados_perguntas`
  ADD PRIMARY KEY (`idmatricula_simulado_pergunta`),
  ADD KEY `IDSIMULADO` (`idmatricula_simulado`),
  ADD KEY `IDPERGUNTA` (`idpergunta`),
  ADD KEY `ATIVO` (`ativo`);

--
-- Índices de tabela `matriculas_simulados_perguntas_opcoes_marcadas`
--
ALTER TABLE `matriculas_simulados_perguntas_opcoes_marcadas`
  ADD PRIMARY KEY (`idmatricula_simulado_pergunta_opcao_marcada`),
  ADD KEY `ix_simulado_pergunta` (`idmatricula_simulado_pergunta`),
  ADD KEY `ix_idopcao` (`idopcao`),
  ADD KEY `ix_ativo` (`ativo`);

--
-- Índices de tabela `matriculas_solicitacoes_declaracoes`
--
ALTER TABLE `matriculas_solicitacoes_declaracoes`
  ADD PRIMARY KEY (`idsolicitacao_declaracao`),
  ADD KEY `matriculas_solicitacoes_declaracoes_idmatricula` (`idmatricula`),
  ADD KEY `matriculas_solicitacoes_declaracoes_idmatriculadeclaracao` (`idmatriculadeclaracao`),
  ADD KEY `matriculas_solicitacoes_declaracoes_iddeclaracao` (`iddeclaracao`);

--
-- Índices de tabela `matriculas_workflow`
--
ALTER TABLE `matriculas_workflow`
  ADD PRIMARY KEY (`idsituacao`),
  ADD KEY `atenimentos_assuntos_index_ativo` (`ativo`),
  ADD KEY `Index_Idapp` (`idapp`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `matriculas_workflow_acoes`
--
ALTER TABLE `matriculas_workflow_acoes`
  ADD PRIMARY KEY (`idacao`),
  ADD KEY `FK_atendimentos_workflow_acoes_idsituacao` (`idsituacao`),
  ADD KEY `FK_atendimentos_workflow_acoes_relacionamento` (`idrelacionamento`),
  ADD KEY `FK_atendimentos_workflow_acoes_opcao` (`idopcao`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `matriculas_workflow_acoes_parametros`
--
ALTER TABLE `matriculas_workflow_acoes_parametros`
  ADD PRIMARY KEY (`idacaoparametro`),
  ADD KEY `FK_atendimentos_workflow_acoes_parametros_idacao` (`idacao`),
  ADD KEY `FK_atendimentos_workflow_acoes_parametros_idparametro` (`idparametro`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `matriculas_workflow_relacionamentos`
--
ALTER TABLE `matriculas_workflow_relacionamentos`
  ADD PRIMARY KEY (`idrelacionamento`),
  ADD KEY `FK_atendimentos_workflow_relacionamentos_de` (`idsituacao_de`),
  ADD KEY `FK_atendimentos_workflow_relacionamentos_para` (`idsituacao_para`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `mensagens_alerta`
--
ALTER TABLE `mensagens_alerta`
  ADD PRIMARY KEY (`idmensagem`);

--
-- Índices de tabela `metas`
--
ALTER TABLE `metas`
  ADD PRIMARY KEY (`idmeta`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `usuario_processou_index` (`idusuario_processou`);

--
-- Índices de tabela `metas_cursos`
--
ALTER TABLE `metas_cursos`
  ADD PRIMARY KEY (`idmeta`),
  ADD KEY `idsindicato` (`idsindicato`),
  ADD KEY `metas_sindicatos_idcurso` (`idcurso`);

--
-- Índices de tabela `metas_sindicatos`
--
ALTER TABLE `metas_sindicatos`
  ADD PRIMARY KEY (`idmeta`),
  ADD KEY `metas_sindicatos_idsindicato` (`idsindicato`);

--
-- Índices de tabela `metas_vendedores`
--
ALTER TABLE `metas_vendedores`
  ADD PRIMARY KEY (`idmeta_vendedor`),
  ADD KEY `metas_vendedores_idmeta` (`idmeta`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `metas_vendedores_idvendedor` (`idvendedor`);

--
-- Índices de tabela `midias_visitas`
--
ALTER TABLE `midias_visitas`
  ADD PRIMARY KEY (`idmidia`),
  ADD KEY `midias_visitas_index_ativo` (`ativo`),
  ADD KEY `midias_visitas_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `modelos_prova`
--
ALTER TABLE `modelos_prova`
  ADD PRIMARY KEY (`idmodelo`),
  ADD KEY `modelos_prova_index_ativo` (`ativo`),
  ADD KEY `modelos_prova_index_ativo_painel` (`ativo_painel`),
  ADD KEY `idsindicato` (`idsindicato`);

--
-- Índices de tabela `monitora_adm`
--
ALTER TABLE `monitora_adm`
  ADD PRIMARY KEY (`idmonitora`),
  ADD KEY `monitora_adm_idusuario` (`idusuario`);

--
-- Índices de tabela `monitora_adm_log`
--
ALTER TABLE `monitora_adm_log`
  ADD PRIMARY KEY (`idlog`),
  ADD KEY `monitora_adm_log_idmonitora` (`idmonitora`);

--
-- Índices de tabela `monitora_escola`
--
ALTER TABLE `monitora_escola`
  ADD PRIMARY KEY (`idmonitora`),
  ADD KEY `monitora_escola_idescola` (`idescola`);

--
-- Índices de tabela `monitora_escola_log`
--
ALTER TABLE `monitora_escola_log`
  ADD PRIMARY KEY (`idlog`),
  ADD KEY `monitora_escola_log_idmonitora` (`idmonitora`);

--
-- Índices de tabela `monitora_pessoa`
--
ALTER TABLE `monitora_pessoa`
  ADD PRIMARY KEY (`idmonitora`),
  ADD KEY `monitora_adm_idpessoa` (`idpessoa`);

--
-- Índices de tabela `monitora_pessoa_log`
--
ALTER TABLE `monitora_pessoa_log`
  ADD PRIMARY KEY (`idlog`),
  ADD KEY `monitora_adm_log_idmonitora` (`idmonitora`);

--
-- Índices de tabela `monitora_professor`
--
ALTER TABLE `monitora_professor`
  ADD PRIMARY KEY (`idmonitora`),
  ADD KEY `monitora_juridico_idprofessor` (`idprofessor`);

--
-- Índices de tabela `monitora_professor_log`
--
ALTER TABLE `monitora_professor_log`
  ADD PRIMARY KEY (`idlog`),
  ADD KEY `monitora_professor_log_idmonitora` (`idmonitora`);

--
-- Índices de tabela `monitora_vendedor`
--
ALTER TABLE `monitora_vendedor`
  ADD PRIMARY KEY (`idmonitora`),
  ADD KEY `monitora_vendedor_idvendedor` (`idvendedor`);

--
-- Índices de tabela `monitora_vendedor_log`
--
ALTER TABLE `monitora_vendedor_log`
  ADD PRIMARY KEY (`idlog`),
  ADD KEY `monitora_vendedor_log_idmonitora` (`idmonitora`);

--
-- Índices de tabela `motivos_cancelamento`
--
ALTER TABLE `motivos_cancelamento`
  ADD PRIMARY KEY (`idmotivo`),
  ADD KEY `motivos_cancelamento_index_ativo` (`ativo`),
  ADD KEY `motivos_cancelamento_index_ativo_painel` (`ativo_painel`),
  ADD KEY `anular_parcelas` (`anular_parcelas`);

--
-- Índices de tabela `motivos_cancelamento_conta`
--
ALTER TABLE `motivos_cancelamento_conta`
  ADD PRIMARY KEY (`idmotivo`),
  ADD KEY `motivos_cancelamento_index_ativo` (`ativo`),
  ADD KEY `motivos_cancelamento_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `motivos_cancelamento_solicitacao_prova`
--
ALTER TABLE `motivos_cancelamento_solicitacao_prova`
  ADD PRIMARY KEY (`idmotivo`),
  ADD KEY `motivos_cancelamento_index_ativo` (`ativo`),
  ADD KEY `motivos_cancelamento_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `motivos_inatividade`
--
ALTER TABLE `motivos_inatividade`
  ADD PRIMARY KEY (`idmotivo`),
  ADD KEY `motivos_inatividade_index_ativo` (`ativo`),
  ADD KEY `motivos_inatividade_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `motivos_visitas`
--
ALTER TABLE `motivos_visitas`
  ADD PRIMARY KEY (`idmotivo`),
  ADD KEY `motivos_visitas_index_ativo` (`ativo`),
  ADD KEY `motivos_visitas_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `murais`
--
ALTER TABLE `murais`
  ADD PRIMARY KEY (`idmural`),
  ADD KEY `index_ativo` (`ativo`);

--
-- Índices de tabela `murais_arquivos`
--
ALTER TABLE `murais_arquivos`
  ADD PRIMARY KEY (`idmural_arquivo`),
  ADD KEY `FK_murais_arquivos_idmural` (`idmural`);

--
-- Índices de tabela `murais_filas`
--
ALTER TABLE `murais_filas`
  ADD PRIMARY KEY (`idfila`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `murais_fila_idusuario_adm` (`idusuario_adm`),
  ADD KEY `murais_fila_idpessoa` (`idpessoa`),
  ADD KEY `murais_fila_idprofessor` (`idprofessor`),
  ADD KEY `murais_fila_idvendedor` (`idvendedor`),
  ADD KEY `murais_fila_idatendimento` (`idatendimento`),
  ADD KEY `murais_fila_idmatricula` (`idmatricula`),
  ADD KEY `murais_fila_idmural` (`idmural`),
  ADD KEY `murais_fila_idescola` (`idescola`),
  ADD KEY `murais_fila_idsindicato` (`idsindicato`);

--
-- Índices de tabela `murais_imagens`
--
ALTER TABLE `murais_imagens`
  ADD PRIMARY KEY (`idmural_imagem`),
  ADD KEY `FK_murais_imagens_idmural` (`idmural`);

--
-- Índices de tabela `ofertas`
--
ALTER TABLE `ofertas`
  ADD PRIMARY KEY (`idoferta`),
  ADD KEY `ofertas_index_ativo` (`ativo`),
  ADD KEY `ofertas_index_ativo_painel` (`ativo_painel`),
  ADD KEY `ofertas_idsituacao` (`idsituacao`);

--
-- Índices de tabela `ofertas_curriculos_avas`
--
ALTER TABLE `ofertas_curriculos_avas`
  ADD PRIMARY KEY (`idoferta_curriculo_ava`),
  ADD KEY `idoferta` (`idoferta`),
  ADD KEY `idoferta_curriculo` (`idcurriculo`),
  ADD KEY `idoferta_ava` (`idava`),
  ADD KEY `iddisciplina` (`iddisciplina`);

--
-- Índices de tabela `ofertas_cursos`
--
ALTER TABLE `ofertas_cursos`
  ADD PRIMARY KEY (`idoferta_curso`),
  ADD KEY `ofertas_cursos_idoferta` (`idoferta`),
  ADD KEY `ofertas_cursos_idcurso` (`idcurso`);

--
-- Índices de tabela `ofertas_cursos_escolas`
--
ALTER TABLE `ofertas_cursos_escolas`
  ADD PRIMARY KEY (`idoferta_curso_escola`),
  ADD KEY `idcurriculo` (`idcurriculo`),
  ADD KEY `idoferta` (`idoferta`),
  ADD KEY `idoferta_curso` (`idcurso`),
  ADD KEY `idoferta_escola` (`idescola`);

--
-- Índices de tabela `ofertas_cursos_sindicatos`
--
ALTER TABLE `ofertas_cursos_sindicatos`
  ADD PRIMARY KEY (`idoferta_curso_sindicato`),
  ADD KEY `idoferta_sindicato` (`idsindicato`),
  ADD KEY `idoferta` (`idoferta`),
  ADD KEY `idoferta_curso` (`idcurso`);

--
-- Índices de tabela `ofertas_cursos_workflow`
--
ALTER TABLE `ofertas_cursos_workflow`
  ADD PRIMARY KEY (`idsituacao`),
  ADD KEY `atenimentos_assuntos_index_ativo` (`ativo`),
  ADD KEY `Index_Idapp` (`idapp`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `ofertas_cursos_workflow_acoes`
--
ALTER TABLE `ofertas_cursos_workflow_acoes`
  ADD PRIMARY KEY (`idacao`),
  ADD KEY `FK_atendimentos_workflow_acoes_idsituacao` (`idsituacao`),
  ADD KEY `FK_atendimentos_workflow_acoes_relacionamento` (`idrelacionamento`),
  ADD KEY `FK_atendimentos_workflow_acoes_opcao` (`idopcao`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `ofertas_cursos_workflow_acoes_parametros`
--
ALTER TABLE `ofertas_cursos_workflow_acoes_parametros`
  ADD PRIMARY KEY (`idacaoparametro`),
  ADD KEY `FK_atendimentos_workflow_acoes_parametros_idacao` (`idacao`),
  ADD KEY `FK_atendimentos_workflow_acoes_parametros_idparametro` (`idparametro`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `ofertas_cursos_workflow_relacionamentos`
--
ALTER TABLE `ofertas_cursos_workflow_relacionamentos`
  ADD PRIMARY KEY (`idrelacionamento`),
  ADD KEY `FK_atendimentos_workflow_relacionamentos_de` (`idsituacao_de`),
  ADD KEY `FK_atendimentos_workflow_relacionamentos_para` (`idsituacao_para`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `ofertas_escolas`
--
ALTER TABLE `ofertas_escolas`
  ADD PRIMARY KEY (`idoferta_escola`),
  ADD KEY `ofertas_cursos_idoferta` (`idoferta`),
  ADD KEY `ofertas_cursos_idescola` (`idescola`);

--
-- Índices de tabela `ofertas_turmas`
--
ALTER TABLE `ofertas_turmas`
  ADD PRIMARY KEY (`idturma`),
  ADD KEY `ofertas_turmas_idoferta` (`idoferta`),
  ADD KEY `ofertas_turmas_idturma` (`nome`);

--
-- Índices de tabela `ofertas_turmas_sindicatos`
--
ALTER TABLE `ofertas_turmas_sindicatos`
  ADD PRIMARY KEY (`idoferta_turma_sindicato`),
  ADD KEY `idoferta_sindicato` (`idsindicato`),
  ADD KEY `idoferta` (`idoferta`),
  ADD KEY `idoferta_turma` (`idturma`);

--
-- Índices de tabela `ofertas_workflow`
--
ALTER TABLE `ofertas_workflow`
  ADD PRIMARY KEY (`idsituacao`),
  ADD KEY `atenimentos_assuntos_index_ativo` (`ativo`),
  ADD KEY `Index_Idapp` (`idapp`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `ofertas_workflow_acoes`
--
ALTER TABLE `ofertas_workflow_acoes`
  ADD PRIMARY KEY (`idacao`),
  ADD KEY `FK_atendimentos_workflow_acoes_idsituacao` (`idsituacao`),
  ADD KEY `FK_atendimentos_workflow_acoes_relacionamento` (`idrelacionamento`),
  ADD KEY `FK_atendimentos_workflow_acoes_opcao` (`idopcao`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `ofertas_workflow_acoes_parametros`
--
ALTER TABLE `ofertas_workflow_acoes_parametros`
  ADD PRIMARY KEY (`idacaoparametro`),
  ADD KEY `FK_atendimentos_workflow_acoes_parametros_idacao` (`idacao`),
  ADD KEY `FK_atendimentos_workflow_acoes_parametros_idparametro` (`idparametro`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `ofertas_workflow_relacionamentos`
--
ALTER TABLE `ofertas_workflow_relacionamentos`
  ADD PRIMARY KEY (`idrelacionamento`),
  ADD KEY `FK_atendimentos_workflow_relacionamentos_de` (`idsituacao_de`),
  ADD KEY `FK_atendimentos_workflow_relacionamentos_para` (`idsituacao_para`),
  ADD KEY `Index_Ativo` (`ativo`);

--
-- Índices de tabela `pagamentos_compartilhados`
--
ALTER TABLE `pagamentos_compartilhados`
  ADD PRIMARY KEY (`idpagamento`),
  ADD KEY `idsindicato` (`idsindicato`);

--
-- Índices de tabela `pagamentos_compartilhados_matriculas`
--
ALTER TABLE `pagamentos_compartilhados_matriculas`
  ADD PRIMARY KEY (`idpagamento_matricula`),
  ADD KEY `pagamentos_compartilhados_matriculas_pagamento` (`idpagamento`),
  ADD KEY `pagamentos_compartilhados_matriculas_matricula` (`idmatricula`);

--
-- Índices de tabela `pagarme`
--
ALTER TABLE `pagarme`
  ADD PRIMARY KEY (`idpagarme`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `index_idconta` (`idconta`),
  ADD KEY `index_status` (`status`);

--
-- Índices de tabela `pagarme_monitora`
--
ALTER TABLE `pagarme_monitora`
  ADD PRIMARY KEY (`idmonitora`),
  ADD KEY `index_idpagarme` (`idpagarme`);

--
-- Índices de tabela `pagarme_monitora_log`
--
ALTER TABLE `pagarme_monitora_log`
  ADD PRIMARY KEY (`idlog`),
  ADD KEY `index_idmonitora` (`idmonitora`);

--
-- Índices de tabela `pagseguro`
--
ALTER TABLE `pagseguro`
  ADD PRIMARY KEY (`idpagseguro`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `index_idconta` (`idconta`),
  ADD KEY `index_status` (`status`);

--
-- Índices de tabela `pagseguro_logs`
--
ALTER TABLE `pagseguro_logs`
  ADD PRIMARY KEY (`idlog`);

--
-- Índices de tabela `pagseguro_monitora`
--
ALTER TABLE `pagseguro_monitora`
  ADD PRIMARY KEY (`idmonitora`),
  ADD KEY `index_idpagseguro` (`idpagseguro`);

--
-- Índices de tabela `pagseguro_monitora_log`
--
ALTER TABLE `pagseguro_monitora_log`
  ADD PRIMARY KEY (`idlog`),
  ADD KEY `index_idmonitora` (`idmonitora`);

--
-- Índices de tabela `paises`
--
ALTER TABLE `paises`
  ADD PRIMARY KEY (`idpais`);

--
-- Índices de tabela `perguntas`
--
ALTER TABLE `perguntas`
  ADD PRIMARY KEY (`idpergunta`),
  ADD KEY `perguntas_index_ativo` (`ativo`),
  ADD KEY `perguntas_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `perguntas_clonar`
--
ALTER TABLE `perguntas_clonar`
  ADD PRIMARY KEY (`idpergunta_clonar`),
  ADD KEY `idx_idpergunta_clonar` (`idpergunta_clonar`),
  ADD KEY `idx_idpergunta` (`idpergunta`),
  ADD KEY `idx_iddisciplina_de` (`iddisciplina_de`),
  ADD KEY `idx_iddisciplina_para` (`iddisciplina_para`),
  ADD KEY `idx_clonada` (`clonada`),
  ADD KEY `idx_idusuario` (`idusuario`);

--
-- Índices de tabela `perguntas_opcoes`
--
ALTER TABLE `perguntas_opcoes`
  ADD PRIMARY KEY (`idopcao`),
  ADD KEY `perguntas_opcoes_idpergunta` (`idpergunta`);

--
-- Índices de tabela `perguntas_pesquisas`
--
ALTER TABLE `perguntas_pesquisas`
  ADD PRIMARY KEY (`idpergunta`),
  ADD KEY `pesquisas_perguntas_index_ativo` (`ativo`),
  ADD KEY `pesquisas_perguntas_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `pesquisas`
--
ALTER TABLE `pesquisas`
  ADD PRIMARY KEY (`idpesquisa`),
  ADD KEY `pesquisas_index_ativo` (`ativo`),
  ADD KEY `pesquisas_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `pesquisas_fila`
--
ALTER TABLE `pesquisas_fila`
  ADD PRIMARY KEY (`idpesquisa_pessoa`),
  ADD KEY `idpesquisa` (`idpesquisa`),
  ADD KEY `idpessoa` (`idpessoa`),
  ADD KEY `FK_pesquisas_fila_idmatricula` (`idmatricula`),
  ADD KEY `FK_pesquisas_fila_idprofessor` (`idprofessor`),
  ADD KEY `FK_pesquisas_fila_gestor_idusuario` (`idusuario_gestor`),
  ADD KEY `pesquisas_fila_idfiltro` (`idfiltro`);

--
-- Índices de tabela `pesquisas_fila_reenvio_historico`
--
ALTER TABLE `pesquisas_fila_reenvio_historico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idpesquisa_pessoa_index` (`idpesquisa_pessoa`);

--
-- Índices de tabela `pesquisas_filtros`
--
ALTER TABLE `pesquisas_filtros`
  ADD PRIMARY KEY (`idfiltro`),
  ADD KEY `pesquisas_filtros_idpesquisa` (`idpesquisa`),
  ADD KEY `pesquisas_filtros_idusuario` (`idusuario`);

--
-- Índices de tabela `pesquisas_imagens`
--
ALTER TABLE `pesquisas_imagens`
  ADD PRIMARY KEY (`idpesquisa_imagem`),
  ADD KEY `FK_pesquisas_imagens_idpesquisa` (`idpesquisa`);

--
-- Índices de tabela `pesquisas_perguntas`
--
ALTER TABLE `pesquisas_perguntas`
  ADD PRIMARY KEY (`idpesquisa_pergunta`),
  ADD KEY `idpesquisa` (`idpesquisa`),
  ADD KEY `idpergunta` (`idpergunta`);

--
-- Índices de tabela `pesquisas_perguntas_opcoes`
--
ALTER TABLE `pesquisas_perguntas_opcoes`
  ADD PRIMARY KEY (`idopcao`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `idpergunta` (`idpergunta`);

--
-- Índices de tabela `pesquisas_respostas`
--
ALTER TABLE `pesquisas_respostas`
  ADD PRIMARY KEY (`idpesquisa_resposta`);

--
-- Índices de tabela `pessoas`
--
ALTER TABLE `pessoas`
  ADD PRIMARY KEY (`idpessoa`),
  ADD KEY `pessoas_index_ativo` (`ativo`),
  ADD KEY `pessoas_index_ativo_login` (`ativo_login`),
  ADD KEY `pessoas_login` (`email`,`senha`,`ativo`,`ativo_login`),
  ADD KEY `idreligiao` (`idreligiao`),
  ADD KEY `biometria` (`biometria`),
  ADD KEY `pessoas_documentos` (`documento_tipo`,`documento`);

--
-- Índices de tabela `pessoas_acessos`
--
ALTER TABLE `pessoas_acessos`
  ADD PRIMARY KEY (`idacesso`),
  ADD KEY `idpessoa` (`idpessoa`);

--
-- Índices de tabela `pessoas_acessos_matriculas`
--
ALTER TABLE `pessoas_acessos_matriculas`
  ADD PRIMARY KEY (`idacessomatricula`),
  ADD KEY `index_acesso` (`idacesso`),
  ADD KEY `index_matriculas` (`idmatricula`),
  ADD KEY `index_pessoas` (`idpessoa`),
  ADD KEY `index_idava` (`idava`),
  ADD KEY `index_data_competencia` (`data_competencia`);

--
-- Índices de tabela `pessoas_associacoes`
--
ALTER TABLE `pessoas_associacoes`
  ADD PRIMARY KEY (`idpessoa_associacao`),
  ADD KEY `pessoas_associacoes_idpessoa_associada` (`idpessoa_associada`),
  ADD KEY `index_idpessoa_idpessoa` (`idpessoa`);

--
-- Índices de tabela `pessoas_contatos`
--
ALTER TABLE `pessoas_contatos`
  ADD PRIMARY KEY (`idcontato`),
  ADD KEY `FK_pessoas_contatos_pessoas` (`idpessoa`),
  ADD KEY `Index_Contatos_ativo` (`ativo`),
  ADD KEY `FK_pessoas_contatos_tipo` (`idtipo`);

--
-- Índices de tabela `pessoas_sindicatos`
--
ALTER TABLE `pessoas_sindicatos`
  ADD PRIMARY KEY (`idpessoa_sindicato`),
  ADD KEY `pessoas_sindicatos_idpessoa` (`idpessoa`),
  ADD KEY `pessoas_sindicatos_idsindicato` (`idsindicato`);

--
-- Índices de tabela `pessoas_validacoes`
--
ALTER TABLE `pessoas_validacoes`
  ADD PRIMARY KEY (`idvalidacao`),
  ADD KEY `FK_pessoas_validacoes_idpessoa` (`idpessoa`),
  ADD KEY `FK_pessoas_validacoes_idusuario` (`idusuario`),
  ADD KEY `FK_pessoas_validacoes_idpessoa_solicitou` (`idpessoa_solicitou`),
  ADD KEY `FK_pessoas_validacoes_idusuario_solicitou` (`idusuario_solicitou`),
  ADD KEY `FK_pessoas_validacoes_idcorretor` (`idcorretor`),
  ADD KEY `FK_pessoas_validacoes_idusuario_imobiliaria` (`idusuario_imobiliaria`);

--
-- Índices de tabela `pessoas_validacoes_campos`
--
ALTER TABLE `pessoas_validacoes_campos`
  ADD PRIMARY KEY (`idvalidacaocampo`),
  ADD KEY `FK_pessoas_validacoes_campos_idvalidacao` (`idvalidacao`);

--
-- Índices de tabela `previsoes_gastos`
--
ALTER TABLE `previsoes_gastos`
  ADD PRIMARY KEY (`idprevisao`),
  ADD KEY `contas_index_ativo` (`ativo`),
  ADD KEY `idsubcategoria` (`idsubcategoria`),
  ADD KEY `contas_index_ativo_painel` (`ativo_painel`),
  ADD KEY `contas_ibfk_4` (`idcategoria`),
  ADD KEY `contas_ibfk_5` (`idsindicato`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`idproduto`),
  ADD KEY `produtos_index_ativo` (`ativo`),
  ADD KEY `produtos_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `produtos_fornecedores`
--
ALTER TABLE `produtos_fornecedores`
  ADD PRIMARY KEY (`idproduto_fornecedor`),
  ADD KEY `produtos_fornecedores_idproduto` (`idproduto`),
  ADD KEY `produtos_fornecedores_idfornecedor` (`idfornecedor`);

--
-- Índices de tabela `professores`
--
ALTER TABLE `professores`
  ADD PRIMARY KEY (`idprofessor`),
  ADD KEY `professores_index_ativo` (`ativo`),
  ADD KEY `professores_index_ativo_painel` (`ativo_login`);

--
-- Índices de tabela `professores_arquivos`
--
ALTER TABLE `professores_arquivos`
  ADD PRIMARY KEY (`idarquivo`),
  ADD KEY `indice_idprofessor` (`idprofessor`);

--
-- Índices de tabela `professores_avas`
--
ALTER TABLE `professores_avas`
  ADD PRIMARY KEY (`idprofessor_ava`),
  ADD KEY `professores_avas_idprofessor` (`idprofessor`),
  ADD KEY `professores_avas_idava` (`idava`),
  ADD KEY `index_ativo` (`ativo`);

--
-- Índices de tabela `professores_cursos`
--
ALTER TABLE `professores_cursos`
  ADD PRIMARY KEY (`idprofessor_curso`),
  ADD KEY `professores_cursos_idprofessor` (`idprofessor`),
  ADD KEY `professores_cursos_idcurso` (`idcurso`),
  ADD KEY `index_ativo` (`ativo`);

--
-- Índices de tabela `professores_disciplinas`
--
ALTER TABLE `professores_disciplinas`
  ADD PRIMARY KEY (`idprofessor_disciplina`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `professores_disciplinas_idprofessor` (`idprofessor`),
  ADD KEY `professores_disciplinas_idava` (`iddisciplina`);

--
-- Índices de tabela `professores_ofertas`
--
ALTER TABLE `professores_ofertas`
  ADD PRIMARY KEY (`idprofessor_oferta`),
  ADD KEY `professores_ofertas_idprofessor` (`idprofessor`),
  ADD KEY `professores_ofertas_idoferta` (`idoferta`),
  ADD KEY `index_ativo` (`ativo`);

--
-- Índices de tabela `provas_impressas`
--
ALTER TABLE `provas_impressas`
  ADD PRIMARY KEY (`idprova_impressa`);

--
-- Índices de tabela `provas_impressas_disciplinas`
--
ALTER TABLE `provas_impressas_disciplinas`
  ADD PRIMARY KEY (`idprova_impressa_disciplina`),
  ADD KEY `provas_impressas_disciplinas_idprova_impressa` (`idprova_impressa`),
  ADD KEY `provas_impressas_disciplinas_iddisciplina` (`iddisciplina`);

--
-- Índices de tabela `provas_impressas_perguntas`
--
ALTER TABLE `provas_impressas_perguntas`
  ADD PRIMARY KEY (`idprova_impressa_pergunta`),
  ADD KEY `FK_provas_impressas_perguntas_idprova_impressa` (`idprova_impressa`),
  ADD KEY `FK_provas_impressas_perguntas_idpergunta` (`idpergunta`);

--
-- Índices de tabela `provas_presenciais`
--
ALTER TABLE `provas_presenciais`
  ADD PRIMARY KEY (`id_prova_presencial`);

--
-- Índices de tabela `provas_presenciais_escolas`
--
ALTER TABLE `provas_presenciais_escolas`
  ADD PRIMARY KEY (`id_prova_escola`),
  ADD KEY `provas_presenciais_escolas_id_prova_presencial` (`id_prova_presencial`),
  ADD KEY `provas_presenciais_escolas_idescola` (`idescola`);

--
-- Índices de tabela `provas_presenciais_locais_provas`
--
ALTER TABLE `provas_presenciais_locais_provas`
  ADD PRIMARY KEY (`id_prova_local`),
  ADD KEY `provas_presenciais_locais_id_prova_presencial` (`id_prova_presencial`),
  ADD KEY `provas_presenciais_locais_idlocal` (`idlocal`);

--
-- Índices de tabela `provas_solicitadas`
--
ALTER TABLE `provas_solicitadas`
  ADD PRIMARY KEY (`id_solicitacao_prova`),
  ADD KEY `provas_solicitadas_idmatricula` (`idmatricula`),
  ADD KEY `provas_solicitadas_idcurso` (`idcurso`),
  ADD KEY `provas_solicitadas_id_prova_presencial` (`id_prova_presencial`),
  ADD KEY `provas_solicitadas_idmotivo` (`idmotivo`),
  ADD KEY `provas_solicitadas_idlocal` (`idlocal`),
  ADD KEY `provas_solicitadas_idescola` (`idescola`);

--
-- Índices de tabela `provas_solicitadas_disciplinas`
--
ALTER TABLE `provas_solicitadas_disciplinas`
  ADD PRIMARY KEY (`id_solicitacao_prova_disciplina`),
  ADD KEY `provas_solicitadas_disciplinas_id_solicitacao_prova` (`id_solicitacao_prova`),
  ADD KEY `provas_solicitadas_disciplinas_iddisciplina` (`iddisciplina`);

--
-- Índices de tabela `quadros_avisos`
--
ALTER TABLE `quadros_avisos`
  ADD PRIMARY KEY (`idquadro`),
  ADD KEY `index_ativo` (`ativo`);

--
-- Índices de tabela `quadros_avisos_cursos`
--
ALTER TABLE `quadros_avisos_cursos`
  ADD PRIMARY KEY (`idquadro_curso`),
  ADD KEY `quadros_avisos_cursos_idquadro` (`idquadro`),
  ADD KEY `quadros_avisos_cursos_idcurso` (`idcurso`),
  ADD KEY `index_ativo` (`ativo`);

--
-- Índices de tabela `quadros_avisos_escolas`
--
ALTER TABLE `quadros_avisos_escolas`
  ADD PRIMARY KEY (`idquadro_escola`),
  ADD KEY `quadros_avisos_escolas_idquadro` (`idquadro`),
  ADD KEY `quadros_avisos_escolas_idescola` (`idescola`),
  ADD KEY `index_ativo` (`ativo`);

--
-- Índices de tabela `quadros_avisos_imagens`
--
ALTER TABLE `quadros_avisos_imagens`
  ADD PRIMARY KEY (`idquadro_imagem`),
  ADD KEY `FK_quadros_avisos_idquadro` (`idquadro`);

--
-- Índices de tabela `quadros_avisos_ofertas`
--
ALTER TABLE `quadros_avisos_ofertas`
  ADD PRIMARY KEY (`idquadro_oferta`),
  ADD KEY `quadros_avisos_ofertas_idquadro` (`idquadro`),
  ADD KEY `quadros_avisos_ofertas_idoferta` (`idoferta`),
  ADD KEY `index_ativo` (`ativo`);

--
-- Índices de tabela `racas`
--
ALTER TABLE `racas`
  ADD PRIMARY KEY (`idraca`),
  ADD KEY `racas_index_ativo` (`ativo`),
  ADD KEY `racas_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `reconhecimento_fotos`
--
ALTER TABLE `reconhecimento_fotos`
  ADD PRIMARY KEY (`idreconhecimento`),
  ADD KEY `reconhecimento_fotos_FK` (`idmatricula`);

--
-- Índices de tabela `regioes`
--
ALTER TABLE `regioes`
  ADD PRIMARY KEY (`idregiao`);

--
-- Índices de tabela `relacionamentos_comerciais`
--
ALTER TABLE `relacionamentos_comerciais`
  ADD PRIMARY KEY (`idrelacionamento`),
  ADD KEY `relacionamentos_comercial_idusuario` (`idusuario`),
  ADD KEY `relacionamentos_comercial_idvendedor` (`idvendedor`),
  ADD KEY `ID_relacionamentos_comerciais_email_pessoa` (`email_pessoa`),
  ADD KEY `relacionamentos_comerciais_idpessoa` (`idpessoa`);

--
-- Índices de tabela `relacionamentos_comerciais_historicos`
--
ALTER TABLE `relacionamentos_comerciais_historicos`
  ADD PRIMARY KEY (`idhistorico`),
  ADD KEY `relacionamentos_comerciais_historicos_idrelacionamento` (`idrelacionamento`),
  ADD KEY `relacionamentos_comerciais_historicos_idusuario` (`idusuario`),
  ADD KEY `relacionamentos_comerciais_historicos_tipo_id` (`tipo`,`id`);

--
-- Índices de tabela `relacionamentos_comerciais_mensagens`
--
ALTER TABLE `relacionamentos_comerciais_mensagens`
  ADD PRIMARY KEY (`idmensagem`),
  ADD KEY `relacionamentos_comerciais_mensagens_idrelacionamento` (`idrelacionamento`),
  ADD KEY `relacionamentos_comerciais_mensagens_idvendedor` (`idvendedor`),
  ADD KEY `relacionamentos_comerciais_mensagens_idusuario` (`idusuario`);

--
-- Índices de tabela `relacionamentos_comercial`
--
ALTER TABLE `relacionamentos_comercial`
  ADD PRIMARY KEY (`idmensagem`),
  ADD KEY `relacionamentos_comercial_idusuario` (`idusuario`),
  ADD KEY `relacionamentos_comercial_idpessoa` (`idpessoa`),
  ADD KEY `relacionamentos_comercial_idvendedor` (`idvendedor`);

--
-- Índices de tabela `relacionamentos_pedagogico`
--
ALTER TABLE `relacionamentos_pedagogico`
  ADD PRIMARY KEY (`idmensagem`),
  ADD KEY `relacionamentos_pedagogico_idusuario` (`idusuario`),
  ADD KEY `relacionamentos_pedagogico_idpessoa` (`idpessoa`);

--
-- Índices de tabela `relatorios`
--
ALTER TABLE `relatorios`
  ADD PRIMARY KEY (`idrelatorio`);

--
-- Índices de tabela `religioes`
--
ALTER TABLE `religioes`
  ADD PRIMARY KEY (`idreligiao`),
  ADD KEY `religioes_index_ativo` (`ativo`),
  ADD KEY `religioes_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `retornos`
--
ALTER TABLE `retornos`
  ADD PRIMARY KEY (`idretorno`),
  ADD KEY `ativo` (`ativo`),
  ADD KEY `idusuario` (`idusuario`),
  ADD KEY `idfechamento` (`idfechamento`);

--
-- Índices de tabela `retornos_contas`
--
ALTER TABLE `retornos_contas`
  ADD PRIMARY KEY (`idretorno_conta`),
  ADD KEY `idconta` (`idconta`),
  ADD KEY `idretorno` (`idretorno`);

--
-- Índices de tabela `retornos_sindicatos`
--
ALTER TABLE `retornos_sindicatos`
  ADD PRIMARY KEY (`idretorno_sindicato`),
  ADD KEY `idretorno` (`idretorno`,`idsindicato`);

--
-- Índices de tabela `sindicatos`
--
ALTER TABLE `sindicatos`
  ADD PRIMARY KEY (`idsindicato`),
  ADD KEY `sindicatos_index_ativo` (`ativo`),
  ADD KEY `sindicatos_index_ativo_painel` (`ativo_painel`),
  ADD KEY `sindicatos_idlogradouro_idx` (`idlogradouro`),
  ADD KEY `sindicatos_idcidade_idx` (`idcidade`),
  ADD KEY `sindicatos_idestado_idx` (`idestado`),
  ADD KEY `sindicatos_idmantenedora_idx` (`idmantenedora`);

--
-- Índices de tabela `sindicatos_arquivos`
--
ALTER TABLE `sindicatos_arquivos`
  ADD PRIMARY KEY (`idarquivo`),
  ADD KEY `indice_idsindicato` (`idsindicato`);

--
-- Índices de tabela `sindicatos_formas_pagamento`
--
ALTER TABLE `sindicatos_formas_pagamento`
  ADD PRIMARY KEY (`idsindicato_forma_pagamento`),
  ADD KEY `index2` (`idsindicato`),
  ADD KEY `index3` (`ativo`);

--
-- Índices de tabela `sindicatos_valores_cursos`
--
ALTER TABLE `sindicatos_valores_cursos`
  ADD PRIMARY KEY (`idvalor_curso`),
  ADD KEY `FK_sindicatos_valores_cursos_escolas` (`idsindicato`),
  ADD KEY `Index_valores_cursos_ativo` (`ativo`),
  ADD KEY `FK_sindicatos_valores_cursos_idcurso` (`idcurso`);

--
-- Índices de tabela `sms_automaticos_log`
--
ALTER TABLE `sms_automaticos_log`
  ADD PRIMARY KEY (`idsms_log`),
  ADD KEY `sms_automaticos_log_index_ativo` (`ativo`);

--
-- Índices de tabela `solicitacoes_cadastros_portal`
--
ALTER TABLE `solicitacoes_cadastros_portal`
  ADD PRIMARY KEY (`idsolicitacao`);

--
-- Índices de tabela `solicitacoes_senhas`
--
ALTER TABLE `solicitacoes_senhas`
  ADD PRIMARY KEY (`idsolicitacao_senha`);

--
-- Índices de tabela `solicitantes_bolsas`
--
ALTER TABLE `solicitantes_bolsas`
  ADD PRIMARY KEY (`idsolicitante`),
  ADD KEY `mantenedoras_idcidade_idx` (`idcidade`),
  ADD KEY `mantenedoras_idestado_idx` (`idestado`),
  ADD KEY `mantenedoras_idlogradouro_idx` (`idlogradouro`),
  ADD KEY `mantenedoras_index_ativo` (`ativo`),
  ADD KEY `mantenedoras_index_ativo_painel` (`ativo_painel`),
  ADD KEY `atendimentos_idmantenedora` (`idsindicato`);

--
-- Índices de tabela `tipos_contatos`
--
ALTER TABLE `tipos_contatos`
  ADD PRIMARY KEY (`idtipo`),
  ADD KEY `Index_Contatos_ativo` (`ativo`);

--
-- Índices de tabela `tipos_contratos`
--
ALTER TABLE `tipos_contratos`
  ADD PRIMARY KEY (`idtipo`),
  ADD KEY `tipos_contratos_index_ativo` (`ativo`),
  ADD KEY `tipos_contratos_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `tipos_documentos`
--
ALTER TABLE `tipos_documentos`
  ADD PRIMARY KEY (`idtipo`),
  ADD KEY `documentos_tipos_index_ativo` (`ativo`),
  ADD KEY `documentos_tipos_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `tipos_documentos_cursos`
--
ALTER TABLE `tipos_documentos_cursos`
  ADD PRIMARY KEY (`idtipo_curso`),
  ADD UNIQUE KEY `index_idtipo_curso` (`idtipo`,`idcurso`),
  ADD KEY `tipos_documentos_cursos_idtipo` (`idtipo`),
  ADD KEY `Index_Ativo` (`ativo`),
  ADD KEY `tipos_documentos_cursos_idcurso` (`idcurso`);

--
-- Índices de tabela `tipos_documentos_sindicatos`
--
ALTER TABLE `tipos_documentos_sindicatos`
  ADD PRIMARY KEY (`idtipo_sindicato`),
  ADD KEY `tipos_documentos_sindicatos_idtipo` (`idtipo`),
  ADD KEY `Index_Ativo` (`ativo`),
  ADD KEY `tipos_documentos_sindicatos_idsindicato` (`idsindicato`),
  ADD KEY `index_idtipo_sindicato` (`idtipo`,`idsindicato`);

--
-- Índices de tabela `tipos_documentos_sindicatos_agendamento`
--
ALTER TABLE `tipos_documentos_sindicatos_agendamento`
  ADD PRIMARY KEY (`idtipo_sindicato`),
  ADD KEY `tipos_documentos_sindicatos_idtipo` (`idtipo`),
  ADD KEY `Index_Ativo` (`ativo`),
  ADD KEY `tipos_documentos_sindicatos_idsindicato` (`idsindicato`),
  ADD KEY `index_idtipo_sindicato` (`idtipo`,`idsindicato`);

--
-- Índices de tabela `turmas`
--
ALTER TABLE `turmas`
  ADD PRIMARY KEY (`idturma`),
  ADD KEY `turmas_index_ativo` (`ativo`),
  ADD KEY `turmas_index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `usuarios_adm`
--
ALTER TABLE `usuarios_adm`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `usuarios_adm_idperfil` (`idperfil`),
  ADD KEY `usuarios_adm_idestado` (`idestado`),
  ADD KEY `usuarios_adm_idcidade` (`idcidade`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `index_ativo_login` (`ativo_login`),
  ADD KEY `relogin` (`relogin`),
  ADD KEY `idexcecao` (`idexcecao`);

--
-- Índices de tabela `usuarios_adm_perfis`
--
ALTER TABLE `usuarios_adm_perfis`
  ADD PRIMARY KEY (`idperfil`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `index_ativo_painel` (`ativo_painel`);

--
-- Índices de tabela `usuarios_adm_sindicatos`
--
ALTER TABLE `usuarios_adm_sindicatos`
  ADD PRIMARY KEY (`idusuario_sindicato`),
  ADD KEY `usuarios_adm_sindicatos_idsindicato` (`idsindicato`),
  ADD KEY `usuarios_adm_sindicatos_idusuario` (`idusuario`);

--
-- Índices de tabela `vendedores`
--
ALTER TABLE `vendedores`
  ADD PRIMARY KEY (`idvendedor`),
  ADD KEY `vendedores_index_ativo` (`ativo`),
  ADD KEY `vendedores_index_ativo_painel` (`ativo_login`),
  ADD KEY `vendedores_idlogradouro` (`idlogradouro`),
  ADD KEY `vendedores_idestado` (`idestado`),
  ADD KEY `vendedores_idcidade` (`idcidade`);

--
-- Índices de tabela `vendedores_contatos`
--
ALTER TABLE `vendedores_contatos`
  ADD PRIMARY KEY (`idcontato`),
  ADD KEY `index_vendedores_contatos_ativo` (`ativo`),
  ADD KEY `vendedores_contatos_idvendedor` (`idvendedor`),
  ADD KEY `vendedores_contatos_idtipo` (`idtipo`);

--
-- Índices de tabela `vendedores_escolas`
--
ALTER TABLE `vendedores_escolas`
  ADD PRIMARY KEY (`idvendedor_escola`);

--
-- Índices de tabela `vendedores_sindicatos`
--
ALTER TABLE `vendedores_sindicatos`
  ADD PRIMARY KEY (`idvendedor_sindicato`),
  ADD KEY `vendedores_sindicatos_idsindicato` (`idsindicato`),
  ADD KEY `vendedores_sindicatos_idvendedor` (`idvendedor`);

--
-- Índices de tabela `videotecas`
--
ALTER TABLE `videotecas`
  ADD PRIMARY KEY (`idvideo`);

--
-- Índices de tabela `videotecas_pastas`
--
ALTER TABLE `videotecas_pastas`
  ADD PRIMARY KEY (`idpasta`);

--
-- Índices de tabela `videotecas_tags`
--
ALTER TABLE `videotecas_tags`
  ADD PRIMARY KEY (`idtag`);

--
-- Índices de tabela `videotecas_tags_videos`
--
ALTER TABLE `videotecas_tags_videos`
  ADD PRIMARY KEY (`idtagvideo`),
  ADD KEY `idtag` (`idtag`),
  ADD KEY `idvideo` (`idvideo`),
  ADD KEY `idvideo_2` (`idvideo`);

--
-- Índices de tabela `visitas_mensagens`
--
ALTER TABLE `visitas_mensagens`
  ADD PRIMARY KEY (`idmensagem`),
  ADD KEY `visitas_mensagens_idvisita` (`idvisita`),
  ADD KEY `visitas_mensagens_idvendedor` (`idvendedor`),
  ADD KEY `visitas_mensagens_idusuario` (`idusuario`);

--
-- Índices de tabela `visitas_vendedores`
--
ALTER TABLE `visitas_vendedores`
  ADD PRIMARY KEY (`idvisita`),
  ADD KEY `visitas_vendedores_idpessoa` (`idpessoa`),
  ADD KEY `visitas_vendedores_idvendedor` (`idvendedor`),
  ADD KEY `visitas_vendedores_idusuario` (`idusuario`),
  ADD KEY `index_ativo` (`ativo`),
  ADD KEY `idmidia` (`idmidia`),
  ADD KEY `idlocal` (`idlocal`),
  ADD KEY `idmotivo` (`idmotivo`),
  ADD KEY `email` (`email`),
  ADD KEY `idcidade` (`idcidade`,`idestado`),
  ADD KEY `visitas_vendedores_ibfk_3` (`idmatricula`);

--
-- Índices de tabela `visitas_vendedores_cursos`
--
ALTER TABLE `visitas_vendedores_cursos`
  ADD PRIMARY KEY (`idvisita_curso`),
  ADD KEY `idvisita` (`idvisita`,`idcurso`),
  ADD KEY `idcurso` (`idcurso`);

--
-- Índices de tabela `visitas_vendedores_iteracoes`
--
ALTER TABLE `visitas_vendedores_iteracoes`
  ADD PRIMARY KEY (`iditeracao`),
  ADD KEY `visitas_vendedores_iteracoes_idvisita` (`idvisita`),
  ADD KEY `visitas_vendedores_iteracoes_ativo` (`ativo`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `areas`
--
ALTER TABLE `areas`
  MODIFY `idarea` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos`
--
ALTER TABLE `atendimentos`
  MODIFY `idatendimento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_arquivos`
--
ALTER TABLE `atendimentos_arquivos`
  MODIFY `idarquivo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_assuntos`
--
ALTER TABLE `atendimentos_assuntos`
  MODIFY `idassunto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_assuntos_grupos`
--
ALTER TABLE `atendimentos_assuntos_grupos`
  MODIFY `idassunto_grupo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_assuntos_subassuntos`
--
ALTER TABLE `atendimentos_assuntos_subassuntos`
  MODIFY `idsubassunto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_assuntos_subassuntos_grupos`
--
ALTER TABLE `atendimentos_assuntos_subassuntos_grupos`
  MODIFY `idsubassunto_grupo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_checklists_opcoes_marcados`
--
ALTER TABLE `atendimentos_checklists_opcoes_marcados`
  MODIFY `idmarcada` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_historicos`
--
ALTER TABLE `atendimentos_historicos`
  MODIFY `idhistorico` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_respostas`
--
ALTER TABLE `atendimentos_respostas`
  MODIFY `idresposta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_respostas_automaticas`
--
ALTER TABLE `atendimentos_respostas_automaticas`
  MODIFY `idresposta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_respostas_automaticas_assuntos`
--
ALTER TABLE `atendimentos_respostas_automaticas_assuntos`
  MODIFY `idresposta_assunto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_sla`
--
ALTER TABLE `atendimentos_sla`
  MODIFY `idsla` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_workflow`
--
ALTER TABLE `atendimentos_workflow`
  MODIFY `idsituacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_workflow_acoes`
--
ALTER TABLE `atendimentos_workflow_acoes`
  MODIFY `idacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_workflow_acoes_parametros`
--
ALTER TABLE `atendimentos_workflow_acoes_parametros`
  MODIFY `idacaoparametro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atendimentos_workflow_relacionamentos`
--
ALTER TABLE `atendimentos_workflow_relacionamentos`
  MODIFY `idrelacionamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `atentimentos_repostas_escolas`
--
ALTER TABLE `atentimentos_repostas_escolas`
  MODIFY `idassociacao` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas`
--
ALTER TABLE `avas`
  MODIFY `idava` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_audios`
--
ALTER TABLE `avas_audios`
  MODIFY `idaudio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_avaliacoes`
--
ALTER TABLE `avas_avaliacoes`
  MODIFY `idavaliacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_avaliacoes_disciplinas`
--
ALTER TABLE `avas_avaliacoes_disciplinas`
  MODIFY `idavaliacao_disciplina` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_chats`
--
ALTER TABLE `avas_chats`
  MODIFY `idchat` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_conteudos`
--
ALTER TABLE `avas_conteudos`
  MODIFY `idconteudo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_conteudos_frames`
--
ALTER TABLE `avas_conteudos_frames`
  MODIFY `idframe` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_conteudos_linksacoes`
--
ALTER TABLE `avas_conteudos_linksacoes`
  MODIFY `idlinkacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_dicosvirtuais_pastas`
--
ALTER TABLE `avas_dicosvirtuais_pastas`
  MODIFY `id_pasta` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_disciplinas`
--
ALTER TABLE `avas_disciplinas`
  MODIFY `idava_disciplina` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_discosvirtuais`
--
ALTER TABLE `avas_discosvirtuais`
  MODIFY `id_discovirtual` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_downloads`
--
ALTER TABLE `avas_downloads`
  MODIFY `iddownload` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_downloads_pastas`
--
ALTER TABLE `avas_downloads_pastas`
  MODIFY `idpasta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_enquetes`
--
ALTER TABLE `avas_enquetes`
  MODIFY `idenquete` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_enquetes_opcoes`
--
ALTER TABLE `avas_enquetes_opcoes`
  MODIFY `idopcao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_enquetes_opcoes_votos`
--
ALTER TABLE `avas_enquetes_opcoes_votos`
  MODIFY `idvoto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_exercicios`
--
ALTER TABLE `avas_exercicios`
  MODIFY `idexercicio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_exercicios_disciplinas`
--
ALTER TABLE `avas_exercicios_disciplinas`
  MODIFY `idexercicio_disciplina` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_faqs`
--
ALTER TABLE `avas_faqs`
  MODIFY `idfaq` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_foruns`
--
ALTER TABLE `avas_foruns`
  MODIFY `idforum` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_foruns_topicos`
--
ALTER TABLE `avas_foruns_topicos`
  MODIFY `idtopico` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_foruns_topicos_assinantes`
--
ALTER TABLE `avas_foruns_topicos_assinantes`
  MODIFY `idassinatura` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_foruns_topicos_assinantes_mensagens`
--
ALTER TABLE `avas_foruns_topicos_assinantes_mensagens`
  MODIFY `idassinatura_mensagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_foruns_topicos_curtidas`
--
ALTER TABLE `avas_foruns_topicos_curtidas`
  MODIFY `idcurtida` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_foruns_topicos_mensagens`
--
ALTER TABLE `avas_foruns_topicos_mensagens`
  MODIFY `idmensagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_links`
--
ALTER TABLE `avas_links`
  MODIFY `idlink` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_mensagem_instantanea`
--
ALTER TABLE `avas_mensagem_instantanea`
  MODIFY `idmensagem_instantanea` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_mensagem_instantanea_conversas`
--
ALTER TABLE `avas_mensagem_instantanea_conversas`
  MODIFY `idmensagem_instantanea_conversa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_mensagem_instantanea_conversas_visualizar`
--
ALTER TABLE `avas_mensagem_instantanea_conversas_visualizar`
  MODIFY `idmensagem_instantanea_conversas_visualizar` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_mensagem_instantanea_integrantes`
--
ALTER TABLE `avas_mensagem_instantanea_integrantes`
  MODIFY `idmensagem_instantanea_integrante` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_mensagens`
--
ALTER TABLE `avas_mensagens`
  MODIFY `idmensagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_mensagens_texto`
--
ALTER TABLE `avas_mensagens_texto`
  MODIFY `idmensagem_texto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_objetos_divisores`
--
ALTER TABLE `avas_objetos_divisores`
  MODIFY `idobjeto_divisor` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_perguntas`
--
ALTER TABLE `avas_perguntas`
  MODIFY `idpergunta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_rotas_aprendizagem`
--
ALTER TABLE `avas_rotas_aprendizagem`
  MODIFY `idrota_aprendizagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_rotas_aprendizagem_objetos`
--
ALTER TABLE `avas_rotas_aprendizagem_objetos`
  MODIFY `idobjeto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_simulados`
--
ALTER TABLE `avas_simulados`
  MODIFY `idsimulado` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_simulados_disciplinas`
--
ALTER TABLE `avas_simulados_disciplinas`
  MODIFY `idsimulado_disciplina` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_tiraduvidas`
--
ALTER TABLE `avas_tiraduvidas`
  MODIFY `idtiraduvida` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_tiraduvidas_categorias`
--
ALTER TABLE `avas_tiraduvidas_categorias`
  MODIFY `idcategoria` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_tiraduvidas_categorias_professores`
--
ALTER TABLE `avas_tiraduvidas_categorias_professores`
  MODIFY `idcategoria_professor` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_tiraduvidas_mensagens`
--
ALTER TABLE `avas_tiraduvidas_mensagens`
  MODIFY `idmensagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_tira_duvidas`
--
ALTER TABLE `avas_tira_duvidas`
  MODIFY `idduvida` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avas_videotecas`
--
ALTER TABLE `avas_videotecas`
  MODIFY `idvideo` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `bancos`
--
ALTER TABLE `bancos`
  MODIFY `idbanco` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `bandeiras_cartoes`
--
ALTER TABLE `bandeiras_cartoes`
  MODIFY `idbandeira` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `banners_ava_aluno`
--
ALTER TABLE `banners_ava_aluno`
  MODIFY `idbanner` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `banners_ava_aluno_dias`
--
ALTER TABLE `banners_ava_aluno_dias`
  MODIFY `idbanner_dia` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `banners_escolas`
--
ALTER TABLE `banners_escolas`
  MODIFY `idbanner_escola` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `banners_sindicatos`
--
ALTER TABLE `banners_sindicatos`
  MODIFY `idbanner_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cartoes`
--
ALTER TABLE `cartoes`
  MODIFY `idcartao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cartoes_sindicatos`
--
ALTER TABLE `cartoes_sindicatos`
  MODIFY `idcartao_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `idcategoria` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categorias_subcategorias`
--
ALTER TABLE `categorias_subcategorias`
  MODIFY `idsubcategoria` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categorias_subcategorias_sindicatos`
--
ALTER TABLE `categorias_subcategorias_sindicatos`
  MODIFY `idassociacao` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `centros_custos`
--
ALTER TABLE `centros_custos`
  MODIFY `idcentro_custo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `centros_custos_sindicatos`
--
ALTER TABLE `centros_custos_sindicatos`
  MODIFY `idcentro_custo_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `certificados`
--
ALTER TABLE `certificados`
  MODIFY `idcertificado` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `certificados_escolas`
--
ALTER TABLE `certificados_escolas`
  MODIFY `idcertificado_escola` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `certificados_midias`
--
ALTER TABLE `certificados_midias`
  MODIFY `idcertificado_midia` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `certificados_paginas`
--
ALTER TABLE `certificados_paginas`
  MODIFY `certificados_paginas` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cfcs_valores_cursos`
--
ALTER TABLE `cfcs_valores_cursos`
  MODIFY `idvalor_curso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cfc_mensagens`
--
ALTER TABLE `cfc_mensagens`
  MODIFY `idmensagem` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `chats`
--
ALTER TABLE `chats`
  MODIFY `idchat` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `chats_acoes`
--
ALTER TABLE `chats_acoes`
  MODIFY `idchat_acao` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `chats_mensagens`
--
ALTER TABLE `chats_mensagens`
  MODIFY `idchat_mensagem` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `chats_pessoas`
--
ALTER TABLE `chats_pessoas`
  MODIFY `idchat_pessoa` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `checklists`
--
ALTER TABLE `checklists`
  MODIFY `idchecklist` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `checklists_opcoes`
--
ALTER TABLE `checklists_opcoes`
  MODIFY `idopcao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cheques`
--
ALTER TABLE `cheques`
  MODIFY `idcheque` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cheques_alineas`
--
ALTER TABLE `cheques_alineas`
  MODIFY `idcheque_alinea` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cidades`
--
ALTER TABLE `cidades`
  MODIFY `idcidade` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cobrancas_log`
--
ALTER TABLE `cobrancas_log`
  MODIFY `idcobranca` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cofeci_log`
--
ALTER TABLE `cofeci_log`
  MODIFY `idcofecilog` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `comissoes_competencias`
--
ALTER TABLE `comissoes_competencias`
  MODIFY `idcompetencia` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `comissoes_competencias_cursos`
--
ALTER TABLE `comissoes_competencias_cursos`
  MODIFY `idcompetencia_curso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `comissoes_competencias_sindicatos_cursos`
--
ALTER TABLE `comissoes_competencias_sindicatos_cursos`
  MODIFY `idcompetencia_oferta_curso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `comissoes_regras`
--
ALTER TABLE `comissoes_regras`
  MODIFY `idregra` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `comissoes_regras_cursos`
--
ALTER TABLE `comissoes_regras_cursos`
  MODIFY `idregra_curso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `comissoes_regras_sindicatos`
--
ALTER TABLE `comissoes_regras_sindicatos`
  MODIFY `idregra_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `comissoes_regras_valores`
--
ALTER TABLE `comissoes_regras_valores`
  MODIFY `idvalor` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas`
--
ALTER TABLE `contas`
  MODIFY `idconta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_arquivos`
--
ALTER TABLE `contas_arquivos`
  MODIFY `idarquivo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_boletos_gerado`
--
ALTER TABLE `contas_boletos_gerado`
  MODIFY `idboletogerado` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_centros_custos`
--
ALTER TABLE `contas_centros_custos`
  MODIFY `idconta_centro_custo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_correntes`
--
ALTER TABLE `contas_correntes`
  MODIFY `idconta_corrente` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_correntes_fechamentos`
--
ALTER TABLE `contas_correntes_fechamentos`
  MODIFY `idconta_corrente_fechamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_correntes_fechamentos_cfc`
--
ALTER TABLE `contas_correntes_fechamentos_cfc`
  MODIFY `idconta_corrente_fechamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_correntes_sindicatos`
--
ALTER TABLE `contas_correntes_sindicatos`
  MODIFY `idconta_corrente_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_historicos`
--
ALTER TABLE `contas_historicos`
  MODIFY `idhistorico` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_matriculas`
--
ALTER TABLE `contas_matriculas`
  MODIFY `idconta_matricula` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_orcamentos`
--
ALTER TABLE `contas_orcamentos`
  MODIFY `idorcamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_pagamentos`
--
ALTER TABLE `contas_pagamentos`
  MODIFY `idpagamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_previsoes`
--
ALTER TABLE `contas_previsoes`
  MODIFY `idprevisao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_relacoes`
--
ALTER TABLE `contas_relacoes`
  MODIFY `idrelacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_workflow`
--
ALTER TABLE `contas_workflow`
  MODIFY `idsituacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_workflow_acoes`
--
ALTER TABLE `contas_workflow_acoes`
  MODIFY `idacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_workflow_acoes_parametros`
--
ALTER TABLE `contas_workflow_acoes_parametros`
  MODIFY `idacaoparametro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_workflow_relacionamentos`
--
ALTER TABLE `contas_workflow_relacionamentos`
  MODIFY `idrelacionamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contratos`
--
ALTER TABLE `contratos`
  MODIFY `idcontrato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contratos_cursos`
--
ALTER TABLE `contratos_cursos`
  MODIFY `idcontrato_curso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contratos_grupos`
--
ALTER TABLE `contratos_grupos`
  MODIFY `idgrupo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contratos_grupos_contratos`
--
ALTER TABLE `contratos_grupos_contratos`
  MODIFY `idgrupo_contrato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contratos_imagens`
--
ALTER TABLE `contratos_imagens`
  MODIFY `idimagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contratos_sindicatos`
--
ALTER TABLE `contratos_sindicatos`
  MODIFY `idcontrato_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contratos_tipos`
--
ALTER TABLE `contratos_tipos`
  MODIFY `idtipo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cupons`
--
ALTER TABLE `cupons`
  MODIFY `idcupom` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cupons_cursos`
--
ALTER TABLE `cupons_cursos`
  MODIFY `idcupom_curso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cupons_escolas`
--
ALTER TABLE `cupons_escolas`
  MODIFY `idcupom_escola` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `curriculos`
--
ALTER TABLE `curriculos`
  MODIFY `idcurriculo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `curriculos_arquivos`
--
ALTER TABLE `curriculos_arquivos`
  MODIFY `idarquivo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `curriculos_avaliacoes`
--
ALTER TABLE `curriculos_avaliacoes`
  MODIFY `idavaliacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `curriculos_blocos`
--
ALTER TABLE `curriculos_blocos`
  MODIFY `idbloco` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `curriculos_blocos_disciplinas`
--
ALTER TABLE `curriculos_blocos_disciplinas`
  MODIFY `idbloco_disciplina` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `curriculos_notas_tipos`
--
ALTER TABLE `curriculos_notas_tipos`
  MODIFY `idcurriculo_tipo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `curriculos_sindicatos`
--
ALTER TABLE `curriculos_sindicatos`
  MODIFY `idcurriculo_sindicatos` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `idcurso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cursos_areas`
--
ALTER TABLE `cursos_areas`
  MODIFY `idcurso_area` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cursos_sindicatos`
--
ALTER TABLE `cursos_sindicatos`
  MODIFY `idcurso_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `declaracoes`
--
ALTER TABLE `declaracoes`
  MODIFY `iddeclaracao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `declaracoes_cursos`
--
ALTER TABLE `declaracoes_cursos`
  MODIFY `iddeclaracao_curso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `declaracoes_grupos`
--
ALTER TABLE `declaracoes_grupos`
  MODIFY `idgrupo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `declaracoes_grupos_declaracoes`
--
ALTER TABLE `declaracoes_grupos_declaracoes`
  MODIFY `idgrupo_declaracao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `declaracoes_imagens`
--
ALTER TABLE `declaracoes_imagens`
  MODIFY `iddeclaracao_imagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `declaracoes_sindicatos`
--
ALTER TABLE `declaracoes_sindicatos`
  MODIFY `iddeclaracao_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `declaracoes_tipos`
--
ALTER TABLE `declaracoes_tipos`
  MODIFY `idtipo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `detran_logs`
--
ALTER TABLE `detran_logs`
  MODIFY `idlog` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `detran_matriculas_disciplinas_enviadas`
--
ALTER TABLE `detran_matriculas_disciplinas_enviadas`
  MODIFY `idenviado` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `diplomas`
--
ALTER TABLE `diplomas`
  MODIFY `iddiploma` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `iddisciplina` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `disciplinas_cursos`
--
ALTER TABLE `disciplinas_cursos`
  MODIFY `iddisciplina_curso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `disciplinas_perguntas`
--
ALTER TABLE `disciplinas_perguntas`
  MODIFY `iddisciplina_pergunta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `emails_automaticos`
--
ALTER TABLE `emails_automaticos`
  MODIFY `idemail` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `emails_automaticos_adm`
--
ALTER TABLE `emails_automaticos_adm`
  MODIFY `idemail` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `emails_automaticos_cursos`
--
ALTER TABLE `emails_automaticos_cursos`
  MODIFY `idemail_curso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `emails_automaticos_cursos_adm`
--
ALTER TABLE `emails_automaticos_cursos_adm`
  MODIFY `idemail_curso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `emails_automaticos_log`
--
ALTER TABLE `emails_automaticos_log`
  MODIFY `idemail_log` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `emails_automaticos_log_adm`
--
ALTER TABLE `emails_automaticos_log_adm`
  MODIFY `idemail_log` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `emails_automaticos_ofertas`
--
ALTER TABLE `emails_automaticos_ofertas`
  MODIFY `idemail_oferta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `emails_automaticos_sindicatos`
--
ALTER TABLE `emails_automaticos_sindicatos`
  MODIFY `idemail_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `emails_log`
--
ALTER TABLE `emails_log`
  MODIFY `idemail` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `emails_newsletter`
--
ALTER TABLE `emails_newsletter`
  MODIFY `idemail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `empresas`
--
ALTER TABLE `empresas`
  MODIFY `idempresa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `escolas`
--
ALTER TABLE `escolas`
  MODIFY `idescola` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `escolas_arquivos`
--
ALTER TABLE `escolas_arquivos`
  MODIFY `idarquivo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `escolas_aux`
--
ALTER TABLE `escolas_aux`
  MODIFY `idescola` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `escolas_contatos`
--
ALTER TABLE `escolas_contatos`
  MODIFY `idcontato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `escolas_contratos`
--
ALTER TABLE `escolas_contratos`
  MODIFY `idescola_contrato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `escolas_contratos_gerados`
--
ALTER TABLE `escolas_contratos_gerados`
  MODIFY `idescola_contrato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `escolas_estados_cidades`
--
ALTER TABLE `escolas_estados_cidades`
  MODIFY `idescola_estado_cidade` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `escolas_formas_pagamento`
--
ALTER TABLE `escolas_formas_pagamento`
  MODIFY `idescola_forma_pagamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `escolas_historico`
--
ALTER TABLE `escolas_historico`
  MODIFY `idhistorico` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `estados`
--
ALTER TABLE `estados`
  MODIFY `idestado` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `etiquetas`
--
ALTER TABLE `etiquetas`
  MODIFY `idetiqueta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `eventos_financeiros`
--
ALTER TABLE `eventos_financeiros`
  MODIFY `idevento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `excecoes`
--
ALTER TABLE `excecoes`
  MODIFY `idexcecao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fastconnect`
--
ALTER TABLE `fastconnect`
  MODIFY `idfastconnect` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fastconnect_logs`
--
ALTER TABLE `fastconnect_logs`
  MODIFY `idlog` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fechamentos_caixa`
--
ALTER TABLE `fechamentos_caixa`
  MODIFY `idfechamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fechamentos_caixa_cfc`
--
ALTER TABLE `fechamentos_caixa_cfc`
  MODIFY `idfechamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fechamentos_caixa_cfc_sindicatos`
--
ALTER TABLE `fechamentos_caixa_cfc_sindicatos`
  MODIFY `idfechamento_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fechamentos_caixa_sindicatos`
--
ALTER TABLE `fechamentos_caixa_sindicatos`
  MODIFY `idfechamento_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `feriados`
--
ALTER TABLE `feriados`
  MODIFY `idferiado` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `feriados_cidades`
--
ALTER TABLE `feriados_cidades`
  MODIFY `idferiado_cidade` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `feriados_escolas`
--
ALTER TABLE `feriados_escolas`
  MODIFY `idferiado_escola` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `feriados_estados`
--
ALTER TABLE `feriados_estados`
  MODIFY `idferiado_estado` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `feriados_sindicatos`
--
ALTER TABLE `feriados_sindicatos`
  MODIFY `idferiado_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `folhas_registros_diplomas`
--
ALTER TABLE `folhas_registros_diplomas`
  MODIFY `idfolha` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `folhas_registros_diplomas_matriculas`
--
ALTER TABLE `folhas_registros_diplomas_matriculas`
  MODIFY `idfolha_matricula` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `formulas_notas`
--
ALTER TABLE `formulas_notas`
  MODIFY `idformula` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `formulas_notas_sindicatos`
--
ALTER TABLE `formulas_notas_sindicatos`
  MODIFY `idformula_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `idfornecedor` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `funcionarios_arquivos`
--
ALTER TABLE `funcionarios_arquivos`
  MODIFY `idarquivo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `grupos_usuarios_adm`
--
ALTER TABLE `grupos_usuarios_adm`
  MODIFY `idgrupo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `grupos_usuarios_adm_usuarios`
--
ALTER TABLE `grupos_usuarios_adm_usuarios`
  MODIFY `idgrupo_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `grupos_vendedores_vendedores`
--
ALTER TABLE `grupos_vendedores_vendedores`
  MODIFY `idgrupo_vendedor` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `historico_escolar`
--
ALTER TABLE `historico_escolar`
  MODIFY `idhistorico_escolar` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `historico_escolar_midias`
--
ALTER TABLE `historico_escolar_midias`
  MODIFY `idhistorico_escolar_midia` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `historico_escolar_paginas`
--
ALTER TABLE `historico_escolar_paginas`
  MODIFY `idhistorico_escolar_paginas` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `interesses_mensagens_arquivos`
--
ALTER TABLE `interesses_mensagens_arquivos`
  MODIFY `idarquivo` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `locais_provas`
--
ALTER TABLE `locais_provas`
  MODIFY `idlocal` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `locais_visitas`
--
ALTER TABLE `locais_visitas`
  MODIFY `idlocal` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `logradouros`
--
ALTER TABLE `logradouros`
  MODIFY `idlogradouro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `log_sms`
--
ALTER TABLE `log_sms`
  MODIFY `idlog_sms` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `loja_pedidos`
--
ALTER TABLE `loja_pedidos`
  MODIFY `idpedido` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mailings`
--
ALTER TABLE `mailings`
  MODIFY `idemail` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mailings_fila`
--
ALTER TABLE `mailings_fila`
  MODIFY `idemail_pessoa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mailings_fila_reenvio_historico`
--
ALTER TABLE `mailings_fila_reenvio_historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mailings_filtros`
--
ALTER TABLE `mailings_filtros`
  MODIFY `idfiltro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mailings_imagens`
--
ALTER TABLE `mailings_imagens`
  MODIFY `idemail_imagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mantenedoras`
--
ALTER TABLE `mantenedoras`
  MODIFY `idmantenedora` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas`
--
ALTER TABLE `matriculas`
  MODIFY `idmatricula` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_alunos_historicos`
--
ALTER TABLE `matriculas_alunos_historicos`
  MODIFY `idhistorico` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_anotacoes`
--
ALTER TABLE `matriculas_anotacoes`
  MODIFY `idanotacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_arquivos`
--
ALTER TABLE `matriculas_arquivos`
  MODIFY `idarquivo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_associados`
--
ALTER TABLE `matriculas_associados`
  MODIFY `idassociado` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_avaliacoes`
--
ALTER TABLE `matriculas_avaliacoes`
  MODIFY `idprova` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_avaliacoes_historicos`
--
ALTER TABLE `matriculas_avaliacoes_historicos`
  MODIFY `idhistorico` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_avaliacoes_perguntas`
--
ALTER TABLE `matriculas_avaliacoes_perguntas`
  MODIFY `id_prova_pergunta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_avaliacoes_perguntas_opcoes_marcadas`
--
ALTER TABLE `matriculas_avaliacoes_perguntas_opcoes_marcadas`
  MODIFY `id_prova_pergunta_opcao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_avas_porcentagem`
--
ALTER TABLE `matriculas_avas_porcentagem`
  MODIFY `idmatricula_ava_porcentagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_comparacoes_fotos`
--
ALTER TABLE `matriculas_comparacoes_fotos`
  MODIFY `idfoto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_contratos`
--
ALTER TABLE `matriculas_contratos`
  MODIFY `idmatricula_contrato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_contratos_gerados`
--
ALTER TABLE `matriculas_contratos_gerados`
  MODIFY `idmatricula_contrato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_declaracoes`
--
ALTER TABLE `matriculas_declaracoes`
  MODIFY `idmatriculadeclaracao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_disciplinas_notas`
--
ALTER TABLE `matriculas_disciplinas_notas`
  MODIFY `idmatricula_disciplina_nota` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_documentos`
--
ALTER TABLE `matriculas_documentos`
  MODIFY `iddocumento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_exercicios`
--
ALTER TABLE `matriculas_exercicios`
  MODIFY `idmatricula_exercicio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_exercicios_perguntas`
--
ALTER TABLE `matriculas_exercicios_perguntas`
  MODIFY `idmatricula_exercicio_pergunta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_exercicios_perguntas_opcoes_marcadas`
--
ALTER TABLE `matriculas_exercicios_perguntas_opcoes_marcadas`
  MODIFY `idmatricula_exercicio_opcao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_historico`
--
ALTER TABLE `matriculas_historico`
  MODIFY `idmatricula_historico` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_historicos`
--
ALTER TABLE `matriculas_historicos`
  MODIFY `idhistorico` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_linksacoes_cliques`
--
ALTER TABLE `matriculas_linksacoes_cliques`
  MODIFY `idclique` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_mensagens`
--
ALTER TABLE `matriculas_mensagens`
  MODIFY `idmensagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_mensagens_arquivos`
--
ALTER TABLE `matriculas_mensagens_arquivos`
  MODIFY `idarquivo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_notas`
--
ALTER TABLE `matriculas_notas`
  MODIFY `idmatricula_nota` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_notas_tipos`
--
ALTER TABLE `matriculas_notas_tipos`
  MODIFY `idtipo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_objetos_favoritos`
--
ALTER TABLE `matriculas_objetos_favoritos`
  MODIFY `idfavorito` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_reconhecimentos`
--
ALTER TABLE `matriculas_reconhecimentos`
  MODIFY `idfoto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_rotas_aprendizagem_objetos`
--
ALTER TABLE `matriculas_rotas_aprendizagem_objetos`
  MODIFY `idmatricula_rota_objeto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_simulados`
--
ALTER TABLE `matriculas_simulados`
  MODIFY `idmatricula_simulado` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_simulados_perguntas`
--
ALTER TABLE `matriculas_simulados_perguntas`
  MODIFY `idmatricula_simulado_pergunta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_simulados_perguntas_opcoes_marcadas`
--
ALTER TABLE `matriculas_simulados_perguntas_opcoes_marcadas`
  MODIFY `idmatricula_simulado_pergunta_opcao_marcada` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_solicitacoes_declaracoes`
--
ALTER TABLE `matriculas_solicitacoes_declaracoes`
  MODIFY `idsolicitacao_declaracao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_workflow`
--
ALTER TABLE `matriculas_workflow`
  MODIFY `idsituacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_workflow_acoes`
--
ALTER TABLE `matriculas_workflow_acoes`
  MODIFY `idacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_workflow_acoes_parametros`
--
ALTER TABLE `matriculas_workflow_acoes_parametros`
  MODIFY `idacaoparametro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `matriculas_workflow_relacionamentos`
--
ALTER TABLE `matriculas_workflow_relacionamentos`
  MODIFY `idrelacionamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mensagens_alerta`
--
ALTER TABLE `mensagens_alerta`
  MODIFY `idmensagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `metas`
--
ALTER TABLE `metas`
  MODIFY `idmeta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `metas_cursos`
--
ALTER TABLE `metas_cursos`
  MODIFY `idmeta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `metas_sindicatos`
--
ALTER TABLE `metas_sindicatos`
  MODIFY `idmeta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `metas_vendedores`
--
ALTER TABLE `metas_vendedores`
  MODIFY `idmeta_vendedor` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `midias_visitas`
--
ALTER TABLE `midias_visitas`
  MODIFY `idmidia` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `modelos_prova`
--
ALTER TABLE `modelos_prova`
  MODIFY `idmodelo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `monitora_adm`
--
ALTER TABLE `monitora_adm`
  MODIFY `idmonitora` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `monitora_adm_log`
--
ALTER TABLE `monitora_adm_log`
  MODIFY `idlog` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `monitora_escola`
--
ALTER TABLE `monitora_escola`
  MODIFY `idmonitora` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `monitora_escola_log`
--
ALTER TABLE `monitora_escola_log`
  MODIFY `idlog` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `monitora_pessoa`
--
ALTER TABLE `monitora_pessoa`
  MODIFY `idmonitora` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `monitora_pessoa_log`
--
ALTER TABLE `monitora_pessoa_log`
  MODIFY `idlog` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `monitora_professor`
--
ALTER TABLE `monitora_professor`
  MODIFY `idmonitora` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `monitora_professor_log`
--
ALTER TABLE `monitora_professor_log`
  MODIFY `idlog` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `monitora_vendedor`
--
ALTER TABLE `monitora_vendedor`
  MODIFY `idmonitora` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `monitora_vendedor_log`
--
ALTER TABLE `monitora_vendedor_log`
  MODIFY `idlog` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `motivos_cancelamento`
--
ALTER TABLE `motivos_cancelamento`
  MODIFY `idmotivo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `motivos_cancelamento_conta`
--
ALTER TABLE `motivos_cancelamento_conta`
  MODIFY `idmotivo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `motivos_cancelamento_solicitacao_prova`
--
ALTER TABLE `motivos_cancelamento_solicitacao_prova`
  MODIFY `idmotivo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `motivos_inatividade`
--
ALTER TABLE `motivos_inatividade`
  MODIFY `idmotivo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `motivos_visitas`
--
ALTER TABLE `motivos_visitas`
  MODIFY `idmotivo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `murais`
--
ALTER TABLE `murais`
  MODIFY `idmural` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `murais_arquivos`
--
ALTER TABLE `murais_arquivos`
  MODIFY `idmural_arquivo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `murais_filas`
--
ALTER TABLE `murais_filas`
  MODIFY `idfila` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `murais_imagens`
--
ALTER TABLE `murais_imagens`
  MODIFY `idmural_imagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ofertas`
--
ALTER TABLE `ofertas`
  MODIFY `idoferta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ofertas_curriculos_avas`
--
ALTER TABLE `ofertas_curriculos_avas`
  MODIFY `idoferta_curriculo_ava` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ofertas_cursos`
--
ALTER TABLE `ofertas_cursos`
  MODIFY `idoferta_curso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ofertas_cursos_escolas`
--
ALTER TABLE `ofertas_cursos_escolas`
  MODIFY `idoferta_curso_escola` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ofertas_cursos_sindicatos`
--
ALTER TABLE `ofertas_cursos_sindicatos`
  MODIFY `idoferta_curso_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ofertas_cursos_workflow`
--
ALTER TABLE `ofertas_cursos_workflow`
  MODIFY `idsituacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ofertas_cursos_workflow_acoes`
--
ALTER TABLE `ofertas_cursos_workflow_acoes`
  MODIFY `idacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ofertas_cursos_workflow_acoes_parametros`
--
ALTER TABLE `ofertas_cursos_workflow_acoes_parametros`
  MODIFY `idacaoparametro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ofertas_cursos_workflow_relacionamentos`
--
ALTER TABLE `ofertas_cursos_workflow_relacionamentos`
  MODIFY `idrelacionamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ofertas_escolas`
--
ALTER TABLE `ofertas_escolas`
  MODIFY `idoferta_escola` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ofertas_turmas`
--
ALTER TABLE `ofertas_turmas`
  MODIFY `idturma` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ofertas_turmas_sindicatos`
--
ALTER TABLE `ofertas_turmas_sindicatos`
  MODIFY `idoferta_turma_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ofertas_workflow`
--
ALTER TABLE `ofertas_workflow`
  MODIFY `idsituacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ofertas_workflow_acoes`
--
ALTER TABLE `ofertas_workflow_acoes`
  MODIFY `idacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ofertas_workflow_acoes_parametros`
--
ALTER TABLE `ofertas_workflow_acoes_parametros`
  MODIFY `idacaoparametro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ofertas_workflow_relacionamentos`
--
ALTER TABLE `ofertas_workflow_relacionamentos`
  MODIFY `idrelacionamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pagamentos_compartilhados`
--
ALTER TABLE `pagamentos_compartilhados`
  MODIFY `idpagamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pagamentos_compartilhados_matriculas`
--
ALTER TABLE `pagamentos_compartilhados_matriculas`
  MODIFY `idpagamento_matricula` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pagarme`
--
ALTER TABLE `pagarme`
  MODIFY `idpagarme` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pagarme_monitora`
--
ALTER TABLE `pagarme_monitora`
  MODIFY `idmonitora` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pagarme_monitora_log`
--
ALTER TABLE `pagarme_monitora_log`
  MODIFY `idlog` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pagseguro`
--
ALTER TABLE `pagseguro`
  MODIFY `idpagseguro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pagseguro_logs`
--
ALTER TABLE `pagseguro_logs`
  MODIFY `idlog` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pagseguro_monitora`
--
ALTER TABLE `pagseguro_monitora`
  MODIFY `idmonitora` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pagseguro_monitora_log`
--
ALTER TABLE `pagseguro_monitora_log`
  MODIFY `idlog` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `paises`
--
ALTER TABLE `paises`
  MODIFY `idpais` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT de tabela `perguntas`
--
ALTER TABLE `perguntas`
  MODIFY `idpergunta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `perguntas_clonar`
--
ALTER TABLE `perguntas_clonar`
  MODIFY `idpergunta_clonar` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `perguntas_opcoes`
--
ALTER TABLE `perguntas_opcoes`
  MODIFY `idopcao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `perguntas_pesquisas`
--
ALTER TABLE `perguntas_pesquisas`
  MODIFY `idpergunta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pesquisas`
--
ALTER TABLE `pesquisas`
  MODIFY `idpesquisa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pesquisas_fila`
--
ALTER TABLE `pesquisas_fila`
  MODIFY `idpesquisa_pessoa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pesquisas_fila_reenvio_historico`
--
ALTER TABLE `pesquisas_fila_reenvio_historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pesquisas_filtros`
--
ALTER TABLE `pesquisas_filtros`
  MODIFY `idfiltro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pesquisas_imagens`
--
ALTER TABLE `pesquisas_imagens`
  MODIFY `idpesquisa_imagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pesquisas_perguntas`
--
ALTER TABLE `pesquisas_perguntas`
  MODIFY `idpesquisa_pergunta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pesquisas_perguntas_opcoes`
--
ALTER TABLE `pesquisas_perguntas_opcoes`
  MODIFY `idopcao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pesquisas_respostas`
--
ALTER TABLE `pesquisas_respostas`
  MODIFY `idpesquisa_resposta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pessoas`
--
ALTER TABLE `pessoas`
  MODIFY `idpessoa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pessoas_acessos`
--
ALTER TABLE `pessoas_acessos`
  MODIFY `idacesso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pessoas_acessos_matriculas`
--
ALTER TABLE `pessoas_acessos_matriculas`
  MODIFY `idacessomatricula` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pessoas_associacoes`
--
ALTER TABLE `pessoas_associacoes`
  MODIFY `idpessoa_associacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pessoas_contatos`
--
ALTER TABLE `pessoas_contatos`
  MODIFY `idcontato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pessoas_sindicatos`
--
ALTER TABLE `pessoas_sindicatos`
  MODIFY `idpessoa_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pessoas_validacoes`
--
ALTER TABLE `pessoas_validacoes`
  MODIFY `idvalidacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pessoas_validacoes_campos`
--
ALTER TABLE `pessoas_validacoes_campos`
  MODIFY `idvalidacaocampo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `previsoes_gastos`
--
ALTER TABLE `previsoes_gastos`
  MODIFY `idprevisao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `idproduto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos_fornecedores`
--
ALTER TABLE `produtos_fornecedores`
  MODIFY `idproduto_fornecedor` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `professores`
--
ALTER TABLE `professores`
  MODIFY `idprofessor` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `professores_arquivos`
--
ALTER TABLE `professores_arquivos`
  MODIFY `idarquivo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `professores_avas`
--
ALTER TABLE `professores_avas`
  MODIFY `idprofessor_ava` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `professores_cursos`
--
ALTER TABLE `professores_cursos`
  MODIFY `idprofessor_curso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `professores_disciplinas`
--
ALTER TABLE `professores_disciplinas`
  MODIFY `idprofessor_disciplina` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `professores_ofertas`
--
ALTER TABLE `professores_ofertas`
  MODIFY `idprofessor_oferta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `provas_impressas`
--
ALTER TABLE `provas_impressas`
  MODIFY `idprova_impressa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `provas_impressas_disciplinas`
--
ALTER TABLE `provas_impressas_disciplinas`
  MODIFY `idprova_impressa_disciplina` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `provas_impressas_perguntas`
--
ALTER TABLE `provas_impressas_perguntas`
  MODIFY `idprova_impressa_pergunta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `provas_presenciais`
--
ALTER TABLE `provas_presenciais`
  MODIFY `id_prova_presencial` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `provas_presenciais_escolas`
--
ALTER TABLE `provas_presenciais_escolas`
  MODIFY `id_prova_escola` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `provas_presenciais_locais_provas`
--
ALTER TABLE `provas_presenciais_locais_provas`
  MODIFY `id_prova_local` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `provas_solicitadas`
--
ALTER TABLE `provas_solicitadas`
  MODIFY `id_solicitacao_prova` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `provas_solicitadas_disciplinas`
--
ALTER TABLE `provas_solicitadas_disciplinas`
  MODIFY `id_solicitacao_prova_disciplina` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `quadros_avisos`
--
ALTER TABLE `quadros_avisos`
  MODIFY `idquadro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `quadros_avisos_cursos`
--
ALTER TABLE `quadros_avisos_cursos`
  MODIFY `idquadro_curso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `quadros_avisos_escolas`
--
ALTER TABLE `quadros_avisos_escolas`
  MODIFY `idquadro_escola` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `quadros_avisos_imagens`
--
ALTER TABLE `quadros_avisos_imagens`
  MODIFY `idquadro_imagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `quadros_avisos_ofertas`
--
ALTER TABLE `quadros_avisos_ofertas`
  MODIFY `idquadro_oferta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `racas`
--
ALTER TABLE `racas`
  MODIFY `idraca` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `reconhecimento_fotos`
--
ALTER TABLE `reconhecimento_fotos`
  MODIFY `idreconhecimento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `regioes`
--
ALTER TABLE `regioes`
  MODIFY `idregiao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `relacionamentos_comerciais`
--
ALTER TABLE `relacionamentos_comerciais`
  MODIFY `idrelacionamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `relacionamentos_comerciais_historicos`
--
ALTER TABLE `relacionamentos_comerciais_historicos`
  MODIFY `idhistorico` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `relacionamentos_comerciais_mensagens`
--
ALTER TABLE `relacionamentos_comerciais_mensagens`
  MODIFY `idmensagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `relacionamentos_comercial`
--
ALTER TABLE `relacionamentos_comercial`
  MODIFY `idmensagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `relacionamentos_pedagogico`
--
ALTER TABLE `relacionamentos_pedagogico`
  MODIFY `idmensagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `relatorios`
--
ALTER TABLE `relatorios`
  MODIFY `idrelatorio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `religioes`
--
ALTER TABLE `religioes`
  MODIFY `idreligiao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `retornos`
--
ALTER TABLE `retornos`
  MODIFY `idretorno` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `retornos_contas`
--
ALTER TABLE `retornos_contas`
  MODIFY `idretorno_conta` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `retornos_sindicatos`
--
ALTER TABLE `retornos_sindicatos`
  MODIFY `idretorno_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sindicatos`
--
ALTER TABLE `sindicatos`
  MODIFY `idsindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sindicatos_arquivos`
--
ALTER TABLE `sindicatos_arquivos`
  MODIFY `idarquivo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sindicatos_formas_pagamento`
--
ALTER TABLE `sindicatos_formas_pagamento`
  MODIFY `idsindicato_forma_pagamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sindicatos_valores_cursos`
--
ALTER TABLE `sindicatos_valores_cursos`
  MODIFY `idvalor_curso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sms_automaticos_log`
--
ALTER TABLE `sms_automaticos_log`
  MODIFY `idsms_log` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `solicitacoes_cadastros_portal`
--
ALTER TABLE `solicitacoes_cadastros_portal`
  MODIFY `idsolicitacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `solicitacoes_senhas`
--
ALTER TABLE `solicitacoes_senhas`
  MODIFY `idsolicitacao_senha` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `solicitantes_bolsas`
--
ALTER TABLE `solicitantes_bolsas`
  MODIFY `idsolicitante` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipos_contatos`
--
ALTER TABLE `tipos_contatos`
  MODIFY `idtipo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipos_contratos`
--
ALTER TABLE `tipos_contratos`
  MODIFY `idtipo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipos_documentos`
--
ALTER TABLE `tipos_documentos`
  MODIFY `idtipo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipos_documentos_cursos`
--
ALTER TABLE `tipos_documentos_cursos`
  MODIFY `idtipo_curso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipos_documentos_sindicatos`
--
ALTER TABLE `tipos_documentos_sindicatos`
  MODIFY `idtipo_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipos_documentos_sindicatos_agendamento`
--
ALTER TABLE `tipos_documentos_sindicatos_agendamento`
  MODIFY `idtipo_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `turmas`
--
ALTER TABLE `turmas`
  MODIFY `idturma` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios_adm`
--
ALTER TABLE `usuarios_adm`
  MODIFY `idusuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios_adm_perfis`
--
ALTER TABLE `usuarios_adm_perfis`
  MODIFY `idperfil` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios_adm_sindicatos`
--
ALTER TABLE `usuarios_adm_sindicatos`
  MODIFY `idusuario_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vendedores`
--
ALTER TABLE `vendedores`
  MODIFY `idvendedor` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vendedores_contatos`
--
ALTER TABLE `vendedores_contatos`
  MODIFY `idcontato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vendedores_escolas`
--
ALTER TABLE `vendedores_escolas`
  MODIFY `idvendedor_escola` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vendedores_sindicatos`
--
ALTER TABLE `vendedores_sindicatos`
  MODIFY `idvendedor_sindicato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `videotecas`
--
ALTER TABLE `videotecas`
  MODIFY `idvideo` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `videotecas_pastas`
--
ALTER TABLE `videotecas_pastas`
  MODIFY `idpasta` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `videotecas_tags`
--
ALTER TABLE `videotecas_tags`
  MODIFY `idtag` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `videotecas_tags_videos`
--
ALTER TABLE `videotecas_tags_videos`
  MODIFY `idtagvideo` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `visitas_mensagens`
--
ALTER TABLE `visitas_mensagens`
  MODIFY `idmensagem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `visitas_vendedores`
--
ALTER TABLE `visitas_vendedores`
  MODIFY `idvisita` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `visitas_vendedores_cursos`
--
ALTER TABLE `visitas_vendedores_cursos`
  MODIFY `idvisita_curso` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `visitas_vendedores_iteracoes`
--
ALTER TABLE `visitas_vendedores_iteracoes`
  MODIFY `iditeracao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `atendimentos`
--
ALTER TABLE `atendimentos`
  ADD CONSTRAINT `atendimentos_ibfk_1` FOREIGN KEY (`idsituacao`) REFERENCES `atendimentos_workflow` (`idsituacao`),
  ADD CONSTRAINT `atendimentos_idassunto` FOREIGN KEY (`idassunto`) REFERENCES `atendimentos_assuntos` (`idassunto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `atendimentos_idclone` FOREIGN KEY (`idclone`) REFERENCES `atendimentos` (`idatendimento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `atendimentos_idcurso` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `atendimentos_idpessoa` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `atendimentos_idsubassunto` FOREIGN KEY (`idsubassunto`) REFERENCES `atendimentos_assuntos_subassuntos` (`idsubassunto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `atendimentos_arquivos`
--
ALTER TABLE `atendimentos_arquivos`
  ADD CONSTRAINT `atendimentos_respostas_arquivos_idresposta` FOREIGN KEY (`idresposta`) REFERENCES `atendimentos_respostas` (`idresposta`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `atendimentos_assuntos`
--
ALTER TABLE `atendimentos_assuntos`
  ADD CONSTRAINT `atendimentos_assuntos_idcheclist` FOREIGN KEY (`idchecklist`) REFERENCES `checklists` (`idchecklist`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `atendimentos_assuntos_grupos`
--
ALTER TABLE `atendimentos_assuntos_grupos`
  ADD CONSTRAINT `atendimentos_assuntos_grupos_idassunto` FOREIGN KEY (`idassunto`) REFERENCES `atendimentos_assuntos` (`idassunto`),
  ADD CONSTRAINT `atendimentos_assuntos_grupos_idgrupo` FOREIGN KEY (`idgrupo`) REFERENCES `grupos_usuarios_adm` (`idgrupo`);

--
-- Restrições para tabelas `atendimentos_assuntos_subassuntos`
--
ALTER TABLE `atendimentos_assuntos_subassuntos`
  ADD CONSTRAINT `atendimentos_assuntos_subassuntos_idassunto` FOREIGN KEY (`idassunto`) REFERENCES `atendimentos_assuntos` (`idassunto`),
  ADD CONSTRAINT `atendimentos_assuntos_subassuntos_idcheclist` FOREIGN KEY (`idchecklist`) REFERENCES `checklists` (`idchecklist`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `atendimentos_assuntos_subassuntos_grupos`
--
ALTER TABLE `atendimentos_assuntos_subassuntos_grupos`
  ADD CONSTRAINT `atendimentos_assuntos_subassuntos_grupos_idgrupo` FOREIGN KEY (`idgrupo`) REFERENCES `grupos_usuarios_adm` (`idgrupo`),
  ADD CONSTRAINT `atendimentos_assuntos_subassuntos_grupos_idsubassunto` FOREIGN KEY (`idsubassunto`) REFERENCES `atendimentos_assuntos_subassuntos` (`idsubassunto`);

--
-- Restrições para tabelas `atendimentos_checklists_opcoes_marcados`
--
ALTER TABLE `atendimentos_checklists_opcoes_marcados`
  ADD CONSTRAINT `atendimentos_checklists_opcoes_marcados_idatendimento` FOREIGN KEY (`idatendimento`) REFERENCES `atendimentos` (`idatendimento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `atendimentos_checklists_opcoes_marcados_idchecklist` FOREIGN KEY (`idchecklist`) REFERENCES `checklists` (`idchecklist`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `atendimentos_checklists_opcoes_marcados_idopcao` FOREIGN KEY (`idopcao`) REFERENCES `checklists_opcoes` (`idopcao`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `atendimentos_historicos`
--
ALTER TABLE `atendimentos_historicos`
  ADD CONSTRAINT `FK_atendimentos_historicos_idpessoa` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`),
  ADD CONSTRAINT `atendimentos_historicos_idatendimento` FOREIGN KEY (`idatendimento`) REFERENCES `atendimentos` (`idatendimento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `atendimentos_historicos_idusuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios_adm` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `atendimentos_respostas`
--
ALTER TABLE `atendimentos_respostas`
  ADD CONSTRAINT `atendimentos_respostas_idatendimento` FOREIGN KEY (`idatendimento`) REFERENCES `atendimentos` (`idatendimento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `atendimentos_respostas_idpessoa` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `atendimentos_respostas_idresposta_automatica` FOREIGN KEY (`idresposta_automatica`) REFERENCES `atendimentos_respostas_automaticas` (`idresposta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `atendimentos_respostas_idusuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios_adm` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `atendimentos_respostas_automaticas_assuntos`
--
ALTER TABLE `atendimentos_respostas_automaticas_assuntos`
  ADD CONSTRAINT `atendimentos_respostas_automaticas_assuntos_idassunto` FOREIGN KEY (`idassunto`) REFERENCES `atendimentos_assuntos` (`idassunto`),
  ADD CONSTRAINT `atendimentos_respostas_automaticas_assuntos_idresposta` FOREIGN KEY (`idresposta`) REFERENCES `atendimentos_respostas_automaticas` (`idresposta`);

--
-- Restrições para tabelas `avas`
--
ALTER TABLE `avas`
  ADD CONSTRAINT `avas_ibfk_1` FOREIGN KEY (`idava_clone`) REFERENCES `avas` (`idava`);

--
-- Restrições para tabelas `avas_audios`
--
ALTER TABLE `avas_audios`
  ADD CONSTRAINT `avas_audios_idava` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avas_avaliacoes`
--
ALTER TABLE `avas_avaliacoes`
  ADD CONSTRAINT `avas_avaliacoes_idava` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avas_avaliacoes_disciplinas`
--
ALTER TABLE `avas_avaliacoes_disciplinas`
  ADD CONSTRAINT `avas_avaliacoes_disciplinas_idavaliacao` FOREIGN KEY (`idavaliacao`) REFERENCES `avas_avaliacoes` (`idavaliacao`),
  ADD CONSTRAINT `avas_avaliacoes_disciplinas_iddisciplina` FOREIGN KEY (`iddisciplina`) REFERENCES `disciplinas` (`iddisciplina`);

--
-- Restrições para tabelas `avas_chats`
--
ALTER TABLE `avas_chats`
  ADD CONSTRAINT `avas_chats_idava` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avas_conteudos`
--
ALTER TABLE `avas_conteudos`
  ADD CONSTRAINT `avas_conteudos_idava` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avas_downloads`
--
ALTER TABLE `avas_downloads`
  ADD CONSTRAINT `avas_downloads_idava` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avas_exercicios`
--
ALTER TABLE `avas_exercicios`
  ADD CONSTRAINT `FK_avas_exercicios_1` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`),
  ADD CONSTRAINT `FK_avas_exercicios_2` FOREIGN KEY (`iddisciplina_nota`) REFERENCES `disciplinas` (`iddisciplina`);

--
-- Restrições para tabelas `avas_faqs`
--
ALTER TABLE `avas_faqs`
  ADD CONSTRAINT `avas_faqs_idava` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avas_foruns`
--
ALTER TABLE `avas_foruns`
  ADD CONSTRAINT `avas_foruns_idava` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avas_links`
--
ALTER TABLE `avas_links`
  ADD CONSTRAINT `avas_links_idava` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avas_mensagem_instantanea`
--
ALTER TABLE `avas_mensagem_instantanea`
  ADD CONSTRAINT `avas_mensagem_instantanea_ibfk_1` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avas_mensagem_instantanea_conversas`
--
ALTER TABLE `avas_mensagem_instantanea_conversas`
  ADD CONSTRAINT `avas_mensagem_instantanea_conversas_ibfk_1` FOREIGN KEY (`idmensagem_instantanea`) REFERENCES `avas_mensagem_instantanea` (`idmensagem_instantanea`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `avas_mensagem_instantanea_conversas_ibfk_2` FOREIGN KEY (`idmensagem_instantanea_integrante`) REFERENCES `avas_mensagem_instantanea_integrantes` (`idmensagem_instantanea_integrante`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avas_mensagem_instantanea_conversas_visualizar`
--
ALTER TABLE `avas_mensagem_instantanea_conversas_visualizar`
  ADD CONSTRAINT `avas_mensagem_instantanea_conversas_visualizar_ibfk_1` FOREIGN KEY (`idmensagem_instantanea_conversa`) REFERENCES `avas_mensagem_instantanea_conversas` (`idmensagem_instantanea_conversa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `avas_mensagem_instantanea_conversas_visualizar_ibfk_2` FOREIGN KEY (`idmensagem_instantanea_integrante`) REFERENCES `avas_mensagem_instantanea_integrantes` (`idmensagem_instantanea_integrante`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avas_mensagem_instantanea_integrantes`
--
ALTER TABLE `avas_mensagem_instantanea_integrantes`
  ADD CONSTRAINT `avas_mensagem_instantanea_integrantes_ibfk_1` FOREIGN KEY (`idmensagem_instantanea`) REFERENCES `avas_mensagem_instantanea` (`idmensagem_instantanea`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `avas_mensagem_instantanea_integrantes_ibfk_2` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `avas_mensagem_instantanea_integrantes_ibfk_3` FOREIGN KEY (`idprofessor`) REFERENCES `professores` (`idprofessor`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avas_objetos_divisores`
--
ALTER TABLE `avas_objetos_divisores`
  ADD CONSTRAINT `FK_avas_objetos_divisores_idava` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avas_perguntas`
--
ALTER TABLE `avas_perguntas`
  ADD CONSTRAINT `avas_perguntas_idava` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avas_rotas_aprendizagem`
--
ALTER TABLE `avas_rotas_aprendizagem`
  ADD CONSTRAINT `avas_rotas_apredizagem_idava` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avas_rotas_aprendizagem_objetos`
--
ALTER TABLE `avas_rotas_aprendizagem_objetos`
  ADD CONSTRAINT `avas_rotas_aprendizagem_objetos_idaudio` FOREIGN KEY (`idaudio`) REFERENCES `avas_audios` (`idaudio`),
  ADD CONSTRAINT `avas_rotas_aprendizagem_objetos_idconteudo` FOREIGN KEY (`idconteudo`) REFERENCES `avas_conteudos` (`idconteudo`),
  ADD CONSTRAINT `avas_rotas_aprendizagem_objetos_iddownload` FOREIGN KEY (`iddownload`) REFERENCES `avas_downloads` (`iddownload`),
  ADD CONSTRAINT `avas_rotas_aprendizagem_objetos_idlink` FOREIGN KEY (`idlink`) REFERENCES `avas_links` (`idlink`),
  ADD CONSTRAINT `avas_rotas_aprendizagem_objetos_idobjeto_divisor` FOREIGN KEY (`idobjeto_divisor`) REFERENCES `avas_objetos_divisores` (`idobjeto_divisor`),
  ADD CONSTRAINT `avas_rotas_aprendizagem_objetos_idpergunta` FOREIGN KEY (`idpergunta`) REFERENCES `avas_perguntas` (`idpergunta`),
  ADD CONSTRAINT `avas_rotas_aprendizagem_objetos_idrota_aprendizagem` FOREIGN KEY (`idrota_aprendizagem`) REFERENCES `avas_rotas_aprendizagem` (`idrota_aprendizagem`),
  ADD CONSTRAINT `avas_rotas_aprendizagem_objetos_idsimulado` FOREIGN KEY (`idsimulado`) REFERENCES `avas_simulados` (`idsimulado`);

--
-- Restrições para tabelas `avas_tiraduvidas_categorias_professores`
--
ALTER TABLE `avas_tiraduvidas_categorias_professores`
  ADD CONSTRAINT `avas_tiraduvidas_categorias_professores_ibfk_1` FOREIGN KEY (`idcategoria`) REFERENCES `avas_tiraduvidas_categorias` (`idcategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `avas_tiraduvidas_categorias_professores_ibfk_2` FOREIGN KEY (`idprofessor`) REFERENCES `professores` (`idprofessor`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avas_tira_duvidas`
--
ALTER TABLE `avas_tira_duvidas`
  ADD CONSTRAINT `avas_comunicadores_idava` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `banners_ava_aluno_dias`
--
ALTER TABLE `banners_ava_aluno_dias`
  ADD CONSTRAINT `banners_ava_aluno_dias_ibfk_1` FOREIGN KEY (`idbanner`) REFERENCES `banners_ava_aluno` (`idbanner`);

--
-- Restrições para tabelas `banners_escolas`
--
ALTER TABLE `banners_escolas`
  ADD CONSTRAINT `banners_escolas_ibfk_1` FOREIGN KEY (`idescola`) REFERENCES `escolas` (`idescola`),
  ADD CONSTRAINT `banners_escolas_ibfk_2` FOREIGN KEY (`idbanner`) REFERENCES `banners_ava_aluno` (`idbanner`);

--
-- Restrições para tabelas `banners_sindicatos`
--
ALTER TABLE `banners_sindicatos`
  ADD CONSTRAINT `banners_sindicatos_ibfk_1` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`),
  ADD CONSTRAINT `banners_sindicatos_ibfk_2` FOREIGN KEY (`idbanner`) REFERENCES `banners_ava_aluno` (`idbanner`);

--
-- Restrições para tabelas `categorias_subcategorias`
--
ALTER TABLE `categorias_subcategorias`
  ADD CONSTRAINT `categorias_subcategorias_idcategoria` FOREIGN KEY (`idcategoria`) REFERENCES `categorias` (`idcategoria`);

--
-- Restrições para tabelas `centros_custos_sindicatos`
--
ALTER TABLE `centros_custos_sindicatos`
  ADD CONSTRAINT `centros_custos_sindicatos_idcentro_custo` FOREIGN KEY (`idcentro_custo`) REFERENCES `centros_custos` (`idcentro_custo`),
  ADD CONSTRAINT `centros_custos_sindicatos_idsindicato` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`);

--
-- Restrições para tabelas `cfcs_valores_cursos`
--
ALTER TABLE `cfcs_valores_cursos`
  ADD CONSTRAINT `fk_cfcs_valores_cursos_1` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cfcs_valores_cursos_2` FOREIGN KEY (`idcfc`) REFERENCES `escolas` (`idescola`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `checklists_opcoes`
--
ALTER TABLE `checklists_opcoes`
  ADD CONSTRAINT `FK_checklists_opcoes_1` FOREIGN KEY (`idchecklist`) REFERENCES `checklists` (`idchecklist`);

--
-- Restrições para tabelas `cidades`
--
ALTER TABLE `cidades`
  ADD CONSTRAINT `cidades_idestado` FOREIGN KEY (`idestado`) REFERENCES `estados` (`idestado`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `cobrancas_log`
--
ALTER TABLE `cobrancas_log`
  ADD CONSTRAINT `cobrancas_idmatricula` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`),
  ADD CONSTRAINT `cobrancas_idusuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios_adm` (`idusuario`);

--
-- Restrições para tabelas `comissoes_competencias`
--
ALTER TABLE `comissoes_competencias`
  ADD CONSTRAINT `comissoes_competencias_idsindicato` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`);

--
-- Restrições para tabelas `comissoes_competencias_cursos`
--
ALTER TABLE `comissoes_competencias_cursos`
  ADD CONSTRAINT `comissoes_competencias_cursos_idcurso` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`);

--
-- Restrições para tabelas `comissoes_competencias_sindicatos_cursos`
--
ALTER TABLE `comissoes_competencias_sindicatos_cursos`
  ADD CONSTRAINT `comissoes_competencias_cursos_idcompetencia` FOREIGN KEY (`idcompetencia`) REFERENCES `comissoes_competencias` (`idcompetencia`),
  ADD CONSTRAINT `comissoes_competencias_cursos_idcurso_sindicato` FOREIGN KEY (`idcurso_sindicato`) REFERENCES `cursos_sindicatos` (`idcurso_sindicato`),
  ADD CONSTRAINT `comissoes_competencias_cursos_idregra` FOREIGN KEY (`idregra`) REFERENCES `comissoes_regras` (`idregra`);

--
-- Restrições para tabelas `comissoes_regras_cursos`
--
ALTER TABLE `comissoes_regras_cursos`
  ADD CONSTRAINT `comissoes_regras_cursos_idcurso` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`),
  ADD CONSTRAINT `comissoes_regras_cursos_idregra` FOREIGN KEY (`idregra`) REFERENCES `comissoes_regras` (`idregra`);

--
-- Restrições para tabelas `comissoes_regras_sindicatos`
--
ALTER TABLE `comissoes_regras_sindicatos`
  ADD CONSTRAINT `comissoes_regras_sindicatos_idregra` FOREIGN KEY (`idregra`) REFERENCES `comissoes_regras` (`idregra`),
  ADD CONSTRAINT `comissoes_regras_sindicatos_idsindicato` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`);

--
-- Restrições para tabelas `contas`
--
ALTER TABLE `contas`
  ADD CONSTRAINT `contas_ibfk_2` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`),
  ADD CONSTRAINT `contas_ibfk_3` FOREIGN KEY (`idfornecedor`) REFERENCES `fornecedores` (`idfornecedor`),
  ADD CONSTRAINT `contas_ibfk_4` FOREIGN KEY (`idcategoria`) REFERENCES `categorias` (`idcategoria`),
  ADD CONSTRAINT `contas_ibfk_5` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`),
  ADD CONSTRAINT `contas_ibfk_6` FOREIGN KEY (`idmantenedora`) REFERENCES `mantenedoras` (`idmantenedora`),
  ADD CONSTRAINT `contas_ibfk_7` FOREIGN KEY (`idfechamento`) REFERENCES `fechamentos_caixa` (`idfechamento`),
  ADD CONSTRAINT `contas_ibfk_8` FOREIGN KEY (`idsubcategoria`) REFERENCES `categorias_subcategorias` (`idsubcategoria`),
  ADD CONSTRAINT `contas_ibfk_9` FOREIGN KEY (`idescola`) REFERENCES `escolas` (`idescola`),
  ADD CONSTRAINT `contas_idbanco` FOREIGN KEY (`idbanco`) REFERENCES `bancos` (`idbanco`),
  ADD CONSTRAINT `contas_idbandeira` FOREIGN KEY (`idbandeira`) REFERENCES `bandeiras_cartoes` (`idbandeira`),
  ADD CONSTRAINT `contas_idconta_corrente` FOREIGN KEY (`idconta_corrente`) REFERENCES `contas_correntes` (`idconta_corrente`),
  ADD CONSTRAINT `contas_idevento` FOREIGN KEY (`idevento`) REFERENCES `eventos_financeiros` (`idevento`),
  ADD CONSTRAINT `contas_idmatricula` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`),
  ADD CONSTRAINT `contas_idrelacao` FOREIGN KEY (`idrelacao`) REFERENCES `contas_relacoes` (`idrelacao`),
  ADD CONSTRAINT `contas_idsituacao` FOREIGN KEY (`idsituacao`) REFERENCES `contas_workflow` (`idsituacao`),
  ADD CONSTRAINT `contas_pagamentos_compartilhados` FOREIGN KEY (`idpagamento_compartilhado`) REFERENCES `pagamentos_compartilhados` (`idpagamento`);

--
-- Restrições para tabelas `contas_boletos_gerado`
--
ALTER TABLE `contas_boletos_gerado`
  ADD CONSTRAINT `contas_boletos_gerado_ibfk_1` FOREIGN KEY (`idconta`) REFERENCES `contas` (`idconta`),
  ADD CONSTRAINT `contas_boletos_gerado_ibfk_2` FOREIGN KEY (`idconta_corrente`) REFERENCES `contas_correntes` (`idconta_corrente`),
  ADD CONSTRAINT `contas_boletos_gerado_ibfk_3` FOREIGN KEY (`idbanco`) REFERENCES `bancos` (`idbanco`);

--
-- Restrições para tabelas `contas_pagamentos`
--
ALTER TABLE `contas_pagamentos`
  ADD CONSTRAINT `contas_pagamentos_ibfk_2` FOREIGN KEY (`idconta`) REFERENCES `contas` (`idconta`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `contratos`
--
ALTER TABLE `contratos`
  ADD CONSTRAINT `FK_contratos_comercial_1` FOREIGN KEY (`idtipo`) REFERENCES `contratos_tipos` (`idtipo`);

--
-- Restrições para tabelas `contratos_cursos`
--
ALTER TABLE `contratos_cursos`
  ADD CONSTRAINT `contratos_cursos_idcontrato` FOREIGN KEY (`idcontrato`) REFERENCES `contratos` (`idcontrato`),
  ADD CONSTRAINT `contratos_cursos_idcurso` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`);

--
-- Restrições para tabelas `curriculos`
--
ALTER TABLE `curriculos`
  ADD CONSTRAINT `curriculos_idcurso` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`);

--
-- Restrições para tabelas `curriculos_avaliacoes`
--
ALTER TABLE `curriculos_avaliacoes`
  ADD CONSTRAINT `curriculos_avaliacoes_idcurriculo` FOREIGN KEY (`idcurriculo`) REFERENCES `curriculos` (`idcurriculo`);

--
-- Restrições para tabelas `curriculos_blocos`
--
ALTER TABLE `curriculos_blocos`
  ADD CONSTRAINT `curriculos_blocos_idcurriculo` FOREIGN KEY (`idcurriculo`) REFERENCES `curriculos` (`idcurriculo`);

--
-- Restrições para tabelas `curriculos_blocos_disciplinas`
--
ALTER TABLE `curriculos_blocos_disciplinas`
  ADD CONSTRAINT `curriculos_blocos_disciplinas_idava` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`),
  ADD CONSTRAINT `curriculos_blocos_disciplinas_idbloco` FOREIGN KEY (`idbloco`) REFERENCES `curriculos_blocos` (`idbloco`),
  ADD CONSTRAINT `curriculos_blocos_disciplinas_iddisciplina` FOREIGN KEY (`iddisciplina`) REFERENCES `disciplinas` (`iddisciplina`);

--
-- Restrições para tabelas `curriculos_notas_tipos`
--
ALTER TABLE `curriculos_notas_tipos`
  ADD CONSTRAINT `curriculos_notas_tipos_idcurriculo` FOREIGN KEY (`idcurriculo`) REFERENCES `curriculos` (`idcurriculo`),
  ADD CONSTRAINT `curriculos_notas_tipos_idtipo` FOREIGN KEY (`idtipo`) REFERENCES `matriculas_notas_tipos` (`idtipo`);

--
-- Restrições para tabelas `cursos_areas`
--
ALTER TABLE `cursos_areas`
  ADD CONSTRAINT `cursos_areas_idarea` FOREIGN KEY (`idarea`) REFERENCES `areas` (`idarea`),
  ADD CONSTRAINT `cursos_areas_idcurso` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`);

--
-- Restrições para tabelas `cursos_sindicatos`
--
ALTER TABLE `cursos_sindicatos`
  ADD CONSTRAINT `cursos_sindicatos_ibfk_1` FOREIGN KEY (`idhistorico_escolar`) REFERENCES `historico_escolar` (`idhistorico_escolar`),
  ADD CONSTRAINT `cursos_sindicatos_idcurso` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`),
  ADD CONSTRAINT `cursos_sindicatos_idsindicato` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`);

--
-- Restrições para tabelas `declaracoes`
--
ALTER TABLE `declaracoes`
  ADD CONSTRAINT `FK_declaracoes_comercial_1` FOREIGN KEY (`idtipo`) REFERENCES `declaracoes_tipos` (`idtipo`);

--
-- Restrições para tabelas `declaracoes_cursos`
--
ALTER TABLE `declaracoes_cursos`
  ADD CONSTRAINT `declaracoes_cursos_idcurso` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`),
  ADD CONSTRAINT `declaracoes_cursos_iddeclaracao` FOREIGN KEY (`iddeclaracao`) REFERENCES `declaracoes` (`iddeclaracao`);

--
-- Restrições para tabelas `declaracoes_imagens`
--
ALTER TABLE `declaracoes_imagens`
  ADD CONSTRAINT `FK_declaracoes_imagens_iddeclaracao` FOREIGN KEY (`iddeclaracao`) REFERENCES `declaracoes` (`iddeclaracao`);

--
-- Restrições para tabelas `declaracoes_sindicatos`
--
ALTER TABLE `declaracoes_sindicatos`
  ADD CONSTRAINT `declaracoes_sindicatos_iddeclaracao` FOREIGN KEY (`iddeclaracao`) REFERENCES `declaracoes` (`iddeclaracao`),
  ADD CONSTRAINT `declaracoes_sindicatos_idsindicato` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`);

--
-- Restrições para tabelas `detran_logs`
--
ALTER TABLE `detran_logs`
  ADD CONSTRAINT `detran_logs_matriculas` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`);

--
-- Restrições para tabelas `disciplinas_cursos`
--
ALTER TABLE `disciplinas_cursos`
  ADD CONSTRAINT `disciplinas_cursos_idcurso` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`),
  ADD CONSTRAINT `disciplinas_cursos_iddisciplina` FOREIGN KEY (`iddisciplina`) REFERENCES `disciplinas` (`iddisciplina`);

--
-- Restrições para tabelas `disciplinas_perguntas`
--
ALTER TABLE `disciplinas_perguntas`
  ADD CONSTRAINT `disciplinas_perguntas_iddisciplina` FOREIGN KEY (`iddisciplina`) REFERENCES `disciplinas` (`iddisciplina`),
  ADD CONSTRAINT `disciplinas_perguntas_idpergunta` FOREIGN KEY (`idpergunta`) REFERENCES `perguntas` (`idpergunta`);

--
-- Restrições para tabelas `escolas`
--
ALTER TABLE `escolas`
  ADD CONSTRAINT `escolas_idcidade` FOREIGN KEY (`idcidade`) REFERENCES `cidades` (`idcidade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `escolas_idestado` FOREIGN KEY (`idestado`) REFERENCES `estados` (`idestado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `escolas_idlogradouro` FOREIGN KEY (`idlogradouro`) REFERENCES `logradouros` (`idlogradouro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `escolas_idsindicato` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `escolas_contatos`
--
ALTER TABLE `escolas_contatos`
  ADD CONSTRAINT `FK_escolas_contatos_escolas` FOREIGN KEY (`idescola`) REFERENCES `escolas` (`idescola`),
  ADD CONSTRAINT `FK_escolas_contatos_tipo` FOREIGN KEY (`idtipo`) REFERENCES `tipos_contatos` (`idtipo`);

--
-- Restrições para tabelas `escolas_contratos`
--
ALTER TABLE `escolas_contratos`
  ADD CONSTRAINT `escolas_contratos_idcontrato` FOREIGN KEY (`idcontrato`) REFERENCES `contratos` (`idcontrato`),
  ADD CONSTRAINT `escolas_contratos_idescola` FOREIGN KEY (`idescola`) REFERENCES `escolas` (`idescola`);

--
-- Restrições para tabelas `estados`
--
ALTER TABLE `estados`
  ADD CONSTRAINT `estados_idpais` FOREIGN KEY (`idpais`) REFERENCES `paises` (`idpais`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `feriados_cidades`
--
ALTER TABLE `feriados_cidades`
  ADD CONSTRAINT `feriados_cidades_idcidade` FOREIGN KEY (`idcidade`) REFERENCES `cidades` (`idcidade`),
  ADD CONSTRAINT `feriados_cidades_idferiado` FOREIGN KEY (`idferiado`) REFERENCES `feriados` (`idferiado`);

--
-- Restrições para tabelas `feriados_escolas`
--
ALTER TABLE `feriados_escolas`
  ADD CONSTRAINT `feriados_escolas_idescola` FOREIGN KEY (`idescola`) REFERENCES `escolas` (`idescola`),
  ADD CONSTRAINT `feriados_escolas_idferiado` FOREIGN KEY (`idferiado`) REFERENCES `feriados` (`idferiado`);

--
-- Restrições para tabelas `feriados_estados`
--
ALTER TABLE `feriados_estados`
  ADD CONSTRAINT `feriados_estados_idestado` FOREIGN KEY (`idestado`) REFERENCES `estados` (`idestado`),
  ADD CONSTRAINT `feriados_estados_idferiado` FOREIGN KEY (`idferiado`) REFERENCES `feriados` (`idferiado`);

--
-- Restrições para tabelas `feriados_sindicatos`
--
ALTER TABLE `feriados_sindicatos`
  ADD CONSTRAINT `feriados_sindicatos_idferiado` FOREIGN KEY (`idferiado`) REFERENCES `feriados` (`idferiado`),
  ADD CONSTRAINT `feriados_sindicatos_idsindicato` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`);

--
-- Restrições para tabelas `formulas_notas_sindicatos`
--
ALTER TABLE `formulas_notas_sindicatos`
  ADD CONSTRAINT `formulas_notas_sindicatos_idformula` FOREIGN KEY (`idformula`) REFERENCES `formulas_notas` (`idformula`),
  ADD CONSTRAINT `formulas_notas_sindicatos_idsindicato` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`);

--
-- Restrições para tabelas `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD CONSTRAINT `fornecedores_idsindicato` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`);

--
-- Restrições para tabelas `grupos_vendedores_vendedores`
--
ALTER TABLE `grupos_vendedores_vendedores`
  ADD CONSTRAINT `grupos_vendedores_vendedores_idvendedor` FOREIGN KEY (`idvendedor`) REFERENCES `vendedores` (`idvendedor`);

--
-- Restrições para tabelas `mailings_fila`
--
ALTER TABLE `mailings_fila`
  ADD CONSTRAINT `FK_mailing_fila_gestor_idusuario` FOREIGN KEY (`idusuario_gestor`) REFERENCES `usuarios_adm` (`idusuario`),
  ADD CONSTRAINT `FK_mailing_fila_idemail` FOREIGN KEY (`idemail`) REFERENCES `mailings` (`idemail`),
  ADD CONSTRAINT `FK_mailing_fila_idmatricula` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`),
  ADD CONSTRAINT `FK_mailing_fila_idprofessor` FOREIGN KEY (`idprofessor`) REFERENCES `professores` (`idprofessor`),
  ADD CONSTRAINT `mailings_fila_idfiltro` FOREIGN KEY (`idfiltro`) REFERENCES `mailings_filtros` (`idfiltro`);

--
-- Restrições para tabelas `mailings_fila_reenvio_historico`
--
ALTER TABLE `mailings_fila_reenvio_historico`
  ADD CONSTRAINT `mailings_fila_reenvio_historico_idemail_pessoa` FOREIGN KEY (`idemail_pessoa`) REFERENCES `mailings_fila` (`idemail_pessoa`);

--
-- Restrições para tabelas `mailings_filtros`
--
ALTER TABLE `mailings_filtros`
  ADD CONSTRAINT `mailings_filtros_idemail` FOREIGN KEY (`idemail`) REFERENCES `mailings` (`idemail`),
  ADD CONSTRAINT `mailings_filtros_idusuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios_adm` (`idusuario`);

--
-- Restrições para tabelas `mailings_imagens`
--
ALTER TABLE `mailings_imagens`
  ADD CONSTRAINT `FK_mailing_imagens_idemail` FOREIGN KEY (`idemail`) REFERENCES `mailings` (`idemail`);

--
-- Restrições para tabelas `mantenedoras`
--
ALTER TABLE `mantenedoras`
  ADD CONSTRAINT `mantenedoras_idcidade` FOREIGN KEY (`idcidade`) REFERENCES `cidades` (`idcidade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `mantenedoras_idestado` FOREIGN KEY (`idestado`) REFERENCES `estados` (`idestado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `mantenedoras_idlogradouro` FOREIGN KEY (`idlogradouro`) REFERENCES `logradouros` (`idlogradouro`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `matriculas`
--
ALTER TABLE `matriculas`
  ADD CONSTRAINT `matriculas_ibfk_1` FOREIGN KEY (`idoferta`) REFERENCES `ofertas` (`idoferta`),
  ADD CONSTRAINT `matriculas_ibfk_2` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`),
  ADD CONSTRAINT `matriculas_ibfk_3` FOREIGN KEY (`idescola`) REFERENCES `escolas` (`idescola`),
  ADD CONSTRAINT `matriculas_idempresa` FOREIGN KEY (`idempresa`) REFERENCES `empresas` (`idempresa`),
  ADD CONSTRAINT `matriculas_idmotivo_cancelamento` FOREIGN KEY (`idmotivo_cancelamento`) REFERENCES `motivos_cancelamento` (`idmotivo`),
  ADD CONSTRAINT `matriculas_idmotivo_inativo` FOREIGN KEY (`idmotivo_inativo`) REFERENCES `motivos_inatividade` (`idmotivo`),
  ADD CONSTRAINT `matriculas_idpessoa` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`),
  ADD CONSTRAINT `matriculas_idsituacao` FOREIGN KEY (`idsituacao`) REFERENCES `matriculas_workflow` (`idsituacao`),
  ADD CONSTRAINT `matriculas_idsolicitante` FOREIGN KEY (`idsolicitante`) REFERENCES `solicitantes_bolsas` (`idsolicitante`),
  ADD CONSTRAINT `matriculas_idusuario_aprovado_comercial` FOREIGN KEY (`idusuario_aprovado_comercial`) REFERENCES `usuarios_adm` (`idusuario`),
  ADD CONSTRAINT `matriculas_idusuario_matriculou` FOREIGN KEY (`idusuario`) REFERENCES `usuarios_adm` (`idusuario`),
  ADD CONSTRAINT `matriculas_idvendedor` FOREIGN KEY (`idvendedor`) REFERENCES `vendedores` (`idvendedor`);

--
-- Restrições para tabelas `matriculas_associados`
--
ALTER TABLE `matriculas_associados`
  ADD CONSTRAINT `matriculas_associados_idmatricula` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`),
  ADD CONSTRAINT `matriculas_associados_idpessoa` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`);

--
-- Restrições para tabelas `matriculas_avaliacoes`
--
ALTER TABLE `matriculas_avaliacoes`
  ADD CONSTRAINT `FK_cbd_avaliacoes_matriculas_matricula` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`),
  ADD CONSTRAINT `FK_cbd_avaliacoes_matriculas_professor` FOREIGN KEY (`idprofessor`) REFERENCES `professores` (`idprofessor`),
  ADD CONSTRAINT `matriculas_avaliacoes_ibfk_1` FOREIGN KEY (`idavaliacao`) REFERENCES `avas_avaliacoes` (`idavaliacao`);

--
-- Restrições para tabelas `matriculas_avaliacoes_perguntas`
--
ALTER TABLE `matriculas_avaliacoes_perguntas`
  ADD CONSTRAINT `FK_cbd_avaliacoes_matriculas_perguntas_pergunta` FOREIGN KEY (`idpergunta`) REFERENCES `perguntas` (`idpergunta`),
  ADD CONSTRAINT `FK_cbd_avaliacoes_matriculas_perguntas_prova` FOREIGN KEY (`idprova`) REFERENCES `matriculas_avaliacoes` (`idprova`);

--
-- Restrições para tabelas `matriculas_avaliacoes_perguntas_opcoes_marcadas`
--
ALTER TABLE `matriculas_avaliacoes_perguntas_opcoes_marcadas`
  ADD CONSTRAINT `FK_cbd_avaliacoes_matriculas_perguntas_opcoes_opcao` FOREIGN KEY (`idopcao`) REFERENCES `perguntas_opcoes` (`idopcao`),
  ADD CONSTRAINT `FK_cbd_avaliacoes_matriculas_perguntas_opcoes_prova_pergunta` FOREIGN KEY (`id_prova_pergunta`) REFERENCES `matriculas_avaliacoes_perguntas` (`id_prova_pergunta`);

--
-- Restrições para tabelas `matriculas_avas_porcentagem`
--
ALTER TABLE `matriculas_avas_porcentagem`
  ADD CONSTRAINT `FK_matriculas_avas_porcentagem_1` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`),
  ADD CONSTRAINT `FK_matriculas_avas_porcentagem_2` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`);

--
-- Restrições para tabelas `matriculas_historicos`
--
ALTER TABLE `matriculas_historicos`
  ADD CONSTRAINT `matriculas_historicos_idmatricula` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`),
  ADD CONSTRAINT `matriculas_historicos_idusuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios_adm` (`idusuario`);

--
-- Restrições para tabelas `matriculas_mensagens_arquivos`
--
ALTER TABLE `matriculas_mensagens_arquivos`
  ADD CONSTRAINT `matriculas_mensagens_arquivos_idmatricula` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`),
  ADD CONSTRAINT `matriculas_mensagens_arquivos_idmensagem` FOREIGN KEY (`idmensagem`) REFERENCES `matriculas_mensagens` (`idmensagem`);

--
-- Restrições para tabelas `matriculas_notas`
--
ALTER TABLE `matriculas_notas`
  ADD CONSTRAINT `FK_matriculas_notas_id_solicitacao_prova` FOREIGN KEY (`id_solicitacao_prova`) REFERENCES `provas_solicitadas` (`id_solicitacao_prova`),
  ADD CONSTRAINT `FK_matriculas_notas_iddisciplina` FOREIGN KEY (`iddisciplina`) REFERENCES `disciplinas` (`iddisciplina`),
  ADD CONSTRAINT `FK_matriculas_notas_idprova` FOREIGN KEY (`idprova`) REFERENCES `matriculas_avaliacoes` (`idprova`),
  ADD CONSTRAINT `FK_matriculas_notas_matricula` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`);

--
-- Restrições para tabelas `matriculas_reconhecimentos`
--
ALTER TABLE `matriculas_reconhecimentos`
  ADD CONSTRAINT `matriculas_reconhecimentos_FK` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`);

--
-- Restrições para tabelas `matriculas_rotas_aprendizagem_objetos`
--
ALTER TABLE `matriculas_rotas_aprendizagem_objetos`
  ADD CONSTRAINT `matriculas_rotas_aprendizagem_objetos_idmatricula` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`),
  ADD CONSTRAINT `matriculas_rotas_aprendizagem_objetos_idobjeto` FOREIGN KEY (`idobjeto`) REFERENCES `avas_rotas_aprendizagem_objetos` (`idobjeto`);

--
-- Restrições para tabelas `matriculas_solicitacoes_declaracoes`
--
ALTER TABLE `matriculas_solicitacoes_declaracoes`
  ADD CONSTRAINT `matriculas_solicitacoes_declaracoes_iddeclaracao` FOREIGN KEY (`iddeclaracao`) REFERENCES `declaracoes` (`iddeclaracao`),
  ADD CONSTRAINT `matriculas_solicitacoes_declaracoes_idmatricula` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`),
  ADD CONSTRAINT `matriculas_solicitacoes_declaracoes_idmatriculadeclaracao` FOREIGN KEY (`idmatriculadeclaracao`) REFERENCES `matriculas_declaracoes` (`idmatriculadeclaracao`);

--
-- Restrições para tabelas `metas`
--
ALTER TABLE `metas`
  ADD CONSTRAINT `metas_ibfk_1` FOREIGN KEY (`idusuario_processou`) REFERENCES `usuarios_adm` (`idusuario`);

--
-- Restrições para tabelas `metas_vendedores`
--
ALTER TABLE `metas_vendedores`
  ADD CONSTRAINT `metas_vendedores_idmeta` FOREIGN KEY (`idmeta`) REFERENCES `metas` (`idmeta`),
  ADD CONSTRAINT `metas_vendedores_idvendedor` FOREIGN KEY (`idvendedor`) REFERENCES `vendedores` (`idvendedor`);

--
-- Restrições para tabelas `monitora_adm`
--
ALTER TABLE `monitora_adm`
  ADD CONSTRAINT `monitora_adm_idusuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios_adm` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `monitora_adm_log`
--
ALTER TABLE `monitora_adm_log`
  ADD CONSTRAINT `monitora_adm_log_idmonitora` FOREIGN KEY (`idmonitora`) REFERENCES `monitora_adm` (`idmonitora`);

--
-- Restrições para tabelas `monitora_escola`
--
ALTER TABLE `monitora_escola`
  ADD CONSTRAINT `monitora_escola_idescola` FOREIGN KEY (`idescola`) REFERENCES `escolas` (`idescola`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `monitora_escola_log`
--
ALTER TABLE `monitora_escola_log`
  ADD CONSTRAINT `monitora_escola_log_idmonitora` FOREIGN KEY (`idmonitora`) REFERENCES `monitora_escola` (`idmonitora`);

--
-- Restrições para tabelas `monitora_pessoa`
--
ALTER TABLE `monitora_pessoa`
  ADD CONSTRAINT `monitora_pessoa_ibfk_1` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`);

--
-- Restrições para tabelas `monitora_pessoa_log`
--
ALTER TABLE `monitora_pessoa_log`
  ADD CONSTRAINT `FK_monitora_pessoa_log_idmonitora` FOREIGN KEY (`idmonitora`) REFERENCES `monitora_pessoa` (`idmonitora`);

--
-- Restrições para tabelas `monitora_professor`
--
ALTER TABLE `monitora_professor`
  ADD CONSTRAINT `monitora_professor_ibfk_1` FOREIGN KEY (`idprofessor`) REFERENCES `professores` (`idprofessor`);

--
-- Restrições para tabelas `monitora_professor_log`
--
ALTER TABLE `monitora_professor_log`
  ADD CONSTRAINT `monitora_professor_log_idmonitora` FOREIGN KEY (`idmonitora`) REFERENCES `monitora_professor` (`idmonitora`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `monitora_vendedor`
--
ALTER TABLE `monitora_vendedor`
  ADD CONSTRAINT `monitora_vendedor_ibfk_1` FOREIGN KEY (`idvendedor`) REFERENCES `vendedores` (`idvendedor`);

--
-- Restrições para tabelas `monitora_vendedor_log`
--
ALTER TABLE `monitora_vendedor_log`
  ADD CONSTRAINT `monitora_vendedor_log_idmonitora` FOREIGN KEY (`idmonitora`) REFERENCES `monitora_vendedor` (`idmonitora`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `murais_arquivos`
--
ALTER TABLE `murais_arquivos`
  ADD CONSTRAINT `FK_murais_arquivos_idmural` FOREIGN KEY (`idmural`) REFERENCES `murais` (`idmural`);

--
-- Restrições para tabelas `murais_filas`
--
ALTER TABLE `murais_filas`
  ADD CONSTRAINT `murais_fila_idmatricula` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`),
  ADD CONSTRAINT `murais_fila_idmural` FOREIGN KEY (`idmural`) REFERENCES `murais` (`idmural`),
  ADD CONSTRAINT `murais_fila_idpessoa` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`),
  ADD CONSTRAINT `murais_fila_idprofessor` FOREIGN KEY (`idprofessor`) REFERENCES `professores` (`idprofessor`),
  ADD CONSTRAINT `murais_fila_idusuario_adm` FOREIGN KEY (`idusuario_adm`) REFERENCES `usuarios_adm` (`idusuario`),
  ADD CONSTRAINT `murais_fila_idvendedor` FOREIGN KEY (`idvendedor`) REFERENCES `vendedores` (`idvendedor`);

--
-- Restrições para tabelas `murais_imagens`
--
ALTER TABLE `murais_imagens`
  ADD CONSTRAINT `FK_murais_imagens_idmural` FOREIGN KEY (`idmural`) REFERENCES `murais` (`idmural`);

--
-- Restrições para tabelas `ofertas`
--
ALTER TABLE `ofertas`
  ADD CONSTRAINT `ofertas_idsituacao` FOREIGN KEY (`idsituacao`) REFERENCES `ofertas_workflow` (`idsituacao`);

--
-- Restrições para tabelas `ofertas_cursos`
--
ALTER TABLE `ofertas_cursos`
  ADD CONSTRAINT `ofertas_cursos_idcurso` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`),
  ADD CONSTRAINT `ofertas_cursos_idoferta` FOREIGN KEY (`idoferta`) REFERENCES `ofertas` (`idoferta`);

--
-- Restrições para tabelas `ofertas_escolas`
--
ALTER TABLE `ofertas_escolas`
  ADD CONSTRAINT `ofertas_escolas_idescola` FOREIGN KEY (`idescola`) REFERENCES `escolas` (`idescola`),
  ADD CONSTRAINT `ofertas_escolas_idoferta` FOREIGN KEY (`idoferta`) REFERENCES `ofertas` (`idoferta`);

--
-- Restrições para tabelas `ofertas_turmas`
--
ALTER TABLE `ofertas_turmas`
  ADD CONSTRAINT `ofertas_turmas_idoferta` FOREIGN KEY (`idoferta`) REFERENCES `ofertas` (`idoferta`);

--
-- Restrições para tabelas `pagamentos_compartilhados`
--
ALTER TABLE `pagamentos_compartilhados`
  ADD CONSTRAINT `pagamentos_compartilhados_ibfk_1` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`);

--
-- Restrições para tabelas `pagamentos_compartilhados_matriculas`
--
ALTER TABLE `pagamentos_compartilhados_matriculas`
  ADD CONSTRAINT `pagamentos_compartilhados_matriculas_matricula` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`),
  ADD CONSTRAINT `pagamentos_compartilhados_matriculas_pagamento` FOREIGN KEY (`idpagamento`) REFERENCES `pagamentos_compartilhados` (`idpagamento`);

--
-- Restrições para tabelas `perguntas_opcoes`
--
ALTER TABLE `perguntas_opcoes`
  ADD CONSTRAINT `perguntas_opcoes_idpergunta` FOREIGN KEY (`idpergunta`) REFERENCES `perguntas` (`idpergunta`);

--
-- Restrições para tabelas `pesquisas_fila`
--
ALTER TABLE `pesquisas_fila`
  ADD CONSTRAINT `FK_pesquisas_fila_gestor_idusuario` FOREIGN KEY (`idusuario_gestor`) REFERENCES `usuarios_adm` (`idusuario`),
  ADD CONSTRAINT `FK_pesquisas_fila_idmatricula` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`),
  ADD CONSTRAINT `FK_pesquisas_fila_idprofessor` FOREIGN KEY (`idprofessor`) REFERENCES `professores` (`idprofessor`),
  ADD CONSTRAINT `FK_pesquisas_pessoas_idpesquisa` FOREIGN KEY (`idpesquisa`) REFERENCES `pesquisas` (`idpesquisa`),
  ADD CONSTRAINT `FK_pesquisas_pessoas_idpessoa` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`),
  ADD CONSTRAINT `pesquisas_fila_idfiltro` FOREIGN KEY (`idfiltro`) REFERENCES `pesquisas_filtros` (`idfiltro`);

--
-- Restrições para tabelas `pesquisas_fila_reenvio_historico`
--
ALTER TABLE `pesquisas_fila_reenvio_historico`
  ADD CONSTRAINT `pesquisas_fila_reenvio_historico_ibfk_1` FOREIGN KEY (`idpesquisa_pessoa`) REFERENCES `pesquisas_fila` (`idpesquisa_pessoa`);

--
-- Restrições para tabelas `pesquisas_filtros`
--
ALTER TABLE `pesquisas_filtros`
  ADD CONSTRAINT `pesquisas_filtros_idpesquisa` FOREIGN KEY (`idpesquisa`) REFERENCES `pesquisas` (`idpesquisa`),
  ADD CONSTRAINT `pesquisas_filtros_idusuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios_adm` (`idusuario`);

--
-- Restrições para tabelas `pesquisas_imagens`
--
ALTER TABLE `pesquisas_imagens`
  ADD CONSTRAINT `FK_pesquisas_imagens_idpesquisa` FOREIGN KEY (`idpesquisa`) REFERENCES `pesquisas` (`idpesquisa`);

--
-- Restrições para tabelas `pesquisas_perguntas`
--
ALTER TABLE `pesquisas_perguntas`
  ADD CONSTRAINT `pesquisas_perguntas_ibfk_1` FOREIGN KEY (`idpesquisa`) REFERENCES `pesquisas` (`idpesquisa`),
  ADD CONSTRAINT `pesquisas_perguntas_ibfk_2` FOREIGN KEY (`idpergunta`) REFERENCES `perguntas_pesquisas` (`idpergunta`);

--
-- Restrições para tabelas `pesquisas_perguntas_opcoes`
--
ALTER TABLE `pesquisas_perguntas_opcoes`
  ADD CONSTRAINT `pesquisas_perguntas_opcoes_ibfk_1` FOREIGN KEY (`idpergunta`) REFERENCES `perguntas_pesquisas` (`idpergunta`);

--
-- Restrições para tabelas `pessoas_associacoes`
--
ALTER TABLE `pessoas_associacoes`
  ADD CONSTRAINT `pessoas_associacoes_idpessoa` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`),
  ADD CONSTRAINT `pessoas_associacoes_idpessoa_associada` FOREIGN KEY (`idpessoa_associada`) REFERENCES `pessoas` (`idpessoa`);

--
-- Restrições para tabelas `pessoas_contatos`
--
ALTER TABLE `pessoas_contatos`
  ADD CONSTRAINT `FK_pessoas_contatos_pessoas` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`),
  ADD CONSTRAINT `FK_pessoas_contatos_tipo` FOREIGN KEY (`idtipo`) REFERENCES `tipos_contatos` (`idtipo`);

--
-- Restrições para tabelas `pessoas_sindicatos`
--
ALTER TABLE `pessoas_sindicatos`
  ADD CONSTRAINT `pessoas_sindicatos_idpessoa` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`),
  ADD CONSTRAINT `pessoas_sindicatos_idsindicato` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`);

--
-- Restrições para tabelas `pessoas_validacoes`
--
ALTER TABLE `pessoas_validacoes`
  ADD CONSTRAINT `FK_pessoas_validacoes_idpessoa` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`),
  ADD CONSTRAINT `FK_pessoas_validacoes_idpessoa_solicitou` FOREIGN KEY (`idpessoa_solicitou`) REFERENCES `pessoas` (`idpessoa`),
  ADD CONSTRAINT `FK_pessoas_validacoes_idusuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios_adm` (`idusuario`),
  ADD CONSTRAINT `FK_pessoas_validacoes_idusuario_solicitou` FOREIGN KEY (`idusuario_solicitou`) REFERENCES `usuarios_adm` (`idusuario`);

--
-- Restrições para tabelas `pessoas_validacoes_campos`
--
ALTER TABLE `pessoas_validacoes_campos`
  ADD CONSTRAINT `FK_pessoas_validacoes_campos_idvalidacao` FOREIGN KEY (`idvalidacao`) REFERENCES `pessoas_validacoes` (`idvalidacao`);

--
-- Restrições para tabelas `produtos_fornecedores`
--
ALTER TABLE `produtos_fornecedores`
  ADD CONSTRAINT `produtos_fornecedores_idfornecedor` FOREIGN KEY (`idfornecedor`) REFERENCES `fornecedores` (`idfornecedor`),
  ADD CONSTRAINT `produtos_fornecedores_idproduto` FOREIGN KEY (`idproduto`) REFERENCES `produtos` (`idproduto`);

--
-- Restrições para tabelas `professores_avas`
--
ALTER TABLE `professores_avas`
  ADD CONSTRAINT `professores_avas_idava` FOREIGN KEY (`idava`) REFERENCES `avas` (`idava`),
  ADD CONSTRAINT `professores_avas_idprofessor` FOREIGN KEY (`idprofessor`) REFERENCES `professores` (`idprofessor`);

--
-- Restrições para tabelas `professores_cursos`
--
ALTER TABLE `professores_cursos`
  ADD CONSTRAINT `professores_cursos_idcurso` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`),
  ADD CONSTRAINT `professores_cursos_idprofessor` FOREIGN KEY (`idprofessor`) REFERENCES `professores` (`idprofessor`);

--
-- Restrições para tabelas `professores_ofertas`
--
ALTER TABLE `professores_ofertas`
  ADD CONSTRAINT `professores_ofertas_idoferta` FOREIGN KEY (`idoferta`) REFERENCES `ofertas` (`idoferta`),
  ADD CONSTRAINT `professores_ofertas_idprofessor` FOREIGN KEY (`idprofessor`) REFERENCES `professores` (`idprofessor`);

--
-- Restrições para tabelas `provas_impressas_disciplinas`
--
ALTER TABLE `provas_impressas_disciplinas`
  ADD CONSTRAINT `provas_impressas_disciplinas_iddisciplina` FOREIGN KEY (`iddisciplina`) REFERENCES `disciplinas` (`iddisciplina`),
  ADD CONSTRAINT `provas_impressas_disciplinas_idprova_impressa` FOREIGN KEY (`idprova_impressa`) REFERENCES `provas_impressas` (`idprova_impressa`);

--
-- Restrições para tabelas `provas_impressas_perguntas`
--
ALTER TABLE `provas_impressas_perguntas`
  ADD CONSTRAINT `FK_provas_impressas_perguntas_idpergunta` FOREIGN KEY (`idpergunta`) REFERENCES `perguntas` (`idpergunta`),
  ADD CONSTRAINT `FK_provas_impressas_perguntas_idprova_impressa` FOREIGN KEY (`idprova_impressa`) REFERENCES `provas_impressas` (`idprova_impressa`);

--
-- Restrições para tabelas `provas_presenciais_escolas`
--
ALTER TABLE `provas_presenciais_escolas`
  ADD CONSTRAINT `provas_presenciais_escolas_id_prova_presencial` FOREIGN KEY (`id_prova_presencial`) REFERENCES `provas_presenciais` (`id_prova_presencial`),
  ADD CONSTRAINT `provas_presenciais_escolas_idescola` FOREIGN KEY (`idescola`) REFERENCES `escolas` (`idescola`);

--
-- Restrições para tabelas `provas_presenciais_locais_provas`
--
ALTER TABLE `provas_presenciais_locais_provas`
  ADD CONSTRAINT `provas_presenciais_locais_provas_id_prova_presencial` FOREIGN KEY (`id_prova_presencial`) REFERENCES `provas_presenciais` (`id_prova_presencial`),
  ADD CONSTRAINT `provas_presenciais_locais_provas_idlocal` FOREIGN KEY (`idlocal`) REFERENCES `locais_provas` (`idlocal`);

--
-- Restrições para tabelas `provas_solicitadas`
--
ALTER TABLE `provas_solicitadas`
  ADD CONSTRAINT `provas_solicitadas_id_prova_presencial` FOREIGN KEY (`id_prova_presencial`) REFERENCES `provas_presenciais` (`id_prova_presencial`),
  ADD CONSTRAINT `provas_solicitadas_idcurso` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`),
  ADD CONSTRAINT `provas_solicitadas_idescola` FOREIGN KEY (`idescola`) REFERENCES `escolas` (`idescola`),
  ADD CONSTRAINT `provas_solicitadas_idlocal` FOREIGN KEY (`idlocal`) REFERENCES `locais_provas` (`idlocal`),
  ADD CONSTRAINT `provas_solicitadas_idmatricula` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`),
  ADD CONSTRAINT `provas_solicitadas_idmotivo` FOREIGN KEY (`idmotivo`) REFERENCES `motivos_cancelamento_solicitacao_prova` (`idmotivo`);

--
-- Restrições para tabelas `provas_solicitadas_disciplinas`
--
ALTER TABLE `provas_solicitadas_disciplinas`
  ADD CONSTRAINT `provas_solicitadas_disciplinas_ibfk_1` FOREIGN KEY (`id_solicitacao_prova`) REFERENCES `provas_solicitadas` (`id_solicitacao_prova`),
  ADD CONSTRAINT `provas_solicitadas_disciplinas_iddisciplina` FOREIGN KEY (`iddisciplina`) REFERENCES `disciplinas` (`iddisciplina`);

--
-- Restrições para tabelas `quadros_avisos_cursos`
--
ALTER TABLE `quadros_avisos_cursos`
  ADD CONSTRAINT `quadros_avisos_cursos_idcurso` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`),
  ADD CONSTRAINT `quadros_avisos_cursos_idquadro` FOREIGN KEY (`idquadro`) REFERENCES `quadros_avisos` (`idquadro`);

--
-- Restrições para tabelas `quadros_avisos_escolas`
--
ALTER TABLE `quadros_avisos_escolas`
  ADD CONSTRAINT `quadros_avisos_escolas_idescola` FOREIGN KEY (`idescola`) REFERENCES `escolas` (`idescola`),
  ADD CONSTRAINT `quadros_avisos_escolas_idquadro` FOREIGN KEY (`idquadro`) REFERENCES `quadros_avisos` (`idquadro`);

--
-- Restrições para tabelas `quadros_avisos_imagens`
--
ALTER TABLE `quadros_avisos_imagens`
  ADD CONSTRAINT `quadros_avisos_imagens_ibfk_1` FOREIGN KEY (`idquadro`) REFERENCES `quadros_avisos` (`idquadro`);

--
-- Restrições para tabelas `quadros_avisos_ofertas`
--
ALTER TABLE `quadros_avisos_ofertas`
  ADD CONSTRAINT `quadros_avisos_ofertas_idoferta` FOREIGN KEY (`idoferta`) REFERENCES `ofertas` (`idoferta`),
  ADD CONSTRAINT `quadros_avisos_ofertas_idquadro` FOREIGN KEY (`idquadro`) REFERENCES `quadros_avisos` (`idquadro`);

--
-- Restrições para tabelas `reconhecimento_fotos`
--
ALTER TABLE `reconhecimento_fotos`
  ADD CONSTRAINT `reconhecimento_fotos_FK` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`) ON DELETE CASCADE;

--
-- Restrições para tabelas `relacionamentos_comerciais`
--
ALTER TABLE `relacionamentos_comerciais`
  ADD CONSTRAINT `relacionamentos_comerciais_idpessoa` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`);

--
-- Restrições para tabelas `relacionamentos_comerciais_mensagens`
--
ALTER TABLE `relacionamentos_comerciais_mensagens`
  ADD CONSTRAINT `relacionamentos_comerciais_mensagens_idrelacionamento` FOREIGN KEY (`idrelacionamento`) REFERENCES `relacionamentos_comerciais` (`idrelacionamento`),
  ADD CONSTRAINT `relacionamentos_comerciais_mensagens_idusuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios_adm` (`idusuario`),
  ADD CONSTRAINT `relacionamentos_comerciais_mensagens_idvendedor` FOREIGN KEY (`idvendedor`) REFERENCES `vendedores` (`idvendedor`);

--
-- Restrições para tabelas `relacionamentos_comercial`
--
ALTER TABLE `relacionamentos_comercial`
  ADD CONSTRAINT `relacionamentos_comercial_idpessoa` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`),
  ADD CONSTRAINT `relacionamentos_comercial_idusuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios_adm` (`idusuario`),
  ADD CONSTRAINT `relacionamentos_comercial_idvendedor` FOREIGN KEY (`idvendedor`) REFERENCES `vendedores` (`idvendedor`);

--
-- Restrições para tabelas `relacionamentos_pedagogico`
--
ALTER TABLE `relacionamentos_pedagogico`
  ADD CONSTRAINT `relacionamentos_pedagogico_idpessoa` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`),
  ADD CONSTRAINT `relacionamentos_pedagogico_idusuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios_adm` (`idusuario`);

--
-- Restrições para tabelas `retornos`
--
ALTER TABLE `retornos`
  ADD CONSTRAINT `retornos_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `usuarios_adm` (`idusuario`);

--
-- Restrições para tabelas `retornos_contas`
--
ALTER TABLE `retornos_contas`
  ADD CONSTRAINT `retornos_contas_ibfk_1` FOREIGN KEY (`idretorno`) REFERENCES `retornos` (`idretorno`),
  ADD CONSTRAINT `retornos_contas_ibfk_2` FOREIGN KEY (`idconta`) REFERENCES `contas` (`idconta`);

--
-- Restrições para tabelas `sindicatos`
--
ALTER TABLE `sindicatos`
  ADD CONSTRAINT `sindicatos_idcidade` FOREIGN KEY (`idcidade`) REFERENCES `cidades` (`idcidade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `sindicatos_idestado` FOREIGN KEY (`idestado`) REFERENCES `estados` (`idestado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `sindicatos_idlogradouro` FOREIGN KEY (`idlogradouro`) REFERENCES `logradouros` (`idlogradouro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `sindicatos_idmantenedora` FOREIGN KEY (`idmantenedora`) REFERENCES `mantenedoras` (`idmantenedora`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `sindicatos_valores_cursos`
--
ALTER TABLE `sindicatos_valores_cursos`
  ADD CONSTRAINT `fk_sindicatos_valores_cursos_1` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_sindicatos_valores_cursos_2` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `solicitantes_bolsas`
--
ALTER TABLE `solicitantes_bolsas`
  ADD CONSTRAINT `solicitantes_bolsas_idcidade` FOREIGN KEY (`idcidade`) REFERENCES `cidades` (`idcidade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `solicitantes_bolsas_idestado` FOREIGN KEY (`idestado`) REFERENCES `estados` (`idestado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `solicitantes_bolsas_idlogradouro` FOREIGN KEY (`idlogradouro`) REFERENCES `logradouros` (`idlogradouro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `solicitantes_bolsas_idsindicato` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `tipos_documentos_cursos`
--
ALTER TABLE `tipos_documentos_cursos`
  ADD CONSTRAINT `tipos_documentos_cursos_idcurso` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tipos_documentos_cursos_idtipo` FOREIGN KEY (`idtipo`) REFERENCES `tipos_documentos` (`idtipo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `tipos_documentos_sindicatos`
--
ALTER TABLE `tipos_documentos_sindicatos`
  ADD CONSTRAINT `tipos_documentos_sindicatos_idsindicato` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tipos_documentos_sindicatos_idtipo` FOREIGN KEY (`idtipo`) REFERENCES `tipos_documentos` (`idtipo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `tipos_documentos_sindicatos_agendamento`
--
ALTER TABLE `tipos_documentos_sindicatos_agendamento`
  ADD CONSTRAINT `tipos_documentos_sindicatos_agendamento_idsindicato` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tipos_documentos_sindicatos_agendamento_idtipo` FOREIGN KEY (`idtipo`) REFERENCES `tipos_documentos` (`idtipo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `usuarios_adm`
--
ALTER TABLE `usuarios_adm`
  ADD CONSTRAINT `usuarios_adm_ibfk_1` FOREIGN KEY (`idexcecao`) REFERENCES `excecoes` (`idexcecao`),
  ADD CONSTRAINT `usuarios_adm_idcidade` FOREIGN KEY (`idcidade`) REFERENCES `cidades` (`idcidade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuarios_adm_idestado` FOREIGN KEY (`idestado`) REFERENCES `estados` (`idestado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuarios_adm_idperfil` FOREIGN KEY (`idperfil`) REFERENCES `usuarios_adm_perfis` (`idperfil`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `usuarios_adm_sindicatos`
--
ALTER TABLE `usuarios_adm_sindicatos`
  ADD CONSTRAINT `usuarios_adm_sindicatos_idsindicato` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`),
  ADD CONSTRAINT `usuarios_adm_sindicatos_idusuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios_adm` (`idusuario`);

--
-- Restrições para tabelas `vendedores`
--
ALTER TABLE `vendedores`
  ADD CONSTRAINT `vendedores_idcidade` FOREIGN KEY (`idcidade`) REFERENCES `cidades` (`idcidade`),
  ADD CONSTRAINT `vendedores_idestado` FOREIGN KEY (`idestado`) REFERENCES `estados` (`idestado`),
  ADD CONSTRAINT `vendedores_idlogradouro` FOREIGN KEY (`idlogradouro`) REFERENCES `logradouros` (`idlogradouro`);

--
-- Restrições para tabelas `vendedores_contatos`
--
ALTER TABLE `vendedores_contatos`
  ADD CONSTRAINT `vendedores_contatos_idtipo` FOREIGN KEY (`idtipo`) REFERENCES `tipos_contatos` (`idtipo`),
  ADD CONSTRAINT `vendedores_contatos_idvendedor` FOREIGN KEY (`idvendedor`) REFERENCES `vendedores` (`idvendedor`);

--
-- Restrições para tabelas `vendedores_sindicatos`
--
ALTER TABLE `vendedores_sindicatos`
  ADD CONSTRAINT `vendedores_sindicatos_idsindicato` FOREIGN KEY (`idsindicato`) REFERENCES `sindicatos` (`idsindicato`),
  ADD CONSTRAINT `vendedores_sindicatos_idvendedor` FOREIGN KEY (`idvendedor`) REFERENCES `vendedores` (`idvendedor`);

--
-- Restrições para tabelas `visitas_mensagens`
--
ALTER TABLE `visitas_mensagens`
  ADD CONSTRAINT `visitas_mensagens_idusuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios_adm` (`idusuario`),
  ADD CONSTRAINT `visitas_mensagens_idvendedor` FOREIGN KEY (`idvendedor`) REFERENCES `vendedores` (`idvendedor`),
  ADD CONSTRAINT `visitas_mensagens_idvisita` FOREIGN KEY (`idvisita`) REFERENCES `visitas_vendedores` (`idvisita`);

--
-- Restrições para tabelas `visitas_vendedores`
--
ALTER TABLE `visitas_vendedores`
  ADD CONSTRAINT `visitas_vendedores_ibfk_1` FOREIGN KEY (`idvendedor`) REFERENCES `vendedores` (`idvendedor`),
  ADD CONSTRAINT `visitas_vendedores_ibfk_2` FOREIGN KEY (`idpessoa`) REFERENCES `pessoas` (`idpessoa`),
  ADD CONSTRAINT `visitas_vendedores_ibfk_3` FOREIGN KEY (`idmatricula`) REFERENCES `matriculas` (`idmatricula`),
  ADD CONSTRAINT `visitas_vendedores_ibfk_4` FOREIGN KEY (`idmidia`) REFERENCES `midias_visitas` (`idmidia`),
  ADD CONSTRAINT `visitas_vendedores_ibfk_5` FOREIGN KEY (`idlocal`) REFERENCES `locais_visitas` (`idlocal`),
  ADD CONSTRAINT `visitas_vendedores_ibfk_6` FOREIGN KEY (`idmotivo`) REFERENCES `motivos_visitas` (`idmotivo`),
  ADD CONSTRAINT `visitas_vendedores_ibfk_7` FOREIGN KEY (`idusuario`) REFERENCES `usuarios_adm` (`idusuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
