<?php

namespace fanouu\GameManager\packets\handlers;

use fanouu\GameManager\packets\RequestPlayerStatus;
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
}