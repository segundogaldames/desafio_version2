<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $fillable = ['nombre','email','activo','role_id'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
