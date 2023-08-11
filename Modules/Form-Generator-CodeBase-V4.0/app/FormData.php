<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormData extends Model
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
        'data' => 'array',
    ];

    public static function sources()
    {
        return ['app'];
    }

    /**
     * Get the form for the form data
     */
    public function form()
    {
        return $this->belongsTo('App\Form');
    }

    /**
     * Get the submitted by for the form.
     */
    public function submittedBy()
    {
        return $this->belongsTo('App\User', 'submitted_by');
    }

    /**
     * Get the form's comment
     */
    public function comments()
    {
        return $this->hasMany('App\FormDataComment', 'form_data_id');
    }
}
