<?php

namespace fanouu\GameManager\packets;


use fanouu\GameManager\packets\handlers\PacketHandler;
use fanouu\GameManager\servers\GameServer;

class Packet extends Buffer
{

    public int $packet_id = 0x00;

    public function decodePayload(): void{}
    public function encodePayload(): void{}

    public function encodeHeader(): void{
        $this->putByte($this->packet_id);
    }

    public function decodeHeader(): void{
        $this->packet_id = $this->getByte();
    }

    final public function decode(): void{
        $this->decodeHeader();
        $this->decodePayload();
    }

final public function encode(): void{
        $this->encodeHeader();
        $this->encodePayload();
    }

    public function handle(PacketHandler $gamePacketHandler, GameServer $gameServer): void{}
}