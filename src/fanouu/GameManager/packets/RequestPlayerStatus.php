<?php

namespace fanouu\GameManager\packets;

use fanouu\GameManager\packets\handlers\PacketHandler;
use fanouu\GameManager\servers\GameServer;

class RequestPlayerStatus extends Packet
{

    public int $packet_id = PacketId::REQUESTPLAYERSTATUS;

    public string $player_name = "";

    public function encodePayload(): void
    {
        $this->putString($this->player_name);
    }

    public function handle(PacketHandler $gamePacketHandler, GameServer $gameServer): void
    {
        $gamePacketHandler->requestPlayerStatus($this, $gameServer);
    }

}