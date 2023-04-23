<?php

namespace Lit\EasyKv\drivers;

use Lit\EasyKv\mappers\DataMapper;
use Lit\EasyKv\mappers\RedisConfigMapper;

class RedisDriver implements DriverInterface
{

    use \Lit\Utils\LiErrMsg;

    protected static $config = null;
    protected static $useRedis = false;

    public static function isEnable() {
        return self::$useRedis;
    }

    public static function config(RedisConfigMapper $redisConfig = null) {
        self::$config = $redisConfig;
        self::$useRedis = !is_null($redisConfig);
    }

    /**
     * 连接 redis
     * @date 2023/4/23
     * @return \Redis
     * @author litong
     */
    public static function connect() {

        return new \Redis();
    }

    public static function add(DataMapper $dataMapper) {
        return true;
    }

    public static function modify(DataMapper $dataMapper, $extendAppend) {
        return true;
    }

    public static function get($topic, $key, $value) {
        return true;
    }

    public static function delete($topic, $key, $value) {
        return true;
    }
}