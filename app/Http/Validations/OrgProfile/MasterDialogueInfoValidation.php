<?php

namespace App\Http\Validations\OrgProfile;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class  MasterDialogueInfoValidation
{
   /**
   * MasterDialogueInfoValidation Validation
   */
   public static function validate($request, $id = 0)
   {

    $dialogue = $request->dialogue;
    $position    = $request->position;

    $validator = Validator::make($request->all(), [
        'dialogue_bn'  => 'required',
        'position'        => 'required',
    ]);

    if ($validator->fails()) {
        return [
            'success' => false,
            'errors'  => $validator->errors()
        ];
    }
    return ['success' => true];
   }


}




