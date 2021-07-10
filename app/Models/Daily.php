<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Daily extends Model
{
    //
    protected $table = 'dailies';
    protected $fillable = ['panel_1_max_power', 'panel_2_max_power', 'date'];
}
