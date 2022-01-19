<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\APanel;
use App\Lib\Csv;
use Carbon\Carbon;

class APanelController extends Controller
{
    //
    public function downloadCsv()
    {
        $csv = new Csv();
        $data = APanel::all()->toArray();
        $csv_header = ['id', 'voltage', 'current', 'power', 'created_at', 'updated_at'];
        $file_name = '光触媒厚塗り_' .Carbon::today()->format('Y_m_d') . '.csv';
        return $csv->download($data, $csv_header, $file_name);

    }
}
