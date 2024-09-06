<?php

namespace App\Console\Commands;

use App\Services\YandexIntegration\YandexAuth;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RefreshYaCookieAndToken extends Command
{
    private $yandexAuth;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:yandex_auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление куки и токена авторизации яндекс';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->yandexAuth = new YandexAuth();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cookieFile = base_path(config('yandex.cookie_file'));
        $tokenFile = base_path(config('yandex.token_file'));
        $message = null;

        if (file_exists($tokenFile)) {
            $data = json_decode(file_get_contents($tokenFile), true);
            if (array_key_exists('x_token', $data)) {
                $status = $this->yandexAuth->loginToken($data['x_token'], $cookieFile);
                if ($status['code'] == 200) {
                    $message = 'Куки';
                    $status = $this->yandexAuth->getXToken($cookieFile);
                    if ($status['code'] == 200) {
                        $message .= ' и Токен';
                    }
                }
            }
        }

        Log::info($message ? ($message.' яндекса обновлены.') : 'Не удалось обновить токен и куки яндекса. Проверьте авторизацию яндекса.');
    }
}
