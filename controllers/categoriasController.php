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
        $this->getMessages();

        $this->_view->assign('title', 'Categorias');
        $this->_view->assign('asunto', 'Lista de Categorías');
        $this->_view->assign('mensaje', 'No hay categorías registradas');
        $this->_view->assign('categorias', Categoria::select('id','nombre')->get());

        $this->_view->render('index');
    }

    public function create()
    {
        $this->validateInAdmin();
    	$this->getMessages();

    	$this->_view->assign('title','Categorías');
    	$this->_view->assign('asunto','Nueva Categoría');
    	$this->_view->assign('categoria', Session::get('data'));
    	$this->_view->assign('process', 'categorias/store');
    	$this->_view->assign('send', $this->encrypt($this->getForm()));

    	$this->_view->render('create');
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
        $this->getMessages();

        $this->_view->assign('title', 'Categorias');
        $this->_view->assign('asunto', 'Detalle Categoría');
        $this->_view->assign('categoria', Categoria::find(Filter::filterInt($id))); //select * from roles where id = value;

        $this->_view->render('view');
    }

    public function edit($id = null)
    {
        $this->validateInAdmin();
        Validate::validateModel(Categoria::class, $id, 'error/error');
        $this->getMessages();

        $this->_view->assign('title','Categorias');
        $this->_view->assign('asunto','Editar Categoría');
        $this->_view->assign('categoria', Categoria::find(Filter::filterInt($id)));
        $this->_view->assign('process', "categorias/update/{$id}");
        $this->_view->assign('send', $this->encrypt($this->getForm()));

        $this->_view->render('edit');
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
