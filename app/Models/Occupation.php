<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Occupation
 * @package App\Models
 * @version April 21, 2025, 8:32 am UTC
 *
 * @property string $title
 * @property string $description
 */
class Occupation extends Model
{

    public $table = 'occupations';
    



    public $fillable = [
        'title',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required'
    ];

    
}
