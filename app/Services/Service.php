<?php
/**
 * Вспомогательные общие функции, которые могут быть необходимы влюбом классе
 */

namespace App\Services;

use App\Models\Alice;
use App\Models\HomeObject;
use App\Repositories\ActionRepository;
use App\Repositories\AliceDevicesRepository;
use App\Repositories\EventRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\NotificationServiceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\SoundRepository;
use App\Repositories\ViewRepository;
use App\Repositories\RoomRepository;


class Service
{

    /**
     * Транслитерация текста
     * @param $s string - входная строка
     * @return string - резульативная строка
     */
    public static function translit($s) {
        $s = (string) $s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат
    }


    /**
     * Получение списка элементов для отображения в полях страниц, которые вызываются контроллерами
     */
    public static function getListElements($idObject)
    {

        $eventRepository = new EventRepository();
        $viewRepository = new ViewRepository();
        $scriptRepository = new ScriptRepository();
        $roomRepository = new RoomRepository();
        $notificationRepository = new NotificationRepository();
        $messageService = new MessageService($notificationRepository);
        $objectRepository = new ObjectRepository();
        $aliceRepository = new AliceDevicesRepository();


        $messages = $messageService->getNotifications($idObject);
        $events = $eventRepository->getAllById($idObject);
        $sounds = SoundRepository::getAllToArray();
        $views = $viewRepository->getAllToArray();
        $rooms = $roomRepository->getAllToArray();
        $scripts = $scriptRepository->getAllToArray();
        $objects = $objectRepository->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();
        $alice = $aliceRepository->getNameAndRoomByObject($idObject);

        return [$messages, $events, $sounds, $views, $rooms, $scripts, $objects, $object_types, $alice];
    }
}