<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Telefono extends Model
{
    protected $table = 'telefonos';
    protected $fillable = [];
}
