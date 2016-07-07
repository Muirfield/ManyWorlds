<!-- template: startup.md -->


<!-- end-include -->
<img id="ManyWorlds-icon.png" src="https://raw.githubusercontent.com/Muirfield/ManyWorlds/master/media/ManyWorlds-icon.png" style="width:64px;height:64px" width="64" height="64"/>
<!-- template: header.md -->

# ManyWorlds

- Summary: Manage Multiple Worlds
- PocketMine-MP API version: 2.0.0
- DependencyPlugins: libcommon
- OptionalPlugins: 
- Categories: N/A
- WebSite: https://github.com/Muirfield/ManyWorlds


<!-- end-include -->
<!-- meta: API = http://muirfield.github.io/libcommon/apidocs/index.html -->
<!-- php: $v_forum_thread = "http://forums.pocketmine.net/threads/manyworlds.7277/"; -->
<!-- php:$copyright="2016"; -->

<!-- template: old/prologue.md -->

**DO NOT POST QUESTIONS/BUG-REPORTS/REQUESTS IN THE REVIEWS**

It is difficult to carry a conversation in the reviews.  If you
have a question/bug-report/request please use the
[Thread](http://forums.pocketmine.net/threads/manyworlds.7277/) for
that.  You are more likely to get a response and help that way.

_NOTE:_

This documentation was last updated for version **2.2.0dev1**.

Please go to
[github](https://github.com/Muirfield/ManyWorlds)
for the most up-to-date documentation.

You can also download this plugin from this [page](https://github.com/Muirfield/pocketmine-plugins/releases/tag/ManyWorlds-2.2.0dev1).


<!-- end-include -->

ManyWorlds implements a full feature set of commands to manage multiple worlds.
Features:

* teleport
* load/unload
* create
* world info
* edit level.dat

Available commands:

<!-- template: cmdoverview.md -->

* create: Create a new world
* default: Sets the default world
* fixname: fixes name mismatches
* generators: List available world generators
* load: Loads a world
* ls: Provide world information
* lvdat: Show/modify `level.dat` variables
* tp: Teleport to another world
* unloads: Unloads world



<!-- end-include -->

## Documentation

This plugin is a world manager that allows you to generate and load
worlds as well as teleport between worlds.

### Command Reference

The following commands are available:

<!-- template: cmdinfo.md -->
* create: Create a new world<br/>
  Usage: /mw **create** _&lt;world&gt;_ _[seed]_ _[generator]_ _[preset]_
  
  Creates a world named _world_.  You can optionally specify a _seed_
  as number, the generator (_flat_ or _normal_) and a _preset_ string.
  
* default: Sets the default world<br/>
  Usage: /mw **default** _&lt;world&gt;_
  
  Changes the default world for the server.
  
* fixname: fixes name mismatches<br/>
  Usage: /mw **fixname** _&lt;world&gt;_
  
  Fixes a world's **level.dat** file so that the name matches the
  folder name.
  
* generators: List available world generators<br/>
  Usage: /mw **generators**
  
  List registered world generators.
  
* load: Loads a world<br/>
  Usage: /mw **load** _&lt;world|--all&gt;_
  
  Loads _world_ directly.  Use _--all_ to load **all** worlds.
  
* ls: Provide world information<br/>
  Usage: /mw **ls** _[world]_
  
  If _world_ is not specified, it will list available worlds.
  Otherwise, details for _world_ will be provided.
  
* lvdat: Show/modify `level.dat` variables<br/>
  Usage: /mw **lvdat** _&lt;world&gt;_ _[attr=value]_
  
  Change directly some **level.dat** values/attributes.  Supported
  attributes:
  
  - spawn=x,y,z : Sets spawn point
  - seed=randomseed : seed used for terrain generation
  - name=string : Level name
  - generator=flat|normal : Terrain generator
  - preset=string : Presets string.
  
* tp: Teleport to another world<br/>
  Usage: /mw **tp** _[player]_ _&lt;world&gt;_
  
  Teleports you to another world.  If _player_ is specified, that
  player will be teleported.
  
* unloads: Unloads world<br/>
  Usage: /mw **unload** _[-f]_  _&lt;world&gt;_
  
  Unloads _world_.  Use _-f_ to force unloads.
  


<!-- end-include -->

### Permission Nodes

<!-- template: permissions.md -->

* mw.cmds: Allow all the ManyWorlds functionality
* mw.cmd.tp (op): Allows users to travel to other worlds
* mw.cmd.tp.others (op): Allows users to make others travel to other worlds
* mw.cmd.ls (op): Allows users to list worlds
* mw.cmd.world.create (op): Allows users to create worlds
* mw.cmd.world.load (op): Allows users to load worlds
* mw.cmd.lvdat (op): Manipulate level.dat
* mw.cmd.default (op): Changes default world


<!-- end-include -->

## Examples

Create a new normal world:

    /mw create overworld 711 normal

Create a new flat world:

    /mw create flatland 404 flat 2;7,59x1,3x3,2;1;

Teleport to this newly created world:

    /mw tp flatland

Teleport a player to another world:

    /mw tp joshua flatland

## Translations

<!-- template: mctxt.md -->

This plugin will honour the server language configuration.  The
languages currently available are:

* English
* Spanish


You can provide your own message file by creating a file called
**messages.ini** in the plugin config directory.

Check [github](https://github.com/Muirfield/ManyWorlds/resources/messages/)
for sample files.


<!-- end-include -->

## Issues

* New world names can not contain spaces.

## FAQ

* Q: How do I create a `FLAT` world?
* A: You must be using PocketMine-MP v1.4.1.  Set the `generator` to
  `flat`.
* Q: How do I load multiple worlds on start-up?
* A: That functionality is provided by PocketMine-MP core by default.
  In the `pocketmine.yml` file there is a `worlds` section where you
  can define which worlds to load on start-up.  Examples:

      [CODE]

      # pocketmine.yml
      worlds:
         world1: []
         world2: []
      [/CODE]

  This will automatically load worlds: "world1" and "world2" on startup.

# Notes

- Tests
  - [x] mw, manyworlds
  - [x] generators
  - [x] create
  - [x] default
  - [x] fixname
  - [x] load
  - [x] ls, ls [world]
  - [x] lvdat (view|write)
  - [x] unloads
  - [x] tp (self, others, console)
  - [x] permissions, op vs user

# Known Issues

- In MwCreate, it can not test properly when a generator does not exist
- Permissions are still broken

# Changes

* 2.2.0: 
  * Updating libcommon to 2.0.0dev1
* 2.1.0: Updating to new API
* 2.0.3: Bugfix update
  * Bug fixes, thanks to reporters: @thebigsmileXD, @SoyPro, @reyak.
  * Updated libcommon.
  * Changed command to manyworlds and mw is an alias.  This is to
    prevent possible name collisions.
  * Completed Spanish translation.
* 2.0.0: Modularization
  * Re-written for modularity
  * teleport manager API deprecated
  * Added `default` command to change the default level.
  * New `genlist` for list of generators
  * tp command changed to more natural English.
  * Translation: Spanish
* 1.3.4:
  * Updated for PM1.5
  * Removed CallbackTask deprecation warnings
  * WorldProtect integration
  * Simple API added
    * Added `lvdat` command to change `level.dat` settings.
  * Added `fixname` command to fix `levelName` vs. `foldername`
    mismatches.
  * Added a setting to control if to broadcast when people teleport.
  * Added TeleportManager to workaround teleport glitches
    plugins use this.
  * Added workaround to remove TileEntities that linger on when teleporting.
  * Show better help messages.
  * Added world unload.  May cause core dumps.
  * `ls` sub-command improvements:
    * paginated output
    * show number of players, autoloading and default status.
  * Added `loadall` functionality.
  * Bug Fixes
* 1.0.0 : Initial release

<!-- template: license/gpl2.md -->
# Copyright

    ManyWorlds
    Copyright (C) 2016 Alejandro Liu
    All Rights Reserved.

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.


<!-- end-include -->

