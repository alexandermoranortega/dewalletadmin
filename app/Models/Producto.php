<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = "producto";
    protected $fillable = [
        'cod_producto',
        'nombre',
        'descripcion',
        'valor_premio',
        'marca',
        'nivel1',
        'NombreNivel1',
        'nivel2',
        'NombreNivel2',
        'nivel3',
        'NombreNivel3',
        'user_updated',
	'url_image',
        'vigencia',
	'valor_premio2',
        'valor_premio3'
    ];

    public $timestamps = false;
}
