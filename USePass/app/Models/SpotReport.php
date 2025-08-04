<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'findings', 'team_leader', 'guard_name', 'action_taken',
        'department_representative', 'location', 'time', 'date', 'is_printed'
    ];
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the user that owns the incident report.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
