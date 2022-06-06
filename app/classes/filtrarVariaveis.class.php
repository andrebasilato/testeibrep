<?php
/**
 * undocumented class
 *
 * @package filtro
 * @author  Jefersson Nathan <jeferssonn@alfamaweb.com.br>
 */
class filtrarVariaveis
{
    /**
     * Coleção com todos os filtros
     *
     * @var array
     */
    private $_filtros = array();

    /**
     * Instancia de matricula
     *
     * @var Matricula object
     */
    private $_matriculaInstancia;

    /**
     * Construtor - Denpendency Injection
     *
     * @param $matriculaInstancia
     *
     * @return \filtrarVariaveis
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function __construct(&$matriculaInstancia)
    {
        $this->_matriculaInstancia = $matriculaInstancia;
    }

    /**
     * Registra um filtro no container
     *
     * @param Filtro $filtro
     *
     * @return void
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function registrarFiltro(Filtro $filtro)
    {
        $this->_filtros[] = $filtro;
        return $this;
    }

    /**
     * Aplica filtros registrados ao conteúdo
     *
     * @return void
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     */
    public function aplicar()
    {
        foreach ($this->_filtros as $filtro) {
            $this->_matriculaInstancia = $filtro->renderizar($this->_matriculaInstancia);
        }
        return $this->_matriculaInstancia;
    }
}