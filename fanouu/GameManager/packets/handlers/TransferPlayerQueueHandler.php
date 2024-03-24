<?php

namespace fanouu\GameManager\packets\handlers;

use fanouu\GameManager\matchmaking\MatchMakingManager;
use fanouu\GameManager\packets\TransferPlayerQueue;
use fanouu\GameManager\servers\GameServer;

class TransferPlayerQueueHandler extends PacketHandler
{

    public function transferPlayerQueue(TransferPlayerQueue $packet, GameServer $gameServer): void
    {
        $queueId = $packet->queueId;
        $gameIdentifier = $packet->gameIdentifier;
        $matchUuid = $packet->matchUuid;

        $match = MatchMakingManager::getInstance()->getMatch($gameIdentifier, $matchUuid);
        if (is_null($match)) return;

        $match->setGameInit(true);
        $match->setGameUuid($queueId);
        $match->setServerName($gameServer->getName());
        $match->transfer();
    }

}