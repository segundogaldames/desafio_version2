<?php
use models\Categoria;

class categoriasController extends Controller
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
            'title' => 'Categorías',
            'asunto' => 'Lista de Categorías',
            'mensaje' => 'No hay categorías registradas'
        ];

        $categorias = Categoria::select('id','nombre')->get();

        $this->_view->load('categorias/index', compact('options','categorias','msg_success','msg_error'));
    }

    public function create()
    {
        $this->validateInAdmin();
    	list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Categorías',
            'asunto' => 'Nueva Categoría',
            'process' => 'categorias/store',
            'send' => $this->encrypt($this->getForm())
        ];

        $categoria = Session::get('data');

    	$this->_view->load('categorias/create', compact('options','categoria','msg_success','msg_error'));
    }

    public function store()
    {
        $this->validateInAdmin();
    	$this->validateForm("categorias/create",[
    		'nombre' => Filter::getText('nombre')
    	]);

    	$categoria = Categoria::select('id')->where('nombre', Filter::getText('nombre'))->first();
    	//select id from roles where nombre = 'role';
    	if ($categoria) {
    		Session::set('msg_error','La categoría ingresada ya existe... intente con otra');
    		$this->redirect('categorias/create');
    	}

    	$categoria = new Categoria;
    	$categoria->nombre = Filter::getText('nombre');
    	$categoria->save();

    	Session::destroy('data');
    	Session::set('msg_success','La categoría se ha registrado correctamente');
    	$this->redirect('categorias');
    }

    public function view($id = null)
    {
        $this->validateInAdminSuper();
        Validate::validateModel(Categoria::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Categorias',
            'asunto' => 'Detalle Categoría'
        ];

        $categoria = Categoria::find(Filter::filterInt($id));

        $this->_view->load('categorias/view', compact('options','categoria','msg_success','msg_error'));
    }

    public function edit($id = null)
    {
        $this->validateInAdmin();
        Validate::validateModel(Categoria::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Categorías',
            'asunto' => 'Editar Categoría',
            'process' => "categorias/update/{$id}",
            'send' => $this->encrypt($this->getForm())
        ];

        $categoria = Categoria::find(Filter::filterInt($id));

        $this->_view->load('categorias/edit', compact('options','categoria','msg_success','msg_error'));
    }

    public function update($id = null)
    {
        $this->validateInAdmin();
        Validate::validateModel(Categoria::class, $id, 'error/error');
        $this->validatePUT();

        $this->validateForm("categorias/edit/{$id}",[
            'nombre' => Filter::getText('nombre')
        ]);

        $categoria = Categoria::find(Filter::filterInt($id));
        $categoria->nombre = Filter::getText('nombre');
        $categoria->save();

        Session::destroy('data');
        Session::set('msg_success','La categoría se ha modificado correctamente');
        $this->redirect('categorias/view/' . $id);
    }
}
