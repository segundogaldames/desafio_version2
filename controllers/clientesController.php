<?php
use models\Cliente;

class clientesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->validateInAdminSuper();
        $this->getMessages();

        $this->_view->assign('title', 'Clientes');
        $this->_view->assign('asunto', 'Lista de Clientes');
        $this->_view->assign('mensaje', 'No hay clientes registrados');
        $this->_view->assign('clientes', Cliente::select('id','rut','nombre','empresa')->get());

        $this->_view->render('index');
    }

    public function create()
    {
        $this->validateInAdmin();
    	$this->getMessages();

    	$this->_view->assign('title','Clientes');
    	$this->_view->assign('asunto','Nuevo Cliente');
    	$this->_view->assign('cliente', Session::get('data'));
        $this->_view->assign('action', 'create');
    	$this->_view->assign('process', 'clientes/store');
    	$this->_view->assign('send', $this->encrypt($this->getForm()));

    	$this->_view->render('create');
    }

    public function store()
    {
        $this->validateInAdmin();
    	$this->validateForm("clientes/create",[
            'rut' => $this->validateRut(Filter::getText('rut')),
    		'nombre' => Filter::getText('nombre'),
            'email' => $this->validateEmail(Filter::getPostParam('email')),
            'empresa' => Filter::getText('empresa')
    	]);

    	$cliente = Cliente::select('id')->where('rut', Filter::getText('rut'))->first();
    	//select id from roles where nombre = 'role';
    	if ($cliente) {
    		Session::set('msg_error','El cliente ingresado ya existe... intente con otro');
    		$this->redirect('clientes/create');
    	}

    	$cliente = new Cliente;
        $cliente->rut = Filter::getText('rut');
    	$cliente->nombre = Filter::getText('nombre');
        $cliente->email = Filter::getPostParam('email');
        $cliente->empresa = Filter::getInt('empresa');
    	$cliente->save();

    	Session::destroy('data');
    	Session::set('msg_success','El cliente se ha registrado correctamente');
    	$this->redirect('clientes');
    }

    public function view($id = null)
    {
        $this->validateInAdminSuper();
        Validate::validateModel(Cliente::class, $id, 'error/error');
        $this->getMessages();

        $this->_view->assign('title', 'Clientes');
        $this->_view->assign('asunto', 'Detalle Cliente');
        $this->_view->assign('cliente', Cliente::find(Filter::filterInt($id))); //select * from roles where id = value;

        $this->_view->render('view');
    }

    public function edit($id = null)
    {
        $this->validateInAdmin();
        Validate::validateModel(Cliente::class, $id, 'error/error');
        $this->getMessages();

        $this->_view->assign('title','CLientes');
        $this->_view->assign('asunto','Editar Cliente');
        $this->_view->assign('cliente', Cliente::find(Filter::filterInt($id)));
        $this->_view->assign('action', 'edit');
        $this->_view->assign('process', "clientes/update/{$id}");
        $this->_view->assign('send', $this->encrypt($this->getForm()));

        $this->_view->render('edit');
    }

    public function update($id = null)
    {
        $this->validateInAdmin();
        Validate::validateModel(Cliente::class, $id, 'error/error');
        $this->validatePUT();

        $this->validateForm("clientes/edit/{$id}",[
            'nombre' => Filter::getText('nombre'),
            'email' => $this->validateEmail(Filter::getPostParam('email')),
            'empresa' => Filter::getText('empresa')
        ]);

        $cliente = CLiente::find(Filter::filterInt($id));
        $cliente->nombre = Filter::getText('nombre');
        $cliente->email = Filter::getPostParam('email');
        $cliente->empresa = Filter::getInt('empresa');
        $cliente->save();

        Session::destroy('data');
        Session::set('msg_success','El cliente se ha modificado correctamente');
        $this->redirect('clientes/view/' . $id);
    }
}
