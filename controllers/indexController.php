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

		$this->_view->load('index/index', compact('title'));
	}
}