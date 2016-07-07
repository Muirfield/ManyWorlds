<?php
//= cmd:default
//: Sets the default world
//> Usage: /mw **default** _<world>_
//:
//: Changes the default world for the server.
//:
namespace aliuly\manyworlds;

use mf\common\mc;
use mf\common\Perms;

use aliuly\manyworlds\MwSubCmd;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class MwDefault extends MwSubCmd {
  public function getName() { return "default"; }
  public function getAliases() { return ["def"]; }
  public function getHelp() { return mc::_("Sets the default world"); }
  public function getUsage() { return mc::_("<world>"); }
  public function getPermission() { return "mw.cmd.default"; }

  public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
    if (count($args) == 0) return FALSE;
    $wname =implode(" ",$args);
    $old = $this->getPlugin()->getServer()->getConfigString("level-name");
    if ($old == $wname) {
      $sender->sendMessage(TextFormat::RED.mc::_("No change"));
      return TRUE;
    }
    if (!$this->getPlugin()->autoLoad($sender,$wname)) {
      $sender->sendMessage(TextFormat::RED.mc::_("[MW] Unable to load %1%",$wname));
      $sender->sendMessage(TextFormat::RED.mc::_("Change failed!"));
      return TRUE;
    }
    $level = $this->getPlugin()->getServer()->getLevelByName($wname);
    if ($level === NULL) {
      $c->sendMessage(TextFormat::RED.mc::_("Error GetLevelByName %1%"));
      return TRUE;
    }
    $this->getPlugin()->getServer()->setConfigString("level-name",$wname);
    $this->getPlugin()->getServer()->setDefaultLevel($level);
    $sender->sendMessage(TextFormat::BLUE.mc::_("Default world changed to %1%",$wname));
    return TRUE;
  }
}
