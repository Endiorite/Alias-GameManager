<?php

namespace fanouu\GameManager\packets\handlers;

use fanouu\GameManager\packets\RequestPlayerStatus;
use fanouu\GameManager\packets\UpdatePlayerStatus;
use fanouu\GameManager\servers\GameServer;

interface PacketHandlerInterface
{

    public function updatePlayerStatus(UpdatePlayerStatus $packet, GameServer $gameServer): void;

    public function requestPlayerStatus(RequestPlayerStatus $packet, GameServer $gameServer): void;

}