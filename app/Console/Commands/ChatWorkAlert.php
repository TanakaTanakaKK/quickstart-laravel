<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class ChatWorkAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:chat-work-alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'chat-work-alert';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new Client();
        $tasks = Task::where('expired_at', '>=', today()->addDay())
            ->where('expired_at', '<=', today()->addDay(2))
            ->get();
    
        $alert_message = '明日が期限のタスクが'.count($tasks).'件あります。'."\n";
        foreach($tasks as $task){
            $alert_message = $alert_message.' ・'.$task->name."\n";
        }
    
        $client->post(config('services.chat_work.url'), [
            'headers' => ['X-ChatWorkToken' => config('services.chat_work.token')],
            'form_params' => ['body' => $alert_message]
        ]);
    }
}
