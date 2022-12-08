<?php
namespace App\Library;

use Illuminate\Support\Facades\DB;
use Log;

class DropDowns
{

  public static function customerList()
  {
    return DB::table('customers')
            ->select('id as value', 'customer_name as text_en', 'customer_name as text_bn', 'status')
            ->orderBy('customer_name', 'asc')
            ->get();
  }

//   public static function divisionList()
//   {
//     return 
    
//     DB::table('master_divisions')
//             ->select('id as value', 'division_name as text_en', 'division_name_bn as text_bn', 'status')
//             ->orderBy('division_name', 'asc')
//             ->get();
//   }

//   public static function countryList()
//   {
//     return DB::table('master_countries')
//             ->select('id as value', 'country_name as text_en', 'country_name_bn as text_bn', 'status')
//             ->orderBy('country_name', 'asc')
//             ->get();
//   }

//   public static function districtList($divisionId = null)
//   {
//     $query = DB::table('master_districts')
//               ->select('id as value', 'district_name as text_en', 'district_name_bn as text_bn', 'division_id', 'status')
//               ->addSelect(DB::raw("'division_id' as parent"))
//               ->orderBy('district_name', 'asc');

//     if ($divisionId) {
//       $query = $query->where('division_id', $divisionId);
//     }

//     return $query->get();
//   }

//   public static function upazilaList($districtId = null)
//   {
//     $query = DB::table('master_upazillas')
//               ->select('id as value', 'upazilla_name as text_en', 'upazilla_name_bn as text_bn', 'district_id', 'status')
//               ->addSelect(DB::raw("'district_id' as parent"))
//               ->orderBy('upazilla_name', 'asc');

//     if ($districtId) {
//       $query = $query->where('district_id', $districtId);
//     }

//     return $query->get();
//   }

//   /**
//    * union list
//    */
//   public static function unionList($upazilla_id  = null)
//   {
//     $query = DB::table('master_unions')
//               ->select('id as value', 'union_name as text_en', 'union_name_bn as text_bn', 'upazilla_id', 'status')
//               ->addSelect(DB::raw("'upazilla_id' as parent"))
//               ->orderBy('union_name', 'asc');

//     if ($upazilla_id) {
//       $query = $query->where('upazilla_id', $upazilla_id);
//     }

//     return $query->get();
//   }

//   public static function userList()
//   {
//     return DB::table('users')->pluck('name', 'id')->all();
//   }

//   public static function orgList()
//   {
//     return DB::table('master_org_profiless')
//           ->select("id as value",'org_name as text_en','org_name_bn as text_bn', 'status')
//           ->orderBy('org_name', 'asc')
//           ->get();
//   }

//   public static function officeTypeList()
//   {
//     return DB::table('master_office_types')
//           ->select("id as value",'office_type_name as text_en','office_type_name_bn as text_bn', 'org_id', 'status')
//           ->addSelect(DB::raw("'org_id' as parent"))
//           ->orderBy('office_type_name', 'asc')
//           ->get();
//   }

//   public static function officeList()
//   {
//     return DB::table('master_offices')
//             ->select("id as value",'office_name as text', 'office_name as text_en','office_name_bn as text_bn','org_id','office_type_id','parent_office_id','is_regional_office','regional_office_id','status','union_id','upazilla_id','district_id','division_id', 'area_type_id', 'city_corporation_id', 'pauroshoba_id', 'ward_id', 'country_id', 'office_code', 'address', 'address_bn', 'office_cat_id')
//             ->addSelect(DB::raw("'office_type_id' as parent"))
//             ->orderBy('office_name', 'asc')
//             ->get();
//   }

//   public static function designationList()
//   {
//     return DB::table('master_designations')
//           ->select("id as value",'designation as text_en','designation_bn as text_bn','org_id', 'status', 'total_post', 'sorting_order')
//           ->orderBy('designation', 'asc')
//           ->get();
//   }

//   public static function gradeList()
//   {
//     return DB::table('master_grades')
//             ->select('id as value', 'grade_name as text_en', 'grade_name_bn as text_bn', 'status')
//             ->orderBy('grade_name', 'asc')
//             ->get();
//   }

//   public static function fiscalYearList()
//   {
//     return DB::table('master_fiscal_years')
//             ->select('id as value', 'year as text_en', 'year as text_bn', 'sorting_order', 'status')
//             ->orderBy('sorting_order', 'asc')
//             ->get();
//   }

//     /**
//    * notification type list
//    */
//   public static function notificationTypeList()
//   {
//     return DB::table('master_notification_types')
//         ->select("id as value",'not_type_name as text','not_type_name_bn as text_bn', 'status')
//         ->orderBy('not_type_name', 'asc')
//         ->get();
//   }
//     /**
//    * cmt_committees type list
//    */
//   public static function cmtCommitteeList()
//   {
//     return DB::table('cmt_committees')
//         ->select("id as value",'committee_name as text','committee_name_bn as text_bn', 'org_id', 'status')
//         ->orderBy('committee_name', 'asc')
//         ->get();
//   }

//     /**
//    * cmt_committees type list
//    */
//   public static function cmtAgendaList()
//   {
//     return DB::table('cmt_agenda')
//         ->select("id as value",'agenda_name as text','agenda_name_bn as text_bn', 'cmt_committee_id')
//         ->orderBy('agenda_name', 'asc')
//         ->get();
//   }

//     /**
//    * categoryList
//    */
//    public static function documentCategoryList()
//   {
//     return DB::table('master_document_categories')
//         ->select("id as value",'category_name as text','category_name_bn as text_bn', 'sorting_order', 'status')
//         ->orderBy('category_name', 'asc')
//         ->get();
//   }


// /**
//  * Bank list
//  */
//   public static function bankList()
//   {
//     return DB::table('master_banks')
//         ->select("id as value",'bank_name as text_en','bank_name_bn as text_bn', 'component_id', 'org_id', 'status')
//         ->addSelect(DB::raw("'org_id' as parent"))
//         ->orderBy('bank_name', 'asc')
//         ->get();
//   }
//   public static function portalServiceCategoryList()
//   {
//     return DB::table('portal_service_categories')
//         ->select("id as value",'name as text_en','name_bn as text_bn', 'status')
//         ->orderBy('name', 'asc')
//         ->get();
//   }
//   public static function portalServiceCustomerTypeList()
//   {
//     return DB::table('portal_service_customer_types')
//         ->select("id as value",'name as text_en','name_bn as text_bn', 'status')
//         ->orderBy('name', 'asc')
//         ->get();
//   }
//   public static function messageTemplateList()
//   {
//          return DB::table('message_template')
//         ->select("id as value",'template as text','mobile_text', 'email_web_text', 'status')
//         ->orderBy('template', 'asc')
//         ->get();
//   }
// /**
//  * Bank and branch list by component
//  */
//   public static function bankByComponent($componentId = null)
//   {
//     $query = DB::table('master_banks')
//           ->select("id as value",'bank_name as text_en','bank_name_bn as text_bn','component_id', 'status')
//           ->addSelect(DB::raw("'component_id' as parent"))
//           ->orderBy('bank_name', 'asc');

//     if ($componentId){
//       $query = $query->where('component_id', $componentId);
//     }

//     return $query->get();
//   }

//   /**
//    * Branch List
//    */
//   public static function branchList($componentId = null, $bankId = null)
//   {
//     $query = DB::table('master_branchs')
//                 ->join('master_banks', 'master_branchs.bank_id', '=', 'master_banks.id')
//                 ->select('master_branchs.id as value', 'branch_name as text_en', 'branch_name_bn as text_bn', 'bank_id', 'master_branchs.status')
//                 ->addSelect(DB::raw("'bank_id' as parent"))
//                 ->orderBy('branch_name', 'asc');

//     if ($componentId) {
//       $query = $query->where('master_banks.component_id', $componentId);
//     }

//     if ($bankId) {
//       $query = $query->where('bank_id', $bankId);
//     }

//     return $query->get();
//   }
//   /**
//    * serviceEligibiltyList List
//    */
//   public static function serviceEligibiltyList()
//   {
//     return DB::table('master_eligibility_types')
//                 ->select('master_eligibility_types.id as value', 'type_name as text_en', 'type_name_bn as text_bn', 'status')
//                 ->orderBy('type_name', 'asc')
//                 ->get();
//   }

//   /**
//    * cityCorporationList List
//    */
//   public static function cityCorporationList()
//   {
//     return DB::table('master_city_corporations')
//                 ->select(
//                   'master_city_corporations.id as value',
//                   'city_corporation_name as text',
//                   'city_corporation_name as text_en',
//                   'city_corporation_name_bn as text_bn',
//                   'division_id',
//                   'district_id',
//                   'status'
//                  )
//                  ->addSelect(DB::raw("'division_id' as parent"))
//                 ->orderBy('city_corporation_name', 'asc')
//                 ->get();
//   }

//   /**
//    * cityCorporationList List
//    */
//   public static function pauroshobaList()
//   {
//     return DB::table('master_pauroshobas')
//                 ->select(
//                   'master_pauroshobas.id as value',
//                   'pauroshoba_name as text',
//                   'pauroshoba_name as text_en',
//                   'pauroshoba_name_bn as text_bn',
//                   'division_id',
//                   'district_id',
//                   'upazilla_id',
//                   'status'
//                  )
//                  ->addSelect(DB::raw("'district_id' as parent"))
//                 ->orderBy('pauroshoba_name', 'asc')
//                 ->get();
//   }

//   /**
//    * wardList
//    */
//   public static function wardList()
//   {
//     return DB::table('master_ward_details')
//         ->join('master_wards', 'master_ward_details.master_ward_id', '=','master_wards.id')
//         ->select(
//           'master_ward_details.id as value',
//           'master_ward_details.ward_name as text',
//           'master_ward_details.ward_name as text_en',
//           'master_ward_details.ward_name_bn as text_bn',
//           'master_wards.division_id',
//           'master_wards.district_id',
//           'master_wards.city_corporation_id',
//           'master_wards.pauroshoba_id',
//           'master_wards.upazilla_id',
//           'master_wards.union_id',
//           'master_wards.status'
//           )
//         ->addSelect(DB::raw("'union_id' as parent"))
//         ->orderBy('master_ward_details.ward_name', 'asc')
//         ->get();
//   }

//   public static function componentList()
//   {
//     return DB::table('master_components')
//             ->select('id as value', 'component_name as text_en','component_name_bn as text_bn', 'status','sorting_order')
//             ->orderBy('component_name', 'asc')
//             ->get();
//   }

//   public static function moduleList($componentId = null)
//   {
//     $query = DB::table('master_modules')
//               ->select('id as value','module_name as text_en','module_name_bn as text_bn', 'component_id', 'status','sorting_order')
//               ->orderBy('module_name', 'asc');

//     if ($componentId) {
//         $query = $query->where('component_id', $componentId);
//     }

//     return $query->get();
//   }

//   public static function serviceList($moduleId = null)
//   {
//     $query = DB::table('master_services')
//               ->select('id as value', 'service_name as text_en', 'service_name_bn as text_bn', 'module_id', 'status','sorting_order')
//               ->orderBy('service_name', 'asc');

//     if ($moduleId) {
//         $query = $query->where('module_id', $moduleId);
//     }

//     return $query->get();
//   }

//   public static function serviceComList($componentId = null)
//   {
//     $query = DB::table('master_services')
//               ->select('id as value', 'service_name as text_en', 'service_name_bn as text_bn', 'component_id', 'status')
//               ->orderBy('service_name', 'asc');

//     if ($componentId) {
//         $query = $query->where('component_id', $componentId);
//     }

//     return $query->get();
//   }

//   public static function menuList($serviceId = null)
//   {
//     $query = DB::table('master_menus')
//               ->select('id as value', 'menu_name as text_en', 'menu_name_bn as text_bn', 'module_id', 'service_id','sorting_order','status')
//               ->orderBy('menu_name', 'asc');

//     if ($serviceId) {
//       $query = $query->where('service_id', $serviceId);
//     }

//     return $query->orderBy('sorting_order', 'asc')->get();
//   }
}