<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Incidente extends Model
{
    protected $table = 'incidentes';
    protected $fillable = [];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function asignacion()
    {
        return $this->hasOne(Asignacion::class);
    }
}