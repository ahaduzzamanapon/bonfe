<?php

namespace App\Models;

use Eloquent as Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;


/**
 * Class Student
 * @package App\Models
 * @version April 20, 2025, 11:24 am UTC
 *
 * @property string $occupation_id
 * @property string $registration_number
 * @property string $candidate_id
 * @property string $candidate_name
 * @property string $father_name
 * @property string $mother_name
 * @property string $nid
 * @property string $district_id
 * @property string $upajila_id
 * @property string $address
 * @property string $date_of_birth
 * @property integer $mobile_number
 * @property string $email
 * @property string $assessment_date
 * @property string $assessment_venue
 * @property string $assessment_center
 * @property string $assessment_center_registration_number
 */
class Student extends Model
{

    public $table = 'students';
    



    public $fillable = [
        'program_id',
        'occupation_id',
        'registration_number',
        'candidate_id',
        'candidate_name',
        'candidate_name_bn',
        'brn',
        'father_name',
        'mother_name',
        'nid',
        'district_id',
        'upajila_id',
        'address',
        'date_of_birth',
        'mobile_number',
        'email',
        'assessment_date',
        'assessment_venue',
        'assessment_center',
        'assessment_center_registration_number',
        'status',
        'exam_status',
        'exam_result_sheet',
        'chairmen_id',
        'chairmen_status',
        'districts_admin_id',
        'districts_admin_status',
        'notified',
        'image',
        'attachment',
        'age',
        'literacy_status',
        'educational_qualification',
        'training_start_date',
        'training_end_date',
        'gender',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'occupation_id' => 'string',
        'registration_number' => 'string',
        'candidate_id' => 'string',
        'candidate_name' => 'string',
        
        'father_name' => 'string',
        'mother_name' => 'string',
        'nid' => 'string',
        'district_id' => 'string',
        'upajila_id' => 'string',
        'address' => 'string',
        'date_of_birth' => 'date',
        'mobile_number' => 'integer',
        'email' => 'string',
        'assessment_date' => 'date',
        'assessment_venue' => 'string',
        'assessment_center' => 'string',
        'assessment_center_registration_number' => 'string'

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
      
    ];
}
