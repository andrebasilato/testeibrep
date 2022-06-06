-- chats table
CREATE TABLE `chats` (
    idchat INT(10) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    idava INT(10) NOT NULL,
    idinstrutor INT(10) NOT NULL COMMENT 'idpessoa',
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    data_cad TIMESTAMP DEFAULT NOW(),
    data_agendamento TIMESTAMP NOT NULL COMMENT 'Inicio do chat',
    data_encerramento TIMESTAMP NOT NULL COMMENT 'data de encerramento do chat',
    foto_chamada VARCHAR(255),
    arquivo VARCHAR(255),
    ativo ENUM('S', 'N') DEFAULT 'S' NOT NULL,
    ativo_painel ENUM('S', 'N') DEFAULT 'S' NOT NULL
);

-- chats_pessoas
CREATE TABLE `chats_pessoas` (
    idchat_pessoa INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idchat INT(10) NOT NULL,
    idpessoa INT(10) NOT NULL,
    data_cad TIMESTAMP DEFAULT NOW(),
    ativo ENUM('S', 'N') DEFAULT 'S' NOT NULL,
    ativo_painel ENUM('S', 'N') DEFAULT 'S' NOT NULL
);

-- chats_mensagens
CREATE TABLE `chats_mensagens` (
    idchat_mensagem INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    idchat INT(10) NOT NULL,
    idpessoa INT(10) NOT NULL,
    acao INT(10),
    mensagem TEXT NOT NULL,
    arquivo VARCHAR(255),
    data_cad TIMESTAMP DEFAULT NOW(),
    ativo ENUM('S', 'N') DEFAULT 'S' NOT NULL,
    ativo_painel ENUM('S', 'N') DEFAULT 'S' NOT NULL
);

-- chat_acoes
CREATE TABLE `chats_acoes` (
    idchat_acao INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    acao INT(10),
    icone VARCHAR(255),
    data_cad TIMESTAMP DEFAULT NOW(),
    ativo ENUM('S', 'N') DEFAULT 'S' NOT NULL,
    ativo_painel ENUM('S', 'N') DEFAULT 'S' NOT NULL
);
