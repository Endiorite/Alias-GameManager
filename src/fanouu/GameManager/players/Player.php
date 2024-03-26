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
    private ?string $matchUuid = "";
    private ?string $matchIdentifier = "";

    public function __construct(
        private string $name
    )
    {
    }

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
     * @param string|null $matchUuid
     */
    public function setMatchUuid(?string $matchUuid): void
    {
        $this->matchUuid = $matchUuid;
    }

    /**
     * @return string|null
     */
    public function getMatchUuid(): ?string
    {
        return $this->matchUuid;
    }

    /**
     * @param string|null $matchIdentifier
     */
    public function setMatchIdentifier(?string $matchIdentifier): void
    {
        $this->matchIdentifier = $matchIdentifier;
    }

    /**
     * @return string|null
     */
    public function getMatchIdentifier(): ?string
    {
        return $this->matchIdentifier;
    }
}