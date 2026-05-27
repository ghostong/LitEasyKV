<?php

namespace Lit\EasyKv\drivers;

use Lit\EasyKv\constants\ErrorMsg;
use Lit\EasyKv\mappers\DataMapper;
use Lit\EasyKv\mappers\MySQLConfigMapper;
use Lit\EasyKv\mappers\SelectMapper;
use Lit\EasyKv\utils\DataConvert;
use Lit\Utils\LiArray;

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
        $data = self::filterNull($data);
        $fields = array_keys($data);
        $columns = array_map(function ($field) {
            return self::quoteIdentifier($field);
        }, $fields);
        $placeholders = array_map(function ($field) {
            return ':' . $field;
        }, $fields);
        $sql = "insert into " . self::tableName() . " (" . implode(',', $columns) . ") values (" . implode(',', $placeholders) . ")"
            . " ON DUPLICATE KEY UPDATE "
            . "`extend` = VALUES(`extend`), `weight` = VALUES(`weight`), `update_time` = VALUES(`update_time`)";
        try {
            $stmt = self::connect()->prepare($sql);
            return self::execute($stmt, $data);
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
        if (!$info) {
            self::setCodeMsg(ErrorMsg::DATA_NOT_EXISTS, ErrorMsg::getComment(ErrorMsg::DATA_NOT_EXISTS));
            return false;
        }
        $newExtend = $dataMapper->extend->value();
        if ($extendAppend) {
            $dataMapper->extend = is_null($newExtend) ? $info->extend->value() : array_merge($info->extend->value(), $newExtend);
        } elseif (is_null($newExtend)) {
            $dataMapper->extend = $info->extend->value();
        }
        $dataMapper->update_time = date("Y-m-d H:i:s");
        $data = DataConvert::dbEncode($dataMapper->getAssigned());
        $data = self::filterNull($data);
        $updateData = LiArray::getValues($data, [], ['topic_id', 'key_id', 'value_id']);
        $fields = array_map(function ($field) {
            return self::quoteIdentifier($field) . " = :" . $field;
        }, array_keys($updateData));
        $sql = "update " . self::tableName() . " set " . implode(',', $fields) . " where `topic_id` = :topic_id and `key_id` = :key_id and `value_id` = :value_id limit 1";
        $stmt = self::connect()->prepare($sql);
        return self::execute($stmt, $data) && $stmt->rowCount() > 0;
    }

    public static function get($topic, $key, $value) {
        $topicId = DataConvert::fieldEncode($topic);
        $keyId = DataConvert::fieldEncode($key);
        $valueId = DataConvert::fieldEncode($value);
        $sql = "select * from " . self::tableName() . " where `topic_id` = :topic_id and `key_id` = :key_id and `value_id` = :value_id limit 1";
        $query = self::connect()->prepare($sql);
        self::execute($query, ['topic_id' => $topicId, 'key_id' => $keyId, 'value_id' => $valueId]);
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
        $sql = "delete from " . self::tableName() . " where `topic_id` = :topic_id and `key_id` = :key_id and `value_id` = :value_id limit 1";
        $query = self::connect()->prepare($sql);
        return self::execute($query, ['topic_id' => $topicId, 'key_id' => $keyId, 'value_id' => $valueId]) && $query->rowCount() > 0;
    }

    public static function select(SelectMapper $selectMapper) {
        $topicId = DataConvert::fieldEncode($selectMapper->topic->value());
        $keyId = DataConvert::fieldEncode($selectMapper->key->value());
        $offset = ($selectMapper->pageNum->value() - 1) * $selectMapper->pageSize->value();
        $pageSize = $selectMapper->pageSize->value();
        $scene = $selectMapper->order_scene->value();

        $sql = "select * from " . self::tableName() . " where `topic_id` = :topic_id and `key_id` = :key_id order by `weight` {$scene} limit :offset,:page_size";
        $query = self::connect()->prepare($sql);
        $query->bindValue(':topic_id', $topicId);
        $query->bindValue(':key_id', $keyId);
        $query->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $query->bindValue(':page_size', $pageSize, \PDO::PARAM_INT);
        if (!$query->execute()) {
            $error = $query->errorInfo();
            self::setCodeMsg($error[0], isset($error[2]) ? $error[2] : 'SQL execute failed');
        }
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);

        $count = self::count($selectMapper->topic->value(), $selectMapper->key->value());
        return DataConvert::dbSelectResult($data ?: [], $count, $selectMapper->pageNum->value(), $selectMapper->pageSize->value());
    }

    public static function count($topic, $key) {
        $topicId = DataConvert::fieldEncode($topic);
        $keyId = DataConvert::fieldEncode($key);
        $countSql = "select count(*) as number from " . self::tableName() . " where `topic_id` = :topic_id and `key_id` = :key_id";
        $query = self::connect()->prepare($countSql);
        self::execute($query, ['topic_id' => $topicId, 'key_id' => $keyId]);
        $count = $query->fetch(\PDO::FETCH_ASSOC);
        return $count["number"] ? intval($count["number"]) : 0;
    }

    private static function filterNull($data) {
        return array_filter($data, function ($value) {
            return !is_null($value);
        });
    }

    private static function execute(\PDOStatement $stmt, $data) {
        foreach ($data as $field => $value) {
            $stmt->bindValue(':' . $field, $value);
        }
        if (!$stmt->execute()) {
            $error = $stmt->errorInfo();
            self::setCodeMsg($error[0], isset($error[2]) ? $error[2] : 'SQL execute failed');
            return false;
        }
        return true;
    }

    private static function quoteIdentifier($name) {
        return '`' . str_replace('`', '``', $name) . '`';
    }

    private static function tableName() {
        return self::quoteIdentifier(self::$config->table->value());
    }
}
