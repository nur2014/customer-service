<?php

namespace App\Http\Controllers\OrgProfile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\OrgProfile\DialogueInfo;
use App\Http\Validations\OrgProfile\MasterDialogueInfoValidation;

class DialogueInfoController extends Controller
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
        $query = new DialogueInfo();

        if ($request->dialogue) {
            $query = $query->where('dialogue', 'like', "{$request->dialogue}%")
                            ->orwhere('dialogue_bn', 'like', "{$request->dialogue}%");
        }

        $list = $query->orderBy('dialogue', 'ASC')->paginate(request('per_page', config('app.per_page')));

        return response([
            'success' => true,
            'message' => 'Dialogue Info list',
            'data' => $list
        ]);
    }
    /**
     * get all countries
     */
    public function agriDialogue(Request $request)
    {
        
        $dialogue = Cache::get('agri_ministry_dialogue');
        if (!Cache::has('agri_ministry_dialogue')) {
       
            $dialogue = DB::table('master_dialogue_settings')
                        ->select('dialogue', 'dialogue_bn', 'position')
                        ->where('status', 1)
                        ->first();
                        
            if (!$dialogue) {
                $dialogue = DB::table('master_dialogue_settings')
                ->select('dialogue', 'dialogue_bn', 'position')
                ->where('status', 1)
                ->first();
            }

            Cache::forever('agri_ministry_dialogue', $dialogue);
        }

        // $data = DB::table('master_dialogue_settings')
        //                 ->select('dialogue', 'dialogue_bn', 'position')
        //                 ->where('status', 1)
        //                 ->first();

        // if (!$data) {
        //     $data = DB::table('master_dialogue_settings')
        //     ->select('dialogue', 'dialogue_bn', 'position')
        //     ->where('status', 1)
        //     ->first();
        // }

        // $dialogue = $data;
        
        return response([
            'success' => true,
            'message' => 'Dialogue Info list',
            'data' => $dialogue,
        ]);
    }

    /**
     * dialogue Info store
     */
    public function store(Request $request)
    {
        $validationResult = MasterDialogueInfoValidation:: validate($request);    
        
        if (!$validationResult['success']) {
            return response($validationResult);
        }

        try {
            $DialogueInfo = new DialogueInfo();
            $DialogueInfo->dialogue    = $request->dialogue;
            $DialogueInfo->dialogue_bn = $request->dialogue_bn;
            $DialogueInfo->position = $request->position;
            $DialogueInfo->created_by       = (int) user_id();
            $DialogueInfo->updated_by       = (int) user_id();
            $DialogueInfo->save();

            Cache::forget('agri_ministry_dialogue');

            save_log([
                'data_id'        => $DialogueInfo->id,
                'table_name'     => 'master_dialogue_settings',
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
            'data'    => $DialogueInfo
        ]);
    }

    /**
     * dialogue Info update
     */
    public function update(Request $request, $id)
    {
        $validationResult = MasterDialogueInfoValidation:: validate($request ,$id);    
        
        if (!$validationResult['success']) {
            return response($validationResult);
        }

        $DialogueInfo = DialogueInfo::find($id);

        if (!$DialogueInfo) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        try {
            $DialogueInfo->dialogue    = $request->dialogue;
            $DialogueInfo->dialogue_bn = $request->dialogue_bn;
            $DialogueInfo->position = $request->position;
            $DialogueInfo->updated_by       = (int) user_id();
            $DialogueInfo->update();

            Cache::forget('agri_ministry_dialogue');
            
            save_log([
                'data_id'        => $DialogueInfo->id,
                'table_name'     => 'master_dialogue_settings',
                'execution_type' => 1,
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
            'data'    => $DialogueInfo
        ]);
    }

    /**
     * dialogue status update
     */
    public function toggleStatus($id)
    {
        $DialogueInfo = DialogueInfo::find($id);

        if (!$DialogueInfo) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }
        
        $query = DB::table('master_dialogue_settings')
                    ->where('id', '!=', $id)
                    ->update(['status' =>  0]);

        if($DialogueInfo->status != 1){
            $DialogueInfo->status = 1;
            $DialogueInfo->update();
        }

        Cache::forget('agri_ministry_dialogue');

        save_log([
            'data_id'        => $DialogueInfo->id,
            'table_name'     => 'master_dialogue_settings',
            'execution_type' => 2,
        ]);

        return response([
            'success' => true,
            'message' => 'Data updated successfully',
            'data'    => $DialogueInfo
        ]);
    }

    /**
     * dialogue destroy
     */
    public function destroy($id)
    {
        $DialogueInfo = DialogueInfo::find($id);

        if (!$DialogueInfo) {
            return response([
                'success' => false,
                'message' => 'Data not found.'
            ]);
        }

        $DialogueInfo->delete();

        Cache::forget('agri_ministry_dialogue');

        save_log([
            'data_id'        => $id,
            'table_name'     => 'master_dialogue_settings',
            'execution_type' => 2,
        ]);
        
        return response([
            'success' => true,
            'message' => 'Data deleted successfully'
        ]);
    }
}
