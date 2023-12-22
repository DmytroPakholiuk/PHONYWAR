<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

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
        Redis::set("name1", []);
        $oldValue = Redis::get("name1");
        Redis::set("name1", array_merge($oldValue, ["Yes"]));
        var_dump(Redis::get("name1"));
//        var_dump(Redis::getName());


//        $redis = Redis::connections();
//        var_dump($redis);
    }
}