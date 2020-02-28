<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    protected $fillable = [
        'user_id', 'transaction_token', 'transaction_type', 'amount', 'balance_before', 'balance_after', 'charges'
    ];
}
