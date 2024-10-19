<?php

use models\Role;

class rolesController extends Controller
{
    public function __construct()
    {
        $this->validateInAdmin();
        parent::__construct();
    }

    public function index()
    {
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Roles',
            'asunto' => 'Lista de Roles',
            'mensaje' => 'No hay roles registrados'
        ];

        $roles = Role::select('id','nombre')->get();

        $this->_view->load('roles/index', compact('options','roles','msg_success','msg_error'));
    }

    public function create()
    {
    	list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Roles',
            'asunto' => 'Nuevo Rol',
            'process' => 'roles/store',
            'send' => $this->encrypt($this->getForm())
        ];

        $role = Session::get('data');

    	$this->_view->load('roles/create', compact('options','role','msg_success','msg_error'));
    }

    public function store()
    {
    	$this->validateForm("roles/create",[
    		'nombre' => Filter::getText('nombre')
    	]);

    	$role = Role::select('id')->where('nombre', Filter::getText('nombre'))->first();
    	//select id from roles where nombre = 'role';
    	if ($role) {
    		Session::set('msg_error','El rol ingresado ya existe... intente con otro');
    		$this->redirect('roles/create');
    	}

    	$role = new Role;
    	$role->nombre = Filter::getText('nombre');
    	$role->save();

    	Session::destroy('data');
    	Session::set('msg_success','El rol se ha registrado correctamente');
    	$this->redirect('roles');
    }

    public function view($id = null)
    {
        Validate::validateModel(Role::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Roles',
            'asunto' => 'Detalle Rol'
        ];

        $role = Role::find(Filter::filterInt($id));

    	$this->_view->load('roles/view', compact('options','role','msg_success','msg_error'));
    }

    public function edit($id = null)
    {
        Validate::validateModel(Role::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Roles',
            'asunto' => 'Editar Rol',
            'process' => "roles/update/{$id}",
            'send' => $this->encrypt($this->getForm())
        ];

        $role = Role::find(Filter::filterInt($id));

    	$this->_view->load('roles/create', compact('options','role','msg_success','msg_error'));
    }

    public function update($id = null)
    {
        Validate::validateModel(Role::class, $id, 'error/error');
        $this->validatePUT();

        $this->validateForm("roles/edit/{$id}",[
            'nombre' => Filter::getText('nombre')
        ]);

        $role = Role::find(Filter::filterInt($id));
        $role->nombre = Filter::getText('nombre');
        $role->save();

        Session::destroy('data');
        Session::set('msg_success','El rol se ha modificado correctamente');
        $this->redirect('roles/view/' . $id);
    }
}
