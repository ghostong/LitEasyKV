<?php

namespace Lit\EasyKv\utils;

use Lit\EasyKv\mappers\DataMapper;

class DataConvert
{

    public static function userEncode(DataMapper $dataMapper) {
        $data = $dataMapper->toArray();
        $data["topic_md5"] = substr(md5($data["topic"]), 0, 8);
        $data["key_md5"] = substr(md5($data["key"]), 0, 8);
        $data["value_md5"] = substr(md5($data["value"]), 0, 8);
        $data["extend"] = json_encode($data["extend"], JSON_UNESCAPED_UNICODE);
        return $data;
    }

}