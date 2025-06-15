<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Program
 * @package App\Models
 * @version May 6, 2025, 4:39 am UTC
 *
 * @property string $program_title
 * @property string $start_date
 * @property string $end_date
 * @property string $description
 */
class Program extends Model
{

    public $table = 'programs';
    



    public $fillable = [
        'program_title',
        'program_type',
        'start_date',
        'end_date',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'program_title' => 'string',
        'start_date' => 'date',
        'end_date' => 'date',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'program_title' => 'required',
        'start_date' => 'required',
        'end_date' => 'required'
    ];

    
}
