<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'request_id',
        'user_id',
        'comment',
    ];

    public function procurementRequest()
    {
        return $this->belongsTo(ProcurementRequest::class, 'request_id');
    }

    public function user()
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }
}
