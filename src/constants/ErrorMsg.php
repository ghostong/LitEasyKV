<?php

namespace Lit\EasyKv\constants;

use Lit\Utils\LiConst;

/**
 * @value("REDIS_HOST_ERROR","需配置redis连地址")
 * @value("REDIS_PORT_ERROR","需配置redis端口")
 * @value("REDIS_DB_ERROR","需配置redis库序号")
 * @value("REDIS_AUTH_ERROR","需配置redis端口")
 *
 * @value("MYSQL_HOSTNAME_ERROR","需配置mysql连地址")
 * @value("MYSQL_PORT_ERROR","需配置mysql端口")
 * @value("MYSQL_CHARSET_ERROR","需配置mysql数据库字符集")
 * @value("MYSQL_USERNAME_ERROR","需配置mysql用户名")
 * @value("MYSQL_PASSWORD_ERROR","需配置mysql密码")
 * @value("MYSQL_DATABASE_ERROR","需配置mysql数据库名")
 *
 * @value("DATA_TOPIC_ERROR","topic 错误, 长度应为 1-64 个字符")
 * @value("DATA_KEY_ERROR","key 错误, 长度应为 1-64 个字符")
 * @value("DATA_VALUE_ERROR","value 错误, 不能为 null, 长度 小于 1024")
 * @value("DATA_STATUS_ERROR","status 错误, 取值范围 -32768 -> 32767")
 * @value("DATA_EXTEND_ERROR","extend 错误, 应为数组且 json_encode 后总长度不超过 65535")
 *
 */
class ErrorMsg extends LiConst
{
    const REDIS_HOST_ERROR = 10001; //需配置redis连地址
    const REDIS_PORT_ERROR = 10002; //需配置redis端口
    const REDIS_DB_ERROR = 10003; //需配置redis库序号
    const REDIS_AUTH_ERROR = 10004; //需配置redis端口

    const MYSQL_HOSTNAME_ERROR = 10101; //需配置mysql连地址
    const MYSQL_PORT_ERROR = 10102; //需配置mysql端口
    const MYSQL_CHARSET_ERROR = 10103; //需配置mysql数据库字符集
    const MYSQL_USERNAME_ERROR = 10104; //需配置mysql用户名
    const MYSQL_PASSWORD_ERROR = 10105; //需配置mysql密码
    const MYSQL_DATABASE_ERROR = 10106; //需配置mysql数据库名

    const DATA_TOPIC_ERROR = 10201; //topic 错误, 长度应为 1-64 个字符
    const DATA_KEY_ERROR = 10202; //key 错误, 长度应为 1-64 个字符
    const DATA_VALUE_ERROR = 10203; //value 错误, 不能为 null, 长度 小于 1024
    const DATA_STATUS_ERROR = 10204; //status 错误, 取值范围 -32768 -> 32767
    const DATA_EXTEND_ERROR = 10205; //extend 错误, 应为数组且 json_encode 后总长度不超过 65535

}