<?php

namespace App\Http\Controllers\OrgProfile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\OrgProfile\MasterComponent;
use App\Models\OrgProfile\MasterOrgComponent;
use App\Http\Validations\OrgProfile\MasterComponentValidation;

class MasterComponentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    public function index(Request $request)
    {
        $query = MasterComponent::with(['org','module']);

        if ($request->component_name) {
            $query = $query->where('component_name', 'like', "{$request->component_name}%")
                            ->orwhere('component_name_bn', 'like', "{$request->component_name}%");
        }

        if ($request->description) {
            $query = $query->where('description', 'like', "{$request->description}%");
        }

        if ($request->description_bn) {
            $query = $query->where('description_bn', 'like', "{$request->description_bn}%");
        }

        if ($request->status) {
            $query = $query->where('status', $request->status);
        }
        
        $list = $query->orderBy('component_name', 'ASC')->paginate(request('per_page', config('app.per_page')));

        return response([
            'success' => true,
            'message' => 'Component list',
            'data' => $list
        ]);
    }

    /**
     * master compoent get org data list
     */
    public function orgWiseComponent(Request $request)
    {
        $query = DB::table('master_org_components')
                ->join('master_components','master_org_components.component_id', '=','master_components.id')
                ->select(
                    'master_org_components.id',
                    'master_org_components.org_id',
                    'master_org_components.component_id as value',
                    'master_org_components.status',
                    'master_components.component_name as text_en',
                    'master_components.component_name_bn as text_bn',
                );
        $query = $query->where('master_org_components.org_id', $request->org_id);
        $query = $query->where('master_org_components.status', 0);
        $master_components = $query->orderBy('master_components.component_name')->get();
        // $master_components[] = [
        //     'value' => -1,
        //     'text_en' => 'Auth Service',
        //     'text_bn' => 'আথ পরিষেবা',
        // ];
        return response([
            'success' => true,
            'message' => 'Org wise comp',
            'data' => $master_components
        ]);
        return $data;
    }
    public function getlist()
    {
        // $query=MasterComponent::with('org')->get();
        $query = DB::table('master_components')
                    ->join('master_org_components','master_components.id', '=','master_org_components.component_id')
                    ->join('master_org_profiless','master_org_components.org_id', '=','master_org_profiless.id')
                    ->select('master_components.*',
                                'master_org_components.*',
                                'master_org_profiless.id','master_org_profiless.org_name');
                                    
        

        $detailslist = $query->paginate($request->per_page);

        return response()->json([
            'success'=>true,
            'data'=>$detailslist
        ]);
        
    }

    /**
     * Component store
     */
    public function store(Request $request)
    {
        $validationResult = MasterComponentValidation::validate($request);

        if (!$validationResult['success']) {
            return response($validationResult);
        }

        DB::beginTransaction();

        try {

            $component = new MasterComponent();
            $component->component_name     = $request->component_name;
            $component->component_name_bn  = $request->component_name_bn;
            $component->description        = $request->description;
            $component->description_bn     = $request->description_bn;
            $component->created_by         = (int)user_id();
            $component->updated_by         = (int)user_id();
            $component->sorting_order  = $request->sorting_order;
            $component->save();

            Cache::forget('dropdown_common_config');

            save_log([
                'data_id'        => $component->id,
                'table_name'     => ' master_components',
            ]);
            // $orgs = explode(',', $request->org_id);
            $orgs = $request->org_id;
            for ($i = 0; $i < count($orgs); $i++) {
                $masterOrgComponent                = new MasterOrgComponent;
                $masterOrgComponent->org_id        = $orgs[$i];
                $masterOrgComponent->component_id  = $component->id;
                $masterOrgComponent->save();
            }

            DB::commit();

        } catch (\Exception $ex) {

            DB::rollback();

            return response([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : []
            ]);
        }

        return response([
            'success' => true,
            'message' => 'Component data save successfully',
            'data'    => $component
        ]);
    }

    /**
     * Component update
     */
    public function update(Request $request, $id)
    {
        $validationResult = MasterComponentValidation::validate($request, $id);

        if (!$validationResult['success']) {
            return response($validationResult);
        }

        $component = MasterComponent::find($id);

        if (!$component) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        DB::beginTransaction();

        try {
            $component->component_name     = $request->component_name;
            $component->component_name_bn  = $request->component_name_bn;
            $component->description        = $request->description;
            $component->description_bn     = $request->description_bn;
            $component->updated_by         = (int)user_id();
            $component->sorting_order  = $request->sorting_order;
            $component->save();

            Cache::forget('dropdown_common_config');

            save_log([
                'data_id'        => $component->id,
                'table_name'     => 'master_components',
                'execution_type' => 1
            ]);
            // $orgs = explode(',', $request->org_id);
            $orgs = $request->org_id;
            MasterOrgComponent::where('component_id', $id)->delete();
            for ($i = 0; $i < count($orgs); $i++) {
                $masterOrgComponent                = new MasterOrgComponent;
                $masterOrgComponent->org_id        = $orgs[$i];
                $masterOrgComponent->component_id  = $component->id;
                $masterOrgComponent->save();  
            }
            
            DB::commit();

        } catch (\Exception $ex) {

            DB::rollback();

            return response([
                'success' => false,
                'message' => 'Failed to update data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : []
            ]);
        }

        return response([
            'success' => true,
            'message' => 'Component Data update successfully',
            'data'    => $component
        ]);
    }

    /**
     * component status update
     */
    public function toggleStatus($id)
    {
        $component = MasterComponent::find($id);

        if (!$component) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        $component->status = $component->status ? 0 : 1;
        $component->save();

        save_log([
            'data_id'        => $component->id,
            'table_name'     => 'master_components',
            'execution_type' => 2
        ]);

        return response([
            'success' => true,
            'message' => 'Component Data updated successfully',
            'data'    => $component
        ]);
    }

    /**
     * Component destroy
     */
    public function destroy($id)
    {
        $component = MasterComponent::find($id);

        if (!$component) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        $component->delete();

        Cache::forget('dropdown_common_config');

        save_log([
            'data_id'        => $id,
            'table_name'     => 'master_components',
            'execution_type' => 2
        ]);

        return response([
            'success' => true,
            'message' => 'Component Data deleted successfully'
        ]);
    }

}
