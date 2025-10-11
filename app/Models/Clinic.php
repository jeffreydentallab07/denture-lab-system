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
    ];

    protected $hidden = [
        'password',
        'remember_token',
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
}
