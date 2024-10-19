<?php
use models\Prioridad;

class prioridadesController extends Controller
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
            'title' => 'Prioridades',
            'asunto' => 'Lista de Prioridades',
            'mensaje' => 'No hay prioridades registradas',
            'prioridades' => Prioridad::select('id','nombre','dias_plazo')->get()
        ];

        $this->_view->load('prioridades/index', compact('options','msg_success','msg_error'));
    }

    public function create()
    {
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Prioridades',
            'asunto' => 'Nueva Prioridad',
            'prioridad' => Session::get('data'),
            'action' => 'create',
            'process' => 'prioridades/store',
            'send' => $this->encrypt($this->getForm())
        ];

        $this->_view->load('prioridades/create', compact('options','msg_success','msg_error'));
    }

    public function store()
    {
        $this->validateForm('prioridades/create',[
            'nombre' => Filter::getText('nombre'),
            'plazo' => Filter::getText('plazo')
        ]);

        $prioridad = Prioridad::select('id')->where('nombre', Filter::getText('nombre'))->first();

        if($prioridad){
            Session::set('msg_error','La prioridad ingresada ya existe... intente con otra');
            $this->redirect('prioridades/create');
        }

        $prioridad = new Prioridad;
        $prioridad->nombre = Filter::getText('nombre');
        $prioridad->dias_plazo = Filter::getInt('plazo');
        $prioridad->save();

        Session::destroy('data');
        Session::set('msg_success','La prioridad se ha registrado correctamente');
        $this->redirect('prioridades');
    }

    public function view($id = null)
    {
        Validate::validateModel(Prioridad::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Prioridades',
            'asunto' => 'Detalle Prioridad',
            'prioridad' => Prioridad::find(Filter::filterInt($id))
        ];

        $this->_view->load('prioridades/view', compact('options','msg_success','msg_error'));
    }

    public function edit($id = null)
    {
        Validate::validateModel(Prioridad::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Prioridades',
            'asunto' => 'Editar Prioridad',
            'prioridad' => Prioridad::find(Filter::filterInt($id)),
            'action' => 'edit',
            'process' => "prioridades/update/{$id}",
            'send' => $this->encrypt($this->getForm())
        ];

        $this->_view->load('prioridades/edit', compact('options','msg_success','msg_error'));
    }

    public function update($id = null)
    {
        Validate::validateModel(Prioridad::class, $id, 'error/error');
        $this->validatePUT();
        $this->validateForm("prioridades/edit/{$id}",[
            'nombre' => Filter::getText('nombre'),
            'plazo' => Filter::getText('plazo')
        ]);

        $prioridad = Prioridad::find(Filter::filterInt($id));
        $prioridad->nombre = Filter::getText('nombre');
        $prioridad->dias_plazo = Filter::getInt('plazo');
        $prioridad->save();

        Session::destroy('data');
        Session::set('msg_success','La prioridad se ha modificado correctamente');
        $this->redirect('prioridades/view/' . $id);
    }
}
