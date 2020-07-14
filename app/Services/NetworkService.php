<?php

namespace App\Services;

class NetworkService {

    private $pathNet = "/etc/network/interfaces";
    private $pathVpn = "/etc/ppp/peers/l2tp.client";

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
     *  Установка параметров для VPN соединения
     *
     * @param $server
     * @param $username
     * @param $password
     * @throws \Exception
     */
    public function setVpn($server, $username, $password) {

        if (!$server || !$username || !$password) {
            throw new \Exception('Не хватает данных');
        }

        $tpl = <<<EOT
        noauth
        nodefaultroute
        name "{$username}"
        password "{$password}"
EOT;


/*
        $tpl = <<<EOT
				pty "pptp {$server} --nolaunchpppd"
				#require-mschap-v2
				#require-mppe-128
				user "{$username}" #имя
				password "{$password}"
				nodeflate
				nobsdcomp
				noauth
				nodefaultroute  #отключаем маршрут по умолчанию,
                #если он вам нужен - замените на defaultroute
				persist #переподключаться при обрыве
				maxfail 10 #количество попыток пере подключения
				holdoff 15 #интервал между подключениями
EOT;
*/
        //$tmpfname = tempnam("/tmp", "vpn");
        file_put_contents($this->pathVpn, $tpl);

        //exec('sh '.$this->cmdPath.' "mv -f '.$tmpfname.' '.$this->pathVpn.'"');
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
     * Чтение параметров VPN соединения
     */
    public function getVpn() {
        $bn = file($this->pathVpn);
        //$server = explode(" ", $bn[0]); //2
        $server = '';
        $user = explode(" ", $bn[2]); //1
        $pass = explode(" ", $bn[3]); //1

        return [trim($server[2]), trim(trim($user[1]),'\"'), trim(trim($pass[1]),'\"')];
    }

    /**
     * Перезагрузка сервера
     */
    public function reload() {
        exec('sudo /sbin/reboot');
        //exec('sudo sh '.$this->cmdPath.' "/sbin/reboot"');
        //exec('sudo sh '.$this->cmdPath.' "/etc/init.d/networking restart"');
        //exec('sh '.$this->cmdPath.' "killall pppd"');
        //exec('sh '.$this->cmdPath.' "pppd call vpn"');
    }
}