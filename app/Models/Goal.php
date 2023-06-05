<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'goal_name',
        'goal_description',
        'days_completed',
        'current_date',
        'start_date',
        'end_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
