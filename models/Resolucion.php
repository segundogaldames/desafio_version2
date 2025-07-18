<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Resolucion extends Model
{
    protected $table = 'resoluciones';
    protected $fillable = [];

    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}