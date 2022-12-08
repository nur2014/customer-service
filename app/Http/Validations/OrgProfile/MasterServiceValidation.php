<?php
namespace app\Http\Validations\OrgProfile;
use Illuminate\Validation\Rule;

use Validator;

class MasterServiceValidation
{
    /**
     * master service validator
    */
    public static function validate($request, $id=0)
    {
        $service_name   = $request->service_name;
        $component_id   = $request->component_id;
        $module_id      = $request->module_id;

        $validator = Validator::make($request->all(), [
        'service_name' => [
            'required',
            Rule::unique('master_services')->where(function ($query) use($service_name, $component_id , $module_id, $id) {
                $subQuery = $query->where('service_name', $service_name)
                            ->where('component_id', $component_id)
                            ->where('module_id', $module_id);
                if ($id) {
                    $subQuery = $subQuery->where('id','!=',$id);
                }

                return $subQuery;
            }),
        ],
        'service_name_bn'  => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }
        return ['success' => true];

    }
}
