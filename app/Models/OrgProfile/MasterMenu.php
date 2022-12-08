<?php

namespace App\Models\OrgProfile;

use Illuminate\Database\Eloquent\Model;

class MasterMenu extends Model
{
    protected $table = "master_menus";

    protected $fillable = [
        'menu_name', 'menu_name_bn', 'url', 'sorting_order', 'associated_urls'
    ];
}
