<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    use HasFactory;

    protected $table = "local";
    protected $fillable = [
        'ruc',
        'nombre',
        'user_updated',
        'ListaPrecio',
	'email',
	'password',
	'dfa'
    ];

}
