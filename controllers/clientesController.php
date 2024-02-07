<?php
use models\Cliente;
use models\Telefono;

class clientesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->validateInAdminSuper();
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Clientes',
            'asunto' => 'Lista de Clientes',
            'mensaje' => 'No hay clientes registrados',
        ];

        $clientes = Cliente::select('id','rut','nombre','empresa')->get();

        $this->_view->load('clientes/index', compact('options','clientes','msg_success','msg_error'));
    }

    public function create()
    {
        $this->validateInAdminSuper();
    	list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Clientes',
            'asunto' => 'Nuevo Cliente',
            'action' => 'create',
            'process' => 'clientes/store',
            'send' => $this->encrypt($this->getForm())
        ];

        $cliente = Session::get('data');

    	$this->_view->load('clientes/create', compact('options','cliente','msg_success','msg_error'));
    }

    public function store()
    {
        $this->validateInAdminSuper();
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
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Clientes',
            'asunto' => 'Detalle Cliente',
            'modelo' => 'Cliente'
        ];

        $cliente = Cliente::find(Filter::filterInt($id));
        $telefonos = Telefono::select('id','numero')->where('telefonoable_id', Filter::filterInt($id))->where('telefonoable_type','Cliente')->get();

        $this->_view->load('clientes/view', compact('options','cliente','msg_success','msg_error','telefonos'));
    }
    
    public function edit($id = null)
    {
        $this->validateInAdmin();
        Validate::validateModel(Cliente::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Clientes',
            'asunto' => 'Editar Cliente',
            'action' => 'edit',
            'process' => "clientes/update/{$id}",
            'send' => $this->encrypt($this->getForm())
        ];

        $cliente = Cliente::find(Filter::filterInt($id));

        $this->_view->load('clientes/edit', compact('options','cliente','msg_success','msg_error'));
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

    //metodo post para encontrar cliente por rut
    public function clienteRut()
    {
        $this->validateInAdminSuper();
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Clientes',
            'asunto' => 'Detalle Cliente',
            'process' => "clientes/clienteRut",
            'send' => $this->encrypt($this->getForm())
        ];

    	$this->validateForm("index/index",[
            'rut' => $this->validateRut(Filter::getText('rut')),
    	]);
    	$cliente = Cliente::where('rut', Filter::getText('rut'))->first();
        
        $telefonos = Telefono::select('id','numero')->where('telefonoable_id', $cliente->id)->where('telefonoable_type','Cliente')->get();
        //print_r($telefonos);exit;
    	if (!$cliente) {
    		Session::set('msg_error','No hay cliente con este RUT');
    		$this->redirect('index');
    	}

        $this->_view->load('clientes/clienteRut', compact('cliente','options','msg_success','msg_error','telefonos'));
    }
}
