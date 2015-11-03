<?php
/**
*clase tareas controller
*Este archivo nos permite realizar acciones en el crud de tareas
*@author Yazmin Fausto <yazfauscor@gmail.com>
*/
class tareasController extends AppController
{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
				
		$this->_view->titulo = "Pagina principal";
		$options=array("fields"=>"tareas.id, tareas.nombre, categorias.nombre AS categoria, fecha, prioridad, status", 
						"join"=>"categorias",
						"on"=> "categorias.id= categoria_id"
			);
		$this->_view->tareas = $this->db->find("tareas", "join", $options);
		$this->_view->renderizar("index");
		
	}
	/**
	*Metodo que nos permite agregar tareas 
	*/
	public function add(){
		$this->_view->categorias=$this->db->find("categorias", "all", null);
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
	/**
	*Metodo que nos permite editar las tareas
	*/
	public function edit($id = NULL){
		$this->_view->categorias=$this->db->find("categorias", "all", null);
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
	/**
	*Metodo que nos permite eliminar tareas
	*/
	public function delete($id = NULL){
		$conditions = "id=".$id;
		if ($this->db->delete("tareas", $conditions)) {
			$this->redirect(array("controller" => "tareas", "action" => "index"));
		}
	}
}
