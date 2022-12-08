<?php

namespace App\Models\OrgProfile;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrgProfile\MasterMenu;
class MasterService extends Model
{
    protected $table ="master_services";

    protected $fillable = [
        'service_name','service_name_bn','component_id','module_id','status'
    ];
    public function master_menus()
    {
        return $this->hasMany(MasterMenu::class,'service_id')->oldest('sorting_order');
    }

}
