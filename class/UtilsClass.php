<?php  

include 'ConexionDb.php';

class UtilsClass{

	public function __construct()
    {
        $this->db = new ConexionDb();
    }

    public function store(){
    	try{
            /**
             * Create the sql sentence
             */
            $sql = "
            INSERT INTO table(row)
            VALUES (:row);
            ";

            /**
             * Process the sql query
             */
            $this->db->query($sql);

            /**
             * Bind the values to store in the db
             */
            $this->db->bind('row',$this->row);

            /**
             * Execute the prepare sql sentence and save in the db
             */

            if($this->db->execute())
                return true;

        }
        catch(PDOException $e)
        {
            echo "Error en ".$e->getMessage();
        }
    }

    public function get(){
    	try{
            $sql = "";
            $this->db->query($sql);
            //$this->db->bind('row',$this->row);
            $result = $this->db->resultset();
            return $result;
        }
        catch(PDOException $e){
            echo "Erro en ".$e->getMessage();
        }
    }
}


?>