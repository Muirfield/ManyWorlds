<?php
//= cmd:tp
//: Teleport to another world
//> Usage: /mw **tp** _[player]_ _<world>_
//:
//: Teleports you to another world.  If _player_ is specified, that
//: player will be teleported.
//:
namespace aliuly\manyworlds;

use mf\common\mc;
use mf\common\Perms;

use aliuly\manyworlds\MwSubCmd;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class MwTp extends MwSubCmd {
  public function getName() { return "teleport"; }
  public function getAliases() { return ["tp"]; }
  public function getHelp() { return mc::_("Teleport across worlds"); }
  public function getUsage() { return mc::_("[player] <world>"); }
  public function getPermission() { return "mw.cmd.tp"; }

  public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
    if (count($args) == 0) return FALSE;
    $player = $sender;
    if (count($args) > 1) {
      $player = $this->getPlugin()->getServer()->getPlayer($args[0]);
      if ($player !== NULL) {
	if (!Perms::access($sender,"mw.cmd.tp.others")) return TRUE;
	array_shift($args);
      } else {
	// Compatibility with old versions...
	$player = $this->getPlugin()->getServer()->getPlayer($args[count($args)-1]);
	if ($player !== NULL) {
	  if (!Perms::access($sender,"mw.cmd.tp.others")) return TRUE;
	  array_pop($args);
	} else {
	  $player = $sender;
	}
      }
    }
    if (!Perms::inGame($player)) return TRUE;
    $wname = implode(" ",$args);
    if ($player->getLevel() == $this->getPlugin()->getServer()->getLevelByName($wname)) {
      $sender->sendMessage($sender == $player ?
			  mc::_("You are already in %1%",$wname) :
			  mc::_("%1% is already in %2%",$player->getName(),$wname));
      return TRUE;
    }
    if (!$this->getPlugin()->autoLoad($sender,$wname)) {
      $sender->sendMessage(TextFormat::RED.mc::_("Teleport failed"));
      return TRUE;
    }
    $level = $this->getPlugin()->getServer()->getLevelByName($wname);
    if ($level === NULL) {
      $sender->sendMessage(TextFormat::RED.mc::_("Error GetLevelByName %1%",$wname));
      return TRUE;
    }
    if ($sender != $player) {
      $player->sendMessage(TextFormat::YELLOW.mc::_("Teleporting you to %1% by %2%", $wname, $sender->getName()));
    } else {
      $sender->sendMessage(TextFormat::GREEN.mc::_("Teleporting to %1%",$wname));
    }
    $player->teleport($level->getSafeSpawn());
    return TRUE;
  }
}
