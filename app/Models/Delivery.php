<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $primaryKey = 'delivery_id';

    protected $fillable = [
        'appointment_id',
        'rider_id',
        'delivery_status',
        'delivery_date',
        'delivered_at',
        'notes',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'delivered_at' => 'datetime',
    ];

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id', 'id');
    }
}
