<?php

namespace fanouu\GameManager\packets;

class PacketId
{
    public const REQUESTPLAYERSTATUS = 0x01;
    public const SERVERREQUESTCONNECTION = 0x04;
    public const ACCEPTREQUESTCONNECTION = 0x05;
    public const UPDATEPLAYERSTATUS = 0x03;
    public const SERVERTOTRANSFER = 0x06;

    public const PACKETS = [
        self::UPDATEPLAYERSTATUS => UpdatePlayerStatus::class
    ];
}