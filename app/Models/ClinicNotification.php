<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicNotification extends Model
{
    use HasFactory;

    protected $table = 'clinic_notifications';

    protected $fillable = [
        'clinic_id',
        'type',
        'title',
        'message',
        'link',
        'reference_id',
        'read',
    ];

    protected $casts = [
        'read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'clinic_id');
    }

    public function markAsRead()
    {
        $this->update(['read' => 1]);
    }

    public function isRead()
    {
        return $this->read == 1;
    }

    public function isUnread()
    {
        return $this->read == 0;
    }
}
