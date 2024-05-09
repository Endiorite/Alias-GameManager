<?php

namespace fanouu\GameManager\matchmaking\rules;

use fanouu\GameManager\matchmaking\PlayerMatchInfo;
use fanouu\GameManager\matchmaking\QueueMatch;

class DefaultMatchRules extends MatchRules
{

    public function __construct(
        int $minPlayer = 1, int $maxPlayer = 2,
        private bool $isRanked = false,
        private int $rank = 0,
        private int $rankDiff = 1,
    ){}

    /**
     * @return int
     */
    public function getRankDiff(): int
    {
        return $this->rankDiff;
    }

    /**
     * @param int $rankDiff
     */
    public function setRankDiff(int $rankDiff): void
    {
        $this->rankDiff = $rankDiff;
    }

    public function satisfy(PlayerMatchInfo $playerMatchInfo, QueueMatch $match): bool{
        $rank = $playerMatchInfo->getRank();
        $diff = $this->getRankDiff();
        return $this->isRanked === $playerMatchInfo->isRanked() && ($rank >= ($this->rank-$diff) && $rank <= ($this->rank+$diff));
    }

    /**
     * @return bool
     */
    public function isRanked(): bool
    {
        return $this->isRanked;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

}