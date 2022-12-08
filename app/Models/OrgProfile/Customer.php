<?php

namespace App\Models\OrgProfile;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customers";
    protected $fillable = [
        'customer_name', 'address'
    ];
}
