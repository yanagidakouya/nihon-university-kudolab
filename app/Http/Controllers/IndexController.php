<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\APanel;
use App\Models\BPanel;
use App\Models\Daily;

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
      if($request->ini == '1') {// 全期間
        $daily = Daily::all();
        return response()->json(array(
          'daily' => $daily,
        ));

      } elseif($request->ini == '0') { // 日付の各時間帯の最大測定値
        $date = $request->date_only;
        if($date) {
          $a_panel = APanel::whereDate('created_at', $date)->where('created_at', 'LIKE', "%:00:%")->get();
          $b_panel = BPanel::whereDate('created_at', $date)->where('created_at', 'LIKE', "%:00:%")->get();
  
          return response()->json(array(
            'panel_1' => $a_panel,
            'panel_2' => $b_panel,
          ));
        } 
      }
      
    }

    // ラズパイからデータ受信してDBに格納
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

