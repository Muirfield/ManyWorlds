<?php
//= cmd:unloads
//: Unloads world
//> Usage: /mw **unload** _[-f]_  _<world>_
//:
//: Unloads _world_.  Use _-f_ to force unloads.
//:
namespace aliuly\manyworlds;

use mf\common\mc;
use mf\common\Perms;

use aliuly\manyworlds\MwSubCmd;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class MwUnload extends MwSubCmd {
  public function getName() { return "unload"; }
  public function getAliases() { return []; }
  public function getHelp() { return mc::_("Attempt to unload worlds"); }
  public function getUsage() { return mc::_("[-f] <world>"); }
  public function getPermission() { return "mw.cmd.world.load"; }

  public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
    if (count($args) == 0) return FALSE;
    $force = FALSE;
    if ($args[0] == "-f") {
      $force = TRUE;
      array_shift($args);
      if (count($args) == 0) return FALSE;
    }
    $wname = implode(" ",$args);

    if (!$this->getPlugin()->getServer()->isLevelLoaded($wname)) {
      $sender->sendMessage(TextFormat::RED.mc::_("[MW] %1% is not loaded.",$wname));
      return TRUE;
    }
    $level = $this->getPlugin()->getServer()->getLevelByName($wname);
    if ($level === NULL) {
      $sender->sendMessage(TextFormat::RED.mc::_("[MW] Unable to get %1%",$wname));
      return TRUE;
    }
    if ($level === $this->getPlugin()->getServer()->getDefaultLevel()) {
      $sender->sendMessage(TextFormat::RED.mc::_("[MW] Unable unload default level %1%",$wname));
      return TRUE;
    }
    if (!$this->getPlugin()->getServer()->unloadLevel($level,$force)) {
      if ($force)
	  $sender->sendMessage(TextFormat::RED.mc::_("[MW] Unable to unload %1%",$wname));
      else
	$sender->sendMessage(TextFormat::RED.mc::_("[MW] Unable to unload %1%.  Try -f",$wname));
    } else {
      $sender->sendMessage(TextFormat::GREEN.mc::_("[MW] %1% unloaded.",$wname));
    }
    return TRUE;
  }
}
