<?php

/*
class AppModel
{
	protected $_db;

	public function __construct(){
		$this->_db = new Database();
	}
}
?>
<?php

class View
{
	private $_controlador;

	public function __construct(Request $peticion){
		$this->_controlador = $peticion->getControlador();
	}

	public function renderizar($vista, $item = false){
		$_layoutParams = array(
			"ruta_css"=>BASE_URL."views/layouts/".DEFAULT_LAYOUT."/css/",
			"ruta_img"=>BASE_URL."views/layouts/".DEFAULT_LAYOUT."/img/",
			"ruta_js"=>BASE_URL."views/layouts/".DEFAULT_LAYOUT."/js/"
		);

		$rutaVista = ROOT."views".DS.$this->_controlador.DS.$vista.".phtml";

		if (is_readable($rutaVista)) {
			include_once(ROOT."views".DS."layouts".DS.DEFAULT_LAYOUT.DS."header.php");
			include_once($rutaVista);
			include_once(ROOT."views".DS."layouts".DS.DEFAULT_LAYOUT.DS."footer.php");
		}else{
			throw new Exception("Vista no encontrada");
		}
	}
}

*/