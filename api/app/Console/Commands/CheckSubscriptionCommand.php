<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatusEnum;
use App\Jobs\CheckSubscriptions;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckSubscriptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all of subscriptions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $subscriptions = Subscription::where('expire_date', '<', Carbon::now()
            ->setTimezone('America/New_York')
            ->format('Y-m-d H:i:s T'))
            ->where('status', '!=', SubscriptionStatusEnum::CANCELED)
            ->cursor();

        if ($subscriptions->isNotEmpty()) {
            $subscriptions->each(function (Subscription $subscription) {
                CheckSubscriptions::dispatch($subscription)->onQueue('check_subscriptions');
            });
        }
    }
}
