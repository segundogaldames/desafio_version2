<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    protected $table = 'asignaciones';
    protected $fillable = [];

    public function incidente()
    {
        return $this->belongsTo(Incidente::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function prioridad()
    {
        return $this->belongsTo(Prioridad::class);
    }
}
