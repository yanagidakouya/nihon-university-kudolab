<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class APanel extends Model
{
    //
    protected $table = 'a_panels';
    protected $fillable = ['voltage', 'current', 'power'];
}
