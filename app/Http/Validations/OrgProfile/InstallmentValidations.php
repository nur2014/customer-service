<?php
namespace app\Http\Validations\OrgProfile;

use Illuminate\Validation\Rule;
use Validator;

class InstallmentValidations
{
    /**
     * complain approve validate
     */
    public static function validate ($request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id'     => 'required'
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
