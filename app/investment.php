<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class investment extends Model
{
    protected $fillable = [
        'user_id', 'package_id', 'start_date', 'end_date', 'status', 'investment_token', 'roi'
    ];

    public function package()
    {
        return $this->belongsTo('App\packages', 'package_id', 'id');
    }
}
