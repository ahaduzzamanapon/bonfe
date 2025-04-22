<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class AssessmentVenue
 * @package App\Models
 * @version April 22, 2025, 4:07 am UTC
 *
 * @property string $venue_name
 * @property string $address
 */
class AssessmentVenue extends Model
{

    public $table = 'assessment_venues';
    



    public $fillable = [
        'venue_name',
        'address'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'venue_name' => 'string',
        'address' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'venue_name' => 'required',
        'address' => 'required'
    ];

    
}
