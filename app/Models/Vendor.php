<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendors';

    protected $fillable = [
        'vendor_name',
        'contact_name',
        'phone',
        'email',
        'tax_id',
        'address',
        'rating',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(ProcurementItem::class, 'vendor_id');
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'vendor_id');
    }

    public function licenses()
    {
        return $this->hasMany(License::class, 'vendor_id');
    }
}
