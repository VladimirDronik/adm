<?php

namespace App\Http\Controllers;

use App\Models\Count;
use App\Repositories\CountRepository;
use App\Services\CountService;

class CountController extends Controller
{
    private $count_rep;
    private $service;

    public function __construct(CountRepository $count_rep, CountService $service)
    {
        $this->count_rep = $count_rep;
        $this->service = $service;
    }

    public function index()
    {
        $counts = $this->count_rep->getAll();

        return view('counts.index', compact('counts'));
    }

    public function create()
    {
        $images = ImageService::getSceneImages();

        return view('counts.create', compact('images'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('counts.edit',[$id])->with('success', 'Счетчик успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении счетчика ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении счетчика');
    }

    public function edit(Count $count)
    {
        $images = ImageService::getSceneImages();

        return view('counts.edit', compact('count', 'images'));
    }

    public function update(UpdateRequest $r, Count $count)
    {
        try {
            if ($this->service->update($count, $r->except('_token'))) {
                return redirect()->route('counts.edit',[$count->id])->with('success', 'Счетчик успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении счетчика '.$count->id.' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении счетчика');
    }
}
