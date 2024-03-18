<?php

namespace fanouu\GameManager\packets;

class ServerRequestConnection extends Packet
{

    public int $packet_id = PacketId::SERVERREQUESTCONNECTION;

    public string $server_name = "default_server";

    public function decodePayload(): void
    {
        // TODO: Implement decodePayload() method.
    }

    public function encodePayload(): void
    {
        $this->putString($this->server_name);
    }
}