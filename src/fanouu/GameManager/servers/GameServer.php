<?php

namespace fanouu\GameManager\servers;

use fanouu\GameManager\packets\Packet;
use fanouu\GameManager\Server;

class GameServer
{

    private $last_sent_packet;
    private $last_receive_packet;
    private int $playersCount = 0;

    public function __construct(
        private readonly string $name,
        private readonly string $address,
        private readonly int $port
    )
    {
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function lastSentPacket(): void
    {
        $this->last_sent_packet = time();
    }

    public function lastReceivePacket(): void
    {
        $this->last_receive_packet = time();
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    public static function setUp(string $serverName, string $address, int $port): GameServer{
        $server = new self($serverName, $address, $port);
        return ServerManager::getInstance()->addServers($server);
    }

    public function sendPacket(Packet $packet): void{
        Server::getInstance()->getSocket()->sendTo($packet, $this);
    }

    /**
     * @return int
     */
    public function getPlayersCount(): int
    {
        return $this->playersCount;
    }

    /**
     * @param int $playersCount
     */
    public function setPlayersCount(int $playersCount): void
    {
        $this->playersCount = $playersCount;
    }

}