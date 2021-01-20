<?php



namespace ItzFabb\Bank;



//Basic Class 

use pocketmine\Player;

use pocketmine\Server;

use pocketmine\plugin\Plugin;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;

use pocketmine\utils\Config;

use pocketmine\utils\TextFormat;

use pocketmine\item\Item;



//Guis Class 

use pocketmine\scheduler\ClosureTask;

use libs\muqsit\invmenu\InvMenu;

use libs\muqsit\invmenu\InvMenuHandler;

use jojoe77777\FormAPI\SimpleForm;

use jojoe77777\FormAPI\ModalForm;

use jojoe77777\FormAPI\CustomForm;

use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\inventory\transaction\action\SlotChangeAction;



//Command Class 

use pocketmine\command\Command;

use pocketmine\command\CommandSender;

use pocketmine\command\ConsoleCommandSender;



//Sound Class 

use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

use pocketmine\network\mcpe\protocol\LevelEventPacket;



//Others class 

use onebone\economyapi\EconomyAPI;



class Main extends PluginBase implements Listener {

     private $uicooldown;

	public function onEnable()
	{

		$economy = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");

	

		$this->saveResource("config.yml");

		$banksize = InvMenu::create(InvMenu::TYPE_CHEST);

		if(!InvMenuHandler::isRegistered()){

			InvMenuHandler::register($this);

			}

		@mkdir($this->getDataFolder());

		$database = new Config($this->getDataFolder() . "database.yml", Config::YAML, array());

		$database->save();

	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {

        switch($cmd->getName()){

        	case "bank-help":

        		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        		if(!$sender instanceof Player){

        		 $cmdingame = $cfg->get("msg-ingame");

        		 $cmdingames = str_replace(["&", "+n"], ["§", "\n"], $cmdingame);

                $sender->sendMessage($cmdingame);

                return false;

        		}

        		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

            if(!$sender->hasPermission("command.bank.help")){

            	$nopermss = $cfg->get("msg-noperms");

            	$noperms = str_replace(["&", "+n"], ["§", "\n"], $nopermss);

                $sender->sendMessage($noperms);

                $volume = mt_rand();

	           $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ANVIL_FALL, (int) $volume);

                return false;

            }

        		$sender->sendMessage("§8§l---( §r§e§lBANK HELP§r §8§l)---");

        		$sender->sendMessage(" §r ");

        		$sender->sendMessage("§e/bank §7- §fto open the menu bank");

        		$sender->sendMessage("§e/deposit §7- §fto open the deposit menu bank");

        		$sender->sendMessage("§e/withdraw §7- §fto open the withdraw menu bank");

        		$sender->sendMessage("§e/bank-help §7- §fshow this help page");

        		$sender->sendMessage("§e/bank-balance §7- §fshow your bank balance");

        		$sender->sendMessage("§e/bank-credits §7- §fshow this plugin info");

        		$sender->sendMessage(" §r ");

        		$sender->sendMessage("§8§l---( §r§e§lBANK HELP§r §8§l)---");

        	break;

        	case "bank-credits":

        		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        		if(!$sender instanceof Player){

        		 $cmdingame = $cfg->get("msg-ingame");

        		 $cmdingames = str_replace(["&", "+n"], ["§", "\n"], $cmdingame);

                $sender->sendMessage($cmdingame);

                return false;

        		}

        		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

            if(!$sender->hasPermission("command.bank.credits")){

            	$nopermss = $cfg->get("msg-noperms");

            	$noperms = str_replace(["&", "+n"], ["§", "\n"], $nopermss);

                $sender->sendMessage($noperms);

                $volume = mt_rand();

	           $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ANVIL_FALL, (int) $volume);

                return false;

            }

            $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

            $sender->sendMessage("§8§l---( §r§e§lBANK CREDITS §r§8§l)---");

            $sender->sendMessage(" §r ");

            $sender->sendMessage("§9§lPlugin Version: §r§f1");

            $sender->sendMessage("§9§lPlugin Api: §r§f[§e3§f]");

            $sender->sendMessage("§9§lPermission Loaded: §r§f[§a6§f]");

            $sender->sendMessage("§9§lPlugin Author: §r§fItzFabb");

            $sender->sendMessage("§9§lPlugin Description: §r§fa bank plugin with high quality stuff");

            $sender->sendMessage("§9§lSupport Pocketmine: §r§c[1.16.100] - [1.16.201]");

            $sender->sendMessage("§9§lDate Created: §r§fDecember, 30 - 2020");

            $sender->sendMessage(" §r ");

            $sender->sendMessage("§8§l---( §r§e§lBANK CREDITS§r §8§l)---");

            break;

        	case "bank-balance":

        		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        		if(!$sender instanceof Player){

        		 $cmdingame = $cfg->get("msg-ingame");

        		 $cmdingames = str_replace(["&", "+n"], ["§", "\n"], $cmdingame);

                $sender->sendMessage($cmdingame);

                return false;

        		}

        		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

            if(!$sender->hasPermission("command.bank.balance")){

            	$nopermss = $cfg->get("msg-noperms");

            	$noperms = str_replace(["&", "+n"], ["§", "\n"], $nopermss);

                $sender->sendMessage($noperms);

                $volume = mt_rand();

	           $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ANVIL_FALL, (int) $volume);

                return false;

            }

               $economy = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");

        		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        		$bbalance = $cfg->get("bank-balance-info");

        		$ding = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $bbalance);

        		$sender->sendMessage($ding);

          break;

        	case "bank":

        		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        		if(!$sender instanceof Player){

        		 $cmdingame = $cfg->get("msg-ingame");

        		 $cmdingames = str_replace(["&", "+n"], ["§", "\n"], $cmdingame);

                $sender->sendMessage($cmdingame);

                return false;

        		}

        		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

            if(!$sender->hasPermission("command.bank.bank")){

            	$nopermss = $cfg->get("msg-noperms");

            	$noperms = str_replace(["&", "+n"], ["§", "\n"], $nopermss);

                $sender->sendMessage($noperms);

                $volume = mt_rand();

	           $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ANVIL_FALL, (int) $volume);

                return false;

            }

            $this->bankmenu($sender);

            $sender->getLevel()->broadcastLevelSoundEvent($sender->add(0, $sender->eyeHeight, 0), LevelSoundEventPacket::SOUND_CHEST_OPEN);

            break;

            case "deposit":

        		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        		if(!$sender instanceof Player){

        		 $cmdingame = $cfg->get("msg-ingame");

        		 $cmdingames = str_replace(["&", "+n"], ["§", "\n"], $cmdingame);

                $sender->sendMessage($cmdingame);

                return false;

        		}

        		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

            if(!$sender->hasPermission("command.bank.deposit")){

            	$nopermss = $cfg->get("msg-noperms");

            	$noperms = str_replace(["&", "+n"], ["§", "\n"], $nopermss);

                $sender->sendMessage($noperms);

                $volume = mt_rand();

	           $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ANVIL_FALL, (int) $volume);

                return false;

            }

            $this->deposit($sender);

            $sender->getLevel()->broadcastLevelSoundEvent($sender->add(0, $sender->eyeHeight, 0), LevelSoundEventPacket::SOUND_CHEST_OPEN);

            break;

            case "withdraw":

        		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        		if(!$sender instanceof Player){

        		 $cmdingame = $cfg->get("msg-ingame");

        		 $cmdingames = str_replace(["&", "+n"], ["§", "\n"], $cmdingame);

                $sender->sendMessage($cmdingame);

                return false;

        		}

        		$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

            if(!$sender->hasPermission("command.bank.withdraw")){

            	$nopermss = $cfg->get("msg-noperms");

            	$noperms = str_replace(["&", "+n"], ["§", "\n"], $nopermss);

                $sender->sendMessage($noperms);

                $volume = mt_rand();

	           $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ANVIL_FALL, (int) $volume);

                return false;

            }

            $this->withdraw($sender);

            $sender->getLevel()->broadcastLevelSoundEvent($sender->add(0, $sender->eyeHeight, 0), LevelSoundEventPacket::SOUND_CHEST_OPEN);

            break;

        }

        return true;

	}

	public function bankmenu($sender)

	{

	    $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

	    

	    $titlepotions1 = $cfg->get("title");

	    $titlepotions = str_replace(["&", "+n",], ["§", "\n"], $titlepotions1);

	    $banksize->readonly();

	    $banksize->setListener([$this, "banksetting"]);

         $banksize->setName($titlepotions);

	    $inventory = $banksize->getInventory();

	    

	    //0

	    $inconfig = $cfg->get("item.name");

	    $id = $cfg->get("item.id");

	    $meta = $cfg->get("item.meta");

	    $count = $cfg->get("item.count");

	    $in = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $in);

	    //1

	    $inconfig1 = $cfg->get("item.name1");

	    $id1 = $cfg->get("item.id1");

	    $meta1 = $cfg->get("item.meta1");

	    $count1 = $cfg->get("item.count1");

	    $in1 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig1);

	    //2 

	    $inconfig2 = $cfg->get("item.name2");

	    $id2 = $cfg->get("item.id2");

	    $meta2 = $cfg->get("item.meta2");

	    $count2 = $cfg->get("item.count2");

	    $in2 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig2);

	    //3 

	    $inconfig3 = $cfg->get("item.name3");

	    $id3 = $cfg->get("item.id3");

	    $meta3 = $cfg->get("item.meta3");

	    $count3 = $cfg->get("item.count3");

	    $in3 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig3);

	    //4 

	    $inconfig4 = $cfg->get("item.name4");

	    $id4 = $cfg->get("item.id4");

	    $meta4 = $cfg->get("item.meta4");

	    $count4 = $cfg->get("item.count4");

	    $in4 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig4);

	    //5 

	    $inconfig5 = $cfg->get("item.name5");

	    $id5 = $cfg->get("item.id5");

	    $meta5 = $cfg->get("item.meta5");

	    $count5 = $cfg->get("item.count5");

	    $in5 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig5);

	    //6 

	    $inconfig6 = $cfg->get("item.name6");

	    $id6 = $cfg->get("item.id6");

	    $meta6 = $cfg->get("item.meta6");

	    $count6 = $cfg->get("item.count6");

	    $in6 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig6);

	    //7 

	    $inconfig7 = $cfg->get("item.name7");

	    $id7 = $cfg->get("item.id7");

	    $meta7 = $cfg->get("item.meta7");

	    $count7 = $cfg->get("item.count7");

	    $in7 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig7);

	    //8 

	    $inconfig8 = $cfg->get("item.name8");

	    $id8 = $cfg->get("item.id8");

	    $meta8 = $cfg->get("item.meta8");

	    $count8 = $cfg->get("item.count8");

	    $in8 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig8);

	    //9 

	    $inconfig9 = $cfg->get("item.name9");

	    $id9 = $cfg->get("item.id9");

	    $meta9 = $cfg->get("item.meta9");

	    $count9 = $cfg->get("item.count9");

	    $in9 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig9);

	    //10 

	    $inconfig10 = $cfg->get("item.name10");

	    $id10 = $cfg->get("item.id10");

	    $meta10 = $cfg->get("item.meta10");

	    $count10 = $cfg->get("item.count10");

	    $in10 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig10);

	    //11 

	    $inconfig11 = $cfg->get("item.name11");

	    $id11 = $cfg->get("item.id11");

	    $meta11 = $cfg->get("item.meta11");

	    $count11 = $cfg->get("item.count11");

	    $in11 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig11);

	    //12

	    $inconfig12 = $cfg->get("item.name12");

	    $id12 = $cfg->get("item.id12");

	    $meta12 = $cfg->get("item.meta12");

	    $count12 = $cfg->get("item.count12");

	    $in12 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig12);

	    //13

	    $inconfig13 = $cfg->get("item.name13");

	    $id13 = $cfg->get("item.id13");

	    $meta13 = $cfg->get("item.meta13");

	    $count13 = $cfg->get("item.count13");

	    $in13 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig13);

	    //14

	    $inconfig14 = $cfg->get("item.name14");

	    $id14 = $cfg->get("item.id14");

	    $meta14 = $cfg->get("item.meta14");

	    $count14 = $cfg->get("item.count14");

	    $in14 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig14);

	    //15 	    

	    $inconfig15 = $cfg->get("item.name15");

	    $id15 = $cfg->get("item.id15");

	    $meta15 = $cfg->get("item.meta15");

	    $count15 = $cfg->get("item.count15");

	    $in15 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig15);

	    //16 	    

	    $inconfig16 = $cfg->get("item.name16");

	    $id16 = $cfg->get("item.id16");

	    $meta16 = $cfg->get("item.meta16");

	    $count16 = $cfg->get("item.count16");

	    $in16 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig16);

	    //17 	    

	    $inconfig17 = $cfg->get("item.name17");

	    $id17 = $cfg->get("item.id17");

	    $meta17 = $cfg->get("item.meta17");

	    $count17 = $cfg->get("item.count17");

	    $in17 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig17);

	    //18

	    $inconfig18 = $cfg->get("item.name18");

	    $id18 = $cfg->get("item.id18");

	    $meta18 = $cfg->get("item.meta18");

	    $count18 = $cfg->get("item.count18");

	    $in18 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig18);

	    //19

	    $inconfig19 = $cfg->get("item.name19");

	    $id19 = $cfg->get("item.id19");

	    $meta19 = $cfg->get("item.meta19");

	    $count19 = $cfg->get("item.count19");

	    $in19 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig19);

	    //20

	    $inconfig20 = $cfg->get("item.name20");

	    $id20 = $cfg->get("item.id20");

	    $meta20 = $cfg->get("item.meta20");

	    $count20 = $cfg->get("item.count20");

	    $in20 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig20);

	    //21

	    $inconfig21 = $cfg->get("item.name21");

	    $id21 = $cfg->get("item.id21");

	    $meta21 = $cfg->get("item.meta21");

	    $count21 = $cfg->get("item.count21");

	    $in21 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig21);

	    //22

	    $inconfig22 = $cfg->get("item.name22");

	    $id22 = $cfg->get("item.id22");

	    $meta22 = $cfg->get("item.meta22");

	    $count22 = $cfg->get("item.count22");

	    $in22 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig22);

	    //23

	    $inconfig23 = $cfg->get("item.name23");

	    $id23 = $cfg->get("item.id23");

	    $meta23 = $cfg->get("item.meta23");

	    $count23 = $cfg->get("item.count23");

	    $in23 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig23);

	    //24

	    $inconfig24 = $cfg->get("item.name24");

	    $id24 = $cfg->get("item.id24");

	    $meta24 = $cfg->get("item.meta24");

	    $count24 = $cfg->get("item.count24");

	    $in24 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig24);

	    //25

	    $inconfig25 = $cfg->get("item.name25");

	    $id25 = $cfg->get("item.id25");

	    $meta25 = $cfg->get("item.meta25");

	    $count25 = $cfg->get("item.count25");

	    $in25 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig25);

	    //26

	    $inconfig26 = $cfg->get("item.name26");

	    $id26 = $cfg->get("item.id26");

	    $meta26 = $cfg->get("item.meta26");

	    $count26 = $cfg->get("item.count26");

	    $in26 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig26);

	    //Chest Section 1-8

	    $inventory->setItem(0, Item::get($id, $meta, $count)->setCustomName($in));

	    $inventory->setItem(1, Item::get($id1, $meta1, $count1)->setCustomName($in1));

	    $inventory->setItem(2, Item::get($id2, $meta2, $count2)->setCustomName($in2));

	    $inventory->setItem(3, Item::get($id3, $meta3, $count3)->setCustomName($in3));

	    $inventory->setItem(4, Item::get($id4, $meta4, $count4)->setCustomName($in4));

	    $inventory->setItem(5, Item::get($id5, $meta5, $count5)->setCustomName($in5));

	    $inventory->setItem(6, Item::get($id6, $meta6, $count6)->setCustomName($in6));

	    $inventory->setItem(7, Item::get($id7, $meta7, $count7)->setCustomName($in7));

	    $inventory->setItem(8, Item::get($id8, $meta8, $count8)->setCustomName($in8));

         //Chest Section 9-17

         $inventory->setItem(9, Item::get($id9, $meta9, $count9)->setCustomName($in9));

	    $inventory->setItem(10, Item::get($id10, $meta10, $count10)->setCustomName($in10));

	    $inventory->setItem(11, Item::get($id11, $meta11, $count11)->setCustomName($in11));

	    $inventory->setItem(12, Item::get($id12, $meta12, $count12)->setCustomName($in12));

	    $inventory->setItem(13, Item::get($id13, $meta13, $count13)->setCustomName($in13));

	    $inventory->setItem(14, Item::get($id14, $meta14, $count14)->setCustomName($in14));

	    $inventory->setItem(15, Item::get($id15, $meta15, $count15)->setCustomName($in15));

	    $inventory->setItem(16, Item::get($id16, $meta16, $count16)->setCustomName($in16));

	    $inventory->setItem(17, Item::get($id17, $meta17, $count17)->setCustomName($in17));

         //Chest Section 18-26

         $inventory->setItem(18, Item::get($id18, $meta18, $count18)->setCustomName($in18));

	    $inventory->setItem(19, Item::get($id19, $meta19, $count19)->setCustomName($in19));

	    $inventory->setItem(20, Item::get($id20, $meta20, $count20)->setCustomName($in20));

	    $inventory->setItem(21, Item::get($id21, $meta21, $count21)->setCustomName($in21));

	    $inventory->setItem(22, Item::get($id22, $meta22, $count22)->setCustomName($in22));

	    $inventory->setItem(23, Item::get($id23, $meta23, $count23)->setCustomName($in23));

	    $inventory->setItem(24, Item::get($id24, $meta24, $count24)->setCustomName($in24));

	    $inventory->setItem(25, Item::get($id25, $meta25, $count25)->setCustomName($in25));

	    $inventory->setItem(26, Item::get($id26, $meta26, $count26)->setCustomName($in26));



	    

	    $banksize->send($sender);

	}

	public function banksetting(Player $sender, Item $item)

	{

	   $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        $hand = $sender->getInventory()->getItemInHand()->getCustomName();

        $inventory = $banksize->getInventory();

        

	 if($item->getId() === $cfg->get("paper-id") && $item->getDamage() === $cfg->get("paper-meta")){

        	$hand = $sender->getInventory()->getItemInHand()->getCustomName();

          $inventory = $banksize->getInventory();

          $sender->getLevel()->broadcastLevelSoundEvent($sender->add(0, $sender->eyeHeight, 0), LevelSoundEventPacket::SOUND_UI_LOOM_TAKE_RESULT);

        }

        if($item->getId() === $cfg->get("decor-id") && $item->getDamage() === $cfg->get("decor-meta")){

        	$hand = $sender->getInventory()->getItemInHand()->getCustomName();

          $inventory = $banksize->getInventory();

          $volume = mt_rand();

	     $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_CLICK, (int) $volume);

        }

        if($item->getId() === $cfg->get("exit-id") && $item->getDamage() === $cfg->get("exit-meta")){

        	$hand = $sender->getInventory()->getItemInHand()->getCustomName();

          $inventory = $banksize->getInventory();

          $sender->removeWindow($inventory);

          $sender->getLevel()->broadcastLevelSoundEvent($sender->add(0, $sender->eyeHeight, 0), LevelSoundEventPacket::SOUND_CHEST_CLOSED);

        }

        if($item->getId() === $cfg->get("deposit-id") && $item->getDamage() === $cfg->get("deposit-meta")){

        	$hand = $sender->getInventory()->getItemInHand()->getCustomName();

          $inventory = $banksize->getInventory();

          $sender->getLevel()->broadcastLevelSoundEvent($sender->add(0, $sender->eyeHeight, 0), LevelSoundEventPacket::SOUND_BLOCK_BARREL_OPEN);

          $this->deposit($sender);

        }

        if($item->getId() === $cfg->get("withdraw-id") && $item->getDamage() === $cfg->get("withdraw-meta")){

        	$hand = $sender->getInventory()->getItemInHand()->getCustomName();

          $inventory = $banksize->getInventory();

          $sender->getLevel()->broadcastLevelSoundEvent($sender->add(0, $sender->eyeHeight, 0), LevelSoundEventPacket::SOUND_BLOCK_BARREL_OPEN);

          $this->withdraw($sender);

        }

	}

	public function deposit($sender)

         {

	    $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

	    $titlepotions1 = $cfg->get("title");

	    $titlepotions = str_replace(["&", "+n",], ["§", "\n"], $titlepotions1);

	    $banksize->readonly();

	    $banksize->setListener([$this, "depositsetting"]);

         $banksize->setName($titlepotions);

	    $inventory = $banksize->getInventory();

	    //0

	    $inconfig = $cfg->get("deposit.item.name");

	    $id = $cfg->get("deposit.item.id");

	    $meta = $cfg->get("deposit.item.meta");

	    $count = $cfg->get("deposit.item.count");

	    $in = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $in);

	    //1

	    $inconfig1 = $cfg->get("deposit.item.name1");

	    $id1 = $cfg->get("deposit.item.id1");

	    $meta1 = $cfg->get("deposit.item.meta1");

	    $count1 = $cfg->get("deposit.item.count1");

	    $in1 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig1);

	    //2 

	    $inconfig2 = $cfg->get("deposit.item.name2");

	    $id2 = $cfg->get("deposit.item.id2");

	    $meta2 = $cfg->get("deposit.item.meta2");

	    $count2 = $cfg->get("deposit.item.count2");

	    $in2 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig2);

	    //3 

	    $inconfig3 = $cfg->get("deposit.item.name3");

	    $id3 = $cfg->get("deposit.item.id3");

	    $meta3 = $cfg->get("deposit.item.meta3");

	    $count3 = $cfg->get("deposit.item.count3");

	    $in3 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig3);

	    //4 

	    $inconfig4 = $cfg->get("deposit.item.name4");

	    $id4 = $cfg->get("deposit.item.id4");

	    $meta4 = $cfg->get("deposit.item.meta4");

	    $count4 = $cfg->get("deposit.item.count4");

	    $in4 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig4);

	    //5 

	    $inconfig5 = $cfg->get("deposit.item.name5");

	    $id5 = $cfg->get("deposit.item.id5");

	    $meta5 = $cfg->get("deposit.item.meta5");

	    $count5 = $cfg->get("deposit.item.count5");

	    $in5 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig5);

	    //6 

	    $inconfig6 = $cfg->get("deposit.item.name6");

	    $id6 = $cfg->get("deposit.item.id6");

	    $meta6 = $cfg->get("deposit.item.meta6");

	    $count6 = $cfg->get("deposit.item.count6");

	    $in6 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig6);

	    //7 

	    $inconfig7 = $cfg->get("deposit.item.name7");

	    $id7 = $cfg->get("deposit.item.id7");

	    $meta7 = $cfg->get("deposit.item.meta7");

	    $count7 = $cfg->get("deposit.item.count7");

	    $in7 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig7);

	    //8 

	    $inconfig8 = $cfg->get("deposit.item.name8");

	    $id8 = $cfg->get("deposit.item.id8");

	    $meta8 = $cfg->get("deposit.item.meta8");

	    $count8 = $cfg->get("deposit.item.count8");

	    $in8 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig8);

	    //9 

	    $inconfig9 = $cfg->get("deposit.item.name9");

	    $id9 = $cfg->get("deposit.item.id9");

	    $meta9 = $cfg->get("deposit.item.meta9");

	    $count9 = $cfg->get("deposit.item.count9");

	    $in9 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig9);

	    //10 

	    $inconfig10 = $cfg->get("deposit.item.name10");

	    $id10 = $cfg->get("deposit.item.id10");

	    $meta10 = $cfg->get("deposit.item.meta10");

	    $count10 = $cfg->get("deposit.item.count10");

	    $in10 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig10);

	    //11 

	    $inconfig11 = $cfg->get("deposit.item.name11");

	    $id11 = $cfg->get("deposit.item.id11");

	    $meta11 = $cfg->get("deposit.item.meta11");

	    $count11 = $cfg->get("deposit.item.count11");

	    $in11 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig11);

	    //12

	    $inconfig12 = $cfg->get("deposit.item.name12");

	    $id12 = $cfg->get("deposit.item.id12");

	    $meta12 = $cfg->get("deposit.item.meta12");

	    $count12 = $cfg->get("deposit.item.count12");

	    $in12 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig12);

	    //13

	    $inconfig13 = $cfg->get("deposit.item.name13");

	    $id13 = $cfg->get("deposit.item.id13");

	    $meta13 = $cfg->get("deposit.item.meta13");

	    $count13 = $cfg->get("deposit.item.count13");

	    $in13 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig13);

	    //14

	    $inconfig14 = $cfg->get("deposit.item.name14");

	    $id14 = $cfg->get("deposit.item.id14");

	    $meta14 = $cfg->get("deposit.item.meta14");

	    $count14 = $cfg->get("deposit.item.count14");

	    $in14 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig14);

	    //15 	    

	    $inconfig15 = $cfg->get("deposit.item.name15");

	    $id15 = $cfg->get("deposit.item.id15");

	    $meta15 = $cfg->get("deposit.item.meta15");

	    $count15 = $cfg->get("deposit.item.count15");

	    $in15 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig15);

	    //16 	    

	    $inconfig16 = $cfg->get("deposit.item.name16");

	    $id16 = $cfg->get("deposit.item.id16");

	    $meta16 = $cfg->get("deposit.item.meta16");

	    $count16 = $cfg->get("deposit.item.count16");

	    $in16 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig16);

	    //17 	    

	    $inconfig17 = $cfg->get("deposit.item.name17");

	    $id17 = $cfg->get("deposit.item.id17");

	    $meta17 = $cfg->get("deposit.item.meta17");

	    $count17 = $cfg->get("deposit.item.count17");

	    $in17 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig17);

	    //18

	    $inconfig18 = $cfg->get("deposit.item.name18");

	    $id18 = $cfg->get("deposit.item.id18");

	    $meta18 = $cfg->get("deposit.item.meta18");

	    $count18 = $cfg->get("deposit.item.count18");

	    $in18 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig18);

	    //19

	    $inconfig19 = $cfg->get("deposit.item.name19");

	    $id19 = $cfg->get("deposit.item.id19");

	    $meta19 = $cfg->get("deposit.item.meta19");

	    $count19 = $cfg->get("deposit.item.count19");

	    $in19 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig19);

	    //20

	    $inconfig20 = $cfg->get("deposit.item.name20");

	    $id20 = $cfg->get("deposit.item.id20");

	    $meta20 = $cfg->get("deposit.item.meta20");

	    $count20 = $cfg->get("deposit.item.count20");

	    $in20 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig20);

	    //21

	    $inconfig21 = $cfg->get("deposit.item.name21");

	    $id21 = $cfg->get("deposit.item.id21");

	    $meta21 = $cfg->get("deposit.item.meta21");

	    $count21 = $cfg->get("deposit.item.count21");

	    $in21 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig21);

	    //22

	    $inconfig22 = $cfg->get("deposit.item.name22");

	    $id22 = $cfg->get("deposit.item.id22");

	    $meta22 = $cfg->get("deposit.item.meta22");

	    $count22 = $cfg->get("deposit.item.count22");

	    $in22 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig22);

	    //23

	    $inconfig23 = $cfg->get("deposit.item.name23");

	    $id23 = $cfg->get("deposit.item.id23");

	    $meta23 = $cfg->get("deposit.item.meta23");

	    $count23 = $cfg->get("deposit.item.count23");

	    $in23 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig23);

	    //24

	    $inconfig24 = $cfg->get("deposit.item.name24");

	    $id24 = $cfg->get("deposit.item.id24");

	    $meta24 = $cfg->get("deposit.item.meta24");

	    $count24 = $cfg->get("deposit.item.count24");

	    $in24 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig24);

	    //25

	    $inconfig25 = $cfg->get("deposit.item.name25");

	    $id25 = $cfg->get("deposit.item.id25");

	    $meta25 = $cfg->get("deposit.item.meta25");

	    $count25 = $cfg->get("deposit.item.count25");

	    $in25 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig25);

	    //26

	    $inconfig26 = $cfg->get("deposit.item.name26");

	    $id26 = $cfg->get("deposit.item.id26");

	    $meta26 = $cfg->get("deposit.item.meta26");

	    $count26 = $cfg->get("deposit.item.count26");

	    $in26 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig26);

	    //Chest Section 1-8

	    $inventory->setItem(0, Item::get($id, $meta, $count)->setCustomName($in));

	    $inventory->setItem(1, Item::get($id1, $meta1, $count1)->setCustomName($in1));

	    $inventory->setItem(2, Item::get($id2, $meta2, $count2)->setCustomName($in2));

	    $inventory->setItem(3, Item::get($id3, $meta3, $count3)->setCustomName($in3));

	    $inventory->setItem(4, Item::get($id4, $meta4, $count4)->setCustomName($in4));

	    $inventory->setItem(5, Item::get($id5, $meta5, $count5)->setCustomName($in5));

	    $inventory->setItem(6, Item::get($id6, $meta6, $count6)->setCustomName($in6));

	    $inventory->setItem(7, Item::get($id7, $meta7, $count7)->setCustomName($in7));

	    $inventory->setItem(8, Item::get($id8, $meta8, $count8)->setCustomName($in8));

         //Chest Section 9-17

         $inventory->setItem(9, Item::get($id9, $meta9, $count9)->setCustomName($in9));

	    $inventory->setItem(10, Item::get($id10, $meta10, $count10)->setCustomName($in10));

	    $inventory->setItem(11, Item::get($id11, $meta11, $count11)->setCustomName($in11));

	    $inventory->setItem(12, Item::get($id12, $meta12, $count12)->setCustomName($in12));

	    $inventory->setItem(13, Item::get($id13, $meta13, $count13)->setCustomName($in13));

	    $inventory->setItem(14, Item::get($id14, $meta14, $count14)->setCustomName($in14));

	    $inventory->setItem(15, Item::get($id15, $meta15, $count15)->setCustomName($in15));

	    $inventory->setItem(16, Item::get($id16, $meta16, $count16)->setCustomName($in16));

	    $inventory->setItem(17, Item::get($id17, $meta17, $count17)->setCustomName($in17));

         //Chest Section 18-26

         $inventory->setItem(18, Item::get($id18, $meta18, $count18)->setCustomName($in18));

	    $inventory->setItem(19, Item::get($id19, $meta19, $count19)->setCustomName($in19));

	    $inventory->setItem(20, Item::get($id20, $meta20, $count20)->setCustomName($in20));

	    $inventory->setItem(21, Item::get($id21, $meta21, $count21)->setCustomName($in21));

	    $inventory->setItem(22, Item::get($id22, $meta22, $count22)->setCustomName($in22));

	    $inventory->setItem(23, Item::get($id23, $meta23, $count23)->setCustomName($in23));

	    $inventory->setItem(24, Item::get($id24, $meta24, $count24)->setCustomName($in24));

	    $inventory->setItem(25, Item::get($id25, $meta25, $count25)->setCustomName($in25));

	    $inventory->setItem(26, Item::get($id26, $meta26, $count26)->setCustomName($in26));



	    $banksize->send($sender);

	}

	public function depositsetting(Player $sender, Item $item){

	   $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        $hand = $sender->getInventory()->getItemInHand()->getCustomName();

        $inventory = $banksize->getInventory();

        

	 if($item->getId() === $cfg->get("back-id") && $item->getDamage() === $cfg->get("back-meta")){

        	$hand = $sender->getInventory()->getItemInHand()->getCustomName();

          $inventory = $banksize->getInventory();

          $sender->getLevel()->broadcastLevelSoundEvent($sender->add(0, $sender->eyeHeight, 0), LevelSoundEventPacket::SOUND_CHEST_OPEN);

          $this->bankmenu($sender);

        }

        if($item->getId() === $cfg->get("decors-id") && $item->getDamage() === $cfg->get("decors-meta")){

        	$hand = $sender->getInventory()->getItemInHand()->getCustomName();

          $inventory = $banksize->getInventory();

          $volume = mt_rand();

	     $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_CLICK, (int) $volume);

        }

        if($item->getId() === $cfg->get("exit-ids") && $item->getDamage() === $cfg->get("exit-metas")){

        	$hand = $sender->getInventory()->getItemInHand()->getCustomName();

          $inventory = $banksize->getInventory();

          $sender->removeWindow($inventory);

          $sender->getLevel()->broadcastLevelSoundEvent($sender->add(0, $sender->eyeHeight, 0), LevelSoundEventPacket::SOUND_CHEST_CLOSED);

        }

        if($item->getId() === $cfg->get("deposit-id1") && $item->getDamage() === $cfg->get("deposit-meta1")){

        	$hand = $sender->getInventory()->getItemInHand()->getCustomName();

          $inventory = $banksize->getInventory();

        	$sender->removeWindow($inventory);

        	$sender->getLevel()->broadcastLevelSoundEvent($sender->add(0, $sender->eyeHeight, 0), LevelSoundEventPacket::SOUND_CHEST_CLOSED);

        	$seconds = 2;

        	$this->getScheduler()->scheduleDelayedTask(new \pocketmine\scheduler\ClosureTask( 

        		function(int $currentTick) use ($sender): void{

        			$this->deposituibank($sender);

        			$sender->getLevel()->broadcastLevelSoundEvent($sender->add(0, $sender->eyeHeight, 0), LevelSoundEventPacket::SOUND_CHEST_OPEN);

        		}

        	), 20 * $seconds);

        }

	}

	public function withdraw($sender)

         {

         	

	    $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

	    

	    $titlepotions1 = $cfg->get("title");

	    $titlepotions = str_replace(["&", "+n",], ["§", "\n"], $titlepotions1);

	    $banksize->readonly();

	    $banksize->setListener([$this, "withdrawsetting"]);

         $banksize->setName($titlepotions);

	    $inventory = $banksize->getInventory();

	    

	    //0

	    $inconfig = $cfg->get("withdraw.item.name");

	    $id = $cfg->get("withdraw.item.id");

	    $meta = $cfg->get("withdraw.item.meta");

	    $count = $cfg->get("withdraw.item.count");

	    $in = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $in);

	    //1

	    $inconfig1 = $cfg->get("withdraw.item.name1");

	    $id1 = $cfg->get("withdraw.item.id1");

	    $meta1 = $cfg->get("withdraw.item.meta1");

	    $count1 = $cfg->get("withdraw.item.count1");

	    $in1 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig1);

	    //2 

	    $inconfig2 = $cfg->get("withdraw.item.name2");

	    $id2 = $cfg->get("withdraw.item.id2");

	    $meta2 = $cfg->get("withdraw.item.meta2");

	    $count2 = $cfg->get("withdraw.item.count2");

	    $in2 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig2);

	    //3 

	    $inconfig3 = $cfg->get("withdraw.item.name3");

	    $id3 = $cfg->get("withdraw.item.id3");

	    $meta3 = $cfg->get("withdraw.item.meta3");

	    $count3 = $cfg->get("withdraw.item.count3");

	    $in3 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig3);

	    //4 

	    $inconfig4 = $cfg->get("withdraw.item.name4");

	    $id4 = $cfg->get("withdraw.item.id4");

	    $meta4 = $cfg->get("withdraw.item.meta4");

	    $count4 = $cfg->get("withdraw.item.count4");

	    $in4 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig4);

	    //5 

	    $inconfig5 = $cfg->get("withdraw.item.name5");

	    $id5 = $cfg->get("withdraw.item.id5");

	    $meta5 = $cfg->get("withdraw.item.meta5");

	    $count5 = $cfg->get("withdraw.item.count5");

	    $in5 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig5);

	    //6 

	    $inconfig6 = $cfg->get("withdraw.item.name6");

	    $id6 = $cfg->get("withdraw.item.id6");

	    $meta6 = $cfg->get("withdraw.item.meta6");

	    $count6 = $cfg->get("withdraw.item.count6");

	    $in6 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig6);

	    //7 

	    $inconfig7 = $cfg->get("withdraw.item.name7");

	    $id7 = $cfg->get("withdraw.item.id7");

	    $meta7 = $cfg->get("withdraw.item.meta7");

	    $count7 = $cfg->get("withdraw.item.count7");

	    $in7 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig7);

	    //8 

	    $inconfig8 = $cfg->get("withdraw.item.name8");

	    $id8 = $cfg->get("withdraw.item.id8");

	    $meta8 = $cfg->get("withdraw.item.meta8");

	    $count8 = $cfg->get("withdraw.item.count8");

	    $in8 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig8);

	    //9 

	    $inconfig9 = $cfg->get("withdraw.item.name9");

	    $id9 = $cfg->get("withdraw.item.id9");

	    $meta9 = $cfg->get("withdraw.item.meta9");

	    $count9 = $cfg->get("withdraw.item.count9");

	    $in9 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig9);

	    //10 

	    $inconfig10 = $cfg->get("withdraw.item.name10");

	    $id10 = $cfg->get("withdraw.item.id10");

	    $meta10 = $cfg->get("withdraw.item.meta10");

	    $count10 = $cfg->get("withdraw.item.count10");

	    $in10 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig10);

	    //11 

	    $inconfig11 = $cfg->get("withdraw.item.name11");

	    $id11 = $cfg->get("withdraw.item.id11");

	    $meta11 = $cfg->get("withdraw.item.meta11");

	    $count11 = $cfg->get("withdraw.item.count11");

	    $in11 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig11);

	    //12

	    $inconfig12 = $cfg->get("withdraw.item.name12");

	    $id12 = $cfg->get("withdraw.item.id12");

	    $meta12 = $cfg->get("withdraw.item.meta12");

	    $count12 = $cfg->get("withdraw.item.count12");

	    $in12 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig12);

	    //13

	    $inconfig13 = $cfg->get("withdraw.item.name13");

	    $id13 = $cfg->get("withdraw.item.id13");

	    $meta13 = $cfg->get("withdraw.item.meta13");

	    $count13 = $cfg->get("withdraw.item.count13");

	    $in13 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig13);

	    //14

	    $inconfig14 = $cfg->get("withdraw.item.name14");

	    $id14 = $cfg->get("withdraw.item.id14");

	    $meta14 = $cfg->get("withdraw.item.meta14");

	    $count14 = $cfg->get("withdraw.item.count14");

	    $in14 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig14);

	    //15 	    

	    $inconfig15 = $cfg->get("withdraw.item.name15");

	    $id15 = $cfg->get("withdraw.item.id15");

	    $meta15 = $cfg->get("withdraw.item.meta15");

	    $count15 = $cfg->get("withdraw.item.count15");

	    $in15 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig15);

	    //16 	    

	    $inconfig16 = $cfg->get("withdraw.item.name16");

	    $id16 = $cfg->get("withdraw.item.id16");

	    $meta16 = $cfg->get("withdraw.item.meta16");

	    $count16 = $cfg->get("withdraw.item.count16");

	    $in16 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig16);

	    //17 	    

	    $inconfig17 = $cfg->get("withdraw.item.name17");

	    $id17 = $cfg->get("withdraw.item.id17");

	    $meta17 = $cfg->get("withdraw.item.meta17");

	    $count17 = $cfg->get("withdraw.item.count17");

	    $in17 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig17);

	    //18

	    $inconfig18 = $cfg->get("withdraw.item.name18");

	    $id18 = $cfg->get("withdraw.item.id18");

	    $meta18 = $cfg->get("withdraw.item.meta18");

	    $count18 = $cfg->get("withdraw.item.count18");

	    $in18 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig18);

	    //19

	    $inconfig19 = $cfg->get("withdraw.item.name19");

	    $id19 = $cfg->get("withdraw.item.id19");

	    $meta19 = $cfg->get("withdraw.item.meta19");

	    $count19 = $cfg->get("withdraw.item.count19");

	    $in19 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig19);

	    //20

	    $inconfig20 = $cfg->get("withdraw.item.name20");

	    $id20 = $cfg->get("withdraw.item.id20");

	    $meta20 = $cfg->get("withdraw.item.meta20");

	    $count20 = $cfg->get("withdraw.item.count20");

	    $in20 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig20);

	    //21

	    $inconfig21 = $cfg->get("withdraw.item.name21");

	    $id21 = $cfg->get("withdraw.item.id21");

	    $meta21 = $cfg->get("withdraw.item.meta21");

	    $count21 = $cfg->get("withdraw.item.count21");

	    $in21 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig21);

	    //22

	    $inconfig22 = $cfg->get("withdraw.item.name22");

	    $id22 = $cfg->get("withdraw.item.id22");

	    $meta22 = $cfg->get("withdraw.item.meta22");

	    $count22 = $cfg->get("withdraw.item.count22");

	    $in22 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig22);

	    //23

	    $inconfig23 = $cfg->get("withdraw.item.name23");

	    $id23 = $cfg->get("withdraw.item.id23");

	    $meta23 = $cfg->get("withdraw.item.meta23");

	    $count23 = $cfg->get("withdraw.item.count23");

	    $in23 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig23);

	    //24

	    $inconfig24 = $cfg->get("withdraw.item.name24");

	    $id24 = $cfg->get("withdraw.item.id24");

	    $meta24 = $cfg->get("withdraw.item.meta24");

	    $count24 = $cfg->get("withdraw.item.count24");

	    $in24 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig24);

	    //25

	    $inconfig25 = $cfg->get("withdraw.item.name25");

	    $id25 = $cfg->get("withdraw.item.id25");

	    $meta25 = $cfg->get("withdraw.item.meta25");

	    $count25 = $cfg->get("withdraw.item.count25");

	    $in25 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig25);

	    //26

	    $inconfig26 = $cfg->get("withdraw.item.name26");

	    $id26 = $cfg->get("withdraw.item.id26");

	    $meta26 = $cfg->get("withdraw.item.meta26");

	    $count26 = $cfg->get("withdraw.item.count26");

	    $in26 = str_replace(["&", "+n", "%player_name%", "%player_displayname%", "%player_balance%", "%player_bank_balance%"], ["§", "\n", $sender->getName(), $sender->getDisplayName(), $economy->myMoney($sender), $this->getMoneyInBank($sender)], $inconfig26);

	    //Chest Section 1-8

	    $inventory->setItem(0, Item::get($id, $meta, $count)->setCustomName($in));

	    $inventory->setItem(1, Item::get($id1, $meta1, $count1)->setCustomName($in1));

	    $inventory->setItem(2, Item::get($id2, $meta2, $count2)->setCustomName($in2));

	    $inventory->setItem(3, Item::get($id3, $meta3, $count3)->setCustomName($in3));

	    $inventory->setItem(4, Item::get($id4, $meta4, $count4)->setCustomName($in4));

	    $inventory->setItem(5, Item::get($id5, $meta5, $count5)->setCustomName($in5));

	    $inventory->setItem(6, Item::get($id6, $meta6, $count6)->setCustomName($in6));

	    $inventory->setItem(7, Item::get($id7, $meta7, $count7)->setCustomName($in7));

	    $inventory->setItem(8, Item::get($id8, $meta8, $count8)->setCustomName($in8));

         //Chest Section 9-17

         $inventory->setItem(9, Item::get($id9, $meta9, $count9)->setCustomName($in9));

	    $inventory->setItem(10, Item::get($id10, $meta10, $count10)->setCustomName($in10));

	    $inventory->setItem(11, Item::get($id11, $meta11, $count11)->setCustomName($in11));

	    $inventory->setItem(12, Item::get($id12, $meta12, $count12)->setCustomName($in12));

	    $inventory->setItem(13, Item::get($id13, $meta13, $count13)->setCustomName($in13));

	    $inventory->setItem(14, Item::get($id14, $meta14, $count14)->setCustomName($in14));

	    $inventory->setItem(15, Item::get($id15, $meta15, $count15)->setCustomName($in15));

	    $inventory->setItem(16, Item::get($id16, $meta16, $count16)->setCustomName($in16));

	    $inventory->setItem(17, Item::get($id17, $meta17, $count17)->setCustomName($in17));

         //Chest Section 18-26

         $inventory->setItem(18, Item::get($id18, $meta18, $count18)->setCustomName($in18));

	    $inventory->setItem(19, Item::get($id19, $meta19, $count19)->setCustomName($in19));

	    $inventory->setItem(20, Item::get($id20, $meta20, $count20)->setCustomName($in20));

	    $inventory->setItem(21, Item::get($id21, $meta21, $count21)->setCustomName($in21));

	    $inventory->setItem(22, Item::get($id22, $meta22, $count22)->setCustomName($in22));

	    $inventory->setItem(23, Item::get($id23, $meta23, $count23)->setCustomName($in23));

	    $inventory->setItem(24, Item::get($id24, $meta24, $count24)->setCustomName($in24));

	    $inventory->setItem(25, Item::get($id25, $meta25, $count25)->setCustomName($in25));

	    $inventory->setItem(26, Item::get($id26, $meta26, $count26)->setCustomName($in26));



	    $banksize->send($sender);

	}

	public function withdrawsetting(Player $sender, Item $item){

	   $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        $hand = $sender->getInventory()->getItemInHand()->getCustomName();

        $inventory = $banksize->getInventory();

        

	 if($item->getId() === $cfg->get("backs-id") && $item->getDamage() === $cfg->get("backs-meta")){

        	$hand = $sender->getInventory()->getItemInHand()->getCustomName();

          $inventory = $banksize->getInventory();

          $sender->getLevel()->broadcastLevelSoundEvent($sender->add(0, $sender->eyeHeight, 0), LevelSoundEventPacket::SOUND_CHEST_OPEN);

          $this->bankmenu($sender);

        }

        if($item->getId() === $cfg->get("decorss-id") && $item->getDamage() === $cfg->get("decorss-meta")){

        	$hand = $sender->getInventory()->getItemInHand()->getCustomName();

          $inventory = $banksize->getInventory();

          $volume = mt_rand();

	     $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_CLICK, (int) $volume);

        }

        if($item->getId() === $cfg->get("exit-idss") && $item->getDamage() === $cfg->get("exit-metass")){

        	$hand = $sender->getInventory()->getItemInHand()->getCustomName();

          $inventory = $banksize->getInventory();

          $sender->removeWindow($inventory);

          $sender->getLevel()->broadcastLevelSoundEvent($sender->add(0, $sender->eyeHeight, 0), LevelSoundEventPacket::SOUND_CHEST_CLOSED);

        }

        if($item->getId() === $cfg->get("withdraw-id1") && $item->getDamage() === $cfg->get("withdraw-meta1")){

        	$hand = $sender->getInventory()->getItemInHand()->getCustomName();

          $inventory = $banksize->getInventory();

        	$sender->removeWindow($inventory);

        	$sender->getLevel()->broadcastLevelSoundEvent($sender->add(0, $sender->eyeHeight, 0), LevelSoundEventPacket::SOUND_CHEST_CLOSED);

	     $seconds = 2;

        	$this->getScheduler()->scheduleDelayedTask(new \pocketmine\scheduler\ClosureTask( 

        		function(int $currentTick) use ($sender): void{

        			$this->withdrawuibank($sender);

        			$sender->getLevel()->broadcastLevelSoundEvent($sender->add(0, $sender->eyeHeight, 0), LevelSoundEventPacket::SOUND_CHEST_OPEN);

        		}

        	), 20 * $seconds);

        }

	}

	public function deposituibank($sender){

		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");

          $form = $formapi->createCustomForm(function(Player $sender, $data){

			$result = $data[0];

			if($result === null){

				return true;

			}

			if(trim($data[0]) === "") {

				$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

				$sender->sendMessage($cfg->get("msg-put-number"));

				$volume = mt_rand();

				$sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ANVIL_FALL, (int) $volume);

				return true;

			}

			if(is_numeric($data[0])){

				$money = $economy->myMoney($sender);

				if($money >= $data[0]){

					EconomyAPI::getInstance()->reduceMoney($sender, $data[0]);

					$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

					$sender->sendMessage($cfg->get("msg-deposited").$data[0]);

					$volume = mt_rand();

				     $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ORB, (int) $volume);

					$this->addMoney($sender, $data[0]);

				}else{

					$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

					$sender->sendMessage($cfg->get("msg-no-enough-money").$data[0]);

					$volume = mt_rand();

				     $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ANVIL_FALL, (int) $volume);

				}

			}else{

				$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

				$sender->sendMessage($cfg->get("msg-put-number"));

				$volume = mt_rand();

				$sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ANVIL_FALL, (int) $volume);

				return true;

			}

		});

		 $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

	      $form->setTitle($cfg->get("title-deposit"));

	      $form->addInput($cfg->get("content-deposit"), "Amount...");

           $form->sendToPlayer($sender);

           return $form;

      	}

      	public function withdrawuibank($sender){

		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");

          $form = $formapi->createCustomForm(function(Player $sender, $data){

			$result = $data[0];

			if($result === null){

				return true;

			}

			if(trim($data[0]) === "") {

				$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

				$sender->sendMessage($cfg->get("msg-put-numbers"));

				$volume = mt_rand();

				$sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ANVIL_FALL, (int) $volume);

				return true;

			}

			if(is_numeric($data[0])){

				$money = $economy->myMoney($sender);

				$mb = $this->getMoneyInBank($sender);

				if($mb >= $data[0]){

					EconomyAPI::getInstance()->addMoney($sender, $data[0]);

					$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

					$sender->sendMessage($cfg->get("msg-withdrawed").$data[0]);

					$volume = mt_rand();

				     $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ORB, (int) $volume);

					$this->reduceMoney($sender, $data[0]);

				}else{

					$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

					$sender->sendMessage($cfg->get("msg-no-enough-bank").$data[0]);

					$volume = mt_rand();

				     $sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ANVIL_FALL, (int) $volume);

				}

			}else{

				$cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

				$sender->sendMessage($cfg->get("msg-put-numbers"));

				$volume = mt_rand();

				$sender->getLevel()->broadcastLevelEvent($sender, LevelEventPacket::EVENT_SOUND_ANVIL_FALL, (int) $volume);

				return true;

			}

		});

		 $cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);

	  $form->setTitle($cfg->get("title-withdraw"));

	  $form->addInput($cfg->get("content-withdraw"), "Amount...");

       $form->sendToPlayer($sender);

        return $form;

	}

        /**

         *DEPOSIT CODE

         * */

         public function addMoney($sender, $int){

		$bbs = strtolower($sender->getName());

		$database->set($bbs, $database->get($bbs) + $int);

		$database->save();

	}

	public function reduceMoney($sender, $int){

		$bbs = strtolower($sender->getName());

		$database->set($bbs, $database->get($bbs) - $int);

		$database->save();

	}

	public function getMoneyInBank($sender){

		$bbs = strtolower($sender->getName());

		return $database->get($bbs);

	}

}
