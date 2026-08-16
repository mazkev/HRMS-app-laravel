<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'period_month',
        'basic_salary',
        'allowances',
        'pph21_amount',
        'bpjs_kesehatan_deduction',
        'bpjs_tk_deduction',
        'loan_deduction',
        'late_deduction',
        'other_deductions',
        'net_salary',
        'total_present_days',
        'total_late_days',
        'status',
        'payment_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'basic_salary' => 'decimal:2',
            'allowances' => 'decimal:2',
            'pph21_amount' => 'decimal:2',
            'bpjs_kesehatan_deduction' => 'decimal:2',
            'bpjs_tk_deduction' => 'decimal:2',
            'loan_deduction' => 'decimal:2',
            'late_deduction' => 'decimal:2',
            'other_deductions' => 'decimal:2',
            'net_salary' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
