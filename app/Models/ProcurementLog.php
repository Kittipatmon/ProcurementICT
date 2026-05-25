<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementLog extends Model
{
    protected $table = 'procurement_logs';

    protected $fillable = [
        'request_id',
        'action',
        'user_id',
        'old_value',
        'new_value',
    ];

    protected function casts(): array
    {
        return [
            'old_value' => 'json',
            'new_value' => 'json',
        ];
    }

    public function procurementRequest()
    {
        return $this->belongsTo(ProcurementRequest::class, 'request_id');
    }

    public function user()
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }
}
