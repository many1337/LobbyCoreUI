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
use pocketmine\level\Level;


class Main extends PluginBase implements Listener
{

    private static $instance = null;

    public $entityRuntimeId = null, $headBar = '', $cmessages = [], $changeSpeed = 0, $i = 0;

    public $API;

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

    public static function getInstance(){
        return self::$instance;
    }
    public function onLoad(){
        self::$instance = $this;
    }

    public function onDisable()
    {
        foreach ($this->getServer()->getOnlinePlayers() as $p) {
            $p->transfer("80.99.208.62", "1941");
        }
    }

    public function onJoin(PlayerJoinEvent $event){
    if (in_array($event->getPlayer()->getLevel(), $this->getWorlds())){
        if ($this->entityRuntimeId === null){
            $this->entityRuntimeId = API::addBossBar([$event->getPlayer()], 'BossBar LOADING...');
            $this->getServer()->getLogger()->debug($this->entityRuntimeId === NULL ? 'Couldn\'t add BossBar' : 'Successfully added BossBar with EID: ' . $this->entityRuntimeId);
        } else{
            API::sendBossBarToPlayer($event->getPlayer(), $this->entityRuntimeId, $this->getText($event->getPlayer()));
            $this->getServer()->getLogger()->debug('Sendt BossBar with existing EID: ' . $this->entityRuntimeId);
        }
    }

        $player = $event->getPlayer();
        $name = $player->getName();
        $this->Main($player);
        $event->setJoinMessage("§7[§9+§7] §9" . $name);

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

    public function sendBossBar(){
        if ($this->entityRuntimeId === null) return;
        $this->i++;
        $worlds = $this->getWorlds();
        foreach ($worlds as $world){
            foreach ($world->getPlayers() as $player){
                API::setTitle($this->getText($player), $this->entityRuntimeId, [$player]);
            }
        }
    }

    public function formatText(Player $player, string $text){
        $text = str_replace("{display_name}", $player->getDisplayName(), $text);
        $text = str_replace("{name}", $player->getName(), $text);
        $text = str_replace("{x}", $player->getFloorX(), $text);
        $text = str_replace("{y}", $player->getFloorY(), $text);
        $text = str_replace("{z}", $player->getFloorZ(), $text);
        $text = str_replace("{world}", (($levelname = $player->getLevel()->getName()) === false ? "" : $levelname), $text);
        $text = str_replace("{level_players}", count($player->getLevel()->getPlayers()), $text);
        $text = str_replace("{server_players}", count($player->getServer()->getOnlinePlayers()), $text);
        $text = str_replace("{server_max_players}", $player->getServer()->getMaxPlayers(), $text);
        $text = str_replace("{hour}", date('H'), $text);
        $text = str_replace("{minute}", date('i'), $text);
        $text = str_replace("{second}", date('s'), $text);
        $text = str_replace("{BLACK}", "&0", $text);
        $text = str_replace("{DARK_BLUE}", "&1", $text);
        $text = str_replace("{DARK_GREEN}", "&2", $text);
        $text = str_replace("{DARK_AQUA}", "&3", $text);
        $text = str_replace("{DARK_RED}", "&4", $text);
        $text = str_replace("{DARK_PURPLE}", "&5", $text);
        $text = str_replace("{GOLD}", "&6", $text);
        $text = str_replace("{GRAY}", "&7", $text);
        $text = str_replace("{DARK_GRAY}", "&8", $text);
        $text = str_replace("{BLUE}", "&9", $text);
        $text = str_replace("{GREEN}", "&a", $text);
        $text = str_replace("{AQUA}", "&b", $text);
        $text = str_replace("{RED}", "&c", $text);
        $text = str_replace("{LIGHT_PURPLE}", "&d", $text);
        $text = str_replace("{YELLOW}", "&e", $text);
        $text = str_replace("{WHITE}", "&f", $text);
        $text = str_replace("{OBFUSCATED}", "&k", $text);
        $text = str_replace("{BOLD}", "&l", $text);
        $text = str_replace("{STRIKETHROUGH}", "&m", $text);
        $text = str_replace("{UNDERLINE}", "&n", $text);
        $text = str_replace("{ITALIC}", "&o", $text);
        $text = str_replace("{RESET}", "&r", $text);

        $text = str_replace("&0", TextFormat::BLACK, $text);
        $text = str_replace("&1", TextFormat::DARK_BLUE, $text);
        $text = str_replace("&2", TextFormat::DARK_GREEN, $text);
        $text = str_replace("&3", TextFormat::DARK_AQUA, $text);
        $text = str_replace("&4", TextFormat::DARK_RED, $text);
        $text = str_replace("&5", TextFormat::DARK_PURPLE, $text);
        $text = str_replace("&6", TextFormat::GOLD, $text);
        $text = str_replace("&7", TextFormat::GRAY, $text);
        $text = str_replace("&8", TextFormat::DARK_GRAY, $text);
        $text = str_replace("&9", TextFormat::BLUE, $text);
        $text = str_replace("&a", TextFormat::GREEN, $text);
        $text = str_replace("&b", TextFormat::AQUA, $text);
        $text = str_replace("&c", TextFormat::RED, $text);
        $text = str_replace("&d", TextFormat::LIGHT_PURPLE, $text);
        $text = str_replace("&e", TextFormat::YELLOW, $text);
        $text = str_replace("&f", TextFormat::WHITE, $text);
        $text = str_replace("&k", TextFormat::OBFUSCATED, $text);
        $text = str_replace("&l", TextFormat::BOLD, $text);
        $text = str_replace("&m", TextFormat::STRIKETHROUGH, $text);
        $text = str_replace("&n", TextFormat::UNDERLINE, $text);
        $text = str_replace("&o", TextFormat::ITALIC, $text);
        $text = str_replace("&r", TextFormat::RESET, $text);

        return $text;
    }

    private function getWorlds(){
        $mode = $this->getConfig()->get("mode", 0);
        $worldnames = $this->getConfig()->get("worlds", []);
        $worlds = [];
        switch ($mode){
            case 0:
                $worlds = $this->getServer()->getLevels();
                break;
            case 1:
                foreach ($worldnames as $name){
                    if (!is_null($level = $this->getServer()->getLevelByName($name))) $worlds[] = $level;
                    else $this->getLogger()->warning("Config error! World " . $name . " not found!");
                }
                break;
            case 2:
                $worlds = $this->getServer()->getLevels();
                foreach ($worlds as $world){
                    if (!in_array(strtolower($world->getName()), $worldnames)){
                        $worlds[] = $world;
                    }
                }
                break;
        }
        return $worlds;
    }
}
