<?php

class TareaModel extends AppModel
{
	public function __construct(){
		parent::__construct();
	}

	public function getTareas(){
		$tareas = $this->_db->query("SELECT * FROM tareas");
		
		return $tareas->fetchall();
	}
}
