<?php
namespace aliuly\manyworlds;
use mf\common\BaseSubCmd;

abstract class MwSubCmd extends BaseSubCmd {
  public function getMainCmd() { return "manyworlds"; }
}
