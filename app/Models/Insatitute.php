<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Insatitute
 * @package App\Models
 * @version July 2, 2025, 4:02 am UTC
 *
 * @property string $insatitute_name
 * @property integer $district
 * @property string $address
 * @property string $status
 * @property string $description
 */
class Insatitute extends Model
{

    public $table = 'insatitutes';
    



    public $fillable = [
        'insatitute_name',
        'district',
        'address',
        'status',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'insatitute_name' => 'string',
        'district' => 'integer',
        'address' => 'string',
        'status' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
