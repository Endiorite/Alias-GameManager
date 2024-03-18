<?php

namespace fanouu\GameManager\packets\handlers;

use fanouu\GameManager\packets\RequestPlayerStatus;
use fanouu\GameManager\players\PlayerManager;
use fanouu\GameManager\servers\GameServer;

class RequestPlayerStatusHandler extends PacketHandler
{

    public function requestPlayerStatus(RequestPlayerStatus $packet, GameServer $gameServer): void
    {
        $playerName = $packet->player_name;

        $player = PlayerManager::getInstance()->getPlayer($playerName);
    }

}