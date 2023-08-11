<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use \App\Traits\UsesUuid;

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'schema' => 'array',
        'mailchimp_details' => 'array'
    ];

    /**
     * Get the data for the form.
     */
    public function data()
    {
        return $this->hasMany('App\FormData');
    }

    /**
     * Get the created by for the form.
     */
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    /**
     * Get the assigned user for form.
     */
    public function assignedTo()
    {
        return $this->hasMany('App\UserForm', 'form_id');
    }
}
