<?php

namespace fanouu\GameManager;

use fanouu\GameManager\matchmaking\MatchMakingManager;
use fanouu\GameManager\players\PlayerManager;
use fanouu\GameManager\servers\ServerManager;
use fanouu\GameManager\utils\Logger;
use fanouu\GameManager\utils\SingletonTrait;

class Server
{
    use SingletonTrait;

    private ?GameSocket $socket = null;
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
        $this->logger->info("Initing GameSocket");
        $this->socket = new GameSocket($this);
        Server::getInstance()->getLogger()->notice("GameSocket was started");

    }

    /**
     * @return PlayerManager|null
     */
    public function getPlayerManager(): ?PlayerManager
    {
        return $this->playerManager;
    }

    /**
     * @return GameSocket|null
     */
    public function getSocket(): ?GameSocket
    {
        return $this->socket;
    }

    /**
     * @return Logger|null
     */
    public function getLogger(): ?Logger
    {
        return $this->logger;
    }

    /**
     * @return MatchMakingManager|null
     */
    public function getMakingManager(): ?MatchMakingManager
    {
        return $this->makingManager;
    }

}