<?php

namespace App\Models\orgProfile;

use Illuminate\Database\Eloquent\Model;

class InstallmentDetails extends Model
{
    protected $table ="cus_installment_details";

    protected $fillable = [
		'customer_main_id', 	
		'amount', 	
		'expire_date',
        'note',
		'created_at', 
		'updated_at'
    ];
}
