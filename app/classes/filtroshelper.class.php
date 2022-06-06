<?php
/**
 * Usado como helper para validação e conversão de dados.
 *
 * Estes métodos é intencionado para o uso em formulários|informações altamente dinâmicas
 * (no detalhe adicione o "wizard" da requisição), as informações que são reconstruídos
 * ou modificadas dependendo de formas diferentes dependendo de que provocando o elemento
 * (AJAX, salvar/modificar/persistir dados ou de outra maneira) for preciso alterar ou
 * formatar a saida de dados.
 *
 * Por exemplo, às vezes é necessário decidir-se como mostrar ou salvar uma infromação
 * proveniente de um formulário baseado no valor de um elemento/id do elemento.
 * Para fins de esclarecimento, tome como exemplo números de CPF que salvamos no banco
 * de dados sem acentuação '11111111111' e os mostramos ao usuário com pontuação
 * '111.111.111-11'
 *
 * Pela necessidade, esta função usa às vezes a variável estática $_messes
 * para deferir algumas tarefas com formatar uma data (ou se necessário em outra ocasião).
 *
 * @author      Jefersson Nathan <jeferssonn@alfamaweb.com.br>
 * @copyright   Copyright (c) Alfama Web 2014
 *
 * @package     Oráculo
 * @since       3.0.0
 * @version     0.1
 *
 */
class FiltrosHelper
{

    /**
     * Usado internamente em alguns métodos para formatação de data e afins.
     */
    private static $_messes = array(
        '01' => 'Janeiro',
        '02' => 'Fevereiro',
        '03' => 'Março',
        '04' => 'Abril',
        '05' => 'Maio',
        '06' => 'Junho',
        '07' => 'Julho',
        '08' => 'Agosto',
        '09' => 'Setembro',
        '10' => 'Outubro',
        '11' => 'Novembro',
        '12' => 'Dezembro'
    );

    /**
     * Converte uma data para o formato padrão Brasileiro ou usando o formato
     * passado para o parâmetro $Output
     *
     * @param string $data Contém uma data válida
     * @param string $Output
     *
     * @internal param string $Ouput Usado para formatar a data
     *
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @return string  Data formatada
     */
    public static function converterData($data, $Output = 'd/m/Y')
    {
        $date = new DateTime($data);
        return $date->format($Output);
    }

    /**
     * Transcreve o mês de uma data de acordo com o formato passado para
     * $Ouput, se não for passado nenhum valor, o padrão será usado
     *
     * @param string $data Contém uma data válida
     * @param string $Output
     *
     * @internal param string $Ouput Usado para formatar a data
     *
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @return string  Data formatada em português Brazil
     */
    public static function transcreverData($data, $Output = '{dia} de {mes} de {ano}')
    {
        $date = new DateTime($data);
        $info = array (
            '{dia}' => $date->format('d'),
            '{mes}' => self::$_messes[$date->format('m')],
            '{ano}' => $date->format('Y')
        );

        $result = str_replace(array_keys($info), $info, $Output);
        return $result;
    }

    /**
     * Formata um número de cpf de
     * acordo com o padrão xxx.xxx.xxx-xx
     *
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @param $data
     *
     * @return string  CPF formatado
     */
    public static function formatarCpf($data)
    {
        preg_match_all('/(\d{3})(\d{3})(\d{3})(\d{2})/i', $data, $cpfParts, PREG_SET_ORDER);

        $cpfParts = $cpfParts[0];
        array_shift($cpfParts);

        return sprintf('%s.%s.%s-%s', $cpfParts[0], $cpfParts[1], $cpfParts[2], $cpfParts[3]);
    }

	public static function formatarCEP($cep) {
        preg_match_all('/(\d{5})(\d{3})/i', $cep, $cepPartes, PREG_SET_ORDER);

        $cepPartes = $cepPartes[0];
        array_shift($cepPartes);

        return sprintf('%s-%s', $cepPartes[0], $cepPartes[1]);
    }

	public static function formatarCNPJ($cnpj)
    {
        preg_match_all('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/i', $cnpj, $cnpjPartes, PREG_SET_ORDER);

        $cnpjPartes = $cnpjPartes[0];
        array_shift($cnpjPartes);

        return sprintf('%s.%s.%s/%s-%s', $cnpjPartes[0], $cnpjPartes[1], $cnpjPartes[2], $cnpjPartes[3], $cnpjPartes[4]);
    }

    /**
     * Retirna o nome do pais referente ao `$idpais`.
     *
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @param $idpais
     *
     * @return string  Data formatada no padrão Brazil
     */
    public static function nomeDoPais($idpais)
    {
        $stmt = mysql_query(sprintf('SELECT * FROM `paises` WHERE idpais = %d', $idpais));
        $result = mysql_fetch_object($stmt);
        return $result->nome;
    }

	public static function nomeDaCidade($idcidade)
    {
        $stmt = mysql_query(sprintf('SELECT * FROM `cidades` WHERE idcidade = %d', $idcidade));
        $result = mysql_fetch_object($stmt);
        return $result;
    }

	public static function nomeUfDoEstado($idestado)
    {
        $stmt = mysql_query(sprintf('SELECT * FROM `estados` WHERE idestado = %d', $idestado));
        $result = mysql_fetch_object($stmt);
        return $result;
    }

    /**
     * Retorna uma coluna de passada para `$field` de um `usuário administrativo`
     * apartir do `idusuario` passado como primeiro parâmetro.
     *
     * @author Jefersson Nathan <jeferssonn@alfamaweb.com.br>
     *
     * @param $idusuario
     * @param string $field
     *
     * @return string  Coluna referente a um `usuário administrativo`
     */
    public static function usuarioAdministrativo($idusuario, $field = 'nome')
    {
        $stmt = mysql_query(sprintf('SELECT * FROM `usuarios_adm` WHERE idusuario = %d', $idusuario));
        $result = mysql_fetch_object($stmt);
        return $result->$field;
    }

	public static function dadosUsuarioAdministrativo($idusuario) {
        $stmt = mysql_query(sprintf('SELECT * FROM `usuarios_adm` WHERE idusuario = %d', $idusuario));
        $result = mysql_fetch_object($stmt);
        return $result;
    }

	public static function nomeLogradouro($idlogradouro) {
        $stmt = mysql_query(sprintf('SELECT * FROM `logradouros` WHERE idlogradouro = %d', $idlogradouro));
        $result = mysql_fetch_object($stmt);
        return $result;
    }
    /**
     * Método para somar a quantidade de anos desejada na data e retornar a data já formatada.
     * @access public
     * @param string $data
     * @param int $quantidadeAnos
     * @param string $formataData
     * @return string
     */
    public static function somarAno($data, $quantidadeAnos, $formatoData = 'd/m/Y')
    {
        try {
            if(!is_int($quantidadeAnos)){
                throw new InvalidArgumentException('O valor da quantidade de anos tem que ser do tipo inteiro.');
            }
        $somarAno = new DateTime($data);
        $somarAno->add(new DateInterval("P{$quantidadeAnos}Y"));
        return $somarAno->format($formatoData);
        } catch (InvalidArgumentException $e){
            return "Ops! ocorreu um erro: {$e->getMessage()}";
        } catch (Exception $e){
            return "Ops! ocorreu um erro: {$e->getMessage()}";
        }
    }

}
