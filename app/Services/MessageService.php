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
    public function __construct(
        private NotificationRepository $notificationRep
    ) {
    }

    public function store(array $data)
    {
        $idObject = Notification::getIdByIdObject($data['object_id']);

        if ($idObject) {
            $notification = Notification::findOrFail($idObject);
        } else {
            $notification = null;
        }

        if (! $notification) {
            $notification = new Notification();
        }

        $notification->id_object = (int) $data['object_id'];

        if ($data['state'] == 'on') {
            $notification->message_1 = trim($data['message']);
            $notification->priority_1 = trim($data['priority']);
        } else {
            $notification->message_2 = trim($data['message']);
            $notification->priority_2 = trim($data['priority']);
        }

        $notification->save();

        return $data['message'];
    }

    public function getNotifications(int $idObject)
    {
        return $this->notificationRep->getByObject($idObject);
    }

    public function delete(string $message, int $id_object)
    {
        if ($message === 'on') {
            $message = 'message_1';
            $priority = 'priority_1';
        } else {
            $message = 'message_2';
            $priority = 'priority_2';
        }

        Notification::where('id_object', $id_object)
            ->update([$message => null, $priority => null]);

        return true;
    }
}
