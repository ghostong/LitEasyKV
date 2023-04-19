<?php

namespace Lit\EasyKv;

use Lit\EasyKv\drivers\AgentDriver;
use Lit\EasyKv\mappers\DataMapper;
use Lit\EasyKv\mappers\MySQLConfigMapper;
use Lit\EasyKv\mappers\RedisConfigMapper;

class EasyKV
{

    use \Lit\Utils\LiErrMsg;

    public static function init(MySQLConfigMapper $mySQLConfig = null, RedisConfigMapper $redisConfig = null) {
        AgentDriver::config($mySQLConfig, $redisConfig);
    }

    public static function add(DataMapper $dataMapper) {
        if (!AgentDriver::add($dataMapper)) {
            self::setCodeMsg(AgentDriver::getCode(), AgentDriver::getMsg());
            return false;
        } else {
            return true;
        }
    }

    public static function modify(DataMapper $dataMapper) {
        if (!AgentDriver::modify($dataMapper)) {
            self::setCodeMsg(AgentDriver::getCode(), AgentDriver::getMsg());
            return false;
        } else {
            return true;
        }
    }

    public static function delete($topic, $key, $value) {
        if (!AgentDriver::delete($topic, $key, $value)) {
            self::setCodeMsg(AgentDriver::getCode(), AgentDriver::getMsg());
            return false;
        } else {
            return true;
        }
    }

    public static function get($topic, $key, $value) {

    }

    public static function select() {

    }

}