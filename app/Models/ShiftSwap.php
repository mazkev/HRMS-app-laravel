<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftSwap extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_id',
        'target_user_id',
        'swap_date',
        'requester_shift_id',
        'target_shift_id',
        'reason',
        'status',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'swap_date' => 'date',
        ];
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function requesterShift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'requester_shift_id');
    }

    public function targetShift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'target_shift_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
