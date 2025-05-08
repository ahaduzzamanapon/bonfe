<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Upazila
 * @package App\Models
 * @version May 6, 2025, 5:25 am UTC
 *
 * @property integer $dis_id
 * @property string $name_en
 * @property string $name_bn
 */
class Upazila extends Model
{

    public $table = 'upazilas';
    



    public $fillable = [
        'dis_id',
        'name_en',
        'name_bn'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'dis_id' => 'integer',
        'name_en' => 'string',
        'name_bn' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'dis_id' => 'required',
        'name_en' => 'required'
    ];

    
}
