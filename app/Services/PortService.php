<?php

namespace App\Services;

use App\Models\Port;

class PortService {

    public function updateComment(array $data)
    {
        $comment = trim($data['comment']);

        if ($comment === 'Отсутствует') {
            $comment = '';
        }

        Port::where('id', $data['port_id'])
            ->where('id_device', $data['device_id'])->update([
                'comment' => $comment
            ]);
    }
}