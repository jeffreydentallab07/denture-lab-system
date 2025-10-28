<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseOrder extends Model
{
    use HasFactory;

    protected $primaryKey = 'co_id';

    protected $fillable = [
        'clinic_id',
        'dentist_id',
        'patient_id',
        'case_type',
        'status',
        'notes',
    ];

    // ✅ Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_FOR_APPOINTMENT = 'for appointment';
    const STATUS_IN_PROGRESS = 'in progress';
    const STATUS_UNDER_REVIEW = 'under review';
    const STATUS_ADJUSTMENT_REQUESTED = 'adjustment requested';
    const STATUS_REVISION_IN_PROGRESS = 'revision in progress';
    const STATUS_COMPLETED = 'completed';

    // ✅ Helper methods
    public function canBeReviewedByClinic()
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }

    public function needsNewAppointment()
    {
        return $this->status === self::STATUS_ADJUSTMENT_REQUESTED;
    }

    public function getSubmissionCountAttribute()
    {
        return $this->appointments()
            ->whereIn('work_status', ['completed', 'delivered'])
            ->count();
    }

    // RELATIONSHIPS

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'clinic_id');
    }

    public function dentist()
    {
        return $this->belongsTo(Dentist::class, 'dentist_id', 'dentist_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    public function pickup()
    {
        return $this->hasOne(Pickup::class, 'case_order_id', 'co_id');
    }

    public function latestDelivery()
    {
        // Get the latest appointment's delivery
        $latestAppointment = $this->appointments()
            ->whereHas('delivery')
            ->orderBy('appointment_id', 'desc')
            ->first();

        return $latestAppointment ? $latestAppointment->delivery() : null;
    }

    public function pickups()
    {
        return $this->hasMany(Pickup::class, 'case_order_id', 'co_id');
    }

    public function latestPickup()
    {
        return $this->hasOne(Pickup::class, 'case_order_id', 'co_id')
            ->orderBy('pickup_id', 'desc');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'case_order_id', 'co_id');
    }

    public function latestAppointment()
    {
        return $this->hasOne(Appointment::class, 'case_order_id', 'co_id')
            ->orderBy('appointment_id', 'desc');
    }
}
