<?php
use models\Resolucion;
use models\Asignacion;
use models\Incidente;

class resolucionesController extends Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function create($asignacion = null)
    {
        Validate::validateModel(Asignacion::class, $asignacion, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Resoluciones',
            'asunto' => 'Nueva Resolución',
            'process' => "resoluciones/store/{$asignacion}",
            'resolucion' => Session::get('data'),
            'action' => 'create',
            'send' => $this->encrypt($this->getForm()),
        ];

        $this->_view->load('resoluciones/create', compact('options','msg_success','msg_error'));
    }

    public function store($asignacion = null)
    {
        Validate::validateModel(Asignacion::class, $asignacion, 'error/error');
        $this->validateForm("resoluciones/create/{$asignacion}",[
            'descripcion' => Filter::getText('descripcion'),
        ]);

        $resolucion = new Resolucion;
        $resolucion->descripcion = Filter::getText('descripcion');
        $resolucion->asignacion_id = Filter::filterInt($asignacion);
        $resolucion->usuario_id = Session::get('user_id');
        $resolucion->save();

        Session::destroy(Session::get('data'));
        Session::set('msg_success','La resolución se ha creado correctamente');
        $this->redirect('asignaciones/view/' . $asignacion);
    }
    
    public function resolucionesAsignacion($asignacion = null)
    {
        //print_r($asignacion);exit;
        Validate::validateModel(Asignacion::class, $asignacion, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Resoluciones',
            'asunto' => 'Lista de Resoluciones',
            'resoluciones' => Resolucion::with(['asignacion','usuario',])->where('asignacion_id', Filter::filterInt($asignacion))->get(),
            'mensaje' => 'No hay resoluciones asociadas',
            'asignacion' => Asignacion::find(Filter::filterInt($asignacion))
        ];

        $this->_view->load('resoluciones/resolucionesAsignacion', compact('options','msg_success','msg_error'));
    }

    public function resolucionesIncidente($incidente = null)
    {
        Validate::validateModel(Incidente::class, $incidente, 'error/error');

        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Resoluciones',
            'asunto' => 'Detalle de Resolucion',
            'resolucion' => Resolucion::with(['asignacion','usuario',])->find(Filter::filterInt($incidente))
        ];

        $this->_view->load('resoluciones/resolucionesIncidente', compact('options','msg_success','msg_error'));
    }
}