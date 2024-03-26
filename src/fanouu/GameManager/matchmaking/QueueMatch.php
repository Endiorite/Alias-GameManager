<?php

namespace fanouu\GameManager\matchmaking;

use fanouu\GameManager\matchmaking\rules\DefaultMatchRules;
use fanouu\GameManager\matchmaking\rules\MatchRules;
use fanouu\GameManager\packets\InitQueue;
use fanouu\GameManager\packets\TransferPlayerQueue;
use fanouu\GameManager\packets\UpdatePlayerStatus;
use fanouu\GameManager\players\Player;
use fanouu\GameManager\players\PlayerManager;
use fanouu\GameManager\Server;
use fanouu\GameManager\servers\ServerManager;

class QueueMatch
{

    /**
     * @var array
     */
    private array $players = [];

    private int $minPlayer = 1;
    private int $maxPlayer = 2;

    public function __construct(
        private readonly string            $gameIdentifier,
        private readonly string            $matchUuid,
        private readonly MatchRules $matchRules,
    )
    {
    }

    private bool $gameInit = false;
    private ?string $gameUuid = null;
    private ?string $serverName = null;

    /**
     * @param string|null $serverName
     */
    public function setServerName(?string $serverName): void
    {
        $this->serverName = $serverName;
    }

    /**
     * @return string|null
     */
    public function getGameUuid(): ?string
    {
        return $this->gameUuid;
    }

    /**
     * @param string|null $gameUuid
     */
    public function setGameUuid(?string $gameUuid): void
    {
        $this->gameUuid = $gameUuid;
    }

    /**
     * @param bool $gameInit
     */
    public function setGameInit(bool $gameInit): void
    {
        $this->gameInit = $gameInit;
    }

    /**
     * @return bool
     */
    public function gameIsInit(): bool
    {
        return $this->gameInit;
    }

    /**
     * @return Player[] array
     */
    public function getPlayers(): array
    {
        return array_map(function (string $playerName){
            return PlayerManager::getInstance()->getPlayer($playerName);
        }, $this->getPlayers());
    }

    public function addPlayer(string $playerName): void
    {
        $this->players[$playerName] = $playerName;
    }

    /**
     * @param Player[] $players
     * @return void
     */
    public function addPlayers(array $players): void{
        foreach ($players as $player){
            $this->addPlayer($player->getName());
        }
    }

    public function removePlayer(string $playerName): void{
        if (!isset($this->players[$playerName])) return;

        unset($this->players[$playerName]);
    }

    /**
     * @return string
     */
    public function getGameIdentifier(): string
    {
        return $this->gameIdentifier;
    }

    /**
     * @return MatchRules
     */
    public function getMatchRules(): MatchRules
    {
        return $this->matchRules;
    }

    /**
     * @return string
     */
    public function getMatchUuid(): string
    {
        return $this->matchUuid;
    }

    public function start(): void
    {
        //TODO: choisir le serveur avec le moins de joueurs
        $server = ServerManager::getInstance()->getOptimalServer();
        if (is_null($server)) {
            foreach ($this->getPlayers() as $player) {
                $player->setStatus(UpdatePlayerStatus::GAME_INIT);
                $player->updateStatus();;
            }
        }

        $pk = new InitQueue();
        $pk->gameIdentifier = $this->gameIdentifier;
        $pk->matchUuid = $this->matchUuid;
        if ($this->matchRules instanceof DefaultMatchRules){
            $pk->isRanked = $this->matchRules->isRanked();
        }

        $server->sendPacket($pk);
    }

    public function transfer(): void{
        $serverName = $this->serverName;
        $gameId = $this->gameUuid;
        $players = $this->getPlayers();

        foreach ($players as $player){
            $player->setStatus(UpdatePlayerStatus::MATCHMAKING_TRANSFER);
            $player->updateStatus();
            Server::getInstance()->getLogger()->info("Transfering player into " . $serverName);

            $player->setServerName($serverName);
            $player->setGameId($gameId);
            $player->setStatus(UpdatePlayerStatus::IN_QUEUE);

            unset($this->players[$player->getName()]);
        }

        $this->removeMaxPlayer(count($players));

        if ($this->getMaxPlayer() <= 0){
            MatchMakingManager::getInstance()->removeMatch($this->gameIdentifier, $this->matchUuid);
            Server::getInstance()->getLogger()->info("Match " . $this->gameIdentifier  . " with UUID=" . $this->matchUuid . " was removed");
        }
    }

    /**
     * @return int
     */
    public function getMaxPlayer(): int
    {
        return $this->maxPlayer;
    }

    /**
     * @return int
     */
    public function getMinPlayer(): int
    {
        return $this->minPlayer;
    }

    public function removeMaxPlayer(int $value): void{
        $this->maxPlayer = $this->maxPlayer-$value;
    }

    /**
     * @param int $minPlayer
     */
    public function setMinPlayer(int $minPlayer): void
    {
        $this->minPlayer = $minPlayer;
    }

    /**
     * @param int $maxPlayer
     */
    public function setMaxPlayer(int $maxPlayer): void
    {
        $this->maxPlayer = $maxPlayer;
    }
}