<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Lead extends Model
{
    protected $table = 'leads';
    protected $fillable = ['phone', 'message', 'categoria', 'session'];
}
