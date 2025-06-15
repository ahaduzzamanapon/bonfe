<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCompetenceModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'competence_id',
    ];
}
