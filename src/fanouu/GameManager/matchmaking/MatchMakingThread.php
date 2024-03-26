<?php

namespace fanouu\GameManager\matchmaking;

use fanouu\GameManager\Server;

class MatchMakingThread extends \Thread
{

    public function __construct(
        private readonly Server             $server,
        private readonly MatchMakingManager $makingManager
    )
    {
    }

    public function run()
    {
        while ($this->isRunning()){
            $this->makingManager->updateMatch();
        }
    }

    /**
     * @return Server
     */
    public function getServer(): Server
    {
        return $this->server;
    }

}