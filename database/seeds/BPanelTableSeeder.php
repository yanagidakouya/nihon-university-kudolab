<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BPanelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

            
        for($month=4; $month<8; $month++) {
            for($day=1; $day<32; $day++) {
                for($hour=0; $hour<24; $hour++) {
                    for($m=0; $m<2; $m++) {
                        $vol = mt_rand(10, 50);
                        $cur = mt_rand(0, 10);
                        $pow = $vol * $cur;
                        if($m == 0) { $minute = '30';}
                        if($m == 1) { $minute = '00'; }
                        DB::table('b_panels')->insert([
                            'voltage' => $vol,
                            'current' => $cur,
                            'power' => $pow,
                            'created_at' => Carbon::parse(
                                '2021-'. $month . '-' . $day . ' ' .$hour . ':' . $minute . ':00'
                            )->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::parse(
                                '2021-'. $month . '-' . $day . ' ' .$hour . ':' . $minute . ':00'
                            )->format('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }
        }
    }
}
