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


        for($i=1; $i<50; $i++) {
            $vol = mt_rand(10, 50);
            $cur = mt_rand(0, 10);
            $pow = $vol * $cur;
            DB::table('a_panels')->insert([
                'voltage' => $vol,
                'current' => $cur,
                'power' => $pow,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

    }
}
