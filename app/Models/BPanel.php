<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BPanel extends Model
{
    //
    protected $table = 'b_panels';
    protected $fillable = ['voltage', 'current', 'power'];
}
