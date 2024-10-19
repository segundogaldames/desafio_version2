<?php
use models\Usuario;

class loginController extends Controller
{
    public function __construct()
    {
        parent::__construct(); 
           
    }

    public function login()
    {
        list($msg_success, $msg_error) = $this->getMessages();

        $title = 'Login';
        $asunto = 'Acceso';
        $process = 'login/store';
    	$send = $this->encrypt($this->getForm());

    	$this->_view->load('login/login', compact('title','asunto','process','send','msg_success','msg_error'));
    }

    public function store()
    {
        $this->validateForm("login/login",[
            'email' => $this->validateEmail(Filter::getPostParam('email')),
            'password' => Filter::getSql('password')
        ]);

        #validamos que el usuario exista en la tabla usuarios
        $usuario = Usuario::with('role')
            ->where('email', Filter::getPostParam('email'))
            ->where('password', Helper::encryptPassword(Filter::getSql('password')))
            ->where('activo', 1)
            ->first();

        if (!$usuario) {
            Session::set('msg_error','El email o la password no están registrados');
            $this->redirect('login/login');
        }

        Session::set('authenticate', true);
        Session::set('user_id', $usuario->id);
        Session::set('user_name', $usuario->nombre);
        Session::set('user_email', $usuario->email);
        Session::set('user_role', $usuario->role->nombre);
        Session::set('time', time());

        Session::set('msg_success', 'Bienvenid@ ' . $usuario->nombre);
        $this->redirect();
    }

    public function logout()
    {
        Session::destroy();
        $this->redirect('login/login');
    }
}
