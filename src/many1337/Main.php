<?php

namespace many1337;
 
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
// Mode CLOSED! use muqsit\invmenu\InvMenuHandler; 
use pocketmine\Server;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\Listeners;
use pocketmine\utils\TextFormat;
use pocketmine\scheduler\Task;
use pocketmine\level\Level;
use many1337\task\GuardianTask;


class Main extends PluginBase implements Listener
{

    public function onEnable() {
        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $pri = $this->getServer()->getPluginManager()->getPlugin("ProfileUI");
        if($api === null){
            $this->getServer()->getLogger()->notice("[LobbyCore] Please use a FormAPI plugin!");
        }

        if($pri === null){
            $this->getServer()->getLogger()->notice("[LobbyCore] Please use a ProfileUI plugin! 
            (https://github.com/Infernus101/ProfileUI)");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

    public function onDisable()
    {
        foreach ($this->getServer()->getOnlinePlayers() as $p) {
            $p->transfer("80.99.208.62", "1941");
        }
    }

    public function onJoin(PlayerJoinEvent $event){

        $player = $event->getPlayer();
        $name = $player->getName();
        $this->Main($player);
        $event->setJoinMessage("§7[§9+§7] §9" . $name);
        $this->getScheduler()->scheduleDelayedTask(new GuardianTask($this, $player), 30);
    }

    public function onQuit(PlayerQuitEvent $event)
    {

        $player = $event->getPlayer();
        $name = $player->getName();
        $event->setQuitMessage("§7[§c-§7] §c" . $name);

    }

    public function onPlace(BlockPlaceEvent $ev)
    {
		$ev->setCancelled(true);
    }

    public function Hunger(PlayerExhaustEvent $ev)
    {
		$ev->setCancelled(true);
    }

    public function ItemMove(PlayerDropItemEvent $ev)
    {
		$ev->setCancelled(true);
    }

    public function onConsume(PlayerItemConsumeEvent $ev)
    {
		$ev->setCancelled(true);
    }

    public function Main(Player $player)
    {
        $player->getInventory()->clearAll();
        $player->getInventory()->setItem(4, Item::get(345)->setCustomName(TextFormat::YELLOW . "Navigator"));
        $player->getInventory()->setItem(0, Item::get(397, 3)->setCustomName(TextFormat::AQUA . "Profile"));
        $player->getInventory()->setItem(8, Item::get(399)->setCustomName(TextFormat::GREEN . "Info"));
        $player->getInventory()->setItem(6, Item::get(288)->setCustomName(TextFormat::BLUE . "Fly"));
        $player->getInventory()->setItem(2, Item::get(280)->setCustomName(TextFormat::YELLOW . "Hide ".TextFormat::GREEN."Players"));

    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $game1 = $cfg->get("Game-1-Name");
        $game2 = $cfg->get("Game-2-Name");
        $game3 = $cfg->get("Game-3-Name");
        $game4 = $cfg->get("Game-4-Name");
        $game5 = $cfg->get("Game-5-Name");

        if ($item->getCustomName() == TextFormat::YELLOW . "Navigator") {
            $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
            $form = $api->createSimpleForm(function (Player $sender, $data) {
                $result = $data[0];

                if ($result === null) {
                    return true;
                }
                switch ($result) {
                    case 0:
                        $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
                        $ip = $cfg->get("ip-port");
                        $this->getServer()->getCommandMap()->dispatch($sender, $ip);
                        break;
                    case 1:
                        $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
                        $ip2 = $cfg->get("ip-port2");
                        $this->getServer()->getCommandMap()->dispatch($sender, $ip2);
                        break;
                    case 2:
                        $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
                        $ip3 = $cfg->get("ip-port3");
                        $this->getServer()->getCommandMap()->dispatch($sender, $ip3);
                        break;
                    case 3:
                        $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
                        $ip4 = $cfg->get("ip-port4");
                        $this->getServer()->getCommandMap()->dispatch($sender, $ip4);
                        break;
                    case 4:
                        $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
                        $ip5 = $cfg->get("ip-port5");
                        $this->getServer()->getCommandMap()->dispatch($sender, $ip5);
                        break;


                }
            });
            $form->setTitle("§l§aServer Selector");
            $form->setContent("Answer a server for teleporting");
            $form->addButton(TextFormat::BOLD . $game1);
            $form->addButton(TextFormat::BOLD . $game2);
            $form->addButton(TextFormat::BOLD . $game3);
            $form->addButton(TextFormat::BOLD . $game4);
            $form->addButton(TextFormat::BOLD . $game5);
            $form->sendToPlayer($player);

        }

        if ($item->getCustomName() == TextFormat::AQUA . "Profile") {

            $this->getServer()->dispatchCommand($event->getPlayer(), "profil " . $player);
        }

        if ($item->getCustomName() == TextFormat::GREEN . "Info") {

            $player = $event->getPlayer();
            $player->addTitle("§c§oSoon...", "§aNext update in working!");

        }

        if ($item->getCustomName() == TextFormat::BLUE . "Fly") {
            $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
            $form = $api->createSimpleForm(function (Player $sender, $data){
                $result = $data;
                if($result != null) {
                }
                switch ($result) {
                    case 0;
                        $sender->setAllowFlight(true);
                        $sender->sendMessage("§aFly has been enabled!§r");
                        break;
                    case 1;
                        $sender->setAllowFlight(false);
                        $sender->sendMessage("§cFly has been disabled!");
                        break;
                    case 2;
                        $sender->sendMessage("§4FlyUI has been closed.");
                }
            });
            $form->setTitle("§6Fly Mode");
            $form->setContent("§b§oOn or Off your fly§r");
            $form->addbutton("§l§aON", 0);
            $form->addbutton("§l§cOFF", 1);
            $form->addButton("§lEXIT", 2);
            $form->sendToPlayer($player);
        }

        if ($item->getName() === TextFormat::YELLOW . "Hide ".TextFormat::GREEN."Players") {
            $player->getInventory()->remove(Item::get(280)->setCustomName(TextFormat::YELLOW . "Hide ".TextFormat::GREEN."Players"));
            $player->getInventory()->setItem(2, Item::get(369)->setCustomName(TextFormat::YELLOW . "Show ".TextFormat::GREEN."Players"));
            $player->sendMessage(TextFormat::RED . "Disabled Player Visibility!");
            $this->hideall[] = $player;
            foreach ($this->getServer()->getOnlinePlayers() as $p2) {
                $player->hideplayer($p2);
            }

        } elseif ($item->getName() === TextFormat::YELLOW . "Show ".TextFormat::GREEN."Players"){
            $player->getInventory()->remove(Item::get(369)->setCustomName(TextFormat::YELLOW . "Show ".TextFormat::GREEN."Players"));
            $player->getInventory()->setItem(2, Item::get(280)->setCustomName(TextFormat::YELLOW . "Hide ".TextFormat::GREEN."Players"));
            $player->sendMessage(TextFormat::GREEN . "Enabled Player Visibility!");
            unset($this->hideall[array_search($player, $this->hideall)]);
            foreach ($this->getServer()->getOnlinePlayers() as $p2) {
                $player->showplayer($p2);
            }
        }
    }
}
