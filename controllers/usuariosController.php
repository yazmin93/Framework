<?php
/**
*clase usurio controller
*clase que nos permite realizar acciones en el crud
*@author Yazmin Fausto <yazfauscor@gmail.com>
*/

class usuariosController extends AppController
{
	public function __construct(){
		parent::__construct();//inicialisamos el controlador padre
	}

	public function index(){
		$this->_view->titulo = "Pagina principal";
		$this->_view->usuarios = $this->db->find("usuarios", "all", NULL);
		$this->_view->renderizar("index");
	}
	/**
	*Metodo que nos permite agregar un usuario 
	*/
	public function add(){
		if ($_POST) {
			$pass = new Password();
			$_POST["password"] = $pass->getPassword($_POST["password"]);
			if ($this->db->save("usuarios", $_POST)) {
				$this->redirect(array("controller" =>"usuarios"));
			}else{
				$this->redirect(array("controller" => "usuarios", "action" => "add"));
			}
		}else{
			$this->_view->titulo = "Agregar usuarios";
		}
		$this->_view->renderizar("add");
	}
	/**
	*Metodo que nos permite editar un usuario 
	*/
	public function edit($id = NULL){
		if ($_POST) {

			if (!empty($_POST["pass"])) {
				$pass = new Password();
				$_POST["password"] = $pass->getPassword($_POST["pass"]);
			}

			if ($this->db->update("usuarios", $_POST)) {
					$this->redirect(array("controller" => "usuarios", "action" => "index"));
				}else{
					$this->redirect(array("controller" => "usuarios", "action" => "edit/".$_POST["id"]));
				}	
		}else{
			$this->_view->titulo = "Editar usuario";
			$this->_view->usuario = $this->db->find("usuarios", "first", array("conditions" => "id=".$id));
			$this->_view->renderizar("edit");
		}
	}
	/**
	*Metodo que nos permite eliminar un usuario 
	*/
	public function delete($id = NULL){
		$conditions = "id=".$id;
		if ($this->db->delete("usuarios", $conditions)) {
			$this->redirect(array("controller" => "usuarios", "action" => "index"));
		}
	}

	/**
	*metodo de los usuarios cuando inicien en la aplicacion
	*/
	public function login(){
		if ($_POST) {
			$pass = new Password();
			$filter = new Validations();//sanear lo que se reciba en el formaulario
			$auth = new Authorization();

			$username = $filter->sanitizeText($_POST["username"]);//sanea cajas 
			$password = $filter->sanitizeText($_POST["password"]);

			$options = array("conditions" => "username = '$username'");
			$usuario = $this->db->find("usuarios", "first", $options);

			if ($pass->isValid($password, $usuario["password"])) {
				$auth->login($usuario);
				$this->redirect(array("controller" => "tareas"));
			}else{
				echo "Usuario invalido";
			}
		}
		$this->_view->renderizar("login");
	}

	/**
	*Metodo para salir de las sesiones activas
	*/
	public function logout(){
		$auth = new Authorization();
		$auth->logout();
	}
}