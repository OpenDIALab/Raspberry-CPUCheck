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
        $this->getLogger()->info(TextFormat::GREEN . "DIA-CheckCPU Enabled | Made by DOKIDIA(twk1024) from OpenDIA");
	}
	
	public static function getInstance() : self{
	    return self::$instance;
	}
 
    public function onCommand(CommandSender $player, Command $cmd, string $label, array $array) : bool{
        if($cmd->getName() == "cpu" || $cmd->getName() == "온도"){
            $this->RaspberryTemp($player);
        }
        return true;
    }
    
    public function RaspberryTemp($sender){
        // 온도 확인 명령어
        $temp = system("cat /sys/class/thermal/thermal_zone0/temp");
        $temp2 = round($temp * 1/1000, 2);

        // cpu 현재 클럭 확인 명령어
        $curcpu = system("cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq");
        $curcpu2 = round($curcpu * 1/1000000, 2);

        if ($temp2 < 40) {
            $status = "Excellent";
            $isThrottle = "No";
        } else if ($temp2 < 45) {
            $status = "Stabled";
            $isThrottle = "No";
        } else if ($temp2 < 54) {
            $status = "Normal";
            $isThrottle = "No";
        } else if ($temp2 < 61) {
            $status = "Warning";
            $isThrottle = "No";
        } else if ($temp2 < 70) {
            $status = "Dangerous";
            $isThrottle = "No";
        } else if ($temp2 < 79) {
            $status = "Critical";
            $isThrottle = "No";
        } else if ($temp2 >= 79) {
            $status = "Extremely Critical - CPU Throttled";
            $isThrottle = "Yes";
        } else {
            $status = "Unknown";
            $isThrottle = "Unknown";
        }

        $sender->sendMessage("§b=====[ Server CPU ]=====\n§eTemperature: {$temp2}C\n§eCPU Frequency: {$curcpu2}GHz\n§eSummary: {$status}\n§eThrottle: {$isThrottle}\n§b==============================");
    }

}

?>
