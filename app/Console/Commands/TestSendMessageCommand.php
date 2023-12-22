<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class TestSendMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-send-message-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new Client();
        $response = $client->post("http://phonywar.com:20080/api/messages/send", [
            'form_params' => [
                "receiver_number" => "1111111111",
                "content" => "FAKE CONTENT"
            ]
        ]);

        var_dump($response->getBody()->getContents());
    }
}
