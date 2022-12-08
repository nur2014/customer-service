<?php

namespace App\Http\Controllers\OrgProfile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\OrgProfile\MasterMenu;
use Illuminate\Support\Facades\Cache;
use App\Models\OrgProfile\MasterModule;
use App\Models\OrgProfile\MasterService;
use App\Library\SidebarMenus;
use App\Models\Notification\NotificationSetting;
use App\Http\Validations\OrgProfile\MasterMenuValidation;
use Illuminate\Support\Facades\Log;

class MasterMenuController extends Controller
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

    /**
     * get all master office
     */
    public function changeSerialOrder(Request $request,$model)
    {
        $datas = $request->all();
        if(!empty($datas)){
            foreach($datas as $key=>$value){
                $modelName = 'App\Models\OrgProfile\\' .$model;
                $UpdateData=$modelName::where('id',$value['id'])->first();
                $UpdateData->sorting_order= $value['sorting_order'];
                $UpdateData->save();
            }
        }
        return response([
            'success' => true,
            'message' => 'Data save successfully',
            'data'    => $datas
        ]);
    }

    public function allMenus(Request $request)
    {
        $query = MasterModule::select(
                        'id',
                        'module_name',
                        'module_name_bn',
                        'component_id',
                    );
        if (!empty($request->component_id)) {
            $query = $query->where('component_id', $request->component_id);
        }

        if (!empty($request->module_id)) {
            $query = $query->where('id', $request->module_id);
        }
        $datas=$query->get();
        $all_menus =array();
        if(!empty($datas)){
            $sl =0;
            foreach ($datas as $key => $value) {
                $services= array();
                $master_muenus=MasterMenu::select([
                                'id',
                                'service_id',
                                'menu_name',
                                'menu_name_bn',
                                'component_id',
                                'module_id',
                                'service_id',
                                'url'
                            ])
                            ->where('module_id',$value['id'])
                            ->where('service_id',null)
                            ->get();
                if(count($master_muenus) > 0){
                    $data_new =array('id'=>date("Ymdhis").rand(10,100),'service_name'=>'','master_menus'=>$master_muenus);
                    $services[]=$data_new;
                }
                $servicelist=MasterService::select(['id','service_name','service_name_bn'])
                    ->with(['master_menus:id,service_id,menu_name,menu_name_bn,component_id,module_id,service_id,url'])
                    ->where('module_id',$value['id'])
                    ->get();
                foreach($servicelist as $key1=>$value1){
                    $services[]=$value1;
                }
                $datas[$key]['service']=$services;
                $all_menus[] = $datas[$key];
            }
        }
        return $all_menus;
    }

    public function index(Request $request)
    {
        $query = DB::table('master_menus')
                    ->join('master_components','master_menus.component_id','=','master_components.id')
                    ->join('master_modules','master_menus.module_id','=','master_modules.id')
                    ->leftJoin('master_services','master_menus.service_id','=','master_services.id')
                    ->select("master_menus.*",'master_components.component_name', 'master_components.component_name_bn',
                            'master_modules.module_name', 'master_modules.module_name_bn','master_services.service_name', 'master_services.service_name_bn'
                    );

        if ($request->menu_name) {
            $query = $query->where('menu_name', 'like', "{$request->menu_name}%")
                            ->orWhere('menu_name_bn', 'like', "{$request->menu_name}%");
        }

        if ($request->component_id) {
            $query = $query->where('master_menus.component_id', $request->component_id);
        }

        if ($request->module_id) {
            $query = $query->where('master_menus.module_id', $request->module_id);
        }

        if ($request->service_id) {
            $query = $query->where('master_menus.service_id', $request->service_id);
        }

        if ($request->status) {
            $query = $query->where('master_menus.status', $request->status);
        }

        $list = $query->orderBy('master_modules.sorting_order')
                        ->orderBy('master_menus.sorting_order')
                        ->paginate(request('per_page', config('app.per_page')));

        return response([
            'success' => true,
            'message' => 'Master menu list',
            'data' => $list
        ]);
    }
    
    /**
     * master menu store
     */    
    public function store(Request $request)
    {
        $validationResult = MasterMenuValidation::validate($request);

        if (!$validationResult['success']) {
            return response($validationResult);
        }

        try {
            $model = new MasterMenu();
            $model->menu_name      = $request->menu_name;
            $model->menu_name_bn   = $request->menu_name_bn;
            $model->url            = $request->url;
            $model->sorting_order  = $request->sorting_order;
            $model->component_id   = $request->component_id;
            $model->module_id      = $request->module_id;
            $model->service_id     = $request->service_id;
            $model->associated_urls = $request->associated_urls;
            $model->created_by     = (int)user_id();
            $model->updated_by     = (int)user_id();
            $model->save();

            Cache::forget('dropdown_common_config');

            save_log([
                'data_id' => $model->id,
                'table_name' => 'master_menus',
            ]);

        } catch (\Exception $ex) {
            return response([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : []
            ]);
        }

        return response([
            'success' => true,
            'message' => 'Data save successfully',
            'data'    => $model
        ]);
    }

    /**
     * master menu update
     */
    public function update(Request $request, $id)
    {
        $validationResult = MasterMenuValidation::validate($request, $id);

        if (!$validationResult['success']) {
            return response($validationResult);
        }

        $model = MasterMenu::find($id);

        if (!$model) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        try {
            $model->menu_name      = $request->menu_name;
            $model->menu_name_bn   = $request->menu_name_bn;
            $model->url            = $request->url;
            $model->sorting_order  = $request->sorting_order;
            $model->component_id   = $request->component_id;
            $model->module_id      = $request->module_id;
            $model->service_id     = $request->service_id;
            $model->associated_urls = $request->associated_urls;
            $model->updated_by     = (int)user_id();
            $model->update();

            Cache::forget('dropdown_common_config');

            save_log([
                'data_id' => $model->id,
                'table_name' => 'master_menus',
                'execution_type' => 1
            ]);

        } catch (\Exception $ex) {
            return response([
                'success' => false,
                'message' => 'Failed to update data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : []
            ]);
        }

        return response([
            'success' => true,
            'message' => 'Data update successfully',
            'data'    => $model
        ]);
    }

    /**
     * master menu status update
     */
    public function toggleStatus($id)
    {
        $model = MasterMenu::find($id);

        if (!$model) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        $model->status = $model->status ? 0 : 1;
        $model->update();

        save_log([
            'data_id' => $model->id,
            'table_name' => 'master_menus',
            'execution_type' => 2
        ]);

        return response([
            'success' => true,
            'message' => 'Data updated successfully',
            'data'    => $model
        ]);
    }

    public function urlToSetting(Request $request) {

        $menuResult = MasterMenu::select(['id', 'url'])->where('url', $request->url)->first();

        if (!$menuResult) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }


        $messageModel = NotificationSetting::where('menu_id', $menuResult['id'])->where('button_id', $request->button_id)->first();

        if (!$messageModel) {
            Log::info("Message Not found. menu: {$request->url}, button id: {$request->button_id}");
            return [
                'success' => false,
                'message' => 'Message not found.'
            ];
        }
        
        return response([
            'success' => true,
            'message' => 'Menu Setting found',
            'data'    => $messageModel
        ]);
    }

    public function urlToId(Request $request)
    {
        $model = MasterMenu::select(['id', 'url'])->where('url', $request->url)->first();

        if (!$model) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }
        
        return response([
            'success' => true,
            'message' => 'Menu Info found',
            'data'    => $model
        ]);
    }
    /**
     * master menu destroy
     */
    public function destroy($id)
    {
        $model = MasterMenu::find($id);

        if (!$model) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        $model->delete();

        Cache::forget('dropdown_common_config');

        save_log([
            'data_id' => $id,
            'table_name' => 'master_menus',
            'execution_type' => 2
        ]);

        return response([
            'success' => true,
            'message' => 'Data deleted successfully'
        ]);
    }
}
