<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Chairman
 * @package App\Models
 * @version April 13, 2025, 8:26 am UTC
 *
 * @property string $name
 * @property string $address
 * @property string $signature
 * @property string $status
 */
class Chairman extends Model
{

    public $table = 'chairmans';
    



    public $fillable = [
        'name',
        'address',
        'signature',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'address' => 'string',
        'signature' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'address' => 'required',
        'signature' => 'required',
        'status' => 'required'
    ];

    
}
