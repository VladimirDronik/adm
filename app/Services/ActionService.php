<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 23.04.21
 * Time: 14:25
 */

namespace App\Services;

use App\Models\Method;
use App\Repositories\ActionRepository;
use App\Repositories\MethodRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\NotificationServiceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\SoundRepository;
use App\Repositories\ViewRepository;

class ActionService
{
    private $actionRepository;


    public function __construct(ActionRepository $actionRepository)
    {
        $this->actionRepository = $actionRepository;
    }

    public function getForEvent($idEvent)
    {
        $actions = $this->actionRepository->getAllActionsByEvent($idEvent);

        $resultActions = [];

        foreach ($actions as $action) {

            $objectName = '';

            switch ($action->type) {

                case 'script':
                   $nameValue = ScriptRepository::getNameById($action->value)->name;
                   break;

                case 'method':
                    $method =  MethodRepository::getMethodByID($action->value);
                    $nameValue = $method->name;
                    $objectName = ObjectRepository::getNameById($method->id_object);
                    break;

                case 'notification':
                    $nameValue = $action->value;
                    break;

                case 'sound':
                    $nameValue = SoundRepository::getNameById($action->value);
                    break;

                case 'property':
                    $nameValue = $action->params;
                    $objectName = ObjectRepository::getNameById($action->value);
                    break;

                case 'view':
                    $nameValue = $action->params;
                    $objectName = ViewRepository::getNameById($action->value);
                    break;

                case 'log':
                    $nameValue = $action->value;
                    break;
                

            }


            $resultActions[] = ['id' => $action->id, 'type' => $action->type, 'nameValue' => $nameValue,
                'objectName' => $objectName];

        }

        return $resultActions;
    }




}