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

    public static function modify(DataMapper $dataMapper, $extendAppend = false) {
        if (!AgentDriver::modify($dataMapper, $extendAppend)) {
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

    /**
     * 获取一个
     * @date 2023/4/23
     * @param $topic
     * @param $key
     * @param $value
     * @return DataMapper|null
     * @author litong
     */
    public static function get($topic, $key, $value) {
        $mapper = AgentDriver::get($topic, $key, $value);
        if ($mapper) {
            self::setCodeMsg(AgentDriver::getCode(), AgentDriver::getMsg());
            return $mapper;
        } else {
            return null;
        }
    }

    public static function select() {

    }

}