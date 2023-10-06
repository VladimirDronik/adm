<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtensionModuleType extends Model
{
    protected $guarded = ['id'];

    public function extensionModules()
    {
        return $this->hasMany(ExtensionModule::class);
    }

    public function getPortsForInserting(int $device_id, int $extension_module_id): array
    {
        $ports = [];

        $ranges = explode(';', $this->ports);

        foreach ($ranges as $range) {
            $range = trim($range);

            if (empty($range)) {
                continue;
            }

            $values = explode(' ', $range);

            if (count($values) !== 3) {
                continue;
            }

            $status = $values[0];
            $min = (int) $values[1];
            $max = (int) $values[2];

            for ($num_port = $min; $num_port <= $max; $num_port++) {
                $ports[] = [
                    'id_device' => $device_id,
                    'extension_module_id' => $extension_module_id,
                    'num_port' => $num_port,
                    'type' => 'ext',
                    'status' => $status,
                    'comment' => '',
                ];
            }
        }

        return $ports;
    }
}
