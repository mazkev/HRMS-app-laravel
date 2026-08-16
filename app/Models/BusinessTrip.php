<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessTrip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sppd_number',
        'destination_city',
        'purpose',
        'start_date',
        'end_date',
        'total_days',
        'daily_allowance_rate',
        'total_allowance',
        'status',
        'approved_by',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'daily_allowance_rate' => 'decimal:2',
            'total_allowance' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
