<?php

namespace Lit\EasyKv\drivers;

use Lit\EasyKv\mappers\DataMapper;
use Lit\EasyKv\mappers\MySQLConfigMapper;
use Lit\EasyKv\mappers\RedisConfigMapper;
use Lit\EasyKv\mappers\SelectMapper;

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
            self::setCodeMsg(RedisDriver::getCode(), RedisDriver::getMsg());
            return false;
        }
        return true;
    }

    public static function modify(DataMapper $dataMapper, $extendAppend) {
        if (!$dataMapper->check()) {
            self::setCodeMsg($dataMapper->errCode(), $dataMapper->errMsg());
            return false;
        }

        if (MySQLDriver::isEnable() && !MySQLDriver::modify($dataMapper, $extendAppend)) {
            self::setCodeMsg(MySQLDriver::getCode(), MySQLDriver::getMsg());
        }

        if (RedisDriver::isEnable() && !RedisDriver::modify($dataMapper, $extendAppend)) {
            self::setCodeMsg(RedisDriver::getCode(), RedisDriver::getMsg());
        }
        return true;
    }

    public static function delete($topic, $key, $value) {
        if (RedisDriver::isEnable() && !RedisDriver::delete($topic, $key, $value)) {
            self::setCodeMsg(RedisDriver::getCode(), RedisDriver::getMsg());
        }
        if (MySQLDriver::isEnable() && !MySQLDriver::delete($topic, $key, $value)) {
            self::setCodeMsg(MySQLDriver::getCode(), MySQLDriver::getMsg());
        }
        return true;
    }

    public static function get($topic, $key, $value) {
        if (RedisDriver::isEnable()) {
            if ($data = RedisDriver::get($topic, $key, $value)) {
                return $data;
            } else {
                self::setCodeMsg(MySQLDriver::getCode(), MySQLDriver::getMsg());
            }
        }
        if (MySQLDriver::isEnable()) {
            if ($data = MySQLDriver::get($topic, $key, $value)) {
                return $data;
            } else {
                self::setCodeMsg(MySQLDriver::getCode(), MySQLDriver::getMsg());
            }
        }
        return null;
    }

    public static function select(SelectMapper $selectMapper) {
        if (!$selectMapper->check()) {
            self::setCodeMsg($selectMapper->errCode(), $selectMapper->errMsg());
            return [];
        }
        if (RedisDriver::isEnable()) {

        }
        if (MySQLDriver::isEnable()) {
            return MySQLDriver::select($selectMapper);
        }
    }
}