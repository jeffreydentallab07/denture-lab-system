<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $primaryKey = 'appointment_id';

    protected $fillable = [
        'case_order_id',
        'technician_id',
        'schedule_datetime',
        'purpose',
        'work_status',
    ];

    protected $casts = [
        'schedule_datetime' => 'datetime',
    ];

    // Relationships
    public function caseOrder()
    {
        return $this->belongsTo(CaseOrder::class, 'case_order_id', 'co_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id', 'id');
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class, 'appointment_id', 'appointment_id');
    }
    public function billing()
    {
        return $this->hasOne(Billing::class, 'appointment_id', 'appointment_id');
    }

    public function materialUsages()
    {
        return $this->hasMany(MaterialUsage::class, 'appointment_id', 'appointment_id');
    }

    // Get total material cost for this appointment
    public function getTotalMaterialCostAttribute()
    {
        return $this->materialUsages->sum(function ($usage) {
            return $usage->quantity_used * $usage->material->price;
        });
    }
}
