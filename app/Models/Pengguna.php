<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model

{
    protected $table = 'pengguna';
    protected $fillable = ['username', 'email', 'password', 'api_key'];
}
