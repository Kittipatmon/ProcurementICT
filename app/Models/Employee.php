<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'employees';

    protected $connection = 'mysql_user';

    public $timestamps = false;

    protected $fillable = [
        'emp_code',
        'firstname',
        'lastname',
        'email',
        'username',
        'password',
        'dept_id',
        'status',
        'role',
        'procurement_role',
        'profile_pic',
        'signature',
        'resign_date',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'resign_date' => 'date',
        ];
    }

    // Accessor for procurement_role
    public function getProcurementRoleAttribute($value)
    {
        if (isset($this->attributes['role']) && $this->attributes['role'] === 'admin') {
            return 'admin';
        }
        return $value ?? 'user';
    }

    // Accessor for full name
    public function getNameAttribute(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    // Relations
    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    public function procurementRequests()
    {
        return $this->hasMany(ProcurementRequest::class, 'requester_id');
    }

    public function approvals()
    {
        return $this->hasMany(ProcurementApproval::class, 'approver_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function licenseAssignments()
    {
        return $this->hasMany(LicenseAssignment::class, 'user_id');
    }
}
