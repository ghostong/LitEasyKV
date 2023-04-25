<?php

namespace Lit\EasyKv\drivers;

use Lit\EasyKv\mappers\DataMapper;
use Lit\EasyKv\mappers\MySQLConfigMapper;
use Lit\EasyKv\mappers\SelectMapper;
use Lit\EasyKv\utils\DataConvert;
use Lit\Utils\LiArray;
use Lit\Utils\LiString;

class MySQLDriver implements DriverInterface
{
    use \Lit\Utils\LiErrMsg;

    /**
     * @var MySQLConfigMapper $config
     */
    protected static $config = null;
    protected static $useMySQL = false;
    protected static $dbConnect = null;

    public static function isEnable() {
        return self::$useMySQL;
    }

    public static function config(MySQLConfigMapper $mySQLConfigMapper = null) {
        self::$config = $mySQLConfigMapper;
        self::$useMySQL = !is_null($mySQLConfigMapper);
    }

    /**
     * 连接数据库
     * @date 2023/4/19
     * @return \PDO
     * @author litong
     */
    public static function connect() {
        $dsn = sprintf('mysql:dbname=%s;host=%s;charset=%s', self::$config->database->value(), self::$config->hostname->value(), self::$config->charset->value());
        if (!self::$dbConnect) {
            self::$dbConnect = new \PDO($dsn, self::$config->username->value(), self::$config->password->value());
        }
        return self::$dbConnect;
    }

    public static function add(DataMapper $dataMapper) {
        $data = DataConvert::dbEncode($dataMapper->toArray());
        $sql = LiString::array2sql(array_filter($data), "easy_kv");
        try {
            self::connect()->query($sql);
            return true;
        } catch (\Exception $exception) {
            self::setCodeMsg($exception->getCode(), $exception->getMessage());
            return false;
        }
    }

    public static function modify(DataMapper $dataMapper, $extendAppend) {
        if ($extendAppend) {
            $info = self::get($dataMapper->topic->value(), $dataMapper->key->value(), $dataMapper->value->value());
            $dataMapper->extend = array_merge($info->extend->value(), $dataMapper->extend->value());
        }
        $data = DataConvert::dbEncode($dataMapper->getAssigned());
        $data = array_filter($data);
        $topicId = LiArray::get($data, 'topic_id', null, true);
        $keyId = LiArray::get($data, 'key_id', null, true);
        $valueId = LiArray::get($data, 'value_id', null, true);
        unset($data['topic'], $data['key'], $data['value']);
        if (empty($data)) {
            return false;
        }
        $fields = array_map(function ($v, $k) {
            return "`{$k}` = '{$v}'";
        }, $data, array_keys($data));
        $setStr = implode(',', $fields);
        $sql = "update `easy_kv` set {$setStr} where `topic_id` = '{$topicId}' and `key_id` = '{$keyId}' and `value_id` = '{$valueId}' limit 1";
        $query = self::connect()->query($sql);
        return $query->rowCount() > 0;
    }

    public static function get($topic, $key, $value) {
        $topicId = DataConvert::fieldEncode($topic);
        $keyId = DataConvert::fieldEncode($key);
        $valueId = DataConvert::fieldEncode($value);
        $sql = "select * from `easy_kv` where `topic_id` = '{$topicId}' and `key_id` = '{$keyId}' and `value_id` = '{$valueId}' limit 1";
        $query = self::connect()->query($sql);
        $oneData = $query->fetch(\PDO::FETCH_ASSOC);
        if ($oneData) {
            return DataConvert::dbDecode($oneData);
        } else {
            return null;
        }
    }

    public static function delete($topic, $key, $value) {
        $topicId = DataConvert::fieldEncode($topic);
        $keyId = DataConvert::fieldEncode($key);
        $valueId = DataConvert::fieldEncode($value);
        $sql = "delete from `easy_kv` where `topic_id` = '{$topicId}' and `key_id` = '{$keyId}' and `value_id` = '{$valueId}' limit 1";
        $query = self::connect()->query($sql);
        return $query->rowCount() > 0;
    }

    public static function select(SelectMapper $selectMapper) {
        $topicId = DataConvert::fieldEncode($selectMapper->topic->value());
        $keyId = DataConvert::fieldEncode($selectMapper->key->value());
        $orderBy = $selectMapper->order_by->value();
        $scene = $selectMapper->order_scene->value();
        $status = $selectMapper->status->value();
        $where = "";
        if ($status) {
            $where .= "and `status` = '{$status}'";
        }
        $sql = "select * from `easy_kv` where `topic_id` = '{$topicId}' and `key_id` = '{$keyId}' {$where} order by {$orderBy} {$scene}";
        $query = self::connect()->query($sql);
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(function ($v) {
            return DataConvert::dbDecode($v);
        }, $data);
    }
}