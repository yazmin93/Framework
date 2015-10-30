<?php

class tareasController extends AppController
{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		//echo "Hola desde el metodo index";
		//$tareas = $this->loadmodel("tarea");
		
		$this->_view->titulo = "Pagina principal";
		$this->_view->tareas = $this->db->find("tareas", "all", NULL);
		$this->_view->renderizar("index");
		/*
		$this->_view->titulo = "Página principal";
		$this->_view->renderizar("index");
		*/
	}

	public function add(){
		if ($_POST) {
			if ($this->db->save("tareas", $_POST)) {
				$this->redirect(array ("controller" =>"tareas"));
			}else{
				$this->redirect(array ("controller" => "tareas", "action" => "add"));
			}
		}else{
			$this->_view->titulo = "Agregar tarea";
		}
		$this->_view->renderizar("add");
	}

	public function edit($id = NULL){
		if ($_POST) {
			if ($this->db->update("tareas", $_POST)) {
					$this->redirect(array("controller" => "tareas", "action" => "index"));
				}else{
					$this->redirect(array("controller" => "tareas", "action" => "edit/".$_POST["id"]));
				}	
		}else{
			$this->_view->titulo = "Editar tarea";
			$this->_view->tarea = $this->db->find("tareas", "first", array("conditions" => "id=".$id));
			$this->_view->renderizar("edit");
		}
	}

	public function delete($id = NULL){
		$conditions = "id=".$id;
		if ($this->db->delete("tareas", $conditions)) {
			$this->redirect(array("controller" => "tareas", "action" => "index"));
		}
	}
}
