<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $table = 'budgets';

    protected $fillable = [
        'fiscal_year',
        'department_id',
        'allocated_budget',
        'used_budget',
        'remaining_budget',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function transactions()
    {
        return $this->hasMany(BudgetTransaction::class, 'budget_id');
    }
}
