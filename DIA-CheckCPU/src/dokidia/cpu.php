<?php

namespace dokidia;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;

class cpu extends PluginBase implements Listener {

    /** @var cpu $instance */
    private static $instance;
	
	public $plugin;

	public function onEnable() : void{
	    self::$instance = $this;
        $this->getLogger()->info(TextFormat::GREEN . "DIA-ServerCPU 활성화");
	}
	
	public static function getInstance() : self{
	    return self::$instance;
	}
 
    public function onCommand(CommandSender $o, Command $cmd, string $label, array $array) : bool{
        if($cmd->getName() == "cpu" || $cmd->getName() == "온도"){
            $this->Menu($o);
        }
        return true;
    }
    
    public function Menu($sender){ 
      //  $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");

        // 온도
        $temp = system("cat /sys/class/thermal/thermal_zone0/temp");
        $temp2 = round($temp * 1/1000, 2);

        // cpu 현재 클럭
        $curcpu = system("cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq");
        $curcpu2 = round($curcpu * 1/1000000, 2);

        if ($temp2 < 40) {
            $status = "매우 좋음";
            $isThrottle = "No";
        } else if ($temp2 < 45) {
            $status = "좋음";
            $isThrottle = "No";
        } else if ($temp2 < 54) {
            $status = "보통";
            $isThrottle = "No";
        } else if ($temp2 < 61) {
            $status = "주의";
            $isThrottle = "No";
        } else if ($temp2 < 70) {
            $status = "경고";
            $isThrottle = "No";
        } else if ($temp2 < 79) {
            $status = "위험";
            $isThrottle = "No";
        } else if ($temp2 >= 79) {
            $status = "매우 위험 - 스로틀링 진행중";
            $isThrottle = "Yes";
        } else {
            $status = "Unknown";
            $isThrottle = "Unknown";
        }

        $sender->sendMessage("§b=====[ 다이아서버 CPU 상태 ]=====\n§e온도: {$temp2}C\n§e현재 클럭: {$curcpu2}GHz\n§e상태: {$status}\n§e스로틀링: {$isThrottle}\n§b==============================");
    }

}

?>
