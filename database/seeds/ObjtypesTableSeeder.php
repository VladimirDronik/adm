<?php

use Illuminate\Database\Seeder;
use App\Models\ObjType;

class ObjtypesTableSeeder extends Seeder
{
    private $objtypes;

    public function __construct()
    {
        $this->objtypes = ObjType::pluck('name')->toArray();
    }

    /**
     * Если тип с указанным именем не существует в таблице,
     * то добавляем, иначе обновляем label.
     *
     * @return array
     */
    private function getObjtypes(): array
    {
        return [
            [
                'name' => 'button',
                'label' => 'Кнопка'
            ],
            [
                'name' => 'lamp',
                'label' => 'Лампа'
            ],
            [
                'name' => 'socket',
                'label' => 'Розетка'
            ],
            [
                'name' => ObjType::TYPE_TERMOSTAT,
                'label' => 'Термостат'
            ],
            [
                'name' => 'hygrometer',
                'label' => 'Гигрометр'
            ],
            [
                'name' => 'Motion_sens',
                'label' => 'Датчик движения'
            ],
            [
                'name' => 'switch',
                'label' => 'Выключатель'
            ],
            [
                'name' => ObjType::TYPE_COUNT,
                'label' => 'Счетчик'
            ],
            [
                'name' => 'IR_transmitter',
                'label' => 'ИК передатчик'
            ],
            [
                'name' => 'pass_sensor',
                'label' => 'Датчик прохода'
            ],
            [
                'name' => 'dry_contact',
                'label' => 'Сухой контакт'
            ],
            [
                'name' => 'reley',
                'label' => 'Реле'
            ],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $objtypes = $this->getObjtypes();
        $result_objtypes = [];

        foreach ($objtypes as $objtype) {
            if (!in_array($objtype['name'], $this->objtypes, true)) {
                $result_objtypes[] = $objtype;
            } else {
                ObjType::where('name', $objtype['name'])
                    ->update(['label' => $objtype['label']]);
            }
        }

        if (count($result_objtypes)) {
            ObjType::insert($result_objtypes);
        }
    }
}
