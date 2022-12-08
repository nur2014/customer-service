<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/helper-test', function () {
    return user_id();
});

$router->get('/notification-event-test', 'NotificationController@fireEventTest');
$router->get('/notification-sender/send-notification', 'NotificationController@sendNotification');
$router->post('/notification-sender/send-notification', 'NotificationController@sendNotification');
$router->get('/notification-sender/notification', 'NotificationController@notifications');
$router->post('/device-token/store', 'DeviceTokenController@notifications');
$router->get('/notification-received/list', 'NotificationController@receivedNotifications');
$router->get('/notification-seen/{id}', 'NotificationController@notificationSeen');


/******************** Data Archive Module *********************/
Route::group(['prefix'=>'/data-archive'], function() {
    Route::get('/database-backup', 'DataArchiveController@dumpDB');
    //download file path from storage
    Route::get('download-backup-db', 'DataArchiveController@downloadBackupDb');
    Route::get('db-backup-files', 'DataArchiveController@getDbBackupFiles');
    Route::delete('db-backup-delete', 'DataArchiveController@deleteDbBackupFile');
});

Route::get('common-dropdowns', function () {
    // Caching commonly used dropdown for 24 hours = 86400s
    $list = [
        'customerList' => \App\Library\DropDowns::customerList(),
        'divisionList' => '',// \App\Library\DropDowns::divisionList(),
        'districtList' => '',//\App\Library\DropDowns::districtList(),
        'upazilaList' =>'',// \App\Library\DropDowns::upazilaList(),
        'unionList' => '',//\App\Library\DropDowns::unionList(),
        'cityCorporationList' =>'',// \App\Library\DropDowns::cityCorporationList(),
        'pauroshobaList' => '',//\App\Library\DropDowns::pauroshobaList(),
        'wardList' => '',//\App\Library\DropDowns::wardList(),
        'officeTypeList' => '',//\App\Library\DropDowns::officeTypeList(),
        'officeList' => '',//\App\Library\DropDowns::officeList(),
        'designationList' => '',//\App\Library\DropDowns::designationList(),
        'gradeList' => '',//\App\Library\DropDowns::gradeList(),
        'fiscalYearList' => '',//\App\Library\DropDowns::fiscalYearList(),
        'bankList' => '',//\App\Library\DropDowns::bankList(),
        'branchList' => '',//\App\Library\DropDowns::branchList(),
        'countryList' =>'',// \App\Library\DropDowns::countryList(),
        'portalServiceCategoryList' => '',//\App\Library\DropDowns::portalServiceCategoryList(),
        'portalServiceCustomerTypeList' => '',//\App\Library\DropDowns::portalServiceCustomerTypeList(),
        'moduleList' => '',//\App\Library\DropDowns::moduleList(),
        'orgList' =>'',// \App\Library\DropDowns::orgList()
    ];

    // Caching should be enabled on live server only and that is why the following cache remeber is disabled
    // $value = \Illuminate\Support\Facades\Cache::remember('commonDropdown', 0, function () {
    // });

    return response()->json([
        'success' => true,
        'data' => $list
    ]);
});

Route::get('portal-common-dropdowns', function () {
    $list = [
        'fiscalYearList' => \App\Library\DropDowns::fiscalYearList(),
        'officeTypeList' => \App\Library\DropDowns::officeTypeList(),
        'officeList' => \App\Library\DropDowns::officeList(),
        'orgList' => \App\Library\DropDowns::orgList()
    ];
    return response()->json([
        'success' => true,
        'data' => $list
    ]);
});

Route::get('ministry-common-dropdown', function () {
    $list = [
        'fiscalYearList' => \App\Library\DropDowns::fiscalYearList()
    ];
    return response()->json([
        'success' => true,
        'data' => $list
    ]);
});

Route::get('common/dropdowns', function () {
    return response([
        'success' => true,
        'data' => [
            'customerList' => \App\Library\DropDowns::customerList(),
            'componentList' => '',//\App\Library\DropDowns::componentList(),
            'moduleList' => '',//\App\Library\DropDowns::moduleList(),
            'serviceList' => '',//\App\Library\DropDowns::serviceList(),
            'serviceComList' => '',//\App\Library\DropDowns::serviceComList(),
            'menuList' => '',//\App\Library\DropDowns::menuList(),
            'bankList' => '',//\App\Library\DropDowns::bankList(),
            'notificationTypeList' => '',//\App\Library\DropDowns::notificationTypeList(),
            'cmtCommitteeList' =>'',// \App\Library\DropDowns::cmtCommitteeList(),
            'cmtAgendaList' =>'',// \App\Library\DropDowns::cmtAgendaList(),
            'branchList' =>'',// \App\Library\DropDowns::branchList(),
            'cityCorporationList' =>'',// \App\Library\DropDowns::cityCorporationList(),
            'pauroshobaList' => '',//\App\Library\DropDowns::pauroshobaList(),
            'wardList' => '',//\App\Library\DropDowns::wardList(),
            'serviceEligibiltyList' =>'',// \App\Library\DropDowns::serviceEligibiltyList(),
            'documentCategoryList' =>'',// \App\Library\DropDowns::documentCategoryList(),
            'messageTemplateList' =>'',// \App\Library\DropDowns::messageTemplateList(),
        ]
    ]);
});

Route::get('external-common-config2-dropdowns', function () {
    return response([
        'success' => true,
        'data' => [
            'bankList' => \App\Library\DropDowns::bankList(),
            'branchList' => \App\Library\DropDowns::branchList(),
            'cityCorporationList' => \App\Library\DropDowns::cityCorporationList(),
            'pauroshobaList' => \App\Library\DropDowns::pauroshobaList(),
            'wardList' => \App\Library\DropDowns::wardList(),
            'countryList' => \App\Library\DropDowns::countryList()
        ]
    ]);
});

Route::get('external-user-dropdowns', 'CommonDropdownController@extenalUserDropdowns');

Route::get('bank-and-branch-by-component-id/{componentId}', function($componentId) {
    return response()->json([
        'success' => true,
        'data' => [
            'bankList' => \App\Library\DropDowns::bankByComponent($componentId),
            'branchList' => \App\Library\DropDowns::branchList($componentId),
        ]
    ]);
});


$router->get('/redis-test', 'RedisTestController@redisTest');
Route::get('download-attachment', 'DownloadController@downloadAttachment');
Route::get('auth-user-office-detail/{officeId}', 'OrgProfile\MasterOfficeController@getOfficeDetail');

