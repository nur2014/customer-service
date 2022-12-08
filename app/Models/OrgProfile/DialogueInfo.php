<?php

namespace App\Models\OrgProfile;

use Illuminate\Database\Eloquent\Model;

class DialogueInfo extends Model
{
    protected $table ="master_dialogue_settings";

    protected $fillable = [
        'dialogue','dialogue_bn', 'position', 'created_by', 'updated_by', 'status'
    ];

}
