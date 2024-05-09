<?php

namespace fanouu\GameManager\packets\handlers;

use fanouu\GameManager\matchmaking\MatchMakingManager;
use fanouu\GameManager\packets\RemoveMatch;
use fanouu\GameManager\servers\GameServer;

class RemoveMatchHandler extends PacketHandler
{

    public function removeMatch(RemoveMatch $packet, GameServer $gameServer): void
    {
        MatchMakingManager::getInstance()->removeMatch($packet->gameIdentifier, $packet->variant, $packet->matchUuid);
    }

}