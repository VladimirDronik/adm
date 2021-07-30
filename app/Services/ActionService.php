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
     * Если событие указано, то достаем actions для него из БД, если не указано, то берем actions из $tempActions
     * @param $idEvent
     * @return array
     */
    public function getForEvent($idEvent, $tempActions)
    {
        if($idEvent)
            $actions = $this->actionRepository->getAllActionsByEvent($idEvent);
        else {
            //Берем все значения из $tempActions и с помощью
            foreach ($tempActions AS $tempAction)
            $actions[] = $this->prepareAction($tempAction);
        }

        $resultActions = $this->fillActionValues($actions);

        return $resultActions;
    }



    /**
     * Заполнение $nameValue, $objectName для action
     */
    public function fillActionValues($actions)
    {
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

                case 'alice':
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
        $action = $this->prepareAction($actionParams, $idEvent);
        return $action->save();
    }


    /**
     * Подготовка параметров action для записи в БД. Если idEvent не указан, то поготовливаем данные для отображения
     * временного action при создании события (в том случае, если idEvent еще неизвестен)
     * @param $actionParams
     * @param null $idEvent
     * @return Action
     */
    private function prepareAction($actionParams, $idEvent = null)
    {
        $action = new Action();
        $action->id_event = $idEvent;
        $action->type = $actionParams['typeAction'];
        $action->active = 1;

        switch ($actionParams['typeAction']) {

            case 'script':
                $action->relate = $actionParams['action_script'];
                break;

            case 'method':
                $action->relate = $actionParams['action_method'];
                break;

            case 'notification':
                $action->value = $actionParams['action_notif'];
                break;

            case 'sound':
                $action->relate = $actionParams['action_sound'];
                break;

            case 'property':
                $action->relate = $actionParams['action_object'];
                $action->value =  $actionParams['action_property'].'='.$actionParams['action_value'];
                break;

            case 'view':
                $action->relate = $actionParams['action_view'];
                $action->value =  $actionParams['action_view_status'];
                break;

            case 'log':
                $action->value =  $actionParams['action_log'];
                break;

            case 'alice':
                $action->value = $actionParams['action_alice'];
                $action->params = $actionParams['action_selected_stations'];
                if($actionParams['action_type_alice_action'] == 'say')
                    $action->relate = 1;
                else
                    $action->relate = 2;

            default: break;

        }

        return $action;

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

    /**
     * Создание
     * @param $tempActions
     * @param $idEvent
     */
    public function createActionsByTempActions($tempActions, $idEvent)
    {
        //Добавляем actions из массива tempActions
        foreach ($tempActions as $tempAction) {
            $this->addAction($idEvent, $tempAction);
        }

    }



}