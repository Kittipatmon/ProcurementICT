<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseAssignment extends Model
{
    protected $table = 'license_assignments';

    protected $fillable = [
        'license_id',
        'user_id',
        'assigned_date',
        'returned_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'assigned_date' => 'date',
            'returned_date' => 'date',
        ];
    }

    public function license()
    {
        return $this->belongsTo(License::class, 'license_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }
}
