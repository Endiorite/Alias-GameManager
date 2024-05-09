<?php

namespace fanouu\GameManager\packets;

use Exception;
use fanouu\GameManager\packets\handlers\PacketHandler;
use fanouu\GameManager\servers\GameServer;

class UpdatePlayerStatus extends Packet
{

    const NONE_STATUS = 0;
    const IN_GAME_STATUS = 2;
    const REMOVE_IN_GAME = 3;
    const SET_IN_GAME_STATUS = 4;
    const IN_MATCHMAKING = 5;
    const REMOVE_IN_MATCHMAKING = 6;
    const MATCHMAKING_TRANSFER = 7;
    const GAME_INIT = 9;
    const IN_QUEUE = 8;
    const REMOVE_IN_QUEUE = 10;

    public int $packet_id = PacketId::UPDATEPLAYERSTATUS;

    public int $status = 0;
    public array $extraData;

    public string $player_name = "";

    /**
     * @throws Exception
     */
    public function decodePayload(): void
    {
        $this->status = $this->getInt();
        $this->extraData = json_decode($this->getString(), true);
        $this->player_name = $this->getString();
    }

    public function encodePayload(): void
    {
        $this->putInt($this->status);
        $this->putString(json_encode($this->extraData));
        $this->putString($this->player_name);
    }

    public function handle(PacketHandler $gamePacketHandler, GameServer $gameServer): void
    {
        $gamePacketHandler->updatePlayerStatus($this, $gameServer);
    }
}