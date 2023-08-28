<?php

namespace Database\Seeders;

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
                'name' => ObjType::TYPE_BUTTON,
                'label' => 'Кнопка'
            ],
            [
                'name' => 'lamp',
                'label' => 'Лампа'
            ],
            [
                'name' => ObjType::TYPE_SOCKET,
                'label' => 'Розетка'
            ],
            [
                'name' => ObjType::TYPE_TERMOSTAT,
                'label' => 'Термостат'
            ],
            [
                'name' => ObjType::TYPE_USENSOR,
                'label' => 'Универсальный датчик'
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
                'name' => ObjType::TYPE_SWITCH,
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
                'name' => ObjType::TYPE_RELAY,
                'label' => 'Реле'
            ],
            [
                'name' => ObjType::TYPE_DIMMER,
                'label' => 'Диммер'
            ],
            [
                'name' => ObjType::TYPE_DRYCONTACT,
                'label' => 'Сухой контакт'
            ],
            [
                'name' => ObjType::TYPE_MANOMETR,
                'label' => 'Манометр'
            ],
            [
                'name' => ObjType::TYPE_CONDITIONER,
                'label' => 'Кондиционер'
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
