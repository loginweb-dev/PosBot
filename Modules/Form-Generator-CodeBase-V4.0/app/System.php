<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function getVersion(){
        $row = System::where('key', 'version')
                ->first();

        if(!empty($row)){
            return $row->value;
        } else {
            false;
        }
    }

    public static function updateSystem($rows){
        foreach ($rows as $key => $value) {
            System::where('key', $key)
                ->update(['value' => $value]);
        }
    }

    /**
    * Returns the date formats
    */
    public static function dateFormats()
    {
        return [
            'd-m-Y' => 'dd-mm-yyyy',
            'm-d-Y' => 'mm-dd-yyyy',
            'd/m/Y' => 'dd/mm/yyyy',
            'm/d/Y' => 'mm/dd/yyyy'
        ];
    }

}
