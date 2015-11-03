<?php
//sirve para cargar clases en el vuelo
function __autoload($name){
	require_once ROOT."libs".DS.$name.".php";

}