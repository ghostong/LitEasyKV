<?php

namespace Lit\EasyKv\drivers;

use Lit\EasyKv\mappers\DataMapper;
use Lit\EasyKv\mappers\MySQLConfigMapper;
use Lit\EasyKv\mappers\RedisConfigMapper;

class AgentDriver
{

    use \Lit\Utils\LiErrMsg;

    public static function config(MySQLConfigMapper $mySQLConfig = null, RedisConfigMapper $redisConfig = null) {
        if (!is_null($mySQLConfig) && !$mySQLConfig->check()) {
            throw new \Exception($mySQLConfig->errMsg(), $mySQLConfig->errCode());
        }
        if (!is_null($redisConfig) && !$redisConfig->check()) {
            throw new \Exception($redisConfig->errMsg(), $redisConfig->errCode());
        }
        MySQLDriver::config($mySQLConfig);
        RedisDriver::config($redisConfig);
    }

    public static function add(DataMapper $dataMapper) {
        if (!$dataMapper->check()) {
            self::setCodeMsg($dataMapper->errCode(), $dataMapper->errMsg());
            return false;
        }

        if (MySQLDriver::isEnable() && !MySQLDriver::add($dataMapper)) {
            self::setCodeMsg(MySQLDriver::getCode(), MySQLDriver::getMsg());
            return false;
        }

        if (RedisDriver::isEnable() && !RedisDriver::add($dataMapper)) {
            return false;
        }
        return true;
    }

    public static function modify(DataMapper $dataMapper) {

        return true;
    }

    public static function delete($topic, $key, $value) {

        return true;
    }
}