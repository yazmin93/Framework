<?php

/**
* 
*/
class ClassPDO
{
	 /**
	 * Archivo de clase de conexión PDO
	 *
	 * Clase que permite acciones CRUD usando PDO
	 *
	 * @package    PDO
	 */
	public $connection;
	private $dsn;
	private $drive;
	private $host;
	private $database;
	private $username;
	private $password;
	public $result;
	public $lastInsertId;
	public $numberRows;


	/**
  	* Constructor de la clase
  	* @return void
  	*/
	public function __construct($drive="mysql", $host="localhost", $database="gestion", $username="root", $password=""){
		$this->drive = $drive;
		$this->host = $host;
		$this->database = $database;
		$this->username = $username;
		$this->password = $password;
		$this->connection();
	}

	/**
	* Método de conexión a la base de datos.
  	*
  	* Método que permite establecer una conexión a la base de datos
 	*
  	* @return void
  	*/

	private function connection(){
		$this->dsn = $this->drive.":host=".$this->host.";dbname=".$this->database;

		try{
			$this->connection = new PDO(
                     $this->dsn,
                     $this->username,
                     $this->password
				);
			         $this->connection->setAttribute(
                     PDO::ATTR_ERRMODE,
                     PDO::ERRMODE_EXCEPTION
			    );
		}catch(PDOException $e){
			         echo "ERROR:".$e->getMessage();
			         die();
		}
	}


		/**
	  * Método find
	  *
	  * Método que sirve para hacer consultas a la base de datos
	  *
	  * @param string $table nombe de la tabla a consultar
	  * @param string $query tipo de consulta
	  *  - all
	  *  - first
	  *  - count
	  * @param array $options restriciones en la consulta
	  *  - fields
	  *  - conditions
	  *  - group
	  *  - order
	  *  - limit
	  * @return object
	  */
	public function find($table = null, $query = null, $options = array()){
		
		$fields = "*";

		$parameters = "";

		if (!empty($options["fields"])) {
			$fields = $options["fields"];
		}

		if (!empty($options["conditions"])) {
			$parameters = " WHERE ".$options["conditions"];
		}

		if (!empty($options["group"])) {
			$parameters = " GROUP BY ".$options["group"];
		}

		if (!empty($options["order"])) {
			$parameters = " ORDER BY ".$options["order"];
		}

		if (!empty($options["limit"])) {
			$parameters = " LIMIT ".$options["limit"];
		}

		switch ($query) {
			case 'all':
				$sql = "SELECT $fields FROM $table"." ".$parameters;
				$this->result = $this->connection->query($sql);
				break;

			case 'count':
				$sql = "SELECT COUNT(*) FROM $table"." ".$parameters;
				$result = $this->connection->query($sql);
				$this->result = $result->fetchColumn();
				break;
					
			case 'first':
				$sql = "SELECT $fields FROM $table"." ".$parameters;
				
				$result = $this->connection->query($sql);
				$this->result = $result->fetch();
				break;
			
			default:
				$sql = "SELECT $fields FROM $table"." ".$parameters;
				$this->result = $this->connection->query($sql);
				break;
		}
		return $this->result;
		
	}


	/**
	 * Metodo save 
	 * 
	 * Metodo que sirve para guardar registros
	 * 
	 * @param  $table tabla a consultar
	 * @param  $data valores a guardar
	 * @return object
	 */

	public function save($table = null, $data = array()){
		$sql = "SELECT * FROM $table";
		$result = $this->connection->query($sql);

		for ($i=0; $i < $result->columnCount(); $i++) { 
			$meta = $result->getColumnMeta($i);
			$fields[$meta["name"]] = null;
		}

		$fieldsToSave = "id";
		$valueToSave = "NULL";

		foreach ($data as $key => $value) {
			if (array_key_exists($key, $fields)) {
				$fieldsToSave .= ", ".$key;
				$valueToSave .= ", "."\"$value\"";
			}
		}

		$sql = "INSERT INTO $table ($fieldsToSave)VALUES($valueToSave);";
       
		$this->result = $this->connection->query($sql);

		return $this->result;
	}


	/**
	 * Metodo update 
	 * 
	 * Metodo que sirve para actualizar registros
	 * 
	 * @param  $table tabla a consultar
	 * @param  $data valores a actualizar
	 * @return object
	 */

	public function update($table = null, $data = array()){
		$sql = "SELECT * FROM $table";
		$result = $this->connection->query($sql);

		for ($i=0; $i < $result->columnCount(); $i++) { 
			$meta = $result->getColumnMeta($i);
			$fields[$meta["name"]] = null;
		}
		if (array_key_exists("id", $data)) {
			$fieldsToSave = "";
			$id = $data["id"];
			unset($data["id"]);

			$i = 0;
			foreach ($data as $key => $value) {
				if (array_key_exists($key, $fields)) {
					$fieldsToSave .=$key."="."\"$value\", ";
				}
				/*if ($i==0) {
					$fieldsToSave .= $key."="."\"$value\", ";
				}elseif ($i == count($data)-1) {
					$fieldsToSave .= $key."="."\"$value\"";
				}else{
					$fieldsToSave .= $key."="."\"$value\", ";
				}
				$i++;*/
			}
			$fieldsToSave = substr_replace($fieldsToSave, "", -2);
			$sql = "UPDATE $table SET $fieldsToSave WHERE $table.id=$id;";
			//echo $sql;
			//exit;
		}
		$this->result = $this->connection->query($sql);
		return $this->result;
	}


	/**
	 * Método delete 
	 * 
	 * Método que sirve para eliminar registros
	 * 
	 * @param  $table tabla a consultar
	 * @param  $condition condición a cumplir
	 * @return object
	 */
	public function delete($table = null, $condition = null){
		$sql = "DELETE FROM $table WHERE $condition".";";

		$this->result = $this->connection->query($sql);

		$this->numberRows = $this->result->rowCount();

		return $this->result;
	}
}






$db = new ClassPDO();

/*
class Database extends PDO{

	public function __construct(){
		parent::__construct(
			'mysql:host='.DB_HOST.';'.
			'dbname='.DB_NAME,
			DB_USER,
			DB_PASS,
			array(
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.DB_CHAR
			)
		);
	}
}
*/
?>
