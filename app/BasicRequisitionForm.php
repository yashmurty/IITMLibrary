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
        'numberofcopies', 'laravel_user_id', 'laravel_lac_id', 'lac_status',
        'librarian_status', 'remarks',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
