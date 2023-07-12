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

    /**
     * 存储redis编码
     * @date 2023/4/27
     * @param $data
     * @return string
     * @author litong
     */
    public static function redisEncode($data) {
        return json_encode($data);
    }

    /**
     * 存储redis解码
     * @date 2023/4/27
     * @param $data
     * @return DataMapper
     * @author litong
     */
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

    /**
     * 检查字段是否合法
     * @date 2023/4/27
     * @param $field
     * @return bool
     * @author litong
     */
    public static function checkField($field) {
        preg_match("/^[a-zA-Z0-9_]+$/", $field, $matches);
        return !empty($matches);
    }

    /**
     * redis 查询结果 格式化
     * @date 2023/4/27
     * @param $data
     * @param $total
     * @param $page
     * @param $pageSize
     * @return array
     * @author litong
     */
    public static function redisSelectResult($data, $total, $page, $pageSize) {
        $data = array_map(function ($v) {
            return self::redisDecode($v);
        }, $data);
        return self::selectResult($data, $total, $page, $pageSize);
    }

    /**
     * 数据库查询结果格式化
     * @date 2023/4/27
     * @param $data
     * @param $total
     * @param $page
     * @param $pageSize
     * @return array
     * @author litong
     */
    public static function dbSelectResult($data, $total, $page, $pageSize) {
        $data = array_map(function ($v) {
            return self::dbDecode($v);
        }, $data);
        return self::selectResult($data, $total, $page, $pageSize);
    }

    protected static function selectResult($data, $total, $page, $pageSize) {
        return ["list" => $data ?: [], "page_num" => $page, "page_size" => $pageSize, "total" => $total, "last_page" => ceil($total / $pageSize)];
    }

}