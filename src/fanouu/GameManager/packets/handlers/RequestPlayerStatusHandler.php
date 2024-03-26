<?php

namespace fanouu\GameManager\packets\handlers;

use fanouu\GameManager\packets\RequestPlayerStatus;
use fanouu\GameManager\packets\UpdatePlayerStatus;
use fanouu\GameManager\players\PlayerManager;
use fanouu\GameManager\servers\GameServer;

class RequestPlayerStatusHandler extends PacketHandler
{

    public function requestPlayerStatus(RequestPlayerStatus $packet, GameServer $gameServer): void
    {
        $playerName = $packet->player_name;

        $player = PlayerManager::getInstance()->getPlayer($playerName);
        $status = $player->getStatus();

        $packet = new UpdatePlayerStatus();
        $packet->player_name = $playerName;
        $packet->status = $status;

        $extraData = [];
        if (!is_null($player->getGameId())){
            $extraData["gameId"] = $player->getGameId();
        }
        if (!is_null($player->getServerName())){
            $extraData["serverName"] = $player->getServerName();
        }
        $packet->extraData = $extraData;

        $gameServer->sendPacket($packet);
    }

}