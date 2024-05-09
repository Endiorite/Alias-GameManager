<?php

namespace fanouu\GameManager\packets;

class RemoveMatch extends Packet
{

    public int $packet_id = PacketId::REMOVEMATCH;

    public string $matchUuid = "";
    public string $gameIdentifier = "";
    public string $variant = "";

    public function encodePayload(): void
    {
        $this->putString($this->matchUuid);
        $this->putString($this->gameIdentifier);
        $this->putString($this->variant);
    }

    public function decodePayload(): void
    {
        $this->matchUuid = $this->getString();
        $this->gameIdentifier = $this->getString();
        $this->variant = $this->getString();
    }

}