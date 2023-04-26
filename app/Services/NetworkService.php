<?php

namespace App\Services;

class NetworkService {

    private $pathNet = "/etc/network/interfaces";

    /**
     * Установка параметров для сетевого интерфейса
     *
     * @param $address
     * @param $netmask
     * @param bool $gateway
     * @throws \Exception
     */
    public function setIface($address, $netmask, $gateway = false) {
        if (!$address || !$netmask) {
            throw new \Exception('Не хватает данных');
        }

        $tplA = <<<EOT1
    			address {$address}
    			netmask {$netmask}
    			gateway {$gateway}
    			
EOT1;

        $tplB = <<<EOT2
    			address {$address}
    			netmask {$netmask}
EOT2;

        $bn = file($this->pathNet);

        $in_block = false;
        $found = false;
        foreach ($bn as $key => $value) {
            if (empty(trim($value)) && $in_block !== false) {
                $in_block = false;
            } else if (strpos($value, "static") !== false) {
                $in_block = $key;

                if ($gateway === false) {
                    if (!isset($bn[$key + 3]) || strpos($bn[$key + 3], "gateway") === false) {
                        $found = true;
                        break;
                    }
                } else {
                    if (isset($bn[$key + 3]) && strpos($bn[$key + 3], "gateway") !== false) {
                        $found = true;
                        break;
                    }
                }
            }
        }

        if ($found) {
            if ($gateway === false) {
                $tpl = $tplB;
                unset($bn[$key + 2]);
            } else {
                $tpl = $tplA;
                unset($bn[$key + 2],$bn[$key + 3]);
            }

            $bn[$key + 1] = $tpl;
            //$tmpfname = tempnam("/tmp", "net");
            file_put_contents($this->pathNet, $bn);
            //exec('bash -c "mv -f '.$tmpfname.' '.$this->pathVpn.'"');
            //exec('sh '.$this->cmdPath.' "mv -f '.$tmpfname.' '.$this->pathNet.'"');
        }
        //return 'bash -c "mv -f '.$tmpfname.' '.$this->pathVpn.'"';
    }

    /**
     * Чтение параметров сетевого интерфейса
     */
    public function getIface($main = false) {

        $bn = file($this->pathNet);

        $in_block = false;
        $found = false;

        foreach ($bn as $key => $value) {
            if (empty(trim($value)) && $in_block !== false) {
                $in_block = false;
            } else if (strpos($value, "static") !== false) {
                $in_block = $key;
                if ($main === false) {
                    if (!isset($bn[$key + 3]) || strpos($bn[$key + 3], "gateway") === false) {
                        $found = true;
                        break;
                    }
                } else {
                    if (isset($bn[$key + 3]) && strpos($bn[$key + 3], "gateway") !== false) {
                        $found = true;
                        break;
                    }
                }
            }
        }

        if ($found) {
            $address = explode(" ", trim($bn[$key + 1]))[1] ?? '';
            $network = explode(" ", trim($bn[$key + 2]))[1] ?? '';

            if ($main) {
                $gateway = explode(" ", trim($bn[$key + 3]))[1] ?? '';

                return [$address, $network, $gateway];
            }

            return [$address, $network, null];
        }

        return [null, null, null];
    }

    /**
     * Перезагрузка сервера
     */
    public function reload() {
        exec('sudo service networking restart');
        //exec('sudo sh '.$this->cmdPath.' "/sbin/reboot"');
        //exec('sudo sh '.$this->cmdPath.' "/etc/init.d/networking restart"');
        //exec('sh '.$this->cmdPath.' "killall pppd"');
        //exec('sh '.$this->cmdPath.' "pppd call vpn"');
    }
}
