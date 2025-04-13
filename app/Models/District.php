<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class District
 * @package App\Models
 * @version April 13, 2025, 8:10 am UTC
 *
 * @property string $name_bn
 * @property string $name_en
 */
class District extends Model
{

    public $table = 'districts';
    



    public $fillable = [
        'name_bn',
        'name_en'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name_bn' => 'string',
        'name_en' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
