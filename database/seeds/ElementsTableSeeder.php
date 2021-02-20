<?php

use Illuminate\Database\Seeder;
use App\Models\Elements;

class ElementsTableSeeder extends Seeder
{

    private $elements;

    public function __construct()
    {
        $this->elements = Elements::pluck('name')->toArray();
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */

        public function run()
    {
        $elements = [
            /////////////////page_1///////////////////////
            [
                'name' => 'Подача',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "24&#176;С"}]',
                'page' => 1,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 1
            ],
            [
                'name' => 'Обратка',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "18&#176;С"}]',
                'page' => 1,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 2
            ],
            [
                'name' => 'Улица',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "10&#176;С"}]',
                'page' => 1,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 3
            ],
            [
                'name' => 'Состояние',
                'type' => 'switch',
                'image' => 'boiler',
                'value' => '[{"status": "on"}]',
                'page' => 1,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 1
            ],
            [
                'name' => 'Автоматический режим',
                'type' => 'switch',
                'image' => 'settings',
                'value' => '[{"status": "on", "settings": "true"}]',
                'page' => 1,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 2
            ],
            [
                'name' => 'Ручной режим',
                'type' => 'switch',
                'image' => 'settings',
                'value' => '[{"status": "off", "settings": "true"}]',
                'page' => 1,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 3
            ],
            [
                'name' => 'Состояние горелки',
                'type' => 'label',
                'image' => 'fire',
                'value' => '[{"status": "Включена", "wh-color": "#00ffbb", "bl_color": "#00ffbb"}]',
                'page' => 1,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 4
            ],
            [
                'name' => 'Состояние горелки ГВС',
                'type' => 'label',
                'image' => 'fire',
                'value' => '[{"status": "Включена", "wh-color": "#00ffbb", "bl_color": "#00ffbb"}]',
                'page' => 1,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 5
            ],
            [
                'name' => 'Модуляция горелки, %',
                'type' => 'label',
                'image' => 'fire',
                'value' => '[{"status": "38", "wh-color": "#00ffbb", "bl_color": "#00ffbb"}]',
                'page' => 1,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 6
            ],
            [
                'name' => 'Состояние насоса',
                'type' => 'label',
                'image' => 'nasos',
                'value' => '[{"status": "Включено", "wh-color": "#00ffbb", "bl_color": "#00ffbb"}]',
                'page' => 1,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 7
            ],
            [
                'name' => 'Давление теплоносителя, бар',
                'type' => 'label',
                'image' => 'davlenie',
                'value' => '[{"status": "5", "wh-color": "#00ffbb", "bl_color": "#00ffbb"}]',
                'page' => 1,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 8
            ],
            /////////////////page_2///////////////////////
            [
                'name' => 'Подача',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "24&#176;С"}]',
                'page' => 2,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 1
            ],
            [
                'name' => 'Обратка',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "18&#176;С"}]',
                'page' => 2,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 2
            ],
            [
                'name' => 'Улица',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "10&#176;С"}]',
                'page' => 2,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 3
            ],
            [
                'name' => 'Состояние',
                'type' => 'switch',
                'image' => 'boiler-gvs',
                'value' => '[{"status": "on"}]',
                'page' => 2,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 1
            ],
            [
                'name' => 'Автоматический режим',
                'type' => 'switch',
                'image' => 'settings',
                'value' => '[{"status": "on", "settings": "true"}]',
                'page' => 2,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 2
            ],
            [
                'name' => 'Ручной режим',
                'type' => 'switch',
                'image' => 'settings',
                'value' => '[{"status": "off", "settings": "true"}]',
                'page' => 2,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 3
            ],
            [
                'name' => 'Состояние насоса',
                'type' => 'switch',
                'image' => 'nasos',
                'value' => '[{"status": "off"}]',
                'page' => 2,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 4
            ],
            ///////////////////page_3//////////////////
            [
                'name' => 'Подача',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "24&#176;С"}]',
                'page' => 3,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 1
            ],
            [
                'name' => 'Обратка',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "18&#176;С"}]',
                'page' => 3,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 2
            ],
            [
                'name' => 'Улица',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "10&#176;С"}]',
                'page' => 3,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 3
            ],
            [
                'name' => 'Автоматический режим',
                'type' => 'switch',
                'image' => 'settings',
                'value' => '[{"status": "on", "settings": "true"}]',
                'page' => 3,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 1
            ],
            [
                'name' => 'Ручной режим',
                'type' => 'switch',
                'image' => 'settings',
                'value' => '[{"status": "off", "settings": "true"}]',
                'page' => 3,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 2
            ],
            [
                'name' => 'Состояние насоса',
                'type' => 'switch',
                'image' => 'nasos',
                'value' => '[{"status": "off"}]',
                'page' => 3,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 3
            ],
            [
                'name' => 'Проток жидкости в контуре, л',
                'type' => 'label',
                'image' => 'teppol',
                'value' => '[{"status": "3", "wh-color": "#00ffbb", "bl_color": "#00ffbb"}]',
                'page' => 3,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 4
            ],
            /////////////////////page_4//////////////////////////
            [
                'name' => 'Подача',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "24&#176;С"}]',
                'page' => 4,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 1
            ],
            [
                'name' => 'Обратка',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "18&#176;С"}]',
                'page' => 4,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 2
            ],
            [
                'name' => 'Улица',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "10&#176;С"}]',
                'page' => 4,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 3
            ],
            [
                'name' => 'Циркуляционный насос 1',
                'type' => 'switch',
                'image' => 'nasos',
                'value' => '[{"status": "on"}]',
                'page' => 4,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 1
            ],
            [
                'name' => 'Циркуляционный насос 2',
                'type' => 'switch',
                'image' => 'nasos',
                'value' => '[{"status": "on"}]',
                'page' => 4,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 2
            ],
            [
                'name' => 'Скважинный насос',
                'type' => 'switch',
                'image' => 'nasos2',
                'value' => '[{"status": "on"}]',
                'page' => 4,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 3
            ],
            [
                'name' => 'Давление воды в системе, бар',
                'type' => 'label',
                'image' => 'davlenie',
                'value' => '[{"status": "5", "wh-color": "#00ffbb", "bl_color": "#00ffbb"}]',
                'page' => 4,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 4
            ],
            [
                'name' => 'Проток теплоносителя',
                'type' => 'label',
                'image' => 'fire',
                'value' => '[{"status": "Есть", "wh-color": "#00ffbb", "bl_color": "#00ffbb"}]',
                'page' => 4,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 5
            ],
            //////////////page_5//////////////
            [
                'name' => 'Фаза А',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "220V"}]',
                'page' => 5,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 1
            ],
            [
                'name' => 'Фаза B',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "220V"}]',
                'page' => 5,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 2
            ],
            [
                'name' => 'Фаза C',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "220V"}]',
                'page' => 5,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 3
            ],
            [
                'name' => 'Напряжение в сети, В',
                'type' => 'label',
                'image' => 'electro',
                'value' => '[{"status": "220V", "wh-color": "#00ffbb", "bl_color": "#00ffbb"}]',
                'page' => 5,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 1
            ],
            [
                'name' => 'Источник электроэнергии',
                'type' => 'label',
                'image' => 'vilka',
                'value' => '[{"status": "Основной", "wh-color": "#00ffbb", "bl_color": "#00ffbb"}]',
                'page' => 5,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 2
            ],
            [
                'name' => 'Заряд батареи, %',
                'type' => 'label',
                'image' => 'batareyka',
                'value' => '[{"status": "76", "wh-color": "#00ffbb", "bl_color": "#00ffbb"}]',
                'page' => 5,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 3
            ],
            ///////////////page_6////////////////
            [
                'name' => 'Подача',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "24&#176;С"}]',
                'page' => 6,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 1
            ],
            [
                'name' => 'Обратка',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "18&#176;С"}]',
                'page' => 6,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 2
            ],
            [
                'name' => 'Улица',
                'type' => 'label',
                'image' => '',
                'value' => '[{"status": "10&#176;С"}]',
                'page' => 6,
                'parent' => 0,
                'position' => 1,
                'active' => 1,
                'sort' => 3
            ],
            [
                'name' => 'Приточная вентиляция',
                'type' => 'switch',
                'image' => 'settings',
                'value' => '[{"status": "off"}]',
                'page' => 6,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 1
            ],
            [
                'name' => 'Вытяжная вентиляция',
                'type' => 'switch',
                'image' => 'settings',
                'value' => '[{"status": "on"}]',
                'page' => 6,
                'parent' => 0,
                'position' => 2,
                'active' => 1,
                'sort' => 2
            ],
        ];

        $result_elements = [];

        foreach ($elements as $element) {
            if (!in_array($element['name'], $this->elements, true)) {
                $result_elements[] = $element;
            }
        }

        if (count($result_elements)) {
            Elements::insert($result_elements);
        }
    }

}
