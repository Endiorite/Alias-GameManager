<?php

namespace fanouu\GameManager\packets\handlers;

use fanouu\GameManager\matchmaking\MatchMakingManager;
use fanouu\GameManager\matchmaking\PlayerMatchInfo;
use fanouu\GameManager\packets\RequestMatchMaking;
use fanouu\GameManager\players\PlayerManager;
use fanouu\GameManager\servers\GameServer;
use fanouu\GameManager\utils\GameInfo;

class RequestMatchMakingHandler extends PacketHandler
{

    public function requestMatchMaking(RequestMatchMaking $packet, GameServer $gameServer): void
    {
        $identifier = $packet->gameInfo["identifier"];
        $minPlayer = (int)$packet->gameInfo["min-players"];
        $maxPlayer = (int)$packet->gameInfo["max-players"];
        $variant = $packet->variant;

        if ($minPlayer == 0 or $maxPlayer == 0 or $identifier == "") return;

        $gameInfo = new GameInfo($identifier, $minPlayer, $maxPlayer);
        $isRanked = $packet->isRanked;
        $rank = $packet->rank;

        $matchInfo = new PlayerMatchInfo($isRanked, $rank);
        $matchInfo->setVariant($variant);
        $matchInfo->setGameInfo($gameInfo);

        $players = array_map(function (string $playerName) use($matchInfo){
            return PlayerManager::getInstance()->getPlayer($playerName);
        }, $packet->players);
        MatchMakingManager::getInstance()->match($players, $matchInfo);
    }

}