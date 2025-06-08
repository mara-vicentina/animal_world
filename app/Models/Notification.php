<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'animal_name',
        'client_name',
        'message',
        'last_appointment_date',
        'read',
    ];

    protected $casts = [
        'read' => 'boolean',
        'last_appointment_date' => 'date',
    ];
}

