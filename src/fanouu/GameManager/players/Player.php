<?php

namespace fanouu\GameManager\players;

use fanouu\GameManager\matchmaking\PlayerMatchInfo;
use fanouu\GameManager\packets\UpdatePlayerStatus;
use fanouu\GameManager\Server;
use fanouu\GameManager\servers\ServerManager;

class Player
{

    private ?string $gameId = null;
    private ?string $serverName = null;
    private int $status = UpdatePlayerStatus::NONE_STATUS;
    private ?PlayerMatchInfo $matchInfo;

    public function __construct(
        private readonly string $name
    ){}

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string|null $gameId
     */
    public function setGameId(?string $gameId): void
    {
        $this->gameId = $gameId;
    }

    /**
     * @return string|null
     */
    public function getGameId(): ?string
    {
        return $this->gameId;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function updateStatus(): void{
        Server::getInstance()->getLogger()->info("Update status for " . $this->name);

        if (is_null($this->serverName)){
            Server::getInstance()->getLogger()->warning("Update failed for " . $this->name . " server name is null");
            return;
        }

        $server = ServerManager::getInstance()->getServer($this->getServerName());
        $packet = new UpdatePlayerStatus();
        $packet->player_name = $this->name;
        $packet->status = $this->status;
        $extraData = [
            "serverName" => $server->getName()
        ];

        if (!is_null($this->gameId)){
            $extraData["gameId"] = $this->gameId;
        }
        if (!is_null($match = $this->matchInfo?->getMatchUuid() ?? null)){
            $extraData["matchId"] = $match;
        }

        $packet->extraData = $extraData;
        $server->sendPacket($packet);
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getServerName(): ?string
    {
        return $this->serverName;
    }

    /**
     * @param string|null $serverName
     */
    public function setServerName(?string $serverName): void
    {
        $this->serverName = $serverName;
    }

    /**
     * @return PlayerMatchInfo|null
     */
    public function getMatchInfo(): ?PlayerMatchInfo
    {
        return $this->matchInfo;
    }

    /**
     * @param PlayerMatchInfo|null $matchInfo
     */
    public function setMatchInfo(?PlayerMatchInfo $matchInfo): void
    {
        $this->matchInfo = $matchInfo;
    }
}