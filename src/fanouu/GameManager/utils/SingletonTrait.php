<?php

namespace fanouu\GameManager\utils;

trait SingletonTrait
{
    /** @var self|null */
    private static $instance = null;

    /**
     * @throws \Exception
     */
    public static function getInstance() : self{
        if(self::$instance === null){
            throw new \Exception("set instance in class construct");
        }
        return self::$instance;
    }

    public static function setInstance(self $instance) : void{
        self::$instance = $instance;
    }

    public static function reset() : void{
        self::$instance = null;
    }
}