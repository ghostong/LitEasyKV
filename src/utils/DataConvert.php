<?php

namespace Lit\EasyKv\utils;

use Lit\EasyKv\mappers\DataMapper;

class DataConvert
{

    /**
     * 存储数据编码
     * @date 2023/4/23
     * @param array $data 存储数据
     * @return array
     * @author litong
     */
    public static function dbEncode($data) {
        $data["topic_id"] = self::fieldEncode($data["topic"]);
        $data["key_id"] = self::fieldEncode($data["key"]);
        $data["value_id"] = self::fieldEncode($data["value"]);
        $data["extend"] = json_encode($data["extend"], JSON_UNESCAPED_UNICODE);
        return $data;
    }

    /**
     * 存储数据解码
     * @date 2023/4/23
     * @param $data
     * @return DataMapper
     * @author litong
     */
    public static function dbDecode($data) {
        $data["extend"] = json_decode($data["extend"], true);
        return new DataMapper($data);
    }

    public static function redisEncode($data) {
        return json_encode($data);
    }

    public static function redisDecode($data) {
        $data = json_decode($data, true);
        return new DataMapper($data);
    }

    /**
     * 字段id编码
     * @date 2023/4/23
     * @param $field
     * @return string
     * @author litong
     */
    public static function fieldEncode($field) {
        return substr(md5($field), 0, 8);
    }

}