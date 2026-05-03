<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateVersion extends Model
{
    protected $fillable = [
        'student_id', 'correction_id', 'version',
        'snapshot_data', 'certificate_path', 'issued_at', 'issued_by',
    ];

    protected $casts = [
        'snapshot_data' => 'array',
        'issued_at'     => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function correction()
    {
        return $this->belongsTo(CertificateCorrection::class, 'correction_id');
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
