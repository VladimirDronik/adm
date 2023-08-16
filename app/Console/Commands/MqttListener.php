<?php

use Illuminate\Console\Command;
use PhpMqtt\Client\MQTTClient;

class MqttListener extends Command
{
    protected $signature = 'mqtt:listen';
    protected $description = 'Listen to MQTT topic';

    public function handle()
    {
        pcntl_async_signals(true);

        $mqtt = new MQTTClient(config('mqtt.host'), config('mqtt.port'), config('mqtt.client_id'));

        pcntl_signal(SIGINT, function (int $signal, $info) use ($mqtt) {
            $mqtt->interrupt();
        });

        $mqtt->connect();

        $mqtt->subscribe('your/mqtt/topic', function ($topic, $message) {
            $this->info("Received MQTT message on topic '$topic': $message");
            //todo Логика записи полученных данных из топика
        });

        $mqtt->loop(true);

        // $mqtt->connect();
        // $mqtt->publish('php-mqtt/client/test', 'Что хотим опубликовать в топик', 0);
        // $mqtt->disconnect();
    }
}
