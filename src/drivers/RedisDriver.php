<?php

namespace Lit\EasyKv\drivers;

use Lit\EasyKv\mappers\DataMapper;
use Lit\EasyKv\mappers\RedisConfigMapper;

class RedisDriver implements DriverInterface
{
    protected static $config = null;
    protected static $useRedis = false;

    public static function isEnable() {
        return self::$useRedis;
    }

    public static function config(RedisConfigMapper $redisConfig = null) {
        self::$config = $redisConfig;
        self::$useRedis = !is_null($redisConfig);
    }

    public static function add(DataMapper $dataMapper) {
        var_dump($dataMapper->toArray());
        return true;
    }

}