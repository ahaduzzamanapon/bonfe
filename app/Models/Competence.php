<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Competence
 * @package App\Models
 * @version May 21, 2025, 4:34 am UTC
 *
 * @property string $title
 */
class Competence extends Model
{

    public $table = 'competences';
    



    public $fillable = [
        'title',
        'occupation_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
