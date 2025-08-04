<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'type',
        'date',
        'what',
        'who',
        'where',
        'when',
        'how',
        'why',
        'recommendation',
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
