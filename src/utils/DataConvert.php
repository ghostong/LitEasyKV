<?php

namespace Lit\EasyKv\utils;

use Lit\EasyKv\mappers\DataMapper;

class DataConvert
{

    public static function userEncode($data) {
        $data["topic_id"] = self::fieldEncode($data["topic"]);
        $data["key_id"] = self::fieldEncode($data["key"]);
        $data["value_id"] = self::fieldEncode($data["value"]);
        $data["extend"] = json_encode($data["extend"], JSON_UNESCAPED_UNICODE);
        return $data;
    }

    public static function userDecode($data) {
        $data["extend"] = json_decode($data["extend"], true);
        return new DataMapper($data);
    }


    public static function fieldEncode($field) {
        return substr(md5($field), 0, 8);
    }

}