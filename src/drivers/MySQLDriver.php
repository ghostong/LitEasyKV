<?php

namespace Lit\EasyKv\drivers;

use Lit\EasyKv\mappers\DataMapper;
use Lit\EasyKv\mappers\MySQLConfigMapper;
use Lit\EasyKv\utils\DataConvert;
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
    protected static function connect() {
        $dsn = sprintf('mysql:dbname=%s;host=%s;charset=%s', self::$config->database->value(), self::$config->hostname->value(), self::$config->charset->value());
        if (!self::$dbConnect) {
            self::$dbConnect = new \PDO($dsn, self::$config->username->value(), self::$config->password->value());
        }
        return self::$dbConnect;
    }

    public static function add(DataMapper $dataMapper) {
        $data = DataConvert::userEncode($dataMapper);
        $sql = LiString::array2sql($data, "easy_kv");
        try {
            self::connect()->query($sql);
            return true;
        } catch (\Exception $exception) {
            self::setCodeMsg($exception->getCode(), $exception->getMessage());
            return false;
        }
    }

    public static function modify(DataMapper $dataMapper, $extendAppend) {
//        var_dump($dataMapper->getAssigned(), $extendAppend);
        if ($extendAppend) {
            $info = self::get($dataMapper->topic->value(), $dataMapper->key->value(), $dataMapper->value->value());
        }
        return true;
    }

    public static function get($topic, $key, $value) {

        [
            "topic_id" => DataConvert::fieldEncode($topic),
            "key_id" => DataConvert::fieldEncode($key),
            "value_id" => DataConvert::fieldEncode($value),
        ];
//        self::connect()->query($sql);
    }
}