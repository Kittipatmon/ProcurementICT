<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequisition extends Model
{
    protected $table = 'purchase_requisitions';

    protected $fillable = [
        'request_id',
        'pr_no',
        'pr_date',
        'created_by',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'pr_date' => 'date',
        ];
    }

    public function procurementRequest()
    {
        return $this->belongsTo(ProcurementRequest::class, 'request_id');
    }

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }
}
