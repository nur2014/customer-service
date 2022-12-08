<?php

namespace app\Http\Validations\OrgProfile;

use Illuminate\Validation\Rule;
use Validator;

class  CustomerValidation 
{
    /**
     * division validation
     */
    public static function validate($request ,$id =0)
    {
        $validator = Validator::make($request->all(), [
            'customer_name'     => 'required|unique:customers,customer_name,'.$id,
            'address'  => 'required',
        ]);

        if ($validator->fails()) {
            return ([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        return ['success'=>true]; 
        
        

    }
}