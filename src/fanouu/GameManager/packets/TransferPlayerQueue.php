<?php

namespace fanouu\GameManager\packets;

use fanouu\GameManager\packets\handlers\PacketHandler;
use fanouu\GameManager\servers\GameServer;

class TransferPlayerQueue extends Packet
{

    public int $packet_id = PacketId::TRANSFERPLAYERQUEUE;

    public string $queueId = "";
    public string $gameIdentifier = "";
    public string $matchUuid = "";
    public string $variant = "";

    public function encodePayload(): void
    {
        $this->putString($this->queueId);
        $this->putString($this->gameIdentifier);
        $this->putString($this->matchUuid);
        $this->putString($this->variant);
    }

    /**
     * @throws \Exception
     */
    public function decodePayload(): void
    {
        $this->queueId = $this->getString();
        $this->gameIdentifier = $this->getString();
        $this->matchUuid = $this->getString();
        $this->variant = $this->getString();
    }

    public function handle(PacketHandler $gamePacketHandler, GameServer $gameServer): void
    {
        $gamePacketHandler->transferPlayerQueue($this, $gameServer);
    }
}