<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 24.05.20
 * Time: 7:09
 */

namespace App\Services;


use App\Models\Notification;
use App\Repositories\NotificationRepository;


class MessageService
{

    private $notificationRep;

    public function __construct(NotificationRepository $notificationRep) {

        $this->notificationRep = $notificationRep;

    }



    public function store(array $data)
    {
        $idObject = Notification::getIdByIdObject($data['object_id']);

        if ($idObject)
        $notification = Notification::findOrFail(Notification::getIdByIdObject($idObject));
        else
            $notification = null;


        if(!$notification)
        $notification = new Notification();

        $notification->id_object = (int)$data['object_id'];

        if ($data['state'] == 'on') {
            $notification->message_on = trim($data['message']);
            $notification->priority_on = trim($data['priority']);
        } else {
            $notification->message_off = trim($data['message']);
            $notification->priority_off = trim($data['priority']);
        }

        $notification->save();


        return $data['message'];

    }

    public function getNotifications(int $idObject) {

        return $this->notificationRep->getByObject($idObject);
    }

    public function delete(string $message, int $id_object)
    {

        if ($message === 'on') {
            $message = 'message_on';
            $priority = 'priority_on';
        }
        else {
            $message = 'message_off';
            $priority = 'priority_off';
        }

        Notification::where('id_object', $id_object)->update([$message => null, $priority => null]);

        return true;
    }
}