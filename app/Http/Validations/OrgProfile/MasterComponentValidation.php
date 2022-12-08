<?php 
namespace App\Http\Validations\OrgProfile;

use Illuminate\Validation\Rule;
use Validator;

class MasterComponentValidation 
{
    /**
     * Master office validate
     */
    public static function validate ($request ,$id=0)
    { 
        $validator = Validator::make($request->all(), [
            'component_name'     =>'required|unique:master_components,component_name,'.$id,
            'org_id'       => 'required',
        ]);

        if ($validator->fails()) {
            return ([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        return ['success'=> 'true'];
    }
}