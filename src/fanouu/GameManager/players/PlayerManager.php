<?php

namespace fanouu\GameManager\players;

use fanouu\GameManager\Server;
use fanouu\GameManager\utils\SingletonTrait;

class PlayerManager
{
    use SingletonTrait;

    private array $players = [];

    public function __construct()
    {
        //self::setInstance($this);
        Server::getInstance()->getLogger()->notice("PlayerManager is ready");
    }

    /**
     * @return array
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    public function getPlayer(string $playerName): Player{
        if (!isset($this->players[$playerName])){
            $this->players[$playerName] = new Player($playerName);
        }

        return $this->players[$playerName];
    }
}