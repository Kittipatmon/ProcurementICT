<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementApproval extends Model
{
    protected $table = 'procurement_approvals';

    protected $fillable = [
        'request_id',
        'approver_id',
        'approval_step',
        'status',
        'comment',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    public function procurementRequest()
    {
        return $this->belongsTo(ProcurementRequest::class, 'request_id');
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approver_id');
    }
}
