<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserForm extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Get the user to which form is assgined
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'assigned_to');
    }

    /**
     * Get the form which is assigned
     */
    public function form()
    {
        return $this->belongsTo('App\Form', 'form_id');
    }
}
