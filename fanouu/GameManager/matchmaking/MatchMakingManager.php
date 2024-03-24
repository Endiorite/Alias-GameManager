<?php

namespace fanouu\GameManager\matchmaking;

use Cassandra\Uuid;
use fanouu\GameManager\matchmaking\rules\DefaultMatchRules;
use fanouu\GameManager\matchmaking\rules\MatchRules;
use fanouu\GameManager\packets\UpdatePlayerStatus;
use fanouu\GameManager\players\Player;
use fanouu\GameManager\Server;
use fanouu\GameManager\utils\SingletonTrait;
use fanouu\GameManager\utils\UuidGenerator;

class MatchMakingManager
{
    use SingletonTrait;

    public function __construct()
    {
        self::setInstance($this);
        Server::getInstance()->getLogger()->notice("MatchMakingManager is ready");
    }

    /**
     * @var QueueMatch[][] array
     */
    private array $match = [];

    /**
     * @param Player[] $players
     * @param PlayerMatchInfo $matchInfo
     * @return bool
     */
    public function match(array $players, PlayerMatchInfo $matchInfo): bool{
        if (is_null($matchInfo->getGameInfo())){
            return false;
        }

        $gameInfo = $matchInfo->getGameInfo();
        $gameIdentifier = $gameInfo->getIdentifier();

        $matched = false;
        $cMatchUuid = null;
        if (isset($this->match[$gameIdentifier])){
            foreach ($this->match[$gameIdentifier] as $matchUuid => $match){
                $playerCount = count($match->getPlayers());
                if (($match->getMaxPlayer() - $playerCount) >= count($players)){
                    if ($match->getMatchRules()->satisfy($matchInfo, $match)){
                        $matched = true;
                        $match->addPlayers($players);
                        $matchInfo->setMatchUuid($matchUuid);
                        $cMatchUuid = $matchUuid;
                    }
                }
            }
        }

        if (!$matched){
            $rules = new DefaultMatchRules(
                $matchInfo->isRanked(),
                $matchInfo->getRank()
            );

            $match = $this->createMatch($gameIdentifier, $gameInfo->getMinPlayer(), $gameInfo->getMaxPlayer(), $rules);
            $cMatchUuid = $match->getMatchUuid();
            $match->addPlayers($players);
        }

        foreach ($players as $player){
            $player->setMatchUuid($matchUuid);
            $player->setMatchIdentifier($gameIdentifier);
            $player->setStatus(UpdatePlayerStatus::IN_MATCHMAKING);
            $player->updateStatus();
        }
        return true;
    }

    public function updateMatch(): void{
        foreach ($this->match as $gameIdentifier => $matches){
            foreach ($matches as $matchUuid => $match){
                $playersCount = count($match->getPlayers());
                $rules = $match->getMatchRules();

                if (!$match->gameIsInit()){
                    if ($playersCount >= $match->getMinPlayer() && !$playersCount > $match->getMaxPlayer()){
                        $match->start();
                    }
                }else{
                    if ($match->getMaxPlayer() > 0){
                        $match->transfer();
                    }else $this->removeMatch($gameIdentifier, $matchUuid);
                }
            }
        }
    }

    public function removePlayerInMatch(Player $player){
        $matchIdentifier = $player->getMatchIdentifier();
        $matchUuid = $player->getMatchUuid();
        if (!is_null($matchUuid) or  !is_null($matchIdentifier)){
            $match = $this->getMatch($matchIdentifier, $matchUuid);
            if (!is_null($match)){
                $match->removePlayer($player->getName());
            }
        }

        $player->setMatchIdentifier(null);
        $player->setMatchUuid(null);
    }

    public function createMatch(string $gameIdentifier, int $minPlayers, int $maxPlayers, MatchRules $matchRules): QueueMatch{
        $uuid = UuidGenerator::getInstance()->generate();
        $match = new QueueMatch($gameIdentifier, $uuid, $matchRules);
        $match->setMinPlayer($minPlayers);
        $match->setMaxPlayer($maxPlayers);
        $this->match[$gameIdentifier][$uuid] = $match;

        return $this->match[$gameIdentifier][$uuid];
    }

    public function getMatch(string $gameIdentifier, string $uuid): ?QueueMatch{
        return $this->match[$gameIdentifier][$uuid] ?? null;
    }

    public function removeMatch(string $gameIdentifier, string $uuid): void{
        if (isset($this->match[$gameIdentifier][$uuid])){
            unset($this->match[$gameIdentifier][$uuid]);
        }
    }
}