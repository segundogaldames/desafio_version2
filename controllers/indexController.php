<?php

class indexController extends Controller
{

	public function __construct(){
		$this->validateSession();
		parent::__construct();
	}

	public function index()
	{
		$this->getMessages();
		$title = 'Página de Inicio';
		$process = 'clientes/clienteRut';
		$send = $this->encrypt($this->getForm());

		$this->_view->load('index/index', compact('title','process','send'));
	}
}