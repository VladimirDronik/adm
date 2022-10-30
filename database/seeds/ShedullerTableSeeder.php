<?php

use Illuminate\Database\Seeder;
use App\Models\SchedulerPoint;
use App\Models\SchedulerTask;
use App\Repositories\ScriptRepository;

class ShedullerTableSeeder extends Seeder
{

    private $shedullerPoint;
    private $shedullerTask;

    public function __construct()
    {
        $this->shedullerTask = SchedulerTask::pluck('name')->toArray();
    }



    private static function getTasks() {

        return [
            [
                'name' => 'Удаление старых логов',
                'script' => ScriptRepository::getIdByLink('delete_logs.php')['id'],
                'is_hidden' => 1,
                'is_system' => 1,
                'active' => 1
            ],

            [
                'name' => 'Удаление старых данных из таблицы графиков',
                'script' => ScriptRepository::getIdByLink('reset_graphs.php')['id'],
                'is_hidden' => 1,
                'is_system' => 1,
                'active' => 1

            ]
        ];


    }


    private static function getPoints() {

        return [
            [
                'name' => 'Удаление старых логов',
                'type' => 'w',
                'time' => '00:00',
                'days' => '0,1,2,3,4,5,6',
                'close' => 1,
                'system' => 1
            ]
        ];

    }




    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks =  self::getTasks();
        $points = self::getPoints();

        foreach ($tasks as $task) {

            if (!in_array($task['name'], $this->shedullerTask, true)) {

                $newTask = new SchedulerTask();
                $newTask->fill($task);
                $newTask->save();

                foreach ($points as $point) {

                    if($point['name'] == $task['name']) {

                        unset($point['name']);
                        $point['id_task'] = $newTask->id;

                        $newPoint = new SchedulerPoint();
                        $newPoint->fill($point);

                        $newPoint->save();

                    }


                }

            }
        }


    }
}
