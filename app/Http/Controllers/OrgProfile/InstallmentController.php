<?php

namespace App\Http\Controllers\OrgProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrgProfile\InstallmentMain;
use App\Models\OrgProfile\InstallmentDetails;
use App\Http\Validations\OrgProfile\InstallmentValidations;

class InstallmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Get all Seeds Stock
     */
    public function index(Request $request)
    {
        $query = InstallmentMain::with('insDetails');        

        $list = $query->paginate(request('per_page', config('app.per_page')));

        if( count($list)>0){
            return response([
                'success' => true,
                'message' => 'Installment list',
                'data' => $list
            ]);
        }
        else
        {
            return response([
                'success' => false,
                'message' => 'Data not found!!'
            ]);
        }
    }

    public function show($id)
    {
        $installments = InstallmentMain::find($id);

        if(!$installments){
        	return response([
                'success' => false,
                'message' => 'Data not found!!'
            ]);          
        }
        else
        {  
            return response([
                'success' => true,
                'message' => 'Installment details',
                'data' => $installments
            ]);
        }        
    }
    
    /**
     * Seeds Stock store
     */
    public function store(Request $request)
    {  
        $validationResult = InstallmentValidations::validate($request);

        if (!$validationResult['success']) {
            return response($validationResult);
        }

        $installments = new InstallmentMain();

        $installments->customer_id 					= (int)$request->type; 
        $installments->created_by     		= (int)user_id();
	    $installments->updated_by     		= (int)user_id();
        $installments->save();
        $customer_main_id = $installments->id;

        try {
        	foreach ($request->cus_ins_details as $value) {
	        		$installmentDetails                  	= new InstallmentDetails();
					$installmentDetails->customer_main_id 	= $customer_main_id; 	
					$installmentDetails->amount 			= $value['amount']; 	
					$installmentDetails->expire_date 		= $value['expire_date'];
                    $installmentDetails->note 		        = $value['note'];			
					$installmentDetails->save();       		
		            
		            // save_log([
		            //     'data_id'    => $installments->id,
		            //     'table_name' => 'master_wards'
		            // ]);		            
        	}		         


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
            'data'    => $installments
        ]);
    }


     /**
     * Seeds Stock Update
     */
    public function update(Request $request, $id)
    {   
        // return $request;
        $validationResult = InstallmentValidations::validate($request, $id);

        if (!$validationResult['success']) {
            return response($validationResult);
        } 

	    InstallmentDetails::where('customer_main_id', $id)->delete();
 
        try {
            $installments = InstallmentMain::find($id);
            $installments->customer_id 					= (int)$request->customer_id;           
            $installments->created_by     		= (int)user_id();
            $installments->updated_by     		= (int)user_id();
            $installments->save();

	        	foreach ($request->cus_ins_details as $value) {
	           	 	$installments                  		= new InstallmentDetails();
					$installments->customer_main_id 		= $id;
					$installmentDetails->amount 			= $value['amount']; 	
					$installmentDetails->expire_date 		= $value['expire_date'];
                    $installmentDetails->note 		        = $value['note'];				
					$installments->save(); 
		            
		            // save_log([
		            //     'data_id'    => $installments->id,
		            //     'table_name' => 'master_wards'
		            // ]);
				} 

        } catch (\Exception $ex) {
            
            return response([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : []
            ]);
        }

        return response([
            'success' => true,
            'message' => 'Data Update successfully',
            'data'    => $installments
        ]);
    }

     /**
     * status update
     */
    public function toggleStatus($id)
    {
        $installments = InstallmentMain::find($id);

        if (!$installments) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        $installments->status = $installments->status == 1 ? 0 : 1;
        $installments->update();

        save_log([
            'data_id'       => $installments->id,
            'table_name'    => 'master_wards',
            'execution_type'=> 2
        ]);

        return response([
            'success' => true,
            'message' => 'Data updated successfully',
            'data'    => $installments
        ]);
    }    

    /**
     *  destroy
    */
    public function destroy($id)
    {
        $installments = InstallmentMain::find($id);

        if (!$installments) {
            return response([
                'success' => false,
                'message' => 'Data not found!!'
            ]);
        }

        $installments->delete();

        save_log([
            'data_id'       => $id,
            'table_name'    => 'master_wards',
            'execution_type'=> 2
        ]);

        return response([
            'success' => true,
            'message' => 'Data deleted successfully'
        ]);
    }
}
