<?php

namespace fanouu\GameManager\utils;
class UuidGenerator
{
    use SingletonTrait;

    public function __construct()
    {
        self::setInstance($this);
    }

    public function generate(): string{
        $bytes = random_bytes(16);

        /** @var array $unpackedTime */
        $unpackedTime = unpack('n*', substr($bytes, 6, 2));
        $timeHi = (int) $unpackedTime[1];
        $timeHiAndVersion = pack('n*', $this->applyVersion($timeHi, 4));

        /** @var array $unpackedClockSeq */
        $unpackedClockSeq = unpack('n*', substr($bytes, 8, 2));
        $clockSeqHi = (int) $unpackedClockSeq[1];
        $clockSeqHiAndReserved = pack('n*', $this->applyVariant($clockSeqHi));

        $bytes = substr_replace($bytes, $timeHiAndVersion, 6, 2);
        $bytes = substr_replace($bytes, $clockSeqHiAndReserved, 8, 2);

        $base16Uuid = bin2hex($bytes);

        return
            substr($base16Uuid, 0, 8)
            . '-'
            . substr($base16Uuid, 8, 4)
            . '-'
            . substr($base16Uuid, 12, 4)
            . '-'
            . substr($base16Uuid, 16, 4)
            . '-'
            . substr($base16Uuid, 20, 12);
    }

    public function applyVersion(int $timeHi, int $version): int
    {
        $timeHi = $timeHi & 0x0fff;
        $timeHi |= $version << 12;

        return $timeHi;
    }

    public function applyVariant(int $clockSeq): int
    {
        $clockSeq = $clockSeq & 0x3fff;
        $clockSeq |= 0x8000;

        return $clockSeq;
    }

}