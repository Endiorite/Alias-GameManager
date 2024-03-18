<?php

namespace fanouu\GameManager;

use fanouu\GameManager\players\PlayerManager;
use fanouu\GameManager\utils\Logger;
use fanouu\GameManager\utils\SingletonTrait;

class Server
{
    use SingletonTrait;

    private GameThread|null $thread = null;
    private ?PlayerManager $playerManager = null;
    private ?Logger $logger = null;

    public const WHITELIST = [
        "address:port"
    ];

    public function __construct()
    {
        $this->logger = new Logger("GameManager");
        $this->logger->info("Initing PlayerManager");
        $this->playerManager = new PlayerManager();
        $this->logger->info("Initing GameThread");
        $this->thread = new GameThread($this);
        $this->thread->start();
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