<?php

namespace fanouu\GameManager\packets\handlers;

use fanouu\GameManager\packets\RemoveMatch;
use fanouu\GameManager\packets\RequestMatchMaking;
use fanouu\GameManager\packets\RequestPlayerStatus;
use fanouu\GameManager\packets\TransferPlayerQueue;
use fanouu\GameManager\packets\UpdatePlayerStatus;
use fanouu\GameManager\servers\GameServer;

class PacketHandler implements PacketHandlerInterface
{

    public function updatePlayerStatus(UpdatePlayerStatus $packet, GameServer $gameServer): void
    {
        // TODO: Implement updatePlayerStatus() method.
    }

    public function requestPlayerStatus(RequestPlayerStatus $packet, GameServer $gameServer): void
    {
        // TODO: Implement requestPlayerStatus() method.
    }

    public function requestMatchMaking(RequestMatchMaking $packet, GameServer $gameServer): void
    {
        // TODO: Implement requestMatchMaking() method.
    }

    public function transferPlayerQueue(TransferPlayerQueue $packet, GameServer $gameServer): void
    {
        // TODO: Implement transferPlayerQueue() method.
    }

    public function removeMatch(RemoveMatch $packet, GameServer $gameServer): void
    {
        // TODO: Implement removeMatch() method.
    }
}