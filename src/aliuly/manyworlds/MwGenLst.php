<?php
//= cmd:generators
//: List available world generators
//> Usage: /mw **generators**
//:
//: List registered world generators.
//:
namespace aliuly\manyworlds;

use mf\common\mc;

use aliuly\manyworlds\MwSubCmd;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\level\generator\Generator;

class MwGenLst extends MwSubCmd {
  public function getName() { return "generators"; }
  public function getAliases() { return ["gen","genls"]; }
  public function getHelp() { return mc::_("List world generators"); }
  public function getUsage() { return ""; }
  public function getPermission() { return "mw.cmd.world.create"; }

  public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
    if (count($args) != 0) return FALSE;
    $sender->sendMessage(implode(", ",Generator::getGeneratorList()));
    return TRUE;
  }
}
