<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\SidebarMenus;
use App\Models\OrgProfile\{ MasterMenu, MasterComponent };

class AccessControlController extends Controller
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

    public function componentsOfSuperAdmin()
    {
        $componentIds = MasterMenu::select('component_id')->groupBy('component_id')->pluck('component_id')->all();
        $components = MasterComponent::whereIn('id', $componentIds)->orderBy('sorting_order')->get();

        return response([
            'success' => true,
            'data' => $components
        ]);
    }

    public function menusByRoleComponent($roleId, $componentId)
    {
        if ((int)$roleId === 1) {
            return response(SidebarMenus::getMenus($roleId, $componentId, null, true));
        }

        $baseUrl = config('app.base_url.auth_service');
        $assignedMenus = \App\Library\RestService::getData($baseUrl, "/role/role-menus/{$roleId}");
        $assignedMenus =json_decode($assignedMenus, true);
        
        if (empty($assignedMenus)) {
            return response([
                'success' => false,
                'data' => [
                    'components' => [],
                    'modules' => [],
                    'services' => [],
                    'menus' => []
                ]
            ]);
        }
        
        if (count($assignedMenus) <= 0) {
            return response([
                'success' => false,
                'data' => [
                    'components' => [],
                    'modules' => [],
                    'services' => [],
                    'menus' => []
                ]
            ]);
        }

        if (empty($assignedMenus)) {
            return response([
                'success' => false,
                'data' => [$role_id, 1],
                'message' => "No menu assigned to this role"
            ]);
        }

        $assignedMenuIds = [];

        foreach ($assignedMenus as $key => $value) {
            $assignedMenuIds[] = $value['master_menu_id'];
        }

        $result = SidebarMenus::getMenus($roleId, $componentId, $assignedMenuIds);

        if (!$result['success']) {
            return response($result);
        }
        
        return response([
            'success' => true,
            'data' => $result['data'],
            'message' => 'All assigned menu details and its related information fetched'
        ]);      
        
    }
}
