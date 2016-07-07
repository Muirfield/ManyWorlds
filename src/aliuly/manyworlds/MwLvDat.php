<?php
//= cmd:lvdat
//: Show/modify `level.dat` variables
//> Usage: /mw **lvdat** _<world>_ _[attr=value]_
//:
//: Change directly some **level.dat** values/attributes.  Supported
//: attributes:
//:
//: - spawn=x,y,z : Sets spawn point
//: - seed=randomseed : seed used for terrain generation
//: - name=string : Level name
//: - generator=flat|normal : Terrain generator
//: - preset=string : Presets string.
//:

namespace aliuly\manyworlds;

use mf\common\mc;
use mf\common\Perms;

use aliuly\manyworlds\MwSubCmd;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use pocketmine\level\generator\Generator;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\StringTag;
use pocketmine\math\Vector3;
//use pocketmine\nbt\tag\IntTag;
//use pocketmine\nbt\tag\LongTag;
//use pocketmine\nbt\tag\CompoundTag;

class MwLvDat extends MwSubCmd {
  public function getName() { return "lvdat"; }
  public function getAliases() { return ["lv"]; }
  public function getHelp() { return mc::_("Change level.dat values"); }
  public function getUsage() { return mc::_("<world> [attr=value]"); }
  public function getPermission() { return "mw.cmd.lvdat"; }

  public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
    if (count($args) == 0) return FALSE;
    $world = array_shift($args);
    if(!$this->getPlugin()->autoLoad($sender,$world)) {
      $sender->sendMessage(TextFormat::RED.mc::_("[MW] %1% is not loaded!",$world));
      return TRUE;
    }
    $level = $this->getPlugin()->getServer()->getLevelByName($world);
    if (!$level) {
      $sender->sendMessage(TextFormat::RED.mc::_("[MW] Unexpected error"));
      return TRUE;
    }
    //==== provider
    $provider = $level->getProvider();
    $changed = FALSE; $unload = FALSE;
    foreach ($args as $kv) {
      $kv = explode("=",$kv,2);
      if (count($kv) != 2) {
	$sender->sendMessage(mc::_("Invalid element: %1%, ignored",$kv[0]));
	continue;
      }
      list($k,$v) = $kv;
      switch (strtolower($k)) {
	case "spawn":
	  $pos = explode(",",$v);
	  if (count($pos)!=3) {
	    $sender->sendMessage(mc::_("Invalid spawn location: %1%",implode(",",$pos)));
	    continue;
	  }
	  list($x,$y,$z) = $pos;
	  $cpos = $provider->getSpawn();
	  if (($x=intval($x)) == $cpos->getX() &&
		   ($y=intval($y)) == $cpos->getY() &&
		   ($z=intval($z)) == $cpos->getZ()) {
	    $sender->sendMessage(mc::_("Spawn location is unchanged"));
	    continue;
	  }
	  $changed = TRUE;
	  $provider->setSpawn(new Vector3($x,$y,$z));
	  break;
	case "seed":
	  if ($provider->getSeed() == intval($v)) {
	    $sender->sendMessage(mc::_("Seed unchanged"));
	    continue;
	  }
	  $changed = TRUE; $unload = TRUE;
	  $provider->setSeed($v);
	  break;
	case "name": // LevelName String
	  if ($provider->getName() == $v) {
	    $c->sendMessage(mc::_("Name unchanged"));
	    continue;
	  }
	  $changed = TRUE; $unload = TRUE;
	  $provider->getLevelData()->LevelName = new StringTag("LevelName",$v);
	  break;
	case "generator":	// generatorName(String)
	  if ($provider->getLevelData()->generatorName == $v) {
	    $sender->sendMessage(mc::_("Generator unchanged"));
	    continue;
	  }
	  $changed=TRUE; $unload=TRUE;
	  $provider->getLevelData()->generatorName=new StringTag("generatorName",$v);
	  break;
	case "preset":	// StringTag("generatorOptions");
	  if ($provider->getLevelData()->generatorOptions == $v) {
	    $sender->sendMessage(mc::_("Preset unchanged"));
	    continue;
	  }
	  $changed=TRUE; $unload=TRUE;
	  $provider->getLevelData()->generatorOptions =new StringTag("generatorOptions",$v);
	  break;
	default:
	  $sender->sendMessage(mc::_("Unknown key %1%, ignored",$k));
	  continue;
      }
    }
    if ($changed) {
      $sender->sendMessage(mc::_("Updating level.dat for %1%",$world));
      $provider->saveLevelData();
      if ($unload) $sender->sendMessage(TextFormat::RED.mc::_("CHANGES WILL NOT TAKE EFFECT UNTIL UNLOAD"));
    } else {
      $sender->sendMessage(mc::_("Nothing happens"));
    }
    
    $sender->sendMessage(TextFormat::AQUA.mc::_("Name:      %1%", TextFormat::WHITE.$provider->getName()));
    $sp = $provider->getSpawn();
    $sender->sendMessage(TextFormat::AQUA.mc::_("Spawn:    %1%(%2%,%3%,%4%)",TextFormat::WHITE,$sp->getX(),$sp->getY(),$sp->getZ()));
    $sender->sendMessage(TextFormat::AQUA.mc::_("Generator: %1%", TextFormat::WHITE.$provider->getLevelData()->generatorName));
    $sender->sendMessage(TextFormat::AQUA.mc::_("Seed:      %1%", TextFormat::WHITE.$provider->getSeed()));
    $sender->sendMessage(TextFormat::AQUA.mc::_("Preset:    %1%", TextFormat::WHITE.$provider->getLevelData()->generatorOptions));

    return TRUE;
  }
}

