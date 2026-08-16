<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThrPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year',
        'holiday_name',
        'tenure_months',
        'basic_salary',
        'thr_amount',
        'payment_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'basic_salary' => 'decimal:2',
            'thr_amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
