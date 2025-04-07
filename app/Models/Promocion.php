<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    use HasFactory;

    protected $table = "promociones";
    protected $fillable = [
        'id_local',
        'nombre',
        'descripcion',
        'valor',
        'fecha_ini',
        'fecha_fin',
        'url_imagen',
        'user_updated',
    ];
}
