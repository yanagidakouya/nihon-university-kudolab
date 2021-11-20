<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class APanelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for($i=0; $i<10000; $i++) {
            $vol = mt_rand(10, 50);
            $cur = mt_rand(0, 10);
            $pow = $vol * $cur;
            $month = mt_rand(10, 12);
            $day = mt_rand(1, 30);
            $minute = mt_rand(0, 59);
            $hour = mt_rand(0, 23);
            if($month < 10) {
                sprintf('%02d', $month);
            }
            if($minute < 10) {
                sprintf('%02d', $minute);
            }
            if($hour < 10) {
                sprintf('%02d', $hour);
            }
            if($day < 10) {
                sprintf('%02d', $day);
            }
            DB::table('a_panels')->insert([
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

        // for($month=4; $month<8; $month++) {
        //     for($day=1; $day<32; $day++) {
        //         for($hour=0; $hour<24; $hour++) {
        //             for($m=0; $m<2; $m++) {
        //                 $vol = mt_rand(10, 50);
        //                 $cur = mt_rand(0, 10);
        //                 $pow = $vol * $cur;
        //                 if($m == 0) { $minute = '30';}
        //                 if($m == 1) { $minute = '00'; }
        //                 DB::table('a_panels')->insert([
        //                     'voltage' => $vol,
        //                     'current' => $cur,
        //                     'power' => $pow,
        //                     'created_at' => Carbon::parse(
        //                         '2021-'. $month . '-' . $day . ' ' .$hour . ':' . $minute . ':00'
        //                     )->format('Y-m-d H:i:s'),
        //                     'updated_at' => Carbon::parse(
        //                         '2021-'. $month . '-' . $day . ' ' .$hour . ':' . $minute . ':00'
        //                     )->format('Y-m-d H:i:s'),
        //                 ]);
        //             }
        //         }
        //     }
        // }

    }
}
