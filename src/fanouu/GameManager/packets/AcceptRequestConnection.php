<?php

namespace Alias\packets;

use fanouu\GameManager\packets\Packet;
use fanouu\GameManager\packets\PacketId;

class AcceptRequestConnection extends Packet
{

    public int $packet_id = PacketId::ACCEPTREQUESTCONNECTION;

}