<?php

use models\Role;

class rolesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->getMessages();

        $this->_view->assign('title', 'Roles');
        $this->_view->assign('asunto', 'Lista de Roles');
        $this->_view->assign('mensaje', 'No hay roles registrados');
        $this->_view->assign('roles', Role::select('id','nombre')->get());

        $this->_view->render('index');
    }

    public function create()
    {
    	$this->getMessages();

    	$this->_view->assign('title','Roles');
    	$this->_view->assign('asunto','Nuevo Rol');
    	$this->_view->assign('role', Session::get('data'));
    	$this->_view->assign('process', 'roles/store');
    	$this->_view->assign('send', $this->encrypt($this->getForm()));

    	$this->_view->render('create');
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
        $this->getMessages();

        $this->_view->assign('title', 'Roles');
        $this->_view->assign('asunto', 'Detalle Rol');
        $this->_view->assign('role', Role::find(Filter::filterInt($id))); //select * from roles where id = value;

        $this->_view->render('view');
    }
}
