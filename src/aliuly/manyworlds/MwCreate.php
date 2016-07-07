<?php
//= cmd:create
//: Create a new world
//> Usage: /mw **create** _<world>_ _[seed]_ _[generator]_ _[preset]_
//:
//: Creates a world named _world_.  You can optionally specify a _seed_
//: as number, the generator (_flat_ or _normal_) and a _preset_ string.
//:
namespace aliuly\manyworlds;

use mf\common\mc;
use mf\common\Perms;

use aliuly\manyworlds\MwSubCmd;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\level\generator\Generator;

class MwCreate extends MwSubCmd {
  public function getName() { return "create"; }
  public function getAliases() { return ["new"]; }
  public function getHelp() { return mc::_("Creates a new world"); }
  public function getUsage() { return mc::_("<world> [seed] [generator] [preset]"); }
  public function getPermission() { return "mw.cmd.world.create"; }

  public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
    if (count($args) < 1 || count($args)>4) return FALSE;
    $world = array_shift($args);
    if ($this->getPlugin()->getServer()->isLevelGenerated($world)) {
      $sender->sendMessage(TextFormat::RED.mc::_("[MW] A world named %1% already exists",$world));
      return TRUE;
    }
    $seed = NULL;
    $generator = NULL;
    $opts = [];
    if (isset($args[0])) $seed = intval($args[0]);
    if (isset($args[1])) {
      $generator = Generator::getGenerator($args[1]);
      if (strtolower($args[1]) != Generator::getGeneratorName($generator)){
	$sender->sendMessage(TextFormat::RED.mc::_("[MW] Unknown generator %1%",$args[1]));
	return TRUE;
      }
      $sender->sendMessage(TextFormat::GREEN.mc::_("[MW] Using %1%",Generator::getGeneratorName($generator)));
    }
    if(isset($args[2])) $opts = ["preset" => $args[2] ];
    $this->getPlugin()->getServer()->broadcastMessage(mc::_("[MW] Creating level %1%... (Expect Lag)", $world));
    $this->getPlugin()->getServer()->generateLevel($world,$seed,$generator,$opts);
    $this->getPlugin()->getServer()->loadLevel($world);
    return TRUE;
  }
}
