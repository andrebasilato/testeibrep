<?php
/**
 * Classe para otmização de contéudo web para o usuário.
 * Se pretende ultilizar para comprimir javascript, considere usar um minify
 *
 * @license Private (c) AlfamaWeb
 * @package Oráculo
 * @author  Jefersson Nathan <jeferssonn@alfamaweb.com.br>
 */
class OptimizeSchema
{
    /**
     * Remover apenas espaços duplicados
     */
    const REMOVE_WHITESPACE = '/(\s{2}+)/';

    /**
     * Essa expressão regular remove espaços em branco
     * encadeados e quebra de linha
     */
    const REMOVE_INVISIBLE_CHARS = '/(\s{2}+)|(\n)|(\s\n)|(\t)|(\r)/';

    /**
     * Mantém o estado da coleta de dados, se estiver false. A coleta de dados
     * não foi iniciado.
     *
     * @var boolean
     */
    private $_wasInitied = false;

    /**
     * Conteúdo conseguida por 'ob_start' é guardado aqui
     *
     * @var string
     */
    private $_webContent;

    /**
     * Inicia a coleta de dados web enviados do php para o navegador e os deixa
     * em buffer, isso é feito pela função ob_start(), geralmente habilitada em
     * ambientes em que o php está em uso.
     *
     * @throws Exeption
     * @return boolean
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function initCollector()
    {
        if (function_exists('ob_start')) {
            ob_start();
            $this->_wasInitied = true;
            return true;
        }

        throw new Exeption('Função ob_start() pareçe não existir.');
    }

    /**
     * Verifica se existe uma coleta ativa, caso contrário, lança um Fatal Error
     * Se tiver uma ativa, limpa o buffer e guarda o conteúdo em $<_webContent>
     * Interface fluída (._. )
     *
     * @throws Exception
     * @return OptimizeSchema object
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function saveCollection()
    {
        if (! $this->_wasInitied) {
            throw new Exception(
                'Use o método initColletion para começar a pegar o conteúdo web'
            );
        }

        $this->_webContent = ob_get_clean();
        return $this;
    }

    /**
     * Aplica um filtro ao conteúdo que estava em buffer :)
     *
     * @param  $regex - Uma expressão regular para aplicar ao conteúdo
     * @param  $by    - Uma string a ser substituida pelo conteúdo que for pego
     *                  pela expressão regular passada em $regex
     *
     * @return OptimizeSchema|boolean
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function useFilter($regex, $by = ' ')
    {
        if ($this->_hasWebContent()) {
            $this->_webContent = preg_replace($regex, $by, $this->_webContent);
            return $this;
        }
        return false;
    }

    /**
     * Retorna o conteúdo, dependendo de "quando" você chame esse método
     * o conteúdo pode estar sem o filtro, ultilize-o após chamar o método
     * OptimizeSchema::useFilter($regex, $by:string)
     *
     * @return string
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function render()
    {
        return $this->_webContent;
    }

    /**
     * Método criado para saber se existe conteúdo guardado em $<_webContent>
     *
     * @throws InvalidArgumentException
     * @return boolean
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    private function _hasWebContent()
    {
        if (! $this->_webContent) {
            throw new InvalidArgumentException(
                'Nenhum conteúdo foi adquirido :('
            );
        }

        return true;
    }
}