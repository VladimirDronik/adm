<?php

namespace App\Services;

class NetworkService {

    private $pathNet = "/home/user/t/interfaces";//"/etc/network/interfaces";
    private $pathVpn = "/home/user/t/vpn"; //"/etc/ppp/peers/vpn";

    public function setIface($address, $netmask, $gateway = false)
    {
        if (!$address || !$netmask) {
            throw new \Exception('Не хватает данных');
        }

        $tplA = <<<EOT1
    address {$address}
    network {$netmask}
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

            $tmpfname = tempnam("/tmp", "net");
            file_put_contents($tmpfname, implode("\n", $bn));
            exec('sudo bash -c "mv -f '.$tmpfname.' '.$this->pathNet.'"');
        } else {
            dd('not found');
        }
    }

    public function setVpn($server, $username, $password)
    {
        if (!$server || !$username || !$password) {
            throw new \Exception('Не хватает данных');
        }

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
maxfail 10 #количество попыток переподключения
holdoff 15 #интервал между подключениями
EOT;

        $tmpfname = tempnam("/tmp", "vpn");
        file_put_contents($tmpfname, $tpl);
        exec('sudo bash -c "mv -f '.$tmpfname.' '.$this->pathVpn.'"');
    }

    public function reload() {
        return true;
        exec('sudo bash -c "/etc/init.d/networking restart"');
        exec('sudo bash -c "killall pppd"');
        exec('sudo bash -c "pppd call vpn"');
    }
}