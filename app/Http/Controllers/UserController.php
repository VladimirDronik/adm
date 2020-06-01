<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\DevuserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\UpdateRequest;

class UserController extends Controller
{
    private $devuser_rep;
    private $service;

    public function __construct(DevuserRepository $devuser_rep, UserService $service)
    {
        $this->devuser_rep = $devuser_rep;
        $this->service = $service;
    }

    public function index()
    {
        $devusers = $this->devuser_rep->getAll();

        return view('users.index', compact('devusers'));
    }

    public function create()
    {
        $priority = [1 => 'Важные', 2 => 'Обычные' ];
        return view('users.create', compact('priority'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('users.index', [$id])
                    ->with('success', 'Пользователь успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении пользователя' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении параметра');
    }

    public function edit(User $user)
    {
        $priority = [1 => 'Важные', 2 => 'Обычные', 3 => 'Все', 0 => 'Не назначено' ];
        return view('users.edit', compact('user', 'priority'));
    }

    public function update(UpdateRequest $r, User $user)
    {

        try {
            if ($this->service->update($user, $r->except('_token'))) {
                return redirect()->route('users.index')
                    ->with('success', 'Пользователь успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении пользователя '.$user->id
                .' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении пользователя');
    }

}
