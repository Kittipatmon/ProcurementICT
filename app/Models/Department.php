<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $connection = 'mysql_user';

    // Disable timestamps if the existing table does not have them
    public $timestamps = false;

    protected $fillable = [
        'name',
        'manager_id',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'dept_id');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class, 'department_id');
    }

    public function procurementRequests()
    {
        return $this->hasMany(ProcurementRequest::class, 'department_id');
    }
}
