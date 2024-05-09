<?php

namespace fanouu\GameManager\packets;

class PacketId
{
    public const REQUESTPLAYERSTATUS = 0x02;
    public const SERVERREQUESTCONNECTION = 0x00;
    public const ACCEPTREQUESTCONNECTION = 0x01;
    public const UPDATEPLAYERSTATUS = 0x03;
    public const SERVERTOTRANSFER = 0x04;
    public const REQUESTMATCHMAKING = 0x05;
    public const INITQUEUE = 0x06;
    public const TRANSFERPLAYERQUEUE = 0xa0;
    public const REMOVEMATCH = 0xa1;


    public const PACKETS = [
        self::UPDATEPLAYERSTATUS => UpdatePlayerStatus::class,
        self::TRANSFERPLAYERQUEUE => TransferPlayerQueue::class,
        self::REQUESTPLAYERSTATUS => RequestPlayerStatus::class,
        self::REMOVEMATCH => RemoveMatch::class,
        self::REQUESTMATCHMAKING => RequestMatchMaking::class
    ];
}