<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormDataComment extends Model
{
   /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the form_data that owns the comment.
     */
    public function post()
    {
        return $this->belongsTo('App\FormData');
    }

    /**
     * Get the commentator for comment.
     */
    public function commentedBy()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
