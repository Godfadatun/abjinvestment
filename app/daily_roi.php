<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class daily_roi extends Model
{
    protected $fillable = [
        'user_id', 'investment_id', 'amount', 'amount', 'percent'
    ];

    public function investment()
    {
        return $this->belongsTo('App\investment', 'investment_id', 'id');
    }

    
}
