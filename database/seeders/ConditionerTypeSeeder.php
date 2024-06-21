<?php

namespace Database\Seeders;

use App\Models\ConditionerType;
use Illuminate\Database\Seeder;

class ConditionerTypeSeeder extends Seeder
{
    public function run(): void
    {
        $conditionerTypes = [
            [
                'name' => 'gr-1-mb-b',
                'device' => 'onokom-gr-1-mb-b',
                'temperature' => '{"min":16,"max":30}',
                'mode' => '{"heat":1,"cool":2,"auto":3,"dry":4,"fan":5}',
                'fan' => '{"auto":0,"silent":1,"1":2,"2":3,"3":4,"turbo":5}',
                'vdir' => '{"swing":1,"down":2,"mdown":3,"middle":4,"mup":5,"up":6}',
                'hdir' => '{"swing":1,"left":2,"mleft":3,"middle":4,"mright":5,"right":6}',
            ],
            [
                'name' => 'gr-3-mb-b',
                'device' => 'onokom-gr-3-mb-b',
                'temperature' => '{"min":16,"max":32}',
                'mode' => '{"heat":1,"cool":2,"auto":3,"dry":4,"fan":5}',
                'fan' => '{"auto":0,"silent":1,"1":2,"2":3,"3":4,"4":5,"5":6,"turbo":7}',
                'vdir' => '{"swing":1,"down":2,"mdown":3,"middle":4,"mup":5,"up":6}',
                'hdir' => '{"swing":1,"left":2,"mleft":3,"middle":4,"mright":5,"right":6}',
            ],
            [
                'name' => 'tcl-1-mb-b',
                'device' => 'onokom-tcl-1-mb-b',
                'temperature' => '{"min":16,"max":31}',
                'mode' => '{"heat":1,"cool":2,"auto":3,"dry":4,"fan":5}',
                'fan' => '{"auto":0,"silent":1,"1":2,"2":3,"3":4,"4":5,"5":6,"turbo":7}',
                'vdir' => '{"swing":1,"down":2,"mdown":3,"middle":4,"mup":5,"up":6}',
                'hdir' => '{"swing":1,"left":2,"mleft":3,"middle":4,"mright":5,"right":6,"soft":7}',
            ],
            [
                'name' => 'dk-1-mb-b',
                'device' => 'onokom-dk-1-mb-b',
                'temperature' => '{"min":18,"max":30}',
                'mode' => '{"heat":1,"cool":2,"auto":3,"dry":4,"fan":5}',
                'fan' => '{"auto":0,"silent":1,"1":2,"2":3,"3":4,"4":5,"5":6,"turbo":7}',
                'vdir' => '{"stop":0,"swing":1}',
                'hdir' => '{"stop":0,"swing":1}',
            ],
            [
                'name' => 'aux-1-mb-b',
                'device' => 'onokom-aux-1-mb-b',
                'temperature' => '{"min":18,"max":30}',
                'mode' => '{"heat":1,"cool":2,"auto":3,"dry":4,"fan":5}',
                'fan' => '{"auto":0,"silent":1,"1":2,"2":3,"3":4,"4":5,"5":6,"turbo":7}',
                'vdir' => '{"swing":1,"down":2,"mdown":3,"middle":4,"mup":5,"up":6}',
                'hdir' => '{"stop":0,"swing":1}',
            ],
            [
                'name' => 'me-1-mb-b',
                'device' => 'onokom-me-1-mb-b',
                'temperature' => '{"min":18,"max":30}',
                'mode' => '{"heat":1,"cool":2,"auto":3,"dry":4,"fan":5}',
                'fan' => '{"auto":0,"silent":1,"1":2,"2":3,"3":4,"4":5}',
                'vdir' => '{"swing":1,"down":2,"mdown":3,"middle":4,"mup":5,"up":6}',
                'hdir' => '{"swing":1,"left":2,"mleft":3,"middle":4,"mright":5,"right":6,"soft":7}',
            ],
            [
                'name' => 'hs-3-mb-b',
                'device' => 'onokom-hs-3-mb-b',
                'temperature' => '{"min":16,"max":32}',
                'mode' => '{"heat":1,"cool":2,"auto":3,"dry":4,"fan":5}',
                'fan' => '{"auto":0,"silent":1,"1":2,"2":3,"3":4,"4":5}',
                'vdir' => '{"stop":0,"swing":1}',
                'hdir' => '{"stop":0,"swing":1}',
            ],
            [
                'name' => 'hr-1-mb-b',
                'device' => 'onokom-hr-1-mb-b',
                'temperature' => '{"min":16,"max":32}',
                'mode' => '{"heat":1,"cool":2,"auto":3,"dry":4,"fan":5}',
                'fan' => '{"auto":0,"silent":1,"1":2,"2":3,"3":4,"turbo":5}',
                'vdir' => '{"swing":1,"down":2,"2":3,"3":4,"middle":5,"5":6,"6":7,"up":8}',
                'hdir' => '{"swing":1,"left":2,"mleft":3,"middle":4,"mright":5,"right":6}',
            ],
            [
                'name' => 'hs-6-mb-b',
                'device' => 'onokom-hs-6-mb-b',
                'temperature' => '{"min":16,"max":30}',
                'mode' => '{"heat":1,"cool":2,"auto":3,"dry":4,"fan":5}',
                'fan' => '{"auto":0,"1":1,"2":2,"3":3}',
                'vdir' => '{"stop":0,"swing":1}',
                'hdir' => '{"stop":0,"swing":1}',
            ],
            [
                'name' => 'mh-8-mb-b',
                'device' => 'onokom-mh-8-mb-b',
                'temperature' => '{"min":16,"max":32}',
                'mode' => '{"heat":1,"cool":2,"auto":3,"dry":4,"fan":5}',
                'fan' => '{"auto":0,"1":1,"2":2,"3":3}',
                'vdir' => '{"swing":1,"down":2,"mdown":3,"mup":4,"up":5}',
                'hdir' => null,
            ],
            [
                'name' => 'mu-1-01',
                'device' => 'lessar-mu-1-01',
                'temperature' => '{"min":17,"max":30}',
                'mode' => '{"heat":4,"cool":2,"auto":1,"dry":3,"fan":5}',
                'fan' => '{"auto":0,"silent":1,"1":2,"2":3,"3":4,"turbo":5}',
                'vdir' => '{"stop":0,"hdir":1,"vdir":2,"hdir+vdir":3}',
            ],
        ];

        foreach ($conditionerTypes as $conditionerType) {
            $name = $conditionerType['name'];
            unset($conditionerType['name']);

            ConditionerType::updateOrCreate(
                ['name' => $name], $conditionerType
            );
        }
    }
}
