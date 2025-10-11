<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialUsage extends Model
{
    use HasFactory;

    protected $primaryKey = 'usage_id';

    protected $fillable = [
        'appointment_id',
        'material_id',
        'quantity_used',
        'notes',
    ];

    protected $casts = [
        'quantity_used' => 'integer',
    ];

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id', 'material_id');
    }

    // Get total cost of this usage
    public function getTotalCostAttribute()
    {
        return $this->quantity_used * $this->material->price;
    }
}
