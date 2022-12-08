<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrgProfile\MasterBank;
use App\Models\OrgProfile\MasterMenu;
use App\Models\OrgProfile\Mastergrade;
use App\Models\OrgProfile\MasterUnion;
use App\Models\OrgProfile\MasterBranch;
use App\Models\OrgProfile\MasterModule;
use App\Models\OrgProfile\MasterOffice;
use App\Models\OrgProfile\MasterCountry;
use App\Models\OrgProfile\MasterService;
use App\Models\OrgProfile\MasterComponent;
use App\Models\OrgProfile\MasterOfficeType;
use App\Models\Organogram\AssignDesignation;
use App\Models\Organogram\MasterDesignation;
use App\Models\Notification\MasterNotification;

class CommonDropdownController extends Controller
{
    /**
     * get all country
     */
    public function countryList() {
        $query = MasterCountry::select("id",'country_name','country_name_bn')
                                ->where('status', 0);


        $list = $query->get();

        return response([
            'success' => true,
            'message' => 'test',
            'data' => $list
        ]);
    }

    /**
     * get all uninon
     */
    public function unionList(Request $request) {
        $query = MasterUnion::select("id",'union_name','union_name_bn')
                                ->where('status', 0);

        if ($request->upazilla_id) {
            $query = $query->where('upazilla_id', $request->upazilla_id);
        }

        $list = $query->get();

        return response([
            'success' => true,
            'message' => 'test',
            'data' => $list
        ]);
    }

    /**
     * get all office type
     */
    public function officeTypeList(Request $request) {
        $list = MasterOfficeType::select("id",'office_type_name','office_type_name_bn')
                                ->where('status', 0)
                                ->get();

        return response([
            'success' => true,
            'data' => $list
        ]);
    }

    /**
     * get all Organization
     */
    public function orgAndOrgComponentList(Request $request) {
        $orgList = DB::table('master_org_profiless')
                ->select('id as value', 'org_name as text_en', 'org_name_bn as text_bn', 'abbreviation', 'abbreviation_bn', 'logo', 'status')
                ->orderBy('org_name', 'asc')
                ->get();

        $orgComponentList = DB::table('master_org_components')->select('org_id', 'component_id')->get();

        return response([
            'success' => true,
            'data' => ['orgList' => $orgList, 'orgComponentList' => $orgComponentList]
        ]);
    }

    /**
     * get all Office
     */
    public function officeList() {
        $list = MasterOffice::select("id",'office_name','office_name_bn','org_id','office_type_id')
                                ->where('status', 0)->get();

        return response([
            'success' => true,
            'message' => 'test',
            'data' => $list
        ]);
    }

    /**
     * get all component
     */
    public function componentList(Request $request) {
        $list = DB::table('master_components')
                        ->select('component_name','component_name_bn','id', 'status')
                        ->orderBy('sorting_order')
                        ->get();

        return response([
            'success' => true,
            'data'    => $list
        ]);
    }

    /**
     * get all module
     */
    public function moduleList(Request $request) {

        $query = DB::table('master_modules')
                    ->join('master_components','master_modules.component_id','=','master_components.id')
                    ->select('master_modules.*','master_components.component_name','master_components.component_name_bn', 'status');

        if ($request->component_id) {
            $query = $query->where('component_id', $request->component_id);
        }



        $list = $query->get();

        return response([
            'success' => true,
            'message' => 'test',
            'data' => $list
        ]);
    }

    /**
     * get all service
     */
    public function serviceList(Request $request) {
        $query = DB::table('master_services')
                 ->join('master_components','master_services.component_id','=','master_components.id')
                 ->join('master_modules','master_services.module_id','=','master_modules.id')
                 ->select('master_services.*','master_components.component_name','master_components.component_name_bn',
                                             'master_modules.module_name','master_modules.module_name_bn', 'status');

        if ($request->component_id) {
            $query = $query->where('component_id', $request->component_id);
        }

        if ($request->module_id) {
            $query = $query->where('module_id', $request->module_id);
        }

        $list = $query->get();

        return response([
            'success' => true,
            'message' => 'test',
            'data' => $list
        ]);
    }

