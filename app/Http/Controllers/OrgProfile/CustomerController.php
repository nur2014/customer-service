<?php

namespace App\Http\Controllers\OrgProfile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\OrgProfile\Customer;
use App\Http\Validations\OrgProfile\CustomerValidation;

class CustomerController extends Controller
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
     * get all countries
     */
    public function index(Request $request)
    {
        $query = DB::table('customers')
                    ->select("customers.*");

        if ($request->customer_name) {
            $query = $query->where('customer_name', 'like', "{$request->customer_name}%");
        }       

        $list = $query->orderBy('customers.customer_name', 'ASC')->paginate(request('per_page', config('app.per_page')));
        
        return response([
            'success' => true,
            'message' => 'Customer list',
            'data' => $list
        ]);
    }

    /**
     * customer store
     */
    public function store(Request $request)
    {
        $validationResult =CustomerValidation:: validate($request);

        if (!$validationResult['success']) {
            return response($validationResult);
        }

        try {
            $customer = new Customer();
            $customer->customer_name    = $request->customer_name;
            $customer->address          = $request->address;
            $customer->created_by       = (int) user_id();
            $customer->updated_by       = (int) user_id();
            $customer->save();           

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
            'data'    => $customer
        ]);
    }

    /**
     * customer update
     */
    public function update(Request $request, $id)
    {
        $validationResult =CustomerValidation:: validate($request ,$id);

        if (!$validationResult['success']) {
            return response($validationResult);
        }

        $customer = Customer::find($id);

        if (!$customer) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        try {
            $customer->customer_name    = $request->customer_name;
            $customer->address          = $request->address;
            $customer->updated_by       = (int) user_id();
            $customer->update();
            
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
            'data'    => $customer
        ]);
    }

    /**
     * customer status update
     */
    public function toggleStatus($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        $customer->status = $customer->status ? 0 : 1;
        $customer->update();

        // save_log([
        //     'data_id'        => $customer->id,
        //     'table_name'     => 'master_divisions',
        //     'execution_type' => 2,
        // ]);

        return response([
            'success' => true,
            'message' => 'Data updated successfully',
            'data'    => $customer
        ]);
    }

    /**
     * customer destroy
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        $customer->delete();

        // Cache::forget('commonDropdown');

        // save_log([
        //     'data_id'        => $id,
        //     'table_name'     => 'master_divisions',
        //     'execution_type' => 2,
        // ]);

        return response([
            'success' => true,
            'message' => 'Data deleted successfully'
        ]);
    }

    /**
     * Show customer
     */
    public function show($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        return response([
            'success' => true,
            'message' => 'Data found!',
            'data'    => $customer
        ]);
    }
}
