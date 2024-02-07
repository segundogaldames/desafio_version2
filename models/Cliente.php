<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $fillable = [];

    public function incidentes()
    {
        return $this->hasMany(Incidente::class);
    }
}
