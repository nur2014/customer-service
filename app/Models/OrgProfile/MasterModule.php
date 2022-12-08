<?php

namespace App\Models\OrgProfile;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrgProfile\MasterOrganizationProfile;
use App\Models\OrgProfile\MasterComponent;

class MasterModule extends Model
{
    protected $table ="master_modules";

    protected $fillable = [
        'module_name','module_name_bn','component_id','status','sorting_order'
    ];
    
    public function org()
    {
        return $this->belongsToMany(MasterOrganizationProfile::class, 'master_org_modules', 'module_id', 'org_id');
    }

    public function component()
    {
        return $this->belongsTo(MasterComponent::class, 'component_id', 'id');
    }
}
