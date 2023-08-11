<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
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
        'smtp' => 'array',
    ];

    /**
    * Get the user that owns the setting.
    */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
