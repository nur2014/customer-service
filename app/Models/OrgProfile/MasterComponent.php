<?php

namespace App\Models\OrgProfile;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrgProfile\MasterOrganizationProfile;
use App\Models\OrgProfile\MasterModule;

class MasterComponent extends Model
{
    protected $table ="master_components";

    protected $fillable = [
        'component_name','component_name_bn','discription','discription_bn','sorting_order'
    ];

    public function org()
    {
        return $this->belongsToMany(MasterOrganizationProfile::class,'master_org_components','component_id','org_id');
    }

    public function module()
    {
        return $this->belongsTo(MasterModule::class,'module_id','id');
    }
    public function service()
    {
        return $this->hasMany('App\Models\OrgProfile\MasterService', 'component_id');
    }

}
