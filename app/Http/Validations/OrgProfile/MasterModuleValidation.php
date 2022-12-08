<?php
namespace App\Http\Validations\OrgProfile;

use Illuminate\Validation\Rule;
use Validator;

class MasterModuleValidation 
{
    /**
     * Master module validate
     */
    public static function validate ($request, $id=0)
    { 
        $module_name    = $request->module_name;
        $component_id   = $request->component_id;
        $validator  = Validator::make($request->all(), [
            'module_name' => [
                'required',
                Rule::unique('master_modules')->where(function ($query) use($module_name, $component_id, $id) {
                    $query->where('module_name', $module_name)
                                 ->where('component_id', $component_id);

                    if ($id) {
                        $query =$query->where('id', '!=' ,$id);
                    }

                    return $query;
                }),
            ],
            'module_name_bn'  => 'required',
            'component_id'    => 'required',        
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