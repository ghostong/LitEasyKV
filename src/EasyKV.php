<?php

namespace Lit\EasyKv;

use Lit\EasyKv\drivers\AgentDriver;
use Lit\EasyKv\mappers\DataMapper;
use Lit\EasyKv\mappers\MySQLConfigMapper;
use Lit\EasyKv\mappers\RedisConfigMapper;
use Lit\EasyKv\mappers\SelectMapper;

/**
 * 简单的kv存储
 * @date 2023/4/23
 * @author litong
 */
class EasyKV
{

    use \Lit\Utils\LiErrMsg;

    /**
     * 初始化存储
     * @date 2023/4/23
     * @param MySQLConfigMapper|null $mySQLConfig
     * @param RedisConfigMapper|null $redisConfig
     * @return void
     * @throws \Exception
     * @author litong
     */
    public static function init(MySQLConfigMapper $mySQLConfig = null, RedisConfigMapper $redisConfig = null) {
        AgentDriver::config($mySQLConfig, $redisConfig);
    }

    /**
     * 增加 key value 数据
     * @date 2023/4/23
     * @param DataMapper $dataMapper
     * @return bool
     * @author litong
     */
    public static function add(DataMapper $dataMapper) {
        if (!AgentDriver::add($dataMapper)) {
            self::setCodeMsg(AgentDriver::getCode(), AgentDriver::getMsg());
            return false;
        } else {
            return true;
        }
    }

    /**
     * 修改 key value 对应的数据
     * @date 2023/4/23
     * @param DataMapper $dataMapper
     * @param bool $extendAppend
     * @return bool
     * @author litong
     */
    public static function modify(DataMapper $dataMapper, $extendAppend = false) {
        if (!AgentDriver::modify($dataMapper, $extendAppend)) {
            self::setCodeMsg(AgentDriver::getCode(), AgentDriver::getMsg());
            return false;
        } else {
            return true;
        }
    }

    /**
     * 删除 key value 对应关系
     * @date 2023/4/23
     * @param string $topic 主题
     * @param string $key 键
     * @param string $value 值
     * @return bool
     * @author litong
     */
    public static function delete($topic, $key, $value) {
        if (!AgentDriver::delete($topic, $key, $value)) {
            self::setCodeMsg(AgentDriver::getCode(), AgentDriver::getMsg());
            return false;
        } else {
            return true;
        }
    }

    /**
     * 获取一条 key value 数据
     * @date 2023/4/23
     * @param string $topic 主题
     * @param string $key 键
     * @param string $value 值
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

    /**
     * 查询
     * @date 2023/4/23
     * @return
     * @author litong
     */
    public static function select(SelectMapper $selectMapper) {
        return AgentDriver::select($selectMapper);
    }

}