<?php

namespace App\Models\orgProfile;

use Illuminate\Database\Eloquent\Model;

class InstallmentMain extends Model
{
    protected $table ="cus_installment_mains";

    protected $fillable = [
		'customer_id',
		'status',
		'created_by',
		'updated_by',
		'created_at',
		'updated_at'
    ];

	public function insDetails() 
	{
        return $this->hasMany(InstallmentDetails::class, 'customer_main_id');
	}
}
