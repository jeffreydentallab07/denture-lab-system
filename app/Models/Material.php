<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $primaryKey = 'material_id';

    protected $fillable = [
        'material_name',
        'description',
        'quantity',
        'unit',
        'price',
        'supplier',
        'status',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    // Get total value
    public function getTotalValueAttribute()
    {
        return $this->quantity * $this->price;
    }

    // Get status badge color
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'available' => 'green',
            'low stock' => 'yellow',
            'out of stock' => 'red',
            default => 'gray',
        };
    }

    public function usages()
    {
        return $this->hasMany(MaterialUsage::class, 'material_id', 'material_id');
    }
}
