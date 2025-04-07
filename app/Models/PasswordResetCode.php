<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetCode extends Model
{
    use HasFactory;

    protected $table = 'vendedor_password_reset_codes';
    protected $fillable = ['email', 'code', 'created_at', 'expires_at'];
    
    public $timestamps = false;
}