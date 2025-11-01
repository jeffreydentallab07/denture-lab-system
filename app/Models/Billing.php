<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;


    protected $fillable = [
        'appointment_id',
        'material_cost',
        'labor_cost',
        'delivery_fee',
        'discount',
        'tax',
        'total_amount',
        'payment_status',
        'payment_method',
        'additional_details',
        'additional_amount',
        'notes',
    ];

    protected $casts = [
        'material_cost' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'additional_amount' => 'decimal:2',
    ];

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }
}
