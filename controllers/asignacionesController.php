<?php

use models\Asignacion;
use models\Incidente;
use models\Prioridad;
use models\Usuario;

class asignacionesController extends Controller
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
            'title' => 'Asignaciones',
            'asunto' => 'Lista de Asignaciones',
            'mensaje' => 'No hay asignaciones registradas',
            'asignaciones' => Asignacion::with('incidente','usuario','prioridad')->orderby('id','desc')->get()
        ];

        $this->_view->load('asignaciones/index', compact('options','msg_success','msg_error'));
    }

    public function create($incidente = null)
    {
        $this->validateInAdminSuper();
        Validate::validateModel(Incidente::class, $incidente, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Asignaciones',
            'asunto' => 'Nueva Asignación',
            'process' => "asignaciones/store/{$incidente}",
            'action' => 'create',
            'send' => $this->encrypt($this->getForm()),
            'tecnicos' => Usuario::select('id','nombre')->where('role_id',3)->where('activo',1)->orderBy('nombre')->get(),
            'prioridades' => Prioridad::select('id','nombre')->orderBy('nombre')->get(),
            'asignacion' => Session::get('data')
        ];

        $this->_view->load('asignaciones/create', compact('options','msg_success','msg_error'));
    }

    public function store($incidente = null)
    {
        $this->validateInAdminSuper();
        Validate::validateModel(Incidente::class, $incidente, 'error/error');
        $this->validateForm("asignaciones/create/{$incidente}",[
            'tecnico' => Filter::getText('tecnico'),
            'prioridad' => Filter::getText('prioridad')
        ]);

        #validar que no este registrado el mismo incidente en asignaciones
        $incidente_asignado = Asignacion::select('id')
            ->where('incidente_id', Filter::filterInt($incidente))->first();

        if ($incidente_asignado) {
            Session::set('msg_error','Este incidente ya ha sido asignado... intente con otro');
            $this->redirect('asignaciones/create/' . $incidente);
        }

        #registrar la asignacion
        $asignacion = new Asignacion;
        $asignacion->resuelto = 2; #este incidente no esta resuelto
        $asignacion->incidente_id = Filter::filterInt($incidente);
        $asignacion->usuario_id = Filter::getInt('tecnico');
        $asignacion->prioridad_id = Filter::getInt('prioridad');
        $asignacion->save();

        Session::destroy(Session::get('data'));
        Session::set('msg_success','La asignación se ha registrado correctamente');
        $this->redirect('incidentes/view/' . $incidente);
    }

    public function view($id = null)
    {
        $this->validateSession();
        Validate::validateModel(Asignacion::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Asignaciones',
            'asunto' => 'Detalle de Asignación',
            'asignacion' => Asignacion::with(['incidente','usuario','prioridad'])->find(Filter::filterInt($id))
        ];

        $this->_view->load('asignaciones/view', compact('options','msg_success','msg_error'));
    }

    public function edit($id = null)
    {
        $this->validateInAdminSuper();
        Validate::validateModel(Asignacion::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Asignaciones',
            'asunto' => 'Editar Asignación',
            'process' => "asignaciones/update/{$id}",
            'action' => 'edit',
            'send' => $this->encrypt($this->getForm()),
            'tecnicos' => Usuario::select('id','nombre')->where('role_id',3)->where('activo',1)->orderBy('nombre')->get(),
            'prioridades' => Prioridad::select('id','nombre')->orderBy('nombre')->get(),
            'asignacion' => Asignacion::with(['incidente','usuario','prioridad'])->find(Filter::filterInt($id))
        ];

        $this->_view->load('asignaciones/edit', compact('options','msg_success','msg_error'));
    }

    public function update($id = null)
    {
        $this->validateInAdminSuper();
        Validate::validateModel(Asignacion::class, $id, 'error/error');
        $this->validatePUT();
        $this->validateForm("asignaciones/edit/{$id}",[
            'tecnico' => Filter::getText('tecnico'),
            'prioridad' => Filter::getText('prioridad'),
            'resuelto' => Filter::getText('resuelto'),
        ]);

        $asignacion = Asignacion::find(Filter::filterInt($id));
        $asignacion->resuelto = Filter::getInt('resuelto');
        $asignacion->usuario_id = Filter::getInt('tecnico');
        $asignacion->prioridad_id = Filter::getInt('prioridad');
        $asignacion->save();

        Session::destroy(Session::get('data'));
        Session::set('msg_success','La asignación se ha modificado correctamente');
        $this->redirect('asignaciones/view/' . $id);
    }

    public function asignacionesTecnico()
    {
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Asignaciones',
            'asunto' => 'Lista de Asignaciones',
            'asignaciones' => Asignacion::with('incidente','prioridad')->where('usuario_id', Session::get('user_id'))->orderBy('created_at','desc')->get(),
            'mensaje' => 'No hay asignaciones disponibles'
        ];

        $this->_view->load('asignaciones/asignacionesTecnico', compact('options','msg_success','msg_error'));
    }
    
}