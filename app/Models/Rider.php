<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Rider extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true; // Changed to true

    protected $fillable = [
        'name',
        'email',
        'contact_number',
        'password',
        'role',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'rider_id', 'id');
    }

    public function scopeRiders($query)
    {
        return $query->where('role', 'rider');
    }
}
