<?php

namespace fanouu\GameManager\packets;

class InitQueue extends Packet
{

    public int $packet_id = PacketId::INITQUEUE;

    public string $variant = "";
    public string $gameIdentifier = "";
    public bool $isRanked = false;
    public string $matchUuid = "";

    /**
     * @throws \Exception
     */
    public function decodePayload(): void
    {
        $this->gameIdentifier = $this->getString();
        $this->variant = $this->getString();
        $this->matchUuid = $this->getString();
    }

    public function encodePayload(): void
    {
        $this->putString($this->gameIdentifier);
        $this->putString($this->variant);
        $this->putString($this->matchUuid);
        $this->putBool($this->isRanked);
    }
}