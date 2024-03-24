<?php

namespace fanouu\GameManager\packets;

class ServerRequestConnection extends Packet
{

    public int $packet_id = PacketId::SERVERREQUESTCONNECTION;

    public string $server_name = "default_server";

    /**
     * @throws \Exception
     */
    public function decodePayload(): void
    {
        $this->server_name = $this->getString();
    }

    public function encodePayload(): void
    {
        $this->putString($this->server_name);
    }
}