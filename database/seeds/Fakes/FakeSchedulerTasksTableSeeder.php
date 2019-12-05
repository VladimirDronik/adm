<?php

use Illuminate\Database\Seeder;
use App\Models\HomeObject;
use App\Models\Script;
use App\Models\SchedulerTask;
use App\Models\SchedulerPoint;

class FakeSchedulerTasksTableSeeder extends Seeder
{
    const COUNT = 10;

    private $scripts;
    private $objects;

    public function __construct()
    {
        $this->scripts = Script::all();
        $this->objects = HomeObject::with('methods')->whereHas('methods')->get();
    }

    public function getRandScriptId()
    {
        return $this->scripts[rand(0, count($this->scripts)-1)]->id;
    }

    public function getRandObject()
    {
        return $this->objects[rand(0, count($this->objects)-1)];
    }

    public function getRandObjectMethodId($object)
    {
        return $object->methods[rand(0, count($object->methods)-1)]->id;
    }

    public function createSchedulerTask($index)
    {
        $task = new SchedulerTask();

        if ($index % 2) {
            $task->script = $this->getRandScriptId();
        } else {
            $object = $this->getRandObject();
            $task->object = $object->id;
            $task->method = $this->getRandObjectMethodId($object);
        }

        $task->is_hidden = rand(0, 1);
        $task->is_system = rand(0, 1);
        $task->name = 'Тестовое событие '.($index + 1);

        $task->save();

        if ($index < self::COUNT / 2) {
            $this->createMinutePoint($task->id);
            if ($index === 0) {
                $this->createDayPoint($task->id);
                $this->createMonthPoint($task->id);
                $this->createYearPoint($task->id);
            }
        }
    }

    public function createMinutePoint($task_id) {
        $point = new SchedulerPoint();

        $point->id_task = $task_id;
        $point->system = 0;
        $point->close = 0;
        $point->type = 'c';
        $point->time = '5';
        $point->days = '';

        $point->save();
    }

    public function createDayPoint($task_id) {
        $point = new SchedulerPoint();

        $point->id_task = $task_id;
        $point->system = 0;
        $point->close = 0;
        $point->type = 'w';
        $point->time = '10:10';
        $point->days = '1,2,6';

        $point->save();
    }

    public function createMonthPoint($task_id) {
        $point = new SchedulerPoint();

        $point->id_task = $task_id;
        $point->system = 0;
        $point->close = 0;
        $point->type = 'm';
        $point->time = '12:10';
        $point->days = '4,17,18';

        $point->save();
    }

    public function createYearPoint($task_id) {
        $point = new SchedulerPoint();

        $point->id_task = $task_id;
        $point->system = 0;
        $point->close = 0;
        $point->type = 'y';
        $point->time = '02:20';
        $point->days = '20.12,25.12,22.05';

        $point->save();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < self::COUNT; $i++) {
            $this->createSchedulerTask($i);
        }
    }
}
