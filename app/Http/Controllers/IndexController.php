<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\APanel;
use App\Models\BPanel;

class IndexController extends Controller
{
    //
    public function index() 
    {
      # code...
      return view('top');
    }

    public function postData(Request $request)
    {
      if($request->ini == '1') {
        $a_panel = APanel::all();
        $b_panel = BPanel::all();
        return response()->json(array(
          'panel_1' => $a_panel,
          'panel_2' => $b_panel,
        ));

      } elseif($request->ini == '0') {
        $year_start = $request->year_start;
        $year_end = $request->year_end;
        $month_start = $request->month_start;
        $month_end = $request->month_end;
        $day_start = $request->day_start;
        $day_end = $request->day_end;
        $panel = $request->panels;
        $type = $request->type;
        
        $a_panel = APanel::all();
        $b_panel = BPanel::all();
        return response()->json(array(
          'panel_1' => $a_panel,
          'panel_2' => $b_panel,
        ));

      }
      


    }

    public function insertData(Request $request)
    {
      $send_data = $request->input('send_data');
      // $raspberry_pi = json_decode($request, true);
      if($send_data) {
        if($send_data != 'None') {
          $ary = explode(",", $request->input('send_data'));
          $current = ltrim($ary[0],'sensI=');
          $voltage = ltrim($ary[1],'sensV=');
          $power = intval($current) * intval($voltage);
          // $current = str_replace('sensI=', '', $ary[1]);
          // $voltage = str_replace('sensV=', '', $ary[0]);
          
          $a_panel = APanel::create([
            'current' => $current,
            'voltage' => $voltage,
            'power' => $power,
          ]);
        } else {
          return;
        }
      }
      return response()->json($request);
    }
}

