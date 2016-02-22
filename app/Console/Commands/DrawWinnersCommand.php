<?php

namespace App\Console\Commands;

use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DrawWinnersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'winners:draw';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get\'s called every minute to draw the winners of each group';

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
        $currentTime = Carbon::now();
        $groupsToDraw = Group:: where('interval_time_start', '<=', $currentTime->format('H:i:s'))
                                ->where('interval_time_end', '>=', $currentTime->format('H:i:s'))
                                ->whereExists(function ($query) {
                                    $query  ->select(DB::raw(1))
                                        ->from('draws')
                                        ->where('draws.group_id = groups.id')
                                        ->whereRaw('draws.created_at < DATE_SUB(NOW(), INTERVAL groups.interval_minutes MINUTE)');
                                })
                                ->get();

        if (!$groupsToDraw->isEmpty()) {
            foreach ($groupsToDraw as $group) {
                $group->drawWinners();
            }
        }
        // notify winners depending on group type
    }
}
