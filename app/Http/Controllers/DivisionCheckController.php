<?php

namespace App\Http\Controllers;

use App\Models\OrgProfile\MasterDistrict;
use Illuminate\Http\Request;

class DivisionCheckController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $district = MasterDistrict::find($request->district_id);

        if (!$district) {
            return response([
                'success' => false,
                'message' => 'District not found!',
            ]);
        }

        if ($district->division_id != $request->division_id) {
            return response([
                'success' => false,
                'message' => 'Division and District mismatched!',
            ]);
        }

        return response([
            'success' => true,
            'message' => 'Division and District matched!',
        ]);
    }
}
