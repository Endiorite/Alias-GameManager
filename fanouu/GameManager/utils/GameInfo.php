<?php

namespace fanouu\GameManager\utils;

class GameInfo
{

    public function __construct(
        private string $identifier,
        private int $minPlayer = 1,
        private int $maxPlayer = 2
    )
    {
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return int
     */
    public function getMaxPlayer(): int
    {
        return $this->maxPlayer;
    }

    /**
     * @return int
     */
    public function getMinPlayer(): int
    {
        return $this->minPlayer;
    }

}