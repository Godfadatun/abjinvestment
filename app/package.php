<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class package extends Model
{
    protected $fillable = [
        'category_id', 'name', 'description', 'amount', 'fee', 'roi', 'duration'
    ];

    public function categories()
    {
        return $this->belongsTo('App\categories', 'category_id', 'id');
    }
}
