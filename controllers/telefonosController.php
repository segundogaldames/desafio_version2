<?php
use models\Telefono;
use models\Cliente;
use models\Usuario;

class telefonosController extends Controller
{
    public function __construct()
    {
        $this->validateInAdminSuper();
        parent::__construct();
    }

    public function create($id, $modelo){
        list($msg_success, $msg_error) = $this->getMessages();

        if ($modelo == 'Cliente') {
            Validate::validateModel(Cliente::class, $id, 'error/error');
            $propietario = Cliente::select('id','nombre')->find(Filter::filterInt($id));
        }else{
            Validate::validateModel(Usuario::class, $id, 'error/error');
            $propietario = Usuario::select('id','nombre')->find(Filter::filterInt($id));
        }

        $options = [
            'title' => 'Telefonos',
            'asunto' => 'Nuevo Teléfono',
            'action' => 'create',
            'process' => 'telefonos/store',
            'send' => $this->encrypt($this->getForm())
        ];

        $telefono = Session::get('data');

    	$this->_view->load('telefonos/create', compact('options','telefono','msg_success','msg_error', 'propietario','modelo'));
    }

    public function store($id, $modelo)
    {
        if ($modelo == 'Cliente') {
            Validate::validateModel(Cliente::class, $id, 'error/error');
            $ruta = 'clientes/view/' . $id;
        }else{
            Validate::validateModel(Usuario::class, $id, 'error/error');
            $ruta = 'usuarios/view/' . $id;
        }

        $this->validateForm("telefonos/create/{$id}/{$modelo}",[
            'numero' => Filter::getText('numero')
        ]);

        if (strlen(Filter::getInt('numero')) < 9) {
            Session::set('msg_error','El número telefónico debe contener al menos 9 dígitos');
            $this->redirect('telefonos/create/' . $id . '/' . $modelo);
        }

        $telefono = Telefono::select('id')->where('numero', Filter::getInt('numero'))->first();

        if ($telefono) {
            Session::set('msg_error','El número telefónico ingresado ya existe... intente con otro');
            $this->redirect('telefonos/create/' . $id . '/' . $modelo);
        }

        $telefono = new Telefono;
        $telefono->numero = Filter::getInt('numero');
        $telefono->telefonoable_id = Filter::filterInt($id);
        $telefono->telefonoable_type = $modelo;
        $telefono->save();

        Session::destroy('data');
        Session::set('msg_success','El teléfono se ha registrado correctamente');

        $this->redirect($ruta);
    }

    public function view($id = null)
    {
        Validate::validateModel(Telefono::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Telefonos',
            'asunto' => 'Detalle Teléfono',
            'process' => "telefonos/delete/{$id}",
            'send' => $this->encrypt($this->getForm())
        ];

        $telefono = Telefono::find(Filter::filterInt($id));

        if ($telefono->telefonoable_type == 'Cliente') {
            $propietario = Cliente::select('id','nombre')->find($telefono->telefonoable_id);
            $ruta = 'clientes/view/' . $propietario->id;
        }else {
            $propietario = Usuario::select('id','nombre')->find($telefono->telefonoable_id);
            $ruta = 'usuarios/view/' . $propietario->id;
        }

        $this->_view->load('telefonos/view', compact('options','telefono','msg_success','msg_error','propietario','ruta'));
    }

    public function edit($id = null)
    {
        Validate::validateModel(Telefono::class, $id, 'error/error');
        list($msg_success, $msg_error) = $this->getMessages();

        $options = [
            'title' => 'Telefonos',
            'asunto' => 'Editar Teléfono',
            'action' => 'edit',
            'process' => "telefonos/update/{$id}",
            'send' => $this->encrypt($this->getForm())
        ];

        $telefono = Telefono::find(Filter::filterInt($id));
        $modelo = $telefono->telefonoable_type;

        if ($telefono->telefonoable_type == 'Cliente') {
            $propietario = Cliente::select('id','nombre')->find($telefono->telefonoable_id);
        }else {
            $propietario = Usuario::select('id','nombre')->find($telefono->telefonoable_id);
        }

        $this->_view->load('telefonos/edit', compact('options','telefono','msg_success','msg_error','propietario','modelo'));
    }

    public function update($id = null)
    {
        Validate::validateModel(Telefono::class, $id, 'error/error');
        $this->validatePUT();

        $this->validateForm("telefonos/edit/{$id}",[
            'numero' => Filter::getText('numero')
        ]);

        if (strlen(Filter::getInt('numero')) < 9) {
            Session::set('msg_error','El número telefónico debe contener al menos 9 dígitos');
            $this->redirect('telefonos/edit/' . $id);
        }

        $telefono = Telefono::find(Filter::filterInt($id));
        $telefono->numero = Filter::getInt('numero');
        $telefono->save();

        Session::destroy('data');
        Session::set('msg_success','El teléfono se ha modificado correctamente');

        $this->redirect('telefonos/view/' . $id);
    }

    public function delete($id = null)
    {
        Validate::validateModel(Telefono::class, $id, 'error/error');

        $telefono = Telefono::find(Filter::filterInt($id));

        if ($telefono->telefonoable_type == 'Cliente') {
            $ruta = 'clientes/view/' . $telefono->telefonoable_id;
        }else {
            $ruta = 'usuarios/view/' . $telefono->telefonoable_id;
        }

        $telefono->delete();

        Session::set('msg_success','El teléfono se ha eliminado correctamente');
        $this->redirect($ruta);
    }
}
