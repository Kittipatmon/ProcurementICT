<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $table = 'licenses';

    protected $fillable = [
        'software_name',
        'license_key',
        'license_type',
        'total_license',
        'used_license',
        'purchase_date',
        'expire_date',
        'annual_cost',
        'vendor_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'expire_date' => 'date',
        ];
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function assignments()
    {
        return $this->hasMany(LicenseAssignment::class, 'license_id');
    }
}
