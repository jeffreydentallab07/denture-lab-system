<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dentist extends Model
{
    use HasFactory;

    protected $primaryKey = 'dentist_id';

    protected $fillable = [
        'clinic_id',
        'name',
        'email',
        'contact_number',
        'address',
        'photo',
    ];

    // RELATIONSHIPS
    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'clinic_id');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class, 'dentist_id', 'dentist_id');
    }

    public function caseOrders()
    {
        return $this->hasMany(CaseOrder::class, 'dentist_id', 'dentist_id');
    }
}
