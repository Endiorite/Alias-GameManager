<?php

namespace fanouu\GameManager\packets\handlers;

use fanouu\GameManager\matchmaking\MatchMakingManager;
use fanouu\GameManager\packets\UpdatePlayerStatus;
use fanouu\GameManager\players\PlayerManager;
use fanouu\GameManager\servers\GameServer;

class UpdatePlayerStatusHandler extends PacketHandler
{

    public function updatePlayerStatus(UpdatePlayerStatus $packet, GameServer $gameServer): void
    {
        $player = PlayerManager::getInstance()->getPlayer($packet->player_name);
        $status = $packet->status;
        $extraData = $packet->extraData;

        switch ($status){
            case UpdatePlayerStatus::SET_IN_GAME_STATUS:
                if (!isset($extraData["gameId"]) or !isset($extraData["serverName"])){
                    return;
                }

                $player->setStatus(UpdatePlayerStatus::IN_GAME_STATUS);
                $player->setGameId($extraData["gameId"]);
                $player->setServerName($extraData["serverName"]);
                break;
            case UpdatePlayerStatus::REMOVE_IN_GAME:
                $player->setGameId(null);
                $player->setServerName(null);
                $player->setStatus(UpdatePlayerStatus::NONE_STATUS);
                break;
            case UpdatePlayerStatus::REMOVE_IN_MATCHMAKING:
                MatchMakingManager::getInstance()->removePlayerInMatch($player);
                break;
            case UpdatePlayerStatus::REMOVE_IN_QUEUE:
                if (!isset($extraData["matchId"]) or !isset($extraData["gameIdentifier"]) or !isset($extraData["gameVariant"])) return;

                $player->setStatus(UpdatePlayerStatus::NONE_STATUS);
                $match = MatchMakingManager::getInstance()->getMatch($extraData["gameIdentifier"], $extraData["gameVariant"], $extraData["matchId"]);
                if (!is_null($match)){
                    $match->addMaxPlayer();
                }
        }
    }
}