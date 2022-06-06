# Oraculo-Transito

[![PHP Composer](https://github.com/alfamaweb/Oraculo-Transito/actions/workflows/php.yml/badge.svg)](https://github.com/alfamaweb/Oraculo-Transito/actions/workflows/php.yml)

Requisitos
-----------------
* PHP 5.6.40
* Mysql 5.7.31

Arquivos de configurações
-----------------
O sistema tem configurações para cada cliente e ambiente específicos.

* **app/especifico/inc/config.banco.php** - Configurações da conexão entre servidor e banco de dados
  * VARIÁVEIS
    * **$config["host"]** - Endereço de servidor. 
      Valores possíveis: "localhost"
    * **$config["usuario"]** - Usuário de acesso ao banco de dados.
      Valores possíveis: "root"
    * **$config["senha"]** - Senha de acesso ao banco de dados.
    * **$config["database"]** - Nome do banco de dados.
      Valores possíveis: "local_transito"
* **config.especifico.php** - Nesse arquivo ira conter as configurações gerais do sistema:
  * VARIÁVEIS
    * **$config["tituloEmpresa"]** - Título da aba;
    * **$config["websiteEmpresa"]** - URL do website
    * **$config["tituloSistema"]** - Título do sistema
    * **$config["urlSistema"]** - URL do sistema
    * **$config["urlSistemaFixa"]** -  URL do sistema
    * **$config["url"]** - URL do sistema
    * **$config["urlSite"]** - URL do sistema
    * **$config["urlSiteCliente"]** - URL padrão do sistema
    * **$config["emailEsqueci"]** - Endereço de email
    * **$config["emailLoja"]** - Endereço de email de notificações da loja
    * **$config["chaveLogin"]** - Chave utilizada para calcular o hash na criptografia das senhas
    * **$config["tamanho_upload_padrao"]** - Tamanho máximo de upload
    * **$config['cadastrar_sindicato']** - Flag de permissão
    * **$config['cadastrar_mantenedora']** - Se essa instalação permite ou não fazer cadastrados de mantenedora

    * **$config['integrado_com_sms']** - Se a integração com SMS estará ativa.
    * **$config["loginSMS"]** - Usuário de acesso da API do serviço de SMS
    * **$config["tokenSMS"]** - Token de acesso da API do serviço de SMS
    * **$config["linkapiSMS"]** - URL do serviço de SMS

    * **$config['limite_emails_mailing']** - Limite de email
    * **$config["emailSistema"]** - Endereço de email
    * **$config["emailMailing"]** - Endereço de email
    * **$config["emailParceria"]** - Endereço de email

    * **$config["telefone"]** - Número de telefone

    * **$config["host_log"]** - Endereço do servidor de log
    * **$config["usuario_log"]** - Usuário de acesso ao servidor de log
    * **$config["senha_log"]** - Senha de acesso ao servidor de log
    * **$config["database_log"]** - Banco de dados do servidor de log

    * **$config["link_chat_cfc"]** - Link de chat
    * **$config['videoteca_local']** - Flag de permissão
    * **$config['filtro_matricula_boleto_semana']** - Flag de permissão
    * **$config['email_naoresponda']** - Endereço de email

    * **$config['email_host']** - URL de serviço de email
    * **$config['email_port']** - Porta do servidor de serviço de email
    * **$config['email_secure']** - Flag do servidor de serviço de email
    * **$config['email_username']** - Usuário de acesso servidor de serviço de email
    * **$config['email_password']** - Senha de acesso servidor de serviço de email

    * **$config['dias_vencimento_conta']** - Dias para o vencimento da conta da matrícula

    * **$config['pagarme']['habilitar_checkout']** - Flag de permissão
    * **$config['pagarme']['postback_url']** - URL da API do serviço Pagarme
    * **$config['pagarme']['api_key']** - Chave da API do serviço Pagarme
    * **$config['pagarme']['encryption_key']** - Chave de encriptação do serviço Pagarme
    * **$config['pagarme']['dias_vencimento']** - Dias de vencimento
    * **$config['pagarme']['multa_atraso']** - Porcentagem de multa por atraso
    * **$config['pagarme']['juros_atraso']** - Porcentagem de juros por dia de atraso

    * **$config['datavalid']['probabDefault']** - Decimal de probabilidade
    * **$config['datavalid']['demo']** - Flag de permissão
    * **$config['datavalid']['consumer_key']** -  Chave da API do serviço Datavalid
    * **$config['datavalid']['consumer_secret']** - Senha da API do serviço Datavalid
    * **$config['datavalid']['urlToken']** - URL de token da API do serviço Datavalid
    * **$config['datavalid']['urlProducao']** - URL da API de produção do serviço Datavalid
    * **$config['datavalid']['urlDemostracao']** - URL da API de demonstração do serviço Datavalid
    * **$config['datavalid']['limite_tentativas']** - Limite de tentativas

    * **$config['pagSeguro']['url']** -  Chave da API do serviço Pagseguro
    * **$config['pagSeguro']['urlWs']** -  URL da API do serviço Pagseguro
    * **$config['pagSeguro']['urlStc']** - URL STC da API do serviço Pagseguro
    * **$config['pagSeguro']['redirectURL']** - URL de redirecionamento para URL da API do serviço Pagseguro
    * **$config['pagSeguro']['notificationURL']** - URL de nofificação da API do serviço Pagseguro

    * **$config['fastConnect']['url']** - URL da API do serviço FastConnect
    * **$config['fastConnect']['url_producao']** - URL de produção da API do serviço FastConnect
    * **$config['fastConnect']['url_sandbox']** - URL de teste da API do serviço FastConnect
    * **$config['fastConnect']['url_retorno']** - URL de retorno da API do serviço FastConnect
    * **$config['fastConnect']['cliente_code']** - Código de cliente da API do serviço FastConnect
    * **$config['fastConnect']['client_key']** - Chave de cliente da API do serviço FastConnect

    * **$config['reconhecimento']['range_minimo']** - Decimal de confidência mínimo, utilizado em integrações de reconhecimento facial
    * **$config['cfc_aviso']** - Flag de alerta de aviso, no painel do CFC;
    * **$config['urlSiteLoja']** - URL do website da loja
    * **$config['matricula_cfc']['idoferta']** - ID oferta padrão, usado para cadastrar uma matrícula pela API matricula_cfc
    * **$config['matricula_cfc']['idatendente']** - ID atendente padrão, usado para cadastrar uma matrícula pela API matricula_cfc
    * **$config['matricula_cfc']['idturma']** -  ID turma padrão, usado para cadastrar uma matrícula pela API matricula_cfc
