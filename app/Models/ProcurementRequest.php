<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementRequest extends Model
{
    protected $table = 'procurement_requests';

    protected $fillable = [
        'request_no',
        'requester_id',
        'department_id',
        'title',
        'description',
        'category',
        'priority',
        'estimated_budget',
        'approved_budget',
        'current_step',
        'status',
        'expected_date',
        'next_renewal_date',
        'completed_date',
    ];

    protected function casts(): array
    {
        return [
            'expected_date' => 'date',
            'next_renewal_date' => 'date',
            'completed_date' => 'date',
        ];
    }

    public function requester()
    {
        return $this->belongsTo(Employee::class, 'requester_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function items()
    {
        return $this->hasMany(ProcurementItem::class, 'request_id');
    }

    public function approvals()
    {
        return $this->hasMany(ProcurementApproval::class, 'request_id');
    }

    public function files()
    {
        return $this->hasMany(ProcurementFile::class, 'request_id');
    }

    public function logs()
    {
        return $this->hasMany(ProcurementLog::class, 'request_id');
    }

    public function purchaseRequisitions()
    {
        return $this->hasMany(PurchaseRequisition::class, 'request_id');
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'request_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'request_id');
    }

    public function budgetTransactions()
    {
        return $this->hasMany(BudgetTransaction::class, 'request_id');
    }

    public function categoryRelation()
    {
        return $this->belongsTo(Category::class, 'category', 'slug');
    }

    public function getStatusTextAttribute()
    {
        $map = [
            'draft'            => 'ร่างคำขอ',
            'submitted'        => 'รออนุมัติแผนก',
            'approved_manager' => 'รอ Manager ICT',
            'approved_ict'     => 'รอ CAO อนุมัติ',
            'approved_cao'     => 'รอออก PR/PO',
            'pr_created'       => 'ออก PR/PO แล้ว (รอ ICT)',
            'pr_approved_ict'  => 'PR/PO รอ CAO อนุมัติ',
            'pr_approved_cao'  => 'รอออก PO',
            'po_created'       => 'รอจัดส่งสินค้า',
            'delivered'        => 'รอส่งเอกสารบัญชี',
            'completed'        => 'เสร็จสิ้นสมบูรณ์',
            'rejected'         => 'ถูกปฏิเสธ',
        ];

        return $map[$this->status] ?? $this->status;
    }
}
