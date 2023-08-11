<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chatbots extends Model
{
    //
    protected $fillable = ['phone', 'message', 'type', 'busine_id'];
}
