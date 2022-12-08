<?php
namespace App\Library;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\OrgProfile\{
    MasterComponent,
    MasterModule,
    MasterService,
    MasterMenu
};

class SidebarMenus
{
    public static function getMenus($roleId, $componentId, $assignedMenuIds, $isSuperAdmin = false)
    {
        try {    
            $query = new MasterMenu();

            if (!$isSuperAdmin) {
                $query = $query->whereIn('id', $assignedMenuIds);
            }

            $allMenus = $query->select(
                        'id',
                        'menu_name',
                        'menu_name_bn',
                        'url',
                        'component_id',
                        'module_id',
                        'service_id',
                        'associated_urls',
                        'status'
                        )
                        ->where('component_id', $componentId)
                        ->where('status', 0)
                        ->orderBy('module_id', 'asc')
                        ->orderBy('service_id', 'asc')
                        ->orderBy('sorting_order', 'asc')
                        ->get();

            $moduleIds = [];
            $serviceIds = [];

            foreach ($allMenus as $menu) {
                $moduleIds[] = $menu->module_id;
                $serviceIds[] = $menu->service_id;
            }

            $modules = MasterModule::select(
                'id',
                'module_name',
                'module_name',
                'module_name_bn',
                'component_id',
            )->whereIn('id', $moduleIds)
            ->orderBy('sorting_order', 'asc')
            ->get();

        $services = MasterService::whereIn('id', $serviceIds)
                        ->select('id', 'service_name', 'service_name_bn', 'component_id', 'module_id', 'status')
                        ->orderBy('sorting_order', 'asc')
                        ->get();

        } catch (\Exception $ex) {
            return [
                'success' => false,
                'data' => [
                    'modules' => [],
                    'services' => [],
                    'menus' => []
                ],
                'message' => "Failed get menu due to server error." . exception_message()
            ];
        }

        // Caching menus for 24 hours with ComponentId and RoleId
        Cache::forget('authorizedMenus' . $roleId . $componentId);
        $value = Cache::remember('authorizedMenus' . $roleId . $componentId, 86400, function () use ($modules, $services, $allMenus){
            return [
                'modules' => $modules,
                'services' => $services,
                'menus' => $allMenus
            ];
        });
        return [
            'success' => true,
            'data' => [
                'modules' => $modules,
                'services' => $services,
                'menus' => $allMenus
            ]
        ];
    }

    public function getAssignedMenuIdsByRole()
    {
        
        
    }
}