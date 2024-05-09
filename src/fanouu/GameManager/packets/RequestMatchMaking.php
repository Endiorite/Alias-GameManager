<?php

namespace fanouu\GameManager\packets;

class RequestMatchMaking extends Packet
{

    public int $packet_id = PacketId::REQUESTMATCHMAKING;

    public array $players = [];
    public array $gameInfo = [
        "identifier" => "",
        "min-players" => 0,
        "max-players" => 1
    ];
    public string $variant = "";
    public bool $isRanked = false;
    public string $rank = "";
    public int $points = 0;

    public function encodePayload(): void
    {
        $this->putString(json_encode($this->players));
        $this->putString(json_encode($this->gameInfo));
        $this->putString($this->variant);
        $this->putBool($this->isRanked);
        $this->putString($this->rank);
        $this->putInt($this->points);
    }

    /**
     * @throws \Exception
     */
    public function decodePayload(): void
    {
        $this->players = json_decode($this->getString(), true);
        $this->gameInfo = json_decode($this->getString(), true);
        $this->variant = $this->getString();
        $this->isRanked = $this->getBool();
        $this->rank = $this->getString();
        $this->points = $this->getInt();
    }
}