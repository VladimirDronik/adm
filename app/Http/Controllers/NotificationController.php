<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\NotificationSettings;
use App\Repositories\SoundRepository;
use App\Services\NotificationService;
use App\Http\Requests\Notifsettings\UpdateRequest;
use App\Repositories\NotificationServiceRepository;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationServiceRepository $notificationRep,
        private NotificationService $service
    ) {
    }

    public function index()
    {
        $notifications = $this->notificationRep->getAll();

        return view('notifications.index', compact('notifications'));
    }

    public function edit(NotificationSettings $notification)
    {
        $priority = [1 => 'Важное', 2 => 'Обычное'];
        $text_flag = [1 => 'Включено', 0 => 'Выключено'];
        $sound_flag = [1 => 'Включено', 0 => 'Выключено'];

        $sounds = SoundRepository::getAllToArray();

        return view('notifications.edit', compact(
            'notification', 'priority', 'text_flag', 'sound_flag', 'sounds'
        ));
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
            Log::error(
                'Ошибка при изменении настроек '.$notifsetting->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении настроек');
    }
}
