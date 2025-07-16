<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'student_first_name',
        'student_last_name',
        'student_middle_initial',
        'student_gender',
        'student_program',
        'student_major',
        'student_unit',
        'student_email',
        'student_phone_number',
        'student_profile_image',
    ];

    public function parentInfo()
    {
        return $this->hasOne(ParentCredential::class);
    }
}
