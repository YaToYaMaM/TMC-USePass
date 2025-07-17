<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'students_last_name',
        'students_first_name',
        'students_middle_initial',
        'students_gender',
        'students_id',
        'students_program',
        'students_major',
        'students_unit',
        'students_email',
        'students_phone_num',
        'students_profile_image',
    ];

    public function parentInfo()
    {
        return $this->hasOne(ParentCredential::class);
    }
}
