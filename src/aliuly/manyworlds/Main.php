<?php
namespace aliuly\manyworlds;

use mf\common\ModularPlugin;
use mf\common\HelpSubCmd;
use mf\common\mc;
use mf\common\Perms;

use pocketmine\command\CommandSender;

use aliuly\manyworlds\MwTp;
use aliuly\manyworlds\MwLs;
use aliuly\manyworlds\MwCreate;
use aliuly\manyworlds\MwGenLst;
use aliuly\manyworlds\MwLoader;
use aliuly\manyworlds\MwLvDat;
use aliuly\manyworlds\MwDefault;

class Main extends ModularPlugin {
  public function onEnable() {
    mc::init($this,$this->getFile());

    $this->addModule("teleport",new MwTp($this,[]));
    $this->addModule("lister",new MwLs($this,[]));
    $this->addModule("creator",new MwCreate($this,[]));
    $this->addModule("genlister",new MwGenLst($this,[]));
    $this->addModule("loader",new MwLoader($this,[]));
    $this->addModule("unloader",new MwUnload($this,[]));
    $this->addModule("default",new MwDefault($this,[]));
    $this->addModule("lvdat",new MwLvDat($this,[]));
    $this->addModule("lvdat",new MwFixName($this,[]));
    $this->addModule("mwhelp",new HelpSubCmd($this,"manyworlds"));
  }

  /**
   * Autoload a world
   *
   * @param CommandSender $c - person attempting this operation
   * @param str $world - world to load
   * @return bool - TRUE on success, FALSE on ERROR
   */
  public function autoLoad(CommandSender $c,$world) {
    if ($this->getServer()->isLevelLoaded($world)) return TRUE;
    if($c !== NULL && !Perms::access($c, "mw.cmd.world.load")) return FALSE;
    if(!$this->getServer()->isLevelGenerated($world)) {
      if ($c !== NULL) $c->sendMessage(mc::_("[MW] No world with the name %1% exists!", $world));
      return FALSE;
    }
    $this->getServer()->loadLevel($world);
    return $this->getServer()->isLevelLoaded($world);
  }
}

