<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 05.06.20
 * Time: 17:42
 */

namespace App\Services;

use App\Models\NotificationSettings;

class NotificationService
{
    public function prepareSetting(NotificationSettings $setting, array $data)
    {

        $setting->priority = $data['priority'];
        $setting->message = trim($data['message']);
        $setting->text_flag = $data['text_flag'];
        $setting->sound_flag = $data['sound_flag'];
        $setting->id_sound = $data['id_sound'];

    }

    public function update(NotificationSettings $notifsettings, array $data): int
    {
        $this->prepareSetting($notifsettings, $data);

        $notifsettings->save();

        return $notifsettings->id;
    }
}
