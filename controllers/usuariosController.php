<?php
use models\Usuario;
use models\Role;

class usuariosController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->getMessages();

        $this->_view->assign('title', 'Usuarios');
        $this->_view->assign('asunto', 'Lista de Usuarios');
        $this->_view->assign('mensaje', 'No hay usuarios registrados');
        $this->_view->assign('usuarios', Usuario::with('role')->get());

        $this->_view->render('index');
    }

    public function create()
    {
        $this->getMessages();

    	$this->_view->assign('title','Usuarios');
    	$this->_view->assign('asunto','Nuevo Usuario');
    	$this->_view->assign('usuario', Session::get('data'));
        $this->_view->assign('roles', Role::select('id','nombre')->orderBy('nombre')->get());
        $this->_view->assign('action', 'Crear');
    	$this->_view->assign('process', 'usuarios/store');
    	$this->_view->assign('send', $this->encrypt($this->getForm()));

    	$this->_view->render('create');
    }

    public function store()
    {
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
        Validate::validateModel(Usuario::class, $id, 'error/error');
        $this->getMessages();

        $this->_view->assign('title', 'Usuarios');
        $this->_view->assign('asunto', 'Detalle Usuario');
        $this->_view->assign('usuario', Usuario::with('role')->find(Filter::filterInt($id)));

        $this->_view->render('view');
    }

    public function edit($id = null)
    {
        Validate::validateModel(Usuario::class, $id, 'error/error');
        $this->getMessages();

        $this->_view->assign('title','Usuarios');
        $this->_view->assign('asunto','Editar Usuario');
        $this->_view->assign('action', 'Editar');
        $this->_view->assign('usuario', Usuario::with('role')->find(Filter::filterInt($id)));
        $this->_view->assign('roles', Role::select('id','nombre')->orderBy('nombre')->get());
        $this->_view->assign('process', "usuarios/update/{$id}");
        $this->_view->assign('send', $this->encrypt($this->getForm()));

        $this->_view->render('edit');
    }

    public function update($id = null)
    {
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
