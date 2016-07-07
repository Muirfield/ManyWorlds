<?php
//= cmd:fixname
//: fixes name mismatches
//> Usage: /mw **fixname** _<world>_
//:
//: Fixes a world's **level.dat** file so that the name matches the
//: folder name.
//:

namespace aliuly\manyworlds;

use mf\common\mc;
use mf\common\Perms;

use aliuly\manyworlds\MwSubCmd;
use aliuly\manyworlds\MwLvDat;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class MwFixName extends MwSubCmd {
  public function getName() { return "fixname"; }
  public function getAliases() { return ["fix"]; }
  public function getHelp() { return mc::_("Fixes world name"); }
  public function getUsage() { return mc::_("<world>"); }
  public function getPermission() { return "mw.cmd.lvdat"; }

  public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
    $world = implode(" ",$args);
    $sender->sendMessage(TextFormat::AQUA.mc::_("Running /mw lvdat %1% name=%1%",$world));
    $args = [ $world, "name=$world" ];
    return $this->getPlugin()->callModule(MwLvDat::class, "onCommand",
				  [ $sender, $command, $label, $args ]);
  }
}
