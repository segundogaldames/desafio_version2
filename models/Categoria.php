<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $fillable = [];

    public function incidentes()
    {
        return $this->hasMany(Incidente::class);
    }
}
