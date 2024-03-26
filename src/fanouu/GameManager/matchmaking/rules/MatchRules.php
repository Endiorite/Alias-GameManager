<?php

namespace fanouu\GameManager\matchmaking\rules;

use fanouu\GameManager\matchmaking\PlayerMatchInfo;
use fanouu\GameManager\matchmaking\QueueMatch;

class MatchRules
{

    public function satisfy(PlayerMatchInfo $playerMatchInfo, QueueMatch $match): bool{
        return true;
    }

}