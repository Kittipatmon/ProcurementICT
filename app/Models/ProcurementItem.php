<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementItem extends Model
{
    protected $table = 'procurement_items';

    protected $fillable = [
        'request_id',
        'item_name',
        'specification',
        'quantity',
        'unit_price',
        'total_price',
        'vendor_id',
    ];

    public function procurementRequest()
    {
        return $this->belongsTo(ProcurementRequest::class, 'request_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
