<?php

namespace App\Repositories;

use App\Models\ModbusBus;

class ModbusRepository
{
    public function getAllBusesByType(string $type, $elementsPerPage = 30)
    {
        return ModbusBus::where('type', $type)->paginate($elementsPerPage);
    }
}
