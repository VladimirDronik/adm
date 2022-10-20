<?php

use Illuminate\Database\Seeder;

class FakeObjectsTableSeeder extends Seeder
{
    public function getObjects()
    {
        return [
            ['name' => 'Гостиная.Выключатель (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'Гостиная.Диван.Выключатель (свет)', 'type' => 'button', 'status' => 'off'],
            ['name' => 'Гостиная.Стол.Выключатель (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'Кухня.Основной.Выключатель (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'Кухня.Бар.Выключатель (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'Прихожая.Выключатель (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'Спальня.Выключатель (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'С/у.Выключатель.1 (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'С/у.Выключатель.2 (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'Котельная.Выключатель (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'Лестница.Выключатель (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'Коридор_2-й_этаж.Выключатель (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'Балкон.Выключатель (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'Спальня.Правая.Выключатель (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'Спальня.Левая.Выключатель (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'Гардероб.Основной.Выключатель (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'Гардероб.Подсветка.Выключатель (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'Крыльцо.Выключатель (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'Фасад.Выключатель (свет)', 'type' => 'button', 'status' => ''],
            ['name' => 'Гостиная.Вход.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => 'Гостиная.Диван.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => 'Гостиная.Стол.Свет', 'type' => 'lamp', 'status' => ''],
            ['name' => 'Кухня.Основной.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => 'Кухня.Бар.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => 'Прихожая.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => 'Спальня.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => 'С/у.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => 'Котельная.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => 'С/у.Вентилятор', 'type' => 'socket', 'status' => 'off'],
            ['name' => 'Гостиная.Кондиционер', 'type' => 'socket', 'status' => 'off'],
            ['name' => 'Лестница.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => 'Коридор.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => 'Балкон.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => 'Спальня.Правая.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => 'Спальня.Левая.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => 'Гардероб.Основной.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => 'Гардероб.Подсветка.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => 'Крыльцо.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => 'Фасад.Свет', 'type' => 'lamp', 'status' => 'off'],
            ['name' => '1-й этаж.Датчик_температуры', 'type' => 'temp', 'status' => 'off'],
            ['name' => 'Котел.Реле', 'type' => 'socket', 'status' => 'off'],
            ['name' => 'Тестовый объект 1', 'type' => 'Motion_sens', 'status' => 'off'],
            ['name' => 'Тестовый объект 2', 'type' => 'count', 'status' => 'off'],
            ['name' => 'Тестовый объект 3', 'type' => 'IR_transmitter', 'status' => 'off'],
            ['name' => 'Тестовый объект 4', 'type' => 'pass_sensor', 'status' => 'off'],
            ['name' => 'Тестовый объект 5', 'type' => 'dry_contact', 'status' => 'off'],
            ['name' => 'Тестовый объект 6', 'type' => 'relay', 'status' => 'off'],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("ALTER TABLE objects AUTO_INCREMENT = 1;");
        DB::table('objects')->insert($this->getObjects());
    }
}
