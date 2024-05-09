<?php

namespace fanouu\GameManager\packets;

class ServerToTransferPlayer extends Packet
{

    public int $packet_id = PacketId::SERVERTOTRANSFER;

    public string $player;
    public string $serverName;

    public function decodePayload(): void
    {
        $this->player = $this->getString();
        $this->serverName = $this->getString();
    }
}