<?php
/**
 * Classe para Conexao com banco de dados MySql
 */
class MySql
{
    //conexao com o banco de dados
    function connOpen()
    {			
        // Dados da conexão
        include('conecta.php');

		@$this->conn = mysql_connect($server, $user, $passw);
		mysql_set_charset('utf8');
        if (!$this->conn)
        {//caso haja erro na conexao
            echo 'Erro ao conectar com o servidor. '.$this->error();
            exit (1);
        }
		
		if (!mysql_select_db($database, $this->conn))
        {//caso nao abra a base de dados
            echo 'Erro ao selecionar a base de dados. '.$this->error();
            exit (1);
        }
    }

    //fechar a conexao com o banco
    function connClose()
    {
        mysql_close($this->conn);
    }

    /**
     * executa uma sql no banco
     *
     * @param String $sql, comando SQL a ser executado no banco ex: SELECT * FROM tabela
     * @param Boolean $boolean, true retorna ultima id, false retorna resultado
     * @return Variante
     */
    function executeQuery($sql,$param=false)
    {
		$this->connOpen();

        $this->result = mysql_query($sql);
        if (!$this->result)
        {//caso nao execute a query corretamente
            echo 'Não foi possivel executar o comando SQL. '.$this->error();
            exit (1);
        }
		
		if ($param){
			$this->result = $this->lastId();
		}
        	$this->connClose();
		
			return $this->result;
    }

    /**
     * contar e retorna o numero de linhas de uma consulta
     *
     * @return integer
     */
    function countLines($array)
    {
        return @mysql_num_rows($array);
    }

    /**
     * mostrar o erro caso haja
     *
     * @return String
     */
    function error()
    {
        return mysql_error();
    }

    /**
     * retorna o valor do campo
     *
     * @param Integer $num, numero da linha a ser mostrada o valor
     * @param String $field, nome do campo a ser mostrado o valor
     * @return Variante
     */
    function result($num, $field)
    {
        return mysql_result($this->result, $num, $field);
    }
	
	/**
	 * Retorna o ultimo id inserido no banco
	 * 
	 * @return Integer
	 */
	function lastId(){
		return mysql_insert_id();
	}
	
	function countFields($array){
		return @mysql_num_fields($array);
	}
	
	function fieldName($array,$num){
		return mysql_fetch_field($array, $num);
	}
}

?>
