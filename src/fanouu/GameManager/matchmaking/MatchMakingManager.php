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
     * @throws \Exception
     */
    public function match(array $players, PlayerMatchInfo $matchInfo): bool{
        if (is_null($matchInfo->getGameInfo())){
            return false;
        }

        $gameInfo = $matchInfo->getGameInfo();
        $variant = $matchInfo->getVariant();
        $gameIdentifier = $gameInfo->getIdentifier();

        $matched = false;
        if (isset($this->match[$gameIdentifier][$variant])){
            foreach ($this->match[$gameIdentifier][$variant] as $matchUuid => $match){
                $playerCount = count($match->getPlayers());
                if (($match->getMaxPlayer() - $playerCount) >= count($players)){
                    if ($match->getMatchRules()->satisfy($matchInfo, $match)){
                        $matched = true;
                        $match->addPlayers($players);
                        $matchInfo->setMatchUuid($matchUuid);
                    }
                }
            }
        }

        if (!$matched){
            $rules = new DefaultMatchRules(
                $matchInfo->isRanked(),
                $matchInfo->getRank()
            );

            $match = $this->createMatch($gameIdentifier, $variant, $gameInfo->getMinPlayer(), $gameInfo->getMaxPlayer(), $rules);
            $matchInfo->setMatchUuid($match->getMatchUuid());
            $match->addPlayers($players);
        }

        foreach ($players as $player){
            $player->setMatchInfo($matchInfo);
            $player->setStatus(UpdatePlayerStatus::IN_MATCHMAKING);
            $player->updateStatus();
        }
        return true;
    }

    public function updateMatch(): void{
        foreach ($this->match as $gameIdentifier => $variants){
            foreach ($variants as $variantName => $matches){
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
                        }else $this->removeMatch($gameIdentifier, $variantName, $matchUuid);
                    }
                }
            }
        }
    }

    public function removePlayerInMatch(Player $player): void
    {
        $matchIdentifier = $player->getMatchInfo()?->getGameInfo()->getIdentifier() ?? null;
        $matchUuid = $player->getMatchInfo()?->getMatchUuid() ?? null;
        $gameVariant = $player->getMatchInfo()->getVariant();
        if (!is_null($matchUuid) and  !is_null($matchIdentifier)){
            $match = $this->getMatch($matchIdentifier, $gameVariant, $matchUuid);
            if (!is_null($match)){
                $match->removePlayer($player->getName());
            }
        }

        $player->setMatchInfo(null);
    }

    /**
     * @throws \Exception
     */
    public function createMatch(string $gameIdentifier, string $gameVariant, int $minPlayers, int $maxPlayers, MatchRules $matchRules): QueueMatch{
        $uuid = UuidGenerator::getInstance()->generate();
        $match = new QueueMatch($gameIdentifier, $gameVariant, $uuid, $matchRules);
        $match->setMinPlayer($minPlayers);
        $match->setMaxPlayer($maxPlayers);
        $match->setDefaultMaxPlayer($maxPlayers);
        return $this->match[$gameIdentifier][$gameVariant][$uuid] = $match;
    }

    public function getMatch(string $gameIdentifier, string $gameVariant, string $uuid): ?QueueMatch{
        return $this->match[$gameIdentifier][$gameVariant][$uuid] ?? null;
    }

    public function removeMatch(string $gameIdentifier, string $gameVariant, string $uuid): void{
        if (isset($this->match[$gameIdentifier][$gameVariant][$uuid])){
            unset($this->match[$gameIdentifier][$gameVariant][$uuid]);
        }
    }
}