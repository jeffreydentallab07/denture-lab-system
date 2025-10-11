<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $primaryKey = 'patient_id';

    protected $fillable = [
        'clinic_id',
        'dentist_id',
        'name',
        'email',
        'contact_number',
        'address',
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

    public function caseOrders()
    {
        return $this->hasMany(CaseOrder::class, 'patient_id', 'patient_id');
    }
}
