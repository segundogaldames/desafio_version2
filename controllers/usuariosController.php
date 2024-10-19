<?php

use Illuminate\Support\Facades\File;
use models\Usuario;
use models\Role;
use models\Telefono;

class usuariosController extends Controller
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
            'title' => 'Usuarios',
            'asunto' => 'Lista de Usuarios',
            'mensaje' => 'No hay usuarios registrados'
        ];

        $usuarios = Usuario::with('role')->get();

        $this->_view->load('usuarios/index', compact('options','usuarios','msg_success','msg_error'));
    }

    public function create()
    {
        $this->validateInAdmin();
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Usuarios',
            'asunto' => 'Nuevo Usuario',
            'action' => 'create',
            'process' => 'usuarios/store',
            'send' => $this->encrypt($this->getForm())
        ];

        $usuario = Session::get('data');
        $roles = Role::select('id','nombre')->orderBy('nombre')->get();

    	$this->_view->load('usuarios/create', compact('options','usuario','roles','msg_success', 'msg_error'));
    }

    public function store()
    {
        $this->validateInAdmin();
        $this->validateForm("usuarios/create",[
    		'nombre' => Filter::getText('nombre'),
            'email' => $this->validateEmail(Filter::getPostParam('email')),
            'rol' => Filter::getText('rol'),
            'password' => Filter::getSql('password'),
    	]);

    	$usuario = Usuario::select('id')->where('email', Filter::getPostParam('email'))->first();
    	//select id from roles where nombre = 'role';
    	if ($usuario) {
    		Session::set('msg_error','El usuario ingresado ya existe... intente con otro');
    		$this->redirect('usuarios/create');
    	}

        if (strlen(Filter::getSql('password')) < 8) {
            Session::set('msg_error','El password debe contener al menos 8 caracteres');
    		$this->redirect('usuarios/create');
        }

        if (Filter::getSql('repassword') != Filter::getSql('password')) {
            Session::set('msg_error','Los passwords ingresados no coinciden');
    		$this->redirect('usuarios/create');
        }

    	$usuario = new Usuario;
    	$usuario->nombre = Filter::getText('nombre');
        $usuario->email = Filter::getPostParam('email');
        $usuario->password = Helper::encryptPassword(Filter::getSql('password'));
        $usuario->activo = 1; //activo
        $usuario->role_id = Filter::getInt('rol');
    	$usuario->save();

    	Session::destroy('data');
    	Session::set('msg_success','El usuario se ha registrado correctamente');
    	$this->redirect('usuarios');
    }

    public function view($id = null)
    {
        $this->validateInAdminSuper();
        Validate::validateModel(Usuario::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Usuarios',
            'asunto' => 'Detalle Usuario',
            'modelo' => 'Usuario'
        ];

        $usuario = Usuario::with('role')->find(Filter::filterInt($id));
        $telefonos = Telefono::select('id','numero')->where('telefonoable_id', Filter::filterInt($id))->where('telefonoable_type','Usuario')->get();

        $this->_view->load('usuarios/view', compact('options','usuario','msg_success','msg_error','telefonos'));
    }

    public function edit($id = null)
    {
        $this->validateInAdmin();
        Validate::validateModel(Usuario::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Usuarios',
            'asunto' => 'Editar Usuario',
            'action' => 'edit',
            'process' => "usuarios/update/{$id}",
            'send' => $this->encrypt($this->getForm())
        ];

        $usuario = Usuario::with('role')->find(Filter::filterInt($id));
        $roles = Role::select('id','nombre')->orderBy('nombre')->get();

        $this->_view->load('usuarios/edit', compact('options','usuario','roles','msg_success','msg_error'));
    }

    public function update($id = null)
    {
        $this->validateInAdmin();
        Validate::validateModel(Usuario::class, $id, 'error/error');
        $this->validatePUT();

        $this->validateForm("usuarios/edit/{$id}",[
    		'nombre' => Filter::getText('nombre'),
            'activo' => Filter::getText('activo'),
            'rol' => Filter::getText('rol')
    	]);

        $usuario = Usuario::find(Filter::filterInt($id));
        $usuario->nombre = Filter::getText('nombre');
        $usuario->activo = Filter::getInt('activo'); //activo
        $usuario->role_id = Filter::getInt('rol');
    	$usuario->save();

        Session::destroy('data');
        Session::set('msg_success','El usuario se ha modificado correctamente');
        $this->redirect('usuarios/view/' . $id);
    }
}
