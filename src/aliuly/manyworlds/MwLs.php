<?php
//= cmd:ls
//: Provide world information
//> Usage: /mw **ls** _[world]_
//:
//: If _world_ is not specified, it will list available worlds.
//: Otherwise, details for _world_ will be provided.
//:
namespace aliuly\manyworlds;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;

use mf\common\mc;
use mf\common\Perms;
use mf\common\Pager;

use aliuly\manyworlds\MwSubCmd;

class MwLs extends MwSubCmd {
  public function getName() { return "list"; }
  public function getAliases() { return ["ls","info"]; }
  public function getHelp() { return mc::_("List world information"); }
  public function getUsage() { return mc::_("[world]"); }
  public function getPermission() { return "mw.cmd.ls"; }

  public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
    $pageNumber = Pager::getPageNumber($args);
    if (count($args) == 0) {
      $txt = $this->mwWorldList($sender);
    } else {
      $wname = implode(" ",$args);
      $txt = $this->mwWorldDetails($sender,$wname);
      if ($txt === NULL) {
        $sender->sendMessage(mc::_("[MW] error examining %1%", $wname));
	return TRUE;
      }
    }
    return Pager::paginateText($sender,$pageNumber,$txt);
  }
  private function mwWorldList(CommandSender $sender) {
    $dir = $this->getPlugin()->getServer()->getDataPath(). "worlds";
    if (!is_dir($dir)) {
      $sender->sendMessage(mc::_("[MW] Missing path %1%",$dir));
      return NULL;
    }
    $txt = ["HDR"];

    $auto = $this->getPlugin()->getServer()->getProperty("worlds",[]);
    $default = $this->getPlugin()->getServer()->getDefaultLevel();
    if ($default) $default = $default->getName();

    $count = 0;
    $dh = opendir($dir);
    if (!$dh) return NULL;
    while (($file = readdir($dh)) !== FALSE) {
      if ($file == '.' || $file == '..') continue;
      if (!$this->getPlugin()->getServer()->isLevelGenerated($file)) continue;
      $attrs = [];
      ++$count;
      if (isset($auto[$file])) $attrs[] = mc::_("auto");
      if ($default == $file) $attrs[]=mc::_("default");
      if ($this->getPlugin()->getServer()->isLevelLoaded($file)) {
	$attrs[] = mc::_("loaded");
	$np = count($this->getPlugin()->getServer()->getLevelByName($file)->getPlayers());
	if ($np) $attrs[] = mc::_("players:%1%",$np);
      }
      $ln = "- $file";
      if (count($attrs)) $ln .= TextFormat::AQUA." (".implode(",",$attrs).")";
      $txt[] = $ln;
    }
    closedir($dh);
    $txt[0] = mc::_("Worlds: %1%",$count);
    return $txt;
  }
  private function mwWorldDetails(CommandSender $sender,$world) {
    $txt = [];
    if ($this->getPlugin()->getServer()->isLevelLoaded($world)) {
      $unload = FALSE;
    } else {
      if (!$this->getPlugin()->autoLoad($sender,$world)) {
	$sender->sendMessage(TextFormat::RED.mc::_("Error getting %1%",$world));
	return NULL;
      }
      $unload = TRUE;
    }
    $level = $this->getPlugin()->getServer()->getLevelByName($world);

    //==== provider
    $provider = $level->getProvider();
    $txt[] = mc::_("Info for %1%",$world);
    $txt[] = TextFormat::AQUA.mc::_("Provider: ").TextFormat::WHITE. $provider::getProviderName();
    $txt[] = TextFormat::AQUA.mc::_("Path: ").TextFormat::WHITE.$provider->getPath();
    $txt[] = TextFormat::AQUA.mc::_("Name: ").TextFormat::WHITE.$provider->getName();
    $txt[] = TextFormat::AQUA.mc::_("Seed: ").TextFormat::WHITE.$provider->getSeed();
    $txt[] = TextFormat::AQUA.mc::_("Generator: ").TextFormat::WHITE.$provider->getGenerator();
    $gopts = $provider->getGeneratorOptions();
    if ($gopts["preset"] != "")
      $txt[] = TextFormat::AQUA.mc::_("Generator Presets: ").TextFormat::WHITE.$gopts["preset"];

    $spawn = $provider->getSpawn();
    $txt[] = TextFormat::AQUA.mc::_("Spawn: ").TextFormat::WHITE.$spawn->getX().",".$spawn->getY().",".$spawn->getZ();
    $plst = $level->getPlayers();
    $lst = "";
    if (count($plst)) {
      foreach ($plst as $p) {
	$lst .= (strlen($lst) ? ", " : "").$p->getName();
      }
    }
    $txt[] = TextFormat::AQUA.mc::_("Players(%1%):",count($plst)).TextFormat::WHITE.$lst;

    // Check for warnings...
    if ($provider->getName() != $world) {
      $txt[] = TextFormat::RED.mc::_("Folder Name and Level.Dat names do NOT match");
      $txt[] = TextFormat::RED.mc::_("This can cause intermitent problems");
      if($sender->hasPermission("mw.cmd.lvdat")) {
	$txt[] = TextFormat::RED.mc::_("Use: ");
	$txt[] = TextFormat::GREEN.mc::_("> /mw fixname %1%",$world);
	$txt[] = TextFormat::RED.mc::_("to fix this issue");
      }
    }

    if ($unload) $this->getPlugin()->getServer()->unloadLevel($level);

    return $txt;
  }

}
