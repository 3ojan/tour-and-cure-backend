<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WebsocketsTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websockets:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send message through websockets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $text = $this->ask("What to write to websockets?");
        $user = \App\Models\User::find(1);
        $message = \App\Models\Message::create([
            'user_id' => $user->id,
            'message' => $text
        ]);

        broadcast(new \App\Events\MessageSent($user, $message))->via('pusher');
    }
}
