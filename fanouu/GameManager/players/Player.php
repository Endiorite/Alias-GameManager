<?php

namespace fanouu\GameManager\players;

class Player
{

    private ?string $gameId = null;

    public function __construct(
        private string $name
    )
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string|null $gameId
     */
    public function setGameId(?string $gameId): void
    {
        $this->gameId = $gameId;
    }

    /**
     * @return string|null
     */
    public function getGameId(): ?string
    {
        return $this->gameId;
    }

}