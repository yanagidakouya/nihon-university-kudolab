<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Lib\Csv;
use Carbon\Carbon;
use App\Models\Daily;

class DailyController extends Controller
{
    //
    public function downloadCsv()
    {
        $csv = new Csv();
        $data = Daily::all()->toArray();
        $csv_header = ['id', 'panel_1_max_power', 'panel_2_max_power', 'date', 'created_at', 'updated_at'];
        $file_name = '日別最大電力_' .Carbon::today()->format('Y_m_d') . '.csv';
        return $csv->download($data, $csv_header, $file_name);
    }
    
}
