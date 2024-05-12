<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Prioridad extends Model
{
    protected $table = 'prioridades';
    protected $fillable = [];
}