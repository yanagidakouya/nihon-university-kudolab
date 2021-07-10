<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Daily;
use App\Models\APanel;
use App\Models\BPanel;
use Carbon\Carbon;

class DailyMaxPower extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:max-power';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '日付が変わったら前日の最大電力をdailiesテーブルに書き込みます。';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $yesterday = new Carbon('yesterday');
        $today = Carbon::now();
        $yesterday = $yesterday->format('Y-m-d');
        $today = $today->format('Y-m-d');
        $panel_1_max_power = APanel::where('created_at', 'LIKE', "%$yesterday%")->max('power');
        $panel_2_max_power = BPanel::where('created_at', 'LIKE', "%$yesterday%")->max('power');
        
        Daily::create([
            'panel_1_max_power' => $panel_1_max_power,
            'panel_2_max_power' => $panel_2_max_power,
            'date' => $today,
        ]);
        $this->info( $panel_1_max_power . ',' . $panel_2_max_power );
        Log::info( $panel_1_max_power . ',' . $panel_2_max_power );
    }
}
