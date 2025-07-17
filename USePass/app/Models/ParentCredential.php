<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ParentCredential extends Model
{
    protected $table = 'parents'; // explicitly define if needed

    protected $fillable = [
        'parent_last_name',
        'parent_first_name',
        'parent_middle_initial',
        'parent_phone_num',
        'parent_email',
        'parent_relation',
        'students_id',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
