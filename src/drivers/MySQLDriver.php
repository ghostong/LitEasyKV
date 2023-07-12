<?php

namespace Lit\EasyKv\drivers;

use Lit\EasyKv\constants\ErrorMsg;
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
        $dsn = sprintf('mysql:dbname=%s;host=%s;charset=%s', self::$config->database->value(), self::$config->host->value(), self::$config->charset->value());
        if (!self::$dbConnect) {
            self::$dbConnect = new \PDO($dsn, self::$config->username->value(), self::$config->password->value());
        }
        return self::$dbConnect;
    }

    public static function add(DataMapper $dataMapper) {
        $dataMapper->create_time = date("Y-m-d H:i:s");
        $dataMapper->update_time = date("Y-m-d H:i:s");
        $data = DataConvert::dbEncode($dataMapper->toArray());
        $sql = LiString::array2sql(array_filter($data), self::$config->table->value());
        try {
            self::connect()->query($sql);
            return true;
        } catch (\Exception $exception) {
            if (stripos($exception->getMessage(), "duplicate entry") !== false) {
                self::setCodeMsg(ErrorMsg::DATA_ALREADY_EXISTS, ErrorMsg::getComment(ErrorMsg::DATA_ALREADY_EXISTS));
            } else {
                self::setCodeMsg($exception->getCode(), $exception->getMessage());
            }
            return false;
        }
    }

    public static function modify(DataMapper $dataMapper, $extendAppend) {
        $info = self::get($dataMapper->topic->value(), $dataMapper->key->value(), $dataMapper->value->value());
        if ($extendAppend) {
            $dataMapper->extend = array_merge($info->extend->value(), $dataMapper->extend->value());
        } elseif (is_null($dataMapper->extend->value())) {
            $dataMapper->extend = $info->extend->value();
        }
        $dataMapper->update_time = date("Y-m-d H:i:s");
        $data = DataConvert::dbEncode($dataMapper->getAssigned());
        $data = array_filter($data);
        $topicId = LiArray::get($data, 'topic_id', null, true);
        $keyId = LiArray::get($data, 'key_id', null, true);
        $valueId = LiArray::get($data, 'value_id', null, true);
        $fields = array_map(function ($v, $k) {
            return "`{$k}` = '{$v}'";
        }, $data, array_keys($data));
        $setStr = implode(',', $fields);
        $sql = "update `" . self::$config->table->value() . "` set {$setStr} where `topic_id` = '{$topicId}' and `key_id` = '{$keyId}' and `value_id` = '{$valueId}' limit 1";
        $query = self::connect()->query($sql);
        return $query->rowCount() > 0;
    }

    public static function get($topic, $key, $value) {
        $topicId = DataConvert::fieldEncode($topic);
        $keyId = DataConvert::fieldEncode($key);
        $valueId = DataConvert::fieldEncode($value);
        $sql = "select * from `" . self::$config->table->value() . "` where `topic_id` = '{$topicId}' and `key_id` = '{$keyId}' and `value_id` = '{$valueId}' limit 1";
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
        $sql = "delete from `" . self::$config->table->value() . "` where `topic_id` = '{$topicId}' and `key_id` = '{$keyId}' and `value_id` = '{$valueId}' limit 1";
        $query = self::connect()->query($sql);
        return $query->rowCount() > 0;
    }

    public static function select(SelectMapper $selectMapper) {
        $topicId = DataConvert::fieldEncode($selectMapper->topic->value());
        $keyId = DataConvert::fieldEncode($selectMapper->key->value());
        $offset = ($selectMapper->pageNum->value() - 1) * $selectMapper->pageSize->value();
        $pageSize = $selectMapper->pageSize->value();
        $scene = $selectMapper->order_scene->value();

        $sql = "select * from `" . self::$config->table->value() . "` where `topic_id` = '{$topicId}' and `key_id` = '{$keyId}' order by `weight` {$scene} limit {$offset},{$pageSize}";
        $query = self::connect()->query($sql);
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);

        $countSql = "select count(*) as number from `" . self::$config->table->value() . "` where `topic_id` = '{$topicId}' and `key_id` = '{$keyId}'";
        $query = self::connect()->query($countSql);
        $count = $query->fetch(\PDO::FETCH_ASSOC);

        return DataConvert::dbSelectResult($data, $count["number"], $selectMapper->pageNum->value(), $selectMapper->pageSize->value());
    }
}