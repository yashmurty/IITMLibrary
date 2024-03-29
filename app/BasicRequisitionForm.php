<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BasicRequisitionForm extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'brfs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'doctype', 'author', 'title', 'publisher','agency',
        'isbn', 'volumne', 'price','sectioncatalogue',
        'numberofcopies', 'laravel_user_id', 'iitm_dept_code', 'iitm_id',
        'faculty', 'lac_status', 'librarian_status', 'remarks',
        'lac_status_date', 'librarian_status_date', 'download_status_date',
    ];

    protected $dates = ['lac_status_date', 'librarian_status_date', 'download_status_date'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
