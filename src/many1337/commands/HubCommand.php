<?php

namespace many1337\commands;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\level\sound\Sound;
use pocketmine\item\Item;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\overload\CommandEnum;
use pocketmine\command\overload\CommandParameter;
use pocketmine\utils\TextFormat as C;
use many1337\Main;

class HubCommand extends Command {
    
  public function __construct($plugin){
    parent::__construct("hub", "teleport to hub");
	}

	public function execute(CommandSender $sender, string $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}
            if(!($sender instanceof Player)){
                $sender->sendMessage(C::RED."> ".C::GRAY."This command can't be used here!");
                return true;
            }

            $player = $sender->getPlayer();
            $sender->sendMessage(C::GREEN."> ".C::GRAY."Sccessfully teleported to Hub!");
            $sender->teleport(Server::getInstance()->getDefaultLevel()->getSafeSpawn());
            $sender->setGamemode(2);
            $hubworldspawn = $sender->getLevel()->getSpawnLocation();
            $sender->teleport($hubworldspawn);
            $sender->getLevel()->addSound(new EndermanTeleportSound($sender));
            $sender->setFood(20);
		return true;
    }
}
