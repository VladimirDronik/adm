<?php

namespace App\Services;

class NetworkService
{
    private $pathNet = '/etc/network/interfaces.d/netcfg';

    /**
     * Установка параметров для сетевого интерфейса
     *
     * @param  ?string  $gateway
     *
     * @throws \Exception
     */
    public function setIface(string $address, string $netmask, string $gateway = null)
    {
        if (! $address || ! $netmask) {
            throw new \Exception('Не хватает данных');
        }

        $bn = file($this->pathNet);

        if ($gateway) {
            $bn[9] = "address $address\r\n";
            $bn[10] = "netmask $netmask\r\n";
            $bn[11] = "gateway $gateway\r\n";
        } else {
            $bn[16] = "address $address\r\n";
            $bn[17] = "netmask $netmask";
        }

        file_put_contents($this->pathNet, $bn);
    }

    /**
     * Чтение параметров сетевого интерфейса
     */
    public function getIface(bool $main = false): array
    {
        $bn = file($this->pathNet);

        if ($main) {
            $address = explode(' ', trim($bn[9]))[1] ?? '';
            $network = explode(' ', trim($bn[10]))[1] ?? '';
            $gateway = explode(' ', trim($bn[11]))[1] ?? '';

            return [$address, $network, $gateway];
        } else {
            $address = explode(' ', trim($bn[16]))[1] ?? '';
            $network = explode(' ', trim($bn[17]))[1] ?? '';

            return [$address, $network, null];
        }
    }

    /**
     * Перезагрузка сервера
     */
    public function reload(string $mainIp, string $ip)
    {
        $output = null;
        $resultCode = null;

        exec('/opt/touchon/scripts/network.sh '.$mainIp.' '.$ip, $output, $resultCode);

        if ($resultCode) {
            throw new \Exception(implode(', ', $output), $resultCode);
        }
    }
}
