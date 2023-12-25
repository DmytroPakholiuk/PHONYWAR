<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

/**
 * This is an auxiliary command to use to ensure that Redis is connected correctly.
 * Bears no usefull business-logic whatsoever
 */
class RedisTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:redis-test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Just to test whether it works or not';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Redis::set("0000000000", "YES");
        Redis::expire("0000000000", 20);
        var_dump(Redis::get("0000000000"));
//        var_dump(Redis::getName());
        var_dump(Redis::ping());

        Redis::lpush("0000000001", "VALUE");
        var_dump(Redis::lrange("0000000001", 0, -1));
        var_dump(Redis::lrange("0000000002", 0, -1));



//        $redis = Redis::connections();
//        var_dump($redis);
    }
}
