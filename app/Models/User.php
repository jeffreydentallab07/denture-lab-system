<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Appointment;

class User extends Authenticatable
{
    use HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'clinic_id', // Make sure this is in fillable if not already
        'role',
        'contact_number',
        'photo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Enum constants (for readability)
    public const ROLE_ADMIN = 'admin';
    public const ROLE_STAFF = 'staff';
    public const ROLE_TECHNICIAN = 'technician';
    public const ROLE_RIDER = 'rider';

    // Clinic relationship - assuming clinic_id foreign key exists in users table
    // In User.php model
    public function clinic()
    {
        // Assuming your Clinic model uses clinic_id as primary key
        return $this->belongsTo(Clinic::class, 'clinic_id', 'clinic_id');
    }
    // Quick role check
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
    public function caseOrders()
    {
        return $this->hasMany(CaseOrder::class, 'technician_id', 'id');
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'technician_id', 'id');
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'rider_id', 'id');
    }


    // Convenience methods
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }
    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }
    public function isTechnician(): bool
    {
        return $this->role === self::ROLE_TECHNICIAN;
    }
    public function isRider(): bool
    {
        return $this->role === self::ROLE_RIDER;
    }



    public function pickups()
    {
        return $this->hasMany(Pickup::class, 'rider_id', 'id');
    }
}