    /**
     * get all menu
     */
    public function menuList(Request $request) {
        $query = DB::table('master_menus')
                 ->join('master_components','master_menus.component_id','=','master_components.id')
                 ->join('master_modules','master_menus.module_id','=','master_modules.id')
                 ->join('master_services','master_menus.service_id','=','master_services.id')
                 ->select('master_menus.*','master_components.component_name','master_components.component_name_bn',
                                              'master_modules.module_name','master_modules.module_name_bn',
                                              'master_services.id as service_id','master_services.service_name','master_services.service_name_bn');

        if ($request->component_id) {
            $query = $query->where('component_id', $request->component_id);
        }

        if ($request->module_id) {
            $query = $query->where('module_id', $request->module_id);
        }

        if ($request->service_id) {
            $query = $query->where('service_id', $request->service_id);
        }



        $list = $query->get();

        return response([
            'success' => true,
            'message' => 'test',
            'data' => $list
        ]);
    }

    /**
     * get all designation
     */
    public function designationList(Request $request) {
        $list = MasterDesignation::select("id",'designation','designation_bn','org_id')
                                ->where('status', 0)
                                ->get();
        return response([
            'success' => true,
            'message' => 'test',
            'data' => $list
        ]);
    }

    /**
     * get all assign designation
     */
    public function assignDesignationList(Request $request) {
        $query = DB::table('assign_designations')
                    ->join('master_designations','assign_designations.designation_id','=','master_designations.id')
                    ->select('master_designations.id','master_designations.designation','master_designations.designation_bn');

        if ($request->org_id) {
            $query = $query->where('org_id', $request->org_id);
        }

        if ($request->office_type_id) {
            $query = $query->where('office_type_id', $request->office_type_id);
        }

        if ($request->office_id) {
            $query = $query->where('office_id', $request->office_id);
        }



        $list = $query->get();

        return response([
            'success' => true,
            'message' => 'test',
            'data' => $list
        ]);
    }

    /**
     * notification type list
     */
    public function notificationtypeList()
    {
        $list = MasterNotification::select("id",'not_type_name','not_type_name_bn', 'status')->get();
        return response([
        'success' => true,
        'message' => 'notification Type',
        'data' => $list
        ]);
    }

    /**
     * Bank type list
     */
    public function banklist()
    {
        $list = MasterBank::select("id",'bank_name','bank_name_bn', 'status')->get();
        return response([
        'success' => true,
        'message' => 'Bank list',
        'data' => $list
        ]);
    }

     /**
     * Branch type list
     */
    public function branchlist(Request $request)
    {
       $query = MasterBank::with('branch');

        if ($request->bank_id) {
         $query = $query->where('master_branchs.bank_id', $request->bank_id);
         }

         $list=$query->get();

        return response([
        'success' => true,
        'message' => 'Branch list',
        'data' => $list
        ]);
    }

    public function extenalUserDropdowns(Request $request)
    {
        $allList = [
            'divisionList' => \App\Library\DropDowns::divisionList(),
            'districtList' => \App\Library\DropDowns::districtList(),
            'upazilaList' => \App\Library\DropDowns::upazilaList(),
            'unionList' => \App\Library\DropDowns::unionList(),
            'officeList' => \App\Library\DropDowns::officeList(),
            'fiscalYearList' => \App\Library\DropDowns::fiscalYearList(),
            'pauroshobaList' => \App\Library\DropDowns::pauroshobaList(),
            'wardList' => \App\Library\DropDowns::wardList(),
            'cityCorporationList' => \App\Library\DropDowns::cityCorporationList(),
			'officeList' => \App\Library\DropDowns::officeList(),
            'officeTypeList' => \App\Library\DropDowns::officeTypeList(),
            'designationList' => \App\Library\DropDowns::designationList(),
            'gradeList' => \App\Library\DropDowns::gradeList(),
            'countryList' => \App\Library\DropDowns::countryList()
        ];

        return response([
            'success' => true,
            'data' => $allList
        ]);
    }

}
