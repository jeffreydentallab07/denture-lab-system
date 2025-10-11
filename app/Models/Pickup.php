<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pickup extends Model
{
    use HasFactory;

    protected $primaryKey = 'pickup_id';

    protected $fillable = [
        'case_order_id',
        'rider_id',
        'pickup_date',
        'pickup_address',
        'status',
        'notes',
        'picked_up_at',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'picked_up_at' => 'datetime',
    ];

    // Relationships
    public function caseOrder()
    {
        return $this->belongsTo(CaseOrder::class, 'case_order_id', 'co_id');
    }

    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id', 'id');
    }

    // Check if completed
    public function isCompleted()
    {
        return $this->status === 'picked-up';
    }
}
