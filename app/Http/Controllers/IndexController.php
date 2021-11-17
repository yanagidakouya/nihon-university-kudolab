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
          $sensI_1 = $ary[0];
          $sensV_1 = $ary[1];
          $sensI_2 = $ary[2];
          $sensV_2 = $ary[3];
          list($current_1, $voltage_1, $current_2, $voltage_2) = $this->measured_value_calculation($sensI_1, $sensV_1, $sensI_2, $sensV_2);
          $power_1   = $current_1 * $voltage_1;
          $power_2   = $current_2 * $voltage_2;
          // TODO sensI_1とsensI_2の値は÷10が必要？？（オームの法則で10オームの抵抗分を割る）
          $a_panel = APanel::create([
            'current' => $current_1,
            'voltage' => $voltage_1,
            'power' => $power_1,
          ]);
          $b_panel = BPanel::create([
            'current' => $current_2,
            'voltage' => $voltage_2,
            'power' => $power_2,
          ]);
        } else {
          return;
        }
      }
      return response()->json($request);
    }

    public function measured_value_calculation ($sensI_1, $sensV_1, $sensI_2, $sensV_2) {
      $current_1 = (intval($sensI_1) * 5 ) / 10230;
      $voltage_1 = (intval($sensV_1) * 5 * 1333) / (1023 * 333);
      $current_2 = (intval($sensI_2) * 5 ) / 10230;
      $voltage_2 = (intval($sensV_2) * 5 * 1333) / (1023 * 333);
      return [$current_1, $voltage_1, $current_2, $voltage_2];

    }
}

