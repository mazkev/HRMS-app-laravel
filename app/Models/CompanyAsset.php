<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'asset_code',
        'name',
        'category',
        'serial_number',
        'purchase_date',
        'purchase_cost',
        'condition',
        'status',
        'assigned_date',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
