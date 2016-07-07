<?php
//= cmd:load
//: Loads a world
//> Usage: /mw **load** _<world|--all>_
//:
//: Loads _world_ directly.  Use _--all_ to load **all** worlds.
//:
namespace aliuly\manyworlds;

use mf\common\mc;
use mf\common\Perms;

use aliuly\manyworlds\MwSubCmd;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class MwLoader extends MwSubCmd {
  public function getName() { return "load"; }
  public function getAliases() { return ["ld"]; }
  public function getHelp() { return mc::_("Load worlds"); }
  public function getUsage() { return mc::_("<world|--all>"); }
  public function getPermission() { return "mw.cmd.world.load"; }

  public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
    if (count($args) == 0) return FALSE;
    $wname = implode(" ",$args);
    if ($wname == "--all") {
      $wlst = [];
      foreach (glob($this->getPlugin()->getServer()->getDataPath(). "worlds/*") as $f) {
	$world = basename($f);
	if ($this->getPlugin()->getServer()->isLevelLoaded($world)) continue;
	if (!$this->getPlugin()->getServer()->isLevelGenerated($world)) continue;
	$wlst[] = $world;
      }
      if (count($wlst) == 0) {
	$sender->sendMessage(TextFormat::RED.mc::_("[MW] No levels to load"));
	return TRUE;
      }
      $sender->sendMessage(TextFormat::AQUA.mc::n(
		      mc::_("[MW] Loading one level"),
		      mc::_("[MW] Loading ALL %1% levels",count($wlst)),
		      count($wlst)));
    } else {
      if ($this->getPlugin()->getServer()->isLevelLoaded($wname)) {
	$sender->sendMessage(TextFormat::RED.mc::_("[MW] %1% already loaded",$wname));
	return TRUE;
      }
      if (!$this->getPlugin()->getServer()->isLevelGenerated($wname)) {
	$sender->sendMessage(TextFormat::RED.mc::_("[MW] %1% does not exists",$wname));
	return TRUE;
      }
      $wlst = [ $wname ];
    }
    foreach ($wlst as $world) {
      if (!$this->getPlugin()->autoLoad($sender,$world)) {
	$sender->sendMessage(TextFormat::RED.mc::_("[MW] Unable to load %1%",$world));
      }
    }
    return TRUE;
  }
}
