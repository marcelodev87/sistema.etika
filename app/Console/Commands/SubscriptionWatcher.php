<?php

namespace App\Console\Commands;

use App\ClientSubscription;
use App\ClientSubscriptionTask;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SubscriptionWatcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watcher:subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $subscriptions = ClientSubscription::where('active', 1)->get();
        foreach ($subscriptions as $sub) {
            $now = Carbon::now();
            $month = str_pad($now->month, 2, '0', STR_PAD_LEFT);
            $year = $now->year;
            $counter = ClientSubscriptionTask::where('client_id', $sub->client_id)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();
            if(!$counter){
                $tasks = json_decode($sub->subscription->tasks, true);
                $time = explode(':', $tasks['delay']);
                foreach ($tasks['tasks'] as $t){
                    $limit = Carbon::now()->hour($time[0])->minute($time[1])->second(0);
                    $new = ClientSubscriptionTask::create([
                        'client_subscription_id' => $sub->id,
                        'client_id' => $sub->client_id,
                        'user_id' => $t['responsible'],
                        'task_id' => $t['task'],
                        'end_at' => $limit->addDays($t['delay']),
                    ]);
                }
            }
        }
    }
}
