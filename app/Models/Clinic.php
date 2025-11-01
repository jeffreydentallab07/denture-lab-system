<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Clinic extends Authenticatable
{
    protected $primaryKey = 'clinic_id';
    protected $guard = 'clinic';

    protected $fillable = [
        'username',
        'clinic_name',
        'email',
        'password',
        'contact_number',
        'address',
        'profile_photo',
        'approval_status',
        'rejection_reason',
        'approved_at',
        'approved_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function caseOrders()
    {
        return $this->hasMany(CaseOrder::class, 'clinic_id', 'clinic_id');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class, 'clinic_id', 'clinic_id');
    }

    public function dentists()
    {
        return $this->hasMany(Dentist::class, 'clinic_id', 'clinic_id');
    }

    public function notifications()
    {
        return $this->hasMany(ClinicNotification::class, 'clinic_id', 'clinic_id');
    }

    // Check if clinic is approved
    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    // Check if clinic is pending
    public function isPending()
    {
        return $this->approval_status === 'pending';
    }

    // Check if clinic is rejected
    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }

    // Relationship with admin who approved
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
