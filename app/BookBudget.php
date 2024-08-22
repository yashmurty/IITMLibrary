<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookBudget extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'book_budgets';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'iitm_dept_code', 'year_from_until', 'budget_allocated', 'budget_spent',
        'budget_on_order', 'budget_available'
    ];

    protected $dates = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
