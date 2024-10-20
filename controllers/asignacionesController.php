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
        Validate::validateModel(Incidente::class, $incidente, 'error/error');
        $this->validateForm("asignaciones/create/{$incidente}",[
            'tecnico' => Filter::getText('tecnico'),
            'prioridad' => Filter::getText('prioridad')
        ]);

        #validar que no este registrado el mismo incidente en asignaciones
        $incidente_asignado = Asignacion::select('id')
            ->where('incidente_id', Filter::filterInt($incidente))->first();

        if ($incidente) {
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
}