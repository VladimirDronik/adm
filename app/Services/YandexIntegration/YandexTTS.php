<?php

namespace App\Services\YandexIntegration;

use App\Models\YandexStation;
use App\Repositories\RoomRepository;
use App\Services\YandexStationService;
use Illuminate\Support\Facades\Log;

class YandexTTS {

    private $cookieFile;
    private $yandexStationService;

    public function __construct()
    {
        $this->cookieFile = base_path(config('yandex.cookie_file'));
        $this->yandexStationService = new YandexStationService(new RoomRepository ());
    }

    public function init()
    {
        $speakers = $this->getSpeakersList();
        Log::info('YandexTTS Speakers list: ' . json_encode($speakers, JSON_UNESCAPED_UNICODE));

        if (is_array($speakers) && count($speakers) > 0) {
            $this->addTTSScenarios($speakers);
            $scenarios = $this->getScenariosList();
            Log::info('YandexTTS Scenarios list: ' . json_encode($scenarios, JSON_UNESCAPED_UNICODE));
            if (is_array($scenarios) && count($scenarios) > 0) {
                $result = true;
            } else {
                $result = false;
            }
            foreach($speakers as $speaker) {
                $speaker_id = $speaker['iot_id'];
                if (is_array($scenarios) && isset($scenarios[$speaker_id])) {
                    $speaker['scenario_id'] = $scenarios[$speaker_id]['id'];
                } else {
                    $speaker['scenario_id'] = null;
                }

                $this->yandexStationService->store($speaker);
            }
        } else {
            $result = false;
        }

        return $result;
    }

    public function say($message = '', $speaker_id = null)
    {
        if ($message !== '' && $speaker_id !== null) {
            $speaker = YandexStation::where('speaker_id', $speaker_id)->first();
            if ($speaker && $speaker->scenario_id) {
                Log::info("YandexTTS Sending SAY '$message' to $speaker->name ($speaker_id)");
                return $this->sendCloudTTS($speaker_id, $speaker->scenario_id, $message, 'phrase_action');
            } else {
                Log::error("YandexStation: $speaker_id - Not Found");
            }
        }
        return false;
    }

    public function cmd($message = '', $speaker_id = null)
    {
        if ($message !== '' && $speaker_id !== null) {
            $speaker = YandexStation::where('speaker_id', $speaker_id)->first();
            if ($speaker && $speaker->scenario_id) {
                Log::info("YandexTTS Sending CMD '$message' to $speaker->name ($speaker_id)");
                return $this->sendCloudTTS($speaker_id, $speaker->scenario_id, $message, 'text_action');
            } else {
                Log::error("YandexStation: $speaker_id - Not Found");
            }
        }
        return false;
    }

    public function sendCloudTTS($iot_id, $scenario_id, $phrase, $action = 'phrase_action')
    {
        $phrase = str_replace(array('(', ')'), ' ', $phrase);
        $phrase = preg_replace('/<.+?>/u', '', $phrase);
        $phrase = preg_replace('/\s+/u', ' ', $phrase);

        if (mb_strlen($phrase, 'UTF-8') >= 100) {
            $phrase = mb_substr($phrase, 0, 99, 'UTF-8');
        }

        $nameEncode = $this->yandexEncode($iot_id);

        $payload = array( //xor2016: изменения у Яндекса
            'name' => $nameEncode,
            'icon' => 'home',
            'triggers' => array(array(
                'type' => 'scenario.trigger.voice',
                'value' => $nameEncode,
            )),
            'steps' => array(array(
                'type' => 'scenarios.steps.actions',
                'parameters' => array(
                    'requested_speaker_capabilities' => array(),
                    'launch_devices' => array(array(
                        'id' => $iot_id,
                        'capabilities' => array(array(
                            'type' => 'devices.capabilities.quasar.server_action',
                            'state' => array(
                                'instance' => $action,
                                'value' => $phrase
                            )
                        ))
                    ))
                )
            ))
        );

        $result = $this->apiRequest('https://iot.quasar.yandex.ru/m/user/scenarios/' . $scenario_id, 'PUT', $payload);

        if (is_array($result) && $result['status'] == 'ok') {
            $payload = [];

            $result = $this->apiRequest('https://iot.quasar.yandex.ru/m/user/scenarios/' . $scenario_id . '/actions', 'POST', $payload);
            Log::info('YandexTTS' . json_encode($result));

            if (is_array($result) && $result['status'] == 'ok') {
                return true;
            } else {
                Log::error('YandexTTS Error TTS-scenario execute');
            }
        } else {
            Log::error('YandexTTS Error TTS-scenario update');
        }
        return false;
    }

    public function getSpeakersList()
    {
        $result = $this->apiRequest('https://iot.quasar.yandex.ru/m/user/devices');

        if (is_array($result) && $result['status'] == 'ok') {
            $speakers = [];
            if (is_array($result['rooms'])) {
                foreach ($result['rooms'] as $room) {
                    if (is_array($room['devices'])) {
                        foreach($room['devices'] as $device) {
                            if (preg_match('/^devices.types.smart_speaker/uis', $device['type'])) {
                                $speakers[$device['id']] = [
                                    'name' => $device['name'],
                                    'room' => $room['name'],
                                    'platform' => $device['quasar_info']['platform'],
                                    'device_id' => $device['quasar_info']['device_id'],
                                    'iot_id' => $device['id'],
                                ];
                            }
                        }
                    }
                }
            }
            if (is_array($result['speakers'])) {
                foreach($result['speakers'] as $device) {
                    $speakers[$device['id']] = [
                        'name' => $device['name'],
                        'room' => 'unknown',
                        'platform' => $device['quasar_info']['platform'],
                        'device_id' => $device['quasar_info']['device_id'],
                        'iot_id' => $device['id'],
                    ];
                }
            }

            return $speakers;
        } else {
            Log::error('YandexTTS Error get speakers list: ' . json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        return false;
    }

    public function getScenariosList()
    {
        $result = $this->apiRequest('https://iot.quasar.yandex.ru/m/user/scenarios');

        if (is_array($result) && $result['status'] == 'ok') {
            if (is_array($result['scenarios'])) {
                $scenarios = [];
                foreach($result['scenarios'] as $scenario) {
                    if (mb_strpos($scenario['name'], 'ТО') !== false) {
                        $scenarios[$this->yandexDecode($scenario['name'])] = [
                            'id' => $scenario['id'],
                            'name' => $scenario['name'],
                            'speaker' => $scenario['devices'][0],
                        ];
                    }
                }
                return $scenarios;
            }
        } else {
            Log::error('YandexTTS Error get TTS-scenarios list: ' . json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        return false;
    }

    public function addTTSScenario($speaker_id)
    {
        $nameEncode = $this->yandexEncode($speaker_id);

        $payload = array( //xor2016: изменения у Яндекса
            'name' => $nameEncode,
            'icon' => 'home',
            'triggers' => array(array(
                'type' => 'scenario.trigger.voice',
                'value' => mb_substr($nameEncode, 4),
            )),
            'steps' => array(array(
                'type' => 'scenarios.steps.actions',
                'parameters' => array(
                    'requested_speaker_capabilities' => array(),
                    'launch_devices' => array(array(
                        'id' => $speaker_id,
                        'capabilities' => array(array(
                            'type' => 'devices.capabilities.quasar.server_action',
                            'state' => array(
                                'instance' => 'phrase_action',
                                'value' => 'Сценарий для МДМ. НЕ УДАЛЯТЬ!'
                            )
                        ))
                    ))
                )
            ))
        );

        // !!!!!!!!!!
        // $result = $this->apiRequest('https://iot.quasar.yandex.ru/m/v2/user/scenarios/', 'POST', $payload);

        $result = $this->apiRequest('https://iot.quasar.yandex.ru/m/user/scenarios/', 'POST', $payload);

        if (is_array($result) && $result['status'] == 'ok') {
            return true;
        } else {
            Log::error('YandexTTS Error create TTS-scenario: ' . json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        return false;
    }

    public function addTTSScenarios($speakers = [])
    {
        foreach($speakers as $speaker) {
            $this->addTTSScenario($speaker['iot_id']);
        }
    }

    private function getTokenFromQuasar()
    {
        // $token = "y0_AgAEA7qj4yloAAoqBwAAAADneetRn6P9Js2CQgCYmvN3sJkI_gdMEm8";
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookieFile);
        curl_setopt($curl, CURLOPT_URL, 'https://yandex.ru/quasar/iot');
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        if (preg_match('/"csrfToken2":"(.+?)"/', $result, $m)) {
            $token = $m[1];
            return $token;
        } else {
            Log::error('YandexTTS Error get CSRF-token');
            return false;
        }
    }

    private function apiRequest($url, $method = 'GET', $params = 0)
    {
        $token = $this->getTokenFromQuasar();

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookieFile);

        if ($method == 'GET') {
            curl_setopt($curl, CURLOPT_POST, false);
        } else {
            $header = [
                'Content-type: application/json',
                'x-csrf-token:' . $token
            ];
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

            if ($method != 'POST') {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            } else {
                curl_setopt($curl, CURLOPT_POST, true);
            }
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);

        $data = json_decode($result, true);

        return $data;
    }

    private function yandexEncode($in)
    {
        $in = strtolower($in);
        $MASK_EN = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', '-');
        $MASK_RU = array('о', 'е', 'а', 'и', 'н', 'т', 'с', 'р', 'в', 'л', 'к', 'м', 'д', 'п', 'у', 'я', 'ы');
        return 'ТО ' . str_replace($MASK_EN, $MASK_RU, $in);
    }

    private function yandexDecode($in)
    {
        $in = str_replace('ТО ', '', $in);
        $MASK_EN = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', '-');
        $MASK_RU = array('о', 'е', 'а', 'и', 'н', 'т', 'с', 'р', 'в', 'л', 'к', 'м', 'д', 'п', 'у', 'я', 'ы');
        return str_replace($MASK_RU, $MASK_EN, $in);
    }
}
