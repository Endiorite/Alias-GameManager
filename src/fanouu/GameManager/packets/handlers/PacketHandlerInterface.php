<?php

namespace fanouu\GameManager\packets\handlers;

use fanouu\GameManager\packets\RemoveMatch;
use fanouu\GameManager\packets\RequestMatchMaking;
use fanouu\GameManager\packets\RequestPlayerStatus;
use fanouu\GameManager\packets\TransferPlayerQueue;
use fanouu\GameManager\packets\UpdatePlayerStatus;
use fanouu\GameManager\servers\GameServer;

interface PacketHandlerInterface
{

    public function updatePlayerStatus(UpdatePlayerStatus $packet, GameServer $gameServer): void;

    public function requestPlayerStatus(RequestPlayerStatus $packet, GameServer $gameServer): void;

    public function requestMatchMaking(RequestMatchMaking $packet, GameServer $gameServer): void;

    public function transferPlayerQueue(TransferPlayerQueue $packet, GameServer $gameServer): void;

    public function removeMatch(RemoveMatch $packet, GameServer $gameServer): void;

}