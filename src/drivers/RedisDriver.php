<?php

namespace Lit\EasyKv\drivers;

use Lit\EasyKv\constants\SelectConst;
use Lit\EasyKv\mappers\DataMapper;
use Lit\EasyKv\mappers\RedisConfigMapper;
use Lit\EasyKv\mappers\SelectMapper;
use Lit\EasyKv\utils\DataConvert;

class RedisDriver implements DriverInterface
{

    use \Lit\Utils\LiErrMsg;

    /**
     * @var RedisConfigMapper $config
     */
    protected static $config = null;
    protected static $useRedis = false;
    protected static $dbConnect = null;

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
        if (!self::$dbConnect) {
            self::$dbConnect = new \Redis();
            self::$dbConnect->connect(self::$config->host->value(), self::$config->port->value());
            if (!is_null(self::$config->auth->value())) {
                self::$dbConnect->auth(self::$config->auth->value());
            }
        }
        return self::$dbConnect;
    }

    public static function add(DataMapper $dataMapper) {
        $dataMapper->create_time = date("Y-m-d H:i:s");
        $dataMapper->update_time = date("Y-m-d H:i:s");
        $infoKey = self::dataKey($dataMapper->topic->value(), $dataMapper->key->value(), $dataMapper->value->value());
        $topicKeyKey = self::topicKeyListKey($dataMapper->topic->value(), $dataMapper->key->value());
        self::connect()->set($infoKey, DataConvert::redisEncode($dataMapper->toArray()));
        self::connect()->zAdd($topicKeyKey, $dataMapper->weight->value(), $infoKey);
        return true;
    }

    public static function modify(DataMapper $dataMapper, $extendAppend) {
        $info = self::get($dataMapper->topic->value(), $dataMapper->key->value(), $dataMapper->value->value());
        if ($extendAppend) {
            $dataMapper->extend = array_merge($info->extend->value(), $dataMapper->extend->value());
        } elseif (is_null($dataMapper->extend->value())) {
            $dataMapper->extend = $info->extend->value();
        }
        $dataMapper->update_time = date("Y-m-d H:i:s");
        $dataMapper->create_time = $info->create_time->value();
        $infoKey = self::dataKey($dataMapper->topic->value(), $dataMapper->key->value(), $dataMapper->value->value());
        $topicKeyKey = self::topicKeyListKey($dataMapper->topic->value(), $dataMapper->key->value());
        self::connect()->set($infoKey, DataConvert::redisEncode() ($dataMapper->toArray()));
        self::connect()->zAdd($topicKeyKey, $dataMapper->weight->value(), $infoKey);
        return true;
    }

    public static function get($topic, $key, $value) {
        $infoKey = self::dataKey($topic, $key, $value);
        $info = self::connect()->get($infoKey);
        if ($info) {
            return DataConvert::redisDecode($info);
        } else {
            return null;
        }
    }

    public static function delete($topic, $key, $value) {
        $infoKey = self::dataKey($topic, $key, $value);
        $topicKeyKey = self::topicKeyListKey($topic, $key);
        self::connect()->del($infoKey);
        self::connect()->zRem($topicKeyKey, $infoKey);
        return true;
    }

    public static function select(SelectMapper $selectMapper) {
        $topicKeyKey = self::topicKeyListKey($selectMapper->topic->value(), $selectMapper->key->value());
        if (SelectConst::ORDER_SCENE_ASC === $selectMapper->order_scene->value()) {
            $dataKeys = self::connect()->zRangeByScore($topicKeyKey, '-inf', '+inf', []);
        } else {
            $dataKeys = self::connect()->zRevRangeByScore($topicKeyKey, '+inf', '-inf', []);
        }
        $data = self::connect()->mget($dataKeys);
        return array_map(function ($v) {
            return DataConvert::redisDecode($v);
        }, $data);
    }

    private static function dataKey($topic, $key, $value) {
        return sprintf("easy:kv:info:%s:%s:%s", DataConvert::fieldEncode($topic), DataConvert::fieldEncode($key), DataConvert::fieldEncode($value));
    }

    private static function topicListKey($topic, $key, $value) {
        return sprintf("easy:kv:list:%s", $topic);
    }

    private static function topicKeyListKey($topic, $key) {
        return sprintf("easy:kv:list:%s:%s", $topic, $key);
    }
}