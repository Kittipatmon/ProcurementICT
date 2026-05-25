<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementFile extends Model
{
    protected $table = 'procurement_files';

    protected $fillable = [
        'request_id',
        'file_name',
        'file_path',
        'file_type',
        'uploaded_by',
    ];

    public function procurementRequest()
    {
        return $this->belongsTo(ProcurementRequest::class, 'request_id');
    }

    public function uploader()
    {
        return $this->belongsTo(Employee::class, 'uploaded_by');
    }
}
