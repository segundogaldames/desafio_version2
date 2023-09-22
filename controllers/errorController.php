<?php

class errorController extends Controller
{
	public function __construct(){
		parent::__construct();
	}

	public function error()
	{
		$options = [
			'title' => 'Página no Encontrada',
			'message' => 'Sitio No Encontrado'
		];
		
		$this->_view->load('error/error', compact('options'));
	}

	public function denied()
	{
		$options = [
			'title' => 'Inaccesible',
			'message' => 'Acceso no permitido'
		];
		
		$this->_view->load('error/denied', compact('options'));
	}
}