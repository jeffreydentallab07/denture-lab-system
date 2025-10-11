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

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'case_order_id', 'co_id');
    }

    public function pickup()
    {
        return $this->hasOne(Pickup::class, 'case_order_id', 'co_id');
    }
}
