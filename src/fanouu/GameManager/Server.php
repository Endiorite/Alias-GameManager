<?php

namespace fanouu\GameManager;

require __DIR__ . '/vendor/autoload.php';

use fanouu\GameManager\matchmaking\MatchMakingManager;
use fanouu\GameManager\matchmaking\MatchMakingThread;
use fanouu\GameManager\players\PlayerManager;
use fanouu\GameManager\servers\ServerManager;
use fanouu\GameManager\utils\Logger;
use fanouu\GameManager\utils\SingletonTrait;

class Server
{
    use SingletonTrait;

    private ?GameThread $thread = null;
    private ?MatchMakingThread $makingThread = null;
    private ?PlayerManager $playerManager = null;
    private ?Logger $logger = null;
    private ?MatchMakingManager $makingManager = null;
    private ?ServerManager $serverManager = null;

    public const WHITELIST = [
        "address:port"
    ];

    public function __construct()
    {
        self::setInstance($this);
        $this->logger = new Logger("GameManager");
        $this->logger->info("Initing PlayerManager");
        $this->playerManager = new PlayerManager();
        $this->logger->info("Initing MatchMakingManager");
        $this->makingManager = new MatchMakingManager();
        $this->logger->info("Initing ServerManager");
        $this->serverManager = new ServerManager();
        $this->logger->info("Initing GameThread");
        $this->thread = new GameThread($this);
        $this->thread->start();
        $this->logger->info("Initing MatchMakingThread");
        $this->makingThread = new MatchMakingThread($this, $this->makingManager);
        $this->makingThread->start();
        Server::getInstance()->getLogger()->notice("GameThread was started");

    }

    /**
     * @return PlayerManager|null
     */
    public function getPlayerManager(): ?PlayerManager
    {
        return $this->playerManager;
    }

    /**
     * @return GameThread|null
     */
    public function getThread(): ?GameThread
    {
        return $this->thread;
    }

    /**
     * @return Logger|null
     */
    public function getLogger(): ?Logger
    {
        return $this->logger;
    }

}