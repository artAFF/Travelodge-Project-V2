<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetExpense extends Model
{
    use HasFactory;

    protected $fillable = ['report_issue_id', 'expense_name', 'expense_amount'];

    public function reportIssue()
    {
        return $this->belongsTo(Travelodge::class, 'report_issue_id');
    }
}
