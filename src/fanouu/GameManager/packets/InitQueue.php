<?php

namespace fanouu\GameManager\packets;

class InitQueue extends Packet
{

    public int $packet_id = PacketId::INITQUEUE;

    public string $gameIdentifier = "";
    public bool $isRanked = false;
    public string $matchUuid = "";

    public function decodePayload(): void
    {
        $this->gameIdentifier = $this->getString();
        $this->matchUuid = $this->getString();
    }

    public function encodePayload(): void
    {
        $this->putString($this->gameIdentifier);
        $this->putString($this->matchUuid);
        $this->putBool($this->isRanked);
    }
}