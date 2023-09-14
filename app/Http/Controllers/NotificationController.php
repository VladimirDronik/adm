<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notifsettings\UpdateRequest;
use App\Models\NotificationSettings;
use App\Repositories\NotificationServiceRepository;
use App\Repositories\SoundRepository;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationServiceRepository $notification_rep,
        private NotificationService $service)
    {}

    public function index()
    {
        $notifications = $this->notification_rep->getAll();

        return view('notifications.index', compact('notifications'));
    }

    public function edit(NotificationSettings $notification)
    {
        $priority = [1 => 'Важное', 2 => 'Обычное'];
        $text_flag = [1 => 'Включено', 0 => 'Выключено'];
        $sound_flag = [1 => 'Включено', 0 => 'Выключено'];

        $sounds = SoundRepository::getAllToArray();

        return view('notifications.edit', compact('notification', 'priority',
            'text_flag', 'sound_flag', 'sounds'));
    }

    public function update(UpdateRequest $r, int $id)
    {
        $notifsetting = NotificationSettings::findOrFail($id);

        try {
            if ($this->service->update($notifsetting, $r->except('_token'))) {
                return redirect()->route('notifications.edit', [$notifsetting->id])
                    ->with('success', 'Настройки успешно изменены');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении настроек '.$notifsetting->id
                .' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении настроек');
    }

}
