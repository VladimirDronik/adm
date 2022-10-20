<?php

use Illuminate\Database\Seeder;
use App\Models\HomeObject;
use Faker\Factory;
use App\Models\Script;
use App\Models\Device;

class FakeMethodsTableSeeder extends Seeder
{
    private $faker;
    private $names;
    private $scripts;
    private $devices;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->names = $this->getNames();
        $this->scripts = Script::all();
        $this->devices = Device::all();
    }

    public function getRandEasy()
    {
        $device = $this->devices[rand(0, count($this->devices)-1)];
        $port = $device->ports[rand(0, count($device->ports)-1)];

        return $device->id.';'.$port->num_port.':'.rand(0,2);
    }

    public function getRandScriptId()
    {
        return $this->scripts[rand(0, count($this->scripts)-1)]->id;
    }

    public function getMethods()
    {
        $methods = [];

        $objects = HomeObject::orderBy('id')->limit(10)->get();

        foreach ($objects as $object) {
            $method_count = rand(1, 3);
            for ($i = 0; $i < $method_count; $i++) {
                $name = $this->names[rand(0, count($this->names)-1)];

                if (rand(0,10) > 6) {
                    $script = null;
                    $easy = $this->getRandEasy();
                } else {
                    $script = $this->getRandScriptId();
                    $easy = null;
                }

                $methods[] = [
                    'id_object' => $object->id,
                    'comment' => $name,
                    'name' => $name,
                    'script' => $script,
                    'easy' => $easy
                ];
            }
        }

        return $methods;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('methods')->insert($this->getMethods());
    }

    public function getNames()
    {
        return [
            'Вкл/выкл свет Гостиная.Вход',
            'Вкл/выкл свет Гостиная.Диван',
            'Вкл/выкл свет Гостиная.Стол',
            'Вкл/выкл свет Кухня.Основной',
            'Вкл/выкл свет Кухня.Бар',
            'Вкл/выкл свет Прихожая',
            'Вкл/выкл свет Спальня',
            'Вкл/выкл свет с/у',
            'Вкл/выкл свет котельная',
            'Вкл/выкл вентилятор с/у',
            'Вкл/выкл кондиционер гостиная',
            'Вкл/выкл свет лестница',
            'Вкл/выкл свет коридор',
            'Вкл/выкл свет балкон',
            'Вкл/выкл свет спальня правая',
            'Вкл/выкл свет спальня левая',
            'Вкл/выкл свет гардероб основной',
            'Вкл/выкл свет гардероб подсветка',
            'Вкл/выкл свет крыльцо',
            'Вкл/выкл свет фасад',
            'Вкл котел',
            'Выкл котел',
            'Проверка термостата'
        ];
    }
}
