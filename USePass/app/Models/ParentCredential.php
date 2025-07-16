<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ParentCredential extends Model
{
    protected $table = 'parents'; // explicitly define if needed

    public function students()
    {
        return $this->belongsTo(Student::class);
    }
}
