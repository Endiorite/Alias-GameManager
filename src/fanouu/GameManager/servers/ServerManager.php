<?php

namespace fanouu\GameManager\servers;

use fanouu\GameManager\players\Player;
use fanouu\GameManager\utils\SingletonTrait;

class ServerManager
{
    use SingletonTrait;

    /**
     * @var GameServer[] array
     */
    private array $servers = [];

    private array $searchByAdress = [];

    public function __construct()
    {
        self::setInstance($this);
    }

    /**
     * @return array
     */
    public function getServers(): array
    {
        return $this->servers;
    }
    public function getServer(string $serverName): ?GameServer{
        return $this->servers[$serverName] ?? null;
    }

    public function addServers(GameServer $gameServer): GameServer{
        return $this->servers[$gameServer->getName()] = $gameServer;
    }

    public function getServerByAddress(string $address, int $port): ?GameServer{
        $addressStr = implode(":", [$address, $port]);

        if (isset($this->searchByAdress[$addressStr])){
            $searchName = $this->searchByAdress[$addressStr];
            if (isset($this->servers[$searchName])){
                return $this->servers[$searchName];
            }else{
                unset($this->searchByAdress[$addressStr]);
            }
        }

        foreach ($this->servers as $serverName => $server){
            if ($server->getAddress() === $address && $server->getPort() === $port){
                $this->searchByAdress[$addressStr] = $serverName;
            }
        }

        return $this->searchByAdress[$addressStr] ?? null;
    }

    public function getOptimalServer(): ?GameServer{
        $server = null;
        foreach ($this->servers as $gameServer){
            if (is_null($server)){
                $server = $gameServer;
                continue;
            }

            if ($server->getPlayersCount() > $gameServer->getPlayersCount()){
                $server = $gameServer;
            }
        }

        return $server;
    }
}