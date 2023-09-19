<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 14.07.20
 * Time: 9:24
 */

namespace App\Services;

use App\Models\Device;
use App\Repositories\DeviceRepository;
use Illuminate\Support\Facades\Storage;

class ConfigMegaService
{
    const LINK_PATH = '/';

    /**
     * Чтение праметров из конфига выбранного устройства
     *
     * @param  int  $idDevice - id устройства, конфиг которого интересует
     * @return array - возвращает прочтенный файл построчно в массиве
     */
    private static function readConfig($idDevice)
    {
        //Если есть файл, который соответсвует устройству
        if (Storage::disk('devices')->exists(self::LINK_PATH.$idDevice.'.cfg')) {
            $text = Storage::disk('devices')->get(self::LINK_PATH.$idDevice.'.cfg');
            $strings = preg_split('/\\r\\n?|\\n/', $text);

            return $strings;
        } else {
            Storage::disk('devices')->put(self::LINK_PATH.$idDevice.'.cfg', '');
        }

    }

    /**
     * Ищет описание порта в конфигурационном файле
     *
     * @param  array  $stringsFromConfig - конфиг устройства построчно в массиве
     * @param  int  $numPort - номер искомого порта в рамках устройства
     * @return mixed - если нашли искомый порт в конфиг файле, значит выводим его настройки в виде массива, если не
     *                 нашлии, значит такой строки нет в файле и выводим false
     */
    private static function findPort($stringsFromConfig, $numPort)
    {
        if ($stringsFromConfig) {
            foreach ($stringsFromConfig as $key => $string) {
                $stringArray = explode('&', $string);

                //если нашли порт в конфиг файле, возвращаем его настройки
                if ($stringArray[0] == 'pn='.$numPort) {
                    array_unshift($stringArray, $key);

                    return $stringArray;
                }
            }
        }

        return false;
    }

    /**
     * Ищет описание настройки устройства в конфигурационном файле
     *
     * @param  array  $stringsFromConfig - конфиг устройства построчно в массиве
     * @param  int  $numSetting - номер искомой настройки в рамках устройства
     * @return mixed - если нашли искомую настройку в конфиг файле, значит выводим его настройки в виде массива, если не
     *                 наши, значит такой строки нет в файле и выводим false
     */
    private static function findSetting($stringsFromConfig, $numSetting)
    {
        if ($stringsFromConfig) {
            foreach ($stringsFromConfig as $key => $string) {
                $stringArray = explode('&', $string);

                //если нашли порт в конфиг файле, вызвращаем его настройки
                if ($stringArray[0] == 'cf='.$numSetting) {
                    array_unshift($stringArray, $key);

                    return $stringArray;
                }
            }
        }

        return false;
    }

    /**
     * Сохранение в файл строки с новыми настройками порта
     *
     * @param  int  $idDevice - ИД устройства
     * @param  int  $numPort - номер порта в рамках устройства
     * @param  string  $params - устанавливаемые параметры порта
     * @return bool
     */
    public static function setPortSetting($idDevice, $numPort, $params)
    {
        $megaConfig = self::readConfig($idDevice);
        $foundValue = self::findPort($megaConfig, $numPort);
        $stringIntoConfig = 'pn='.$numPort.'&'.$params;

        self::saveChanges($idDevice, $megaConfig, $foundValue, $stringIntoConfig);

        return true;
    }

    /**
     * Устанавливает выбранный тип порта для физического устройства
     *
     * @param  int  $idDevice - ИД устройства для которого меняем порт
     * @param  int  $numPort - ИД порта на устройстве, тип которого меняем
     * @param $type - тип на который меняем значение
     */
    public static function setPortType($idDevice, $numPort, $type)
    {
        self::setPortSetting($idDevice, $numPort, self::prepareCommand($type));

        return true;
    }

    /**
     * Подготавливает команду для отправки на устройство
     *
     * @param  string  $type - тип порта, для которго будем отправлять команду
     * @return string
     */
    private static function prepareCommand(string $type)
    {
        switch ($type) {
            case 'IN': $paramString = 'ecmd=&af=&eth=&naf=&misc=&d=&mt=&pty=0&m=0&nr=1';
                break;

            case 'IN-P&R': $paramString = 'ecmd=&af=&eth=&naf=&misc=&d=&mt=&pty=0&m=1&nr=1';
                break;

            case 'IN-C': $paramString = 'ecmd=&af=&eth=&naf=&d=&mt=&pty=0&m=3&nr=1';
                break;

            case 'OUT': $paramString = 'grp=&pty=1&d=0&m=0&nr=1';
                break;

            case '1WIRE': $paramString = 'misc=0.00&hst=0.00&ecmd=&af=&eth=&naf=&pty=3&m=3&d=3&nr=1';
                break;

            case '1W-BUS': $paramString = 'pty=3&d=5&nr=1';
                break;

            case 'SDA': $paramString = 'misc=255&pty=4&m=1&nr=1';
                break;

            case 'SCL': $paramString = 'pty=4&m=2&nr=1';
                break;

            case 'ADC': $paramString = 'misc=0&hst=0&ecmd=&af=&eth=&naf=&pty=2&m=0&nr=1';
                break;

            case 'PWM': $paramString = 'pwm=0&d=0&grp=&misc=&pty=1&m=1&fr=1&nr=1';
                break;

            default: $paramString = 'pty=255&m=0&misc=0.00&hst=0.00&ecmd=&eth=&d=3';
                break;
        }

        return $paramString;
    }

    public function setDeviceSetting($idDevice, $numSetting, $params)
    {
        $megaConfig = $this->readConfig($idDevice);
        $foundValue = $this->findSetting($megaConfig, $numSetting);
        $stringIntoConfig = 'cf='.$numSetting.'&'.$params;

        $this->saveChanges($idDevice, $megaConfig, $foundValue, $stringIntoConfig);

        return true;
    }

    public function setPWM($idDevice, $numPort, $params)
    {

    }

    private static function saveChanges($idDevice, $megaConfig, $foundValue, $stringIntoConfig)
    {
        //Если есть запрошенный файл
        if ($foundValue) {
            //Если нашли порт с такими настройками, то удаляем его из конфига и вставляем вместо него своё
            if ($foundValue) {
                $resultArray = array_slice($megaConfig, 0, $foundValue[0], true) +
                    [$foundValue[0] => $stringIntoConfig] +
                    array_slice($megaConfig, $foundValue[0] + 1, count($megaConfig) - 1, true);

                Storage::disk('devices')->put(self::LINK_PATH.$idDevice.'.cfg', '');

                foreach ($resultArray as $string) {
                    if ($string != '') {
                        Storage::disk('devices')
                            ->append(self::LINK_PATH.$idDevice.'.cfg', $string);
                    }
                }
            } else { //Если не нашли нужный порт в файле, то добавляем порт в файл
                Storage::disk('devices')
                    ->append(self::LINK_PATH.$idDevice.'.cfg', $stringIntoConfig);
            }
        } else { //Если запрошенный файл пустой, то добавляем порт в файл
            Storage::disk('devices')->append(self::LINK_PATH.$idDevice.'.cfg', $stringIntoConfig);
        }

        //Ставим флаг, что есть изменения в конфиге для этого контроллера
        if (DeviceRepository::getDevByIdDevice($idDevice) != 'Hite-pro') {
            Device::where('id', $idDevice)->update(['changed' => 1]);
        }
    }

    /**
     * Отправить текущий конфиг на физическое устройство
     */
    public static function sendConfigToDevice($idDevice)
    {
        $countResult = 0; //количество успешных шагов
        $countAll = 0; //общее количество шагов
        $error = '';

        $ipAddress = DeviceService::getDeviceIP($idDevice);

        //Если устройство доступно
        if (DeviceService::getStatus($idDevice) == 1) {
            //Если есть файл, который соответсвует устройству
            if (Storage::disk('devices')->exists(self::LINK_PATH.$idDevice.'.cfg')) {
                $text = Storage::disk('devices')
                    ->get(self::LINK_PATH.$idDevice.'.cfg');
                $strings = preg_split('/\\r\\n?|\\n/', $text);

                foreach ($strings as $string) {
                    if ($string != '') {
                        $result = file_get_contents('http://'.$ipAddress.'/sec/?'.$string);

                        if (! $result) {
                            $countResult++;
                        }

                        $countAll++;
                    }
                }

                //Ставим флаг, что нет изменений в конфиге для этого контроллера
                Device::where('id', $idDevice)->update(['changed' => 0]);
            } else {
                $error = 'Отсутсвует конфигурационный файл для контроллера.';
            }
        } else {
            $error = 'Контроллер недоступен';
        }

        return [
            'error' => $error,
            'count_all' => $countAll,
            'count_result' => $countResult,
        ];
    }

    /**
     * Отправка конфига на все доступные контроллеры
     */
    public static function sendAllConfig()
    {
        $controllers = DeviceRepository::getAllDevicesForConfigs();

        foreach ($controllers as $controller) {
            self::sendConfigToDevice($controller->id);
        }
    }
}
