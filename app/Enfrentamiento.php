<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Enfrentamiento extends Model{

    protected $table = "enfrentamiento";

    protected $fillable = [
        'calendario_id',
        'inscripcion_equipo_visitante_id',
        'inscripcion_equipo_local_id',
        'lugar_id',
        'user_id'
    ];
}