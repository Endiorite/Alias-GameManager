<?php

namespace fanouu\GameManager\matchmaking;

use fanouu\GameManager\utils\GameInfo;

class PlayerMatchInfo
{

    private ?GameInfo $gameInfo = null;
    private ?string $matchUuid = null;

    private string $variant = "";

    public function __construct(
        private bool $isRanked = false,
        private int $rank = 0,
    )
    {
    }

    /**
     * @return string
     */
    public function getVariant(): string
    {
        return $this->variant;
    }

    /**
     * @param string $variant
     */
    public function setVariant(string $variant): void
    {
        $this->variant = $variant;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @return bool
     */
    public function isRanked(): bool
    {
        return $this->isRanked;
    }

    /**
     * @return GameInfo|null
     */
    public function getGameInfo(): ?GameInfo
    {
        return $this->gameInfo;
    }

    /**
     * @return string|null
     */
    public function getMatchUuid(): ?string
    {
        return $this->matchUuid;
    }

    /**
     * @param string|null $matchUuid
     */
    public function setMatchUuid(?string $matchUuid): void
    {
        $this->matchUuid = $matchUuid;
    }

    /**
     * @param GameInfo|null $gameInfo
     */
    public function setGameInfo(?GameInfo $gameInfo): void
    {
        $this->gameInfo = $gameInfo;
    }
}