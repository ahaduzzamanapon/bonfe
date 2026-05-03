<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateCorrection extends Model
{
    const STATUS_PENDING             = 'pending';
    const STATUS_CONTROLLER_APPROVED = 'controller_approved';
    const STATUS_CHAIRMAN_APPROVED   = 'chairman_approved';
    const STATUS_REJECTED            = 'rejected';

    protected $fillable = [
        'student_id', 'application_date', 'correction_fields', 'reason',
        'supporting_documents', 'status', 'controller_id', 'controller_remarks',
        'controller_approved_at', 'chairman_id', 'chairman_remarks',
        'chairman_approved_at', 'corrected_certificate_path',
    ];

    protected $casts = [
        'application_date'      => 'date',
        'correction_fields'     => 'array',
        'supporting_documents'  => 'array',
        'controller_approved_at'=> 'datetime',
        'chairman_approved_at'  => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function versions()
    {
        return $this->hasMany(CertificateVersion::class, 'correction_id');
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
