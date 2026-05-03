<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reassessment extends Model
{
    const STATUS_PENDING            = 'pending';
    const STATUS_SCHEDULED          = 'scheduled';
    const STATUS_RESULT_ENTERED     = 'result_entered';
    const STATUS_WAITING_CONTROLLER = 'waiting_controller';
    const STATUS_WAITING_CHAIRMAN   = 'waiting_chairman';
    const STATUS_APPROVED           = 'approved';
    const STATUS_REJECTED           = 'rejected';

    protected $fillable = [
        'student_id', 'attempt_number', 'application_date', 'reason', 'status',
        'scheduled_date', 'scheduled_center_id', 'exam_status', 'exam_result_sheet',
        'controller_id', 'controller_approved_at', 'chairman_id', 'chairman_approved_at',
        'certificate_generated',
    ];

    protected $casts = [
        'application_date'      => 'date',
        'scheduled_date'        => 'date',
        'controller_approved_at'=> 'datetime',
        'chairman_approved_at'  => 'datetime',
        'certificate_generated' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function assessmentCenter()
    {
        return $this->belongsTo(AssessmentCenter::class, 'scheduled_center_id');
    }

    public function controller()
    {
        return $this->belongsTo(User::class, 'controller_id');
    }

    public function chairman()
    {
        return $this->belongsTo(User::class, 'chairman_id');
    }
}
