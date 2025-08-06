<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'facultyStaff';
    protected $primaryKey = 'faculty_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'faculty_id',
        'faculty_first_name',
        'faculty_last_name',
        'faculty_middle_initial',
        'faculty_gender',
        'faculty_department',
        'faculty_unit',
        'faculty_email',
        'faculty_phone_num',
        'faculty_profile_image',
    ];
}