Route::group(['middleware'  =>  'token'], function () {
    Route::get('/protected-route', function () {
        Log::info('Protected route executed');

        return response([
            'success' => true
        ]);
    });

    Route::group(['prefix'=>'/installment'], function(){
        Route::get('/list', 'OrgProfile\InstallmentController@index');
        Route::get('/details/{id}', 'OrgProfile\InstallmentController@show');
        Route::post('/store', 'OrgProfile\InstallmentController@store');
        Route::put('/update/{id}', 'OrgProfile\InstallmentController@update');
        Route::delete('/toggle-status/{id}', 'OrgProfile\InstallmentController@toggleStatus');
        Route::delete('/destroy/{id}', 'OrgProfile\InstallmentController@destroy');
    });

    Route::group(['prefix'=>'/customer'], function(){
        Route::get('/list', 'OrgProfile\CustomerController@index');
        Route::post('/store', 'OrgProfile\CustomerController@store');
        Route::put('/update/{id}', 'OrgProfile\CustomerController@update');
        Route::delete('/toggle-status/{id}', 'OrgProfile\CustomerController@toggleStatus');
        Route::delete('/destroy/{id}', 'OrgProfile\CustomerController@destroy');
        Route::get('/show/{id}', 'OrgProfile\CustomerController@show');
    });

  
    // Component Crud operation routes
    Route::group(['prefix'=>'/component'], function(){
        Route::get('/list', 'OrgProfile\MasterComponentController@index');
        Route::get('/details/list', 'OrgProfile\MasterComponentController@getlist');
        Route::post('/store', 'OrgProfile\MasterComponentController@store');
        Route::put('/update/{id}', 'OrgProfile\MasterComponentController@update');
        Route::delete('/toggle-status/{id}', 'OrgProfile\MasterComponentController@toggleStatus');
        Route::get('/org-wise-component', 'OrgProfile\MasterComponentController@orgWiseComponent');
        // Route::delete('/destroy/{id}', 'OrgProfile\MasterComponentController@destroy');
    });

    // Module Crud operation routes
    Route::group(['prefix'=>'/module'], function(){
        Route::get('/list', 'OrgProfile\MasterModuleController@index');
        Route::get('/details/list', 'OrgProfile\MasterModuleController@getlist');
        Route::post('/store', 'OrgProfile\MasterModuleController@store');
        Route::put('/update/{id}', 'OrgProfile\MasterModuleController@update');
        Route::delete('/toggle-status/{id}', 'OrgProfile\MasterModuleController@toggleStatus');
        // Route::delete('/destroy/{id}', 'OrgProfile\MasterModuleController@destroy');
    });

    // Service Crud operation routes
    Route::group(['prefix'=>'/service'], function(){
        Route::get('/list', 'OrgProfile\MasterServiceController@index');
        Route::post('/store', 'OrgProfile\MasterServiceController@store');
        Route::put('/update/{id}', 'OrgProfile\MasterServiceController@update');
        Route::delete('/toggle-status/{id}', 'OrgProfile\MasterServiceController@toggleStatus');
        Route::delete('/destroy/{id}', 'OrgProfile\MasterServiceController@destroy');
    });

  

    // Master Menu Crud operation routes
    Route::group(['prefix'=>'/master-menu'], function(){
        Route::post('/change-serial-order/{model}', 'OrgProfile\MasterMenuController@changeSerialOrder');

        Route::get('/all-menu-list', 'OrgProfile\MasterMenuController@allMenus');
        Route::get('/sidebar-menus/{roleId}', 'OrgProfile\MasterMenuController@menusByRole');

        Route::get('/list', 'OrgProfile\MasterMenuController@index');
        Route::get('/url-to-id', 'OrgProfile\MasterMenuController@urlToId');
        Route::get('/url-to-setting', 'OrgProfile\MasterMenuController@urlToSetting');
        Route::post('/store', 'OrgProfile\MasterMenuController@store');
        Route::put('/update/{id}', 'OrgProfile\MasterMenuController@update');
        Route::delete('/toggle-status/{id}', 'OrgProfile\MasterMenuController@toggleStatus');
        Route::delete('/destroy/{id}', 'OrgProfile\MasterMenuController@destroy');
    });

    

   

    
  

    // Dialogue Info crud routes
    Route::group(['prefix'=>'/master-dialogue-info-settings'], function(){
        Route::get('/list', 'OrgProfile\DialogueInfoController@index');
        Route::post('/store', 'OrgProfile\DialogueInfoController@store');
        Route::put('/update/{id}', 'OrgProfile\DialogueInfoController@update');
        Route::delete('/toggle-status/{id}', 'OrgProfile\DialogueInfoController@toggleStatus');
        Route::delete('/destroy/{id}', 'OrgProfile\DialogueInfoController@destroy');
    });


  

});

//Common dropdown routes
Route::group(['prefix'=>'/common'], function(){
    Route::get('/country-list', 'CommonDropdownController@countryList');
    Route::get('/union-list', 'CommonDropdownController@unionList');
    Route::get('/office-type-list', 'CommonDropdownController@officeTypeList');
    Route::get('/org-and-org-component-list', 'CommonDropdownController@orgAndOrgComponentList');

    Route::get('/office-list', 'CommonDropdownController@officeList');
    Route::get('/component-list', 'CommonDropdownController@componentList');
    Route::get('/module-list', 'CommonDropdownController@moduleList');
    Route::get('/service-list', 'CommonDropdownController@serviceList');
    Route::get('/menu-list', 'CommonDropdownController@menuList');
    Route::get('/designation-list', 'CommonDropdownController@designationList');
    Route::get('/assign-designation-list', 'CommonDropdownController@assignDesignationList');
    Route::get('/notification-types', 'CommonDropdownController@notificationtypeList');
    Route::get('/bank-list', 'CommonDropdownController@banklist');
    Route::get('/branch-list', 'CommonDropdownController@branchlist');

});

Route::get('agri-dialogue', 'OrgProfile\DialogueInfoController@agriDialogue');
Route::post('division-district-matching', 'DivisionCheckController');


 Route::group(['prefix'=>'/log-report'], function(){
    Route::get('/list', 'LogReport\LogReportController@index');
});


//Auth User access
Route::group(['prefix'=>'/access-control'], function() {
    Route::get('/components-from-menu', 'AccessControlController@componentsOfSuperAdmin');
    Route::get('/sidebar-menus/{roleId}/{componentId}', 'AccessControlController@menusByRoleComponent');
});



require_once "portal.php";
