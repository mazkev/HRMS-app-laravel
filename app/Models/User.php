<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nik',
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'shift_id',
        'position',
        'join_date',
        'salary',
        'ptkp_status',
        'npwp',
        'bpjs_kesehatan_no',
        'bpjs_ketenagakerjaan_no',
        'leave_quota',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'join_date' => 'date',
            'salary' => 'decimal:2',
            'leave_quota' => 'integer',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function overtimes(): HasMany
    {
        return $this->hasMany(Overtime::class);
    }

    public function reimbursements(): HasMany
    {
        return $this->hasMany(Reimbursement::class);
    }

    public function performanceReviews(): HasMany
    {
        return $this->hasMany(PerformanceReview::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(CompanyAsset::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(EmployeeLoan::class);
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(TrainingParticipant::class);
    }

    public function warningLetters(): HasMany
    {
        return $this->hasMany(WarningLetter::class);
    }

    public function resignations(): HasMany
    {
        return $this->hasMany(Resignation::class);
    }

    public function thrPayments(): HasMany
    {
        return $this->hasMany(ThrPayment::class);
    }

    public function businessTrips(): HasMany
    {
        return $this->hasMany(BusinessTrip::class);
    }

    public function kudosReceived(): HasMany
    {
        return $this->hasMany(PeerKudos::class, 'receiver_id');
    }

    public function kudosSent(): HasMany
    {
        return $this->hasMany(PeerKudos::class, 'sender_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin_hr';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }
}
