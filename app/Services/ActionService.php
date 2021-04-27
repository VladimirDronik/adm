<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 23.04.21
 * Time: 14:25
 */

namespace App\Services;

use App\Models\Action;
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


    /**
     * Отдает массив действий для события
     *
     * @param $idEvent
     * @return array
     */
    public function getForEvent($idEvent)
    {
        $actions = $this->actionRepository->getAllActionsByEvent($idEvent);

        $resultActions = [];

        foreach ($actions as $action) {

            $objectName = '';
            $nameValue = '';
            $delete = false;


            switch ($action->type) {

                case 'script':
                    $script = ScriptRepository::getNameById($action->relate);

                    if ($script != null) {
                        $nameValue = $script->name;
                    } else $delete = true;

                   break;

                case 'method':
                    $method =  MethodRepository::getMethodByID($action->relate);

                    if ($method != null) {
                        $nameValue = $method->name;
                        $objectName = ObjectRepository::getNameById($method->id_object)->name;
                    } else $delete = true;

                    break;

                case 'notification':
                    $nameValue = $action->value;
                    break;

                case 'sound':
                    $sound = SoundRepository::getNameById($action->relate);

                    if ($sound != null) {
                        $nameValue = $sound->name;
                    } else $delete = true;

                    break;

                case 'property':
                    $object = ObjectRepository::getNameById($action->relate);

                    if ($object != null) {
                        $nameValue = $action->value;
                        $objectName = $object->name;
                    } else $delete = true;

                    break;

                case 'view':
                    $view = ViewRepository::getNameById($action->relate);

                    if ($view != null) {
                        $nameValue = $action->value;
                        $objectName = $view->description;
                    } else $delete = true;

                    break;

                case 'log':
                    $nameValue = $action->value;
                    break;


            }

            //Если был где-то указан флаг, это значит, что не удалось найти связанный объект, метод, скрипт и т.д.
            // Возможно он был удален, но т.к. таблицы у нас не связаны, то в этом случае удаляем action вручную.
            if($delete) $action->delete();
            else
            $resultActions[] = ['id' => $action->id, 'type' => $action->type, 'nameValue' => $nameValue,
                'objectName' => $objectName];

        }

        return $resultActions;
    }


    public function addAction($idEvent, $actionParams)
    {

        $action = new Action();
        $action->id_event = $idEvent;
        $action->type = $actionParams->typeAction;
        $action->active = 1;

        switch ($actionParams->typeAction) {

            case 'script':
                $action->relate = $actionParams->action_script;
                break;

            case 'method':
                $action->relate = $actionParams->action_method;
                break;

            case 'notification':
                $action->value = $actionParams->action_notif;
                break;

            case 'sound':
                $action->relate = $actionParams->action_sound;
                break;

            case 'property':
                $action->relate = $actionParams->action_object;
                $action->value =  $actionParams->action_property.'='.$actionParams->action_value;
                break;

            case 'view':
                $action->relate = $actionParams->action_view;
                $action->value =  $actionParams->action_view_status;
                break;

            case 'log':
                $action->value =  $actionParams->action_log;
                break;

            default: break;

        }

        return $action->save();

    }


    public function delete($id_action)
    {
        $action = Action::findorfail($id_action);

        if($action) {
            $action->delete();
            return true;
        }

        return false;
    }




}