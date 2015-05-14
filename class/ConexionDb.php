<?php


/**
 * Clase Conexion a base de datos
 */
class ConexionDb
{
    // variables

    protected $_pdo;
    private $stmt;

    private $dbhost;
    private $dbname;
    private $dbpass;


    // Conexion a base de datos utilizando el metodo PDO
    public function __construct()
    {
        // change connection local y production server 172.16.20.21
        $this->dbhost = "mysql:host=localhost;dbname=moodle_control";
        $this->dbname = "root";

        /**
         * Definir el password de local y de servidor
         */

        if($_SERVER["SERVER_NAME"] == '172.16.20.21')
        {
            $this->dbpass = ""; // root para localhost y "" para servidor 20.24
        }
        else
        {
            $this->dbpass = "root";
        }



        try{
            $this->_pdo = new PDO($this->dbhost,$this->dbname,$this->dbpass);
            $this->_pdo->exec("SET CHARACTER SET utf8");
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e){
            echo 'Error: '. $e->getMessage();
        }
    }

    // recuperamos el valor de pdo.
    public function RtnPdo()
    {
        return $this->_pdo;
    }

    /**
     * query PDO::prepare function, permite proteger los valores de una consulta SQL
     * con esto obtenemos un mejor rendimiento a la hora de ejecutar diferentes SQL
     * 
     * @param  type $sql [sentencia SQL a procesar]
     * @return type      [no hay retorno]
     */
    public function query($sql)
    {
        $this->stmt = $this->_pdo->prepare($sql);
    }

    /**
     * bind PDO:bindValue, se utiliza para preparar las variables para la sentencia SQL
     * @param  type] $param [Nombre del valor que se va a usar en la consulta SQL]
     * @param  type] $value [valor actual que vamos a procesar]
     * @param  type] $type  [formato del valor]
     * @return type]        [description]
     */

    public function bind($param,$value,$type = NULL)
    {
        if(is_null($type))
        {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
        }
        $this->stmt->bindValue($param,$value,$type);
    }


    /**
     * execute PDOStatement:execute, Ejecuta las sentacias SQL previamente preparadas]
     * @return type] [description]
     */
    public function execute()
    {
        $this->stmt->execute();
    }

    /**
     * resultset PDOStatement:fetchAll retorna un array con los valores consultados DB]
     * @return type] [return array sql values]
     */
    public function resultset()
    {
        $this->execute();
        $count = $this->rowCount();
        if($count > 0)
            return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        else
            return false;

    }

    /**
     * single PDOStatement:fetch retorna un unico valor de la DB]
     * @return type] [return the single result]
     */
    public function single()
    {
        $this->execute();
        $count = $this->rowCount();
        if($count > 0)
            return $this->stmt->fetch(PDO::FETCH_ASSOC);
        else
            return false;

    }

    /**
     * [rowCount Retorna el numero de registros seleccionandos]
     * @return [type] [none]
     */
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    /**
     * lastInsertId retorna el ultimo id del registro insertado]
     * @return type] [none]
     */
    public function lastInsertId()
    {
        return $this->_pdo->lastInsertId();
    }

    /**
     * beginTransaction ]
     * @return type] [description]
     */
    public function beginTransaction()
    {
        return $this->_pdo->beginTransaction();
    }

    /**
     * endTransaction description]
     * @return type] [description]
     */
    public function endTransaction()
    {
        return $this->_pdo->commit();
    }

    /**
     * cancelTransaction description]
     * @return type] [description]
     */
    public function cancelTransaction()
    {
        return $this->_pdo->rollBack();
    }

    /**
     * debugDumpParams Vuelca un comando preparado de SQL]
     * @return type] [description]
     */
    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }






    public function __clone()
    {
        trigger_error("No esta permitida la clonación del objeto",E_USER_ERROR);
    }


}


?>