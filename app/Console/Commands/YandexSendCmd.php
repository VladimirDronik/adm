<?php

namespace App\Console\Commands;

use App\Services\YandexIntegration\YandexTTS;
use Illuminate\Console\Command;

class YandexSendCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yandexstation:cmd {speakerId} {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send CMD to Yandex station';

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
        $tts = new YandexTTS();

        $ttsStatus = $tts->cmd($this->argument('message'), $this->argument('speakerId'));

        if ($ttsStatus) {
            $this->info('CMD sent successfully');
        } else {
            $this->error('Error sending CMD to Yandex station. For details check laravel.log');
        }
    }
}
