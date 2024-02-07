<?php
use models\Incidente;
use models\Categoria;
use models\Cliente;
use models\Usuario;

class incidentesController extends Controller
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
            'title' => 'Incidentes',
            'asunto' => 'Lista de Incidentes',
            'mensaje' => 'No hay incidentes registrados'
        ];

        $incidentes = Incidente::with(['categoria','cliente','usuario'])->orderBy('created_at','desc')->get();

        $this->_view->load('incidentes/index', compact('options','incidentes','msg_success','msg_error'));
    }

    public function create()
    {
        $this->validateInAdminSuper();
    	list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Incidentes',
            'asunto' => 'Nuevo Incidente',
            'process' => 'incidentes/store',
            'send' => $this->encrypt($this->getForm())
        ];

        $incidente = Session::get('data');
        $categorias = Categoria::orderBy('nombre')->get();
        $clientes = Cliente::orderBy('nombre')->get();

    	$this->_view->load('incidentes/create', compact('options','incidente','msg_success','msg_error','categorias','clientes'));
    }

    public function store()
    {
        $this->validateInAdminSuper();
    	$this->validateForm("incidentes/create",[
    		'descripcion' => Filter::getText('descripcion'),
            'categoria' => Filter::getText('categoria'),
            'cliente' => Filter::getText('cliente')
    	]);

    	$incidente = new Incidente();
    	$incidente->descripcion = Filter::getText('descripcion');
        $incidente->categoria_id = Filter::getInt('categoria');
        $incidente->cliente_id = Filter::getInt('cliente');
        $incidente->usuario_id = Session::get('user_id');
    	$incidente->save();

    	Session::destroy('data');
    	Session::set('msg_success','El incidente se ha registrado correctamente');
    	$this->redirect('incidentes');
    }

    public function view($id = null)
    {
        $this->validateInAdminSuper();
        Validate::validateModel(Incidente::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Incidentes',
            'asunto' => 'Detalle Incidente'
        ];

        $incidente = Incidente::with(['categoria','cliente','usuario'])->find(Filter::filterInt($id));

        $this->_view->load('incidentes/view', compact('options','incidente','msg_success','msg_error'));
    }

    public function edit($id = null)
    {
        $this->validateInAdmin();
        Validate::validateModel(Incidente::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Incidentes',
            'asunto' => 'Editar Incidente',
            'process' => "incidentes/update/{$id}",
            'send' => $this->encrypt($this->getForm())
        ];

        $incidente = Incidente::with(['categoria','cliente','usuario'])->find(Filter::filterInt($id));
        $categorias = Categoria::orderBy('nombre')->get();
        $clientes = Cliente::orderBy('nombre')->get();

        $this->_view->load('categorias/edit', compact('options','incidente','msg_success','msg_error','categorias','clientes'));
    }

    public function update($id = null)
    {
        $this->validateInAdmin();
        Validate::validateModel(Incidente::class, $id, 'error/error');
        $this->validatePUT();

        $this->validateForm("incidentes/edit/{$id}",[
            'descripcion' => Filter::getText('descripcion'),
            'categoria' => Filter::getText('categoria'),
            'cliente' => Filter::getText('cliente')
        ]);

        $incidente = Incidente::find(Filter::filterInt($id));
        $incidente->descripcion = Filter::getText('descripcion');
        $incidente->categoria_id = Filter::getInt('categoria');
        $incidente->cliente_id = Filter::getInt('cliente');
    	$incidente->save();

        Session::destroy('data');
        Session::set('msg_success','El incidente se ha modificado correctamente');
        $this->redirect('incidentes/view/' . $id);
    }
}
