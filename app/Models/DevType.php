<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DevType
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Devtype newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Devtype newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Devtype query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property int $start_in
 * @property int $end_in
 * @property int $start_out
 * @property string $end_out
 * @property int $total_ports
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DevType whereEndIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DevType whereEndOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DevType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DevType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DevType whereStartIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DevType whereStartOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DevType whereTotalPorts($value)
 * @property string|null $port_numbers
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DevType wherePortNumbers($value)
 */
class DevType extends Model
{
    protected $table = 'devtypes';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function getPortsForInserting(int $device_id): array
    {
        $ports = [];

        $ranges = explode(';', $this->port_numbers);

        foreach ($ranges as $range) {
            $range = trim($range);

            if (empty($range)) {
                continue;
            }

            $values = explode(' ', $range);

            if (count($values) !== 3) {
                continue;
            }

            $type = $values[0];
            $min = (int)$values[1];
            $max = (int)$values[2];

            for ($num_port = $min; $num_port <= $max; $num_port++) {
                $ports[] = [
                    'id_device' => $device_id,
                    'num_port' => $num_port,
                    'type' => $type,
                    'status' => 'NC',
                    'comment' => ''
                ];
            }
        }

        return $ports;
    }
}
