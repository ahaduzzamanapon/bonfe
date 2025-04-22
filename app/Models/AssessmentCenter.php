<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class AssessmentCenter
 * @package App\Models
 * @version April 22, 2025, 4:10 am UTC
 *
 * @property string $center_name
 * @property string $registration_number
 * @property string $address
 */
class AssessmentCenter extends Model
{

    public $table = 'assessment_centers';
    



    public $fillable = [
        'center_name',
        'registration_number',
        'address'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'center_name' => 'string',
        'registration_number' => 'string',
        'address' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'center_name' => 'required',
        'address' => 'required'
    ];

    
}
