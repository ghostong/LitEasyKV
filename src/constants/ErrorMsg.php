<?php

namespace Lit\EasyKv\constants;

use Lit\Utils\LiConst;

/**
 * @value("REDIS_HOST_ERROR","需配置redis连地址")
 * @value("REDIS_PORT_ERROR","需配置redis端口")
 * @value("REDIS_DB_ERROR","需配置redis库序号")
 * @value("REDIS_AUTH_ERROR","需配置redis身份认证")
 * @value("REDIS_PREFIX_ERROR","redis key前缀配置错误")
 *
 * @value("MYSQL_HOSTNAME_ERROR","需配置mysql连地址")
 * @value("MYSQL_PORT_ERROR","需配置mysql端口")
 * @value("MYSQL_CHARSET_ERROR","需配置mysql数据库字符集")
 * @value("MYSQL_USERNAME_ERROR","需配置mysql用户名")
 * @value("MYSQL_PASSWORD_ERROR","需配置mysql密码")
 * @value("MYSQL_DATABASE_ERROR","需配置mysql数据库名")
 * @value("MYSQL_TABLE_ERROR","配置mysql表名错误")
 *
 * @value("DATA_TOPIC_ERROR","topic 错误, 长度应为 1-64 个字符, 只能包含数字 字母 下划线")
 * @value("DATA_KEY_ERROR","key 错误, 长度应为 1-64 个字符, 只能包含数字 字母 下划线")
 * @value("DATA_VALUE_ERROR","value 错误, 不能为 null, 长度 小于 1024, 只能包含数字 字母 下划线")
 * @value("DATA_EXTEND_ERROR","extend 错误, 应为数组且 json_encode 后总长度不超过 65535")
 * @value("DATA_CREATE_TIME_ERROR","创建时间错误")
 * @value("DATA_UPDATE_TIME_ERROR","更新时间错误")
 * @value("DATA_WEIGHT_ERROR","用户自定义权重错误")
 * @value("DATA_ALREADY_EXISTS","数据已经存在")
 *
 * @value("SELECT_ORDER_SCENE_ERROR","排序方式必须在白名单之内")
 * @value("SELECT_PAGE_ERROR","页码错误")
 * @value("SELECT_PAGE_SIZE_ERROR","每页条数错误")
 */
class ErrorMsg extends LiConst
{
    const REDIS_HOST_ERROR = 10001; //需配置redis连地址
    const REDIS_PORT_ERROR = 10002; //需配置redis端口
    const REDIS_DB_ERROR = 10003; //需配置redis库序号
    const REDIS_AUTH_ERROR = 10004; //需配置redis身份认证
    const REDIS_PREFIX_ERROR = 10006; //redis key 前缀配置错误

    const MYSQL_HOSTNAME_ERROR = 10101; //需配置mysql连地址
    const MYSQL_PORT_ERROR = 10102; //需配置mysql端口
    const MYSQL_CHARSET_ERROR = 10103; //需配置mysql数据库字符集
    const MYSQL_USERNAME_ERROR = 10104; //需配置mysql用户名
    const MYSQL_PASSWORD_ERROR = 10105; //需配置mysql密码
    const MYSQL_DATABASE_ERROR = 10106; //需配置mysql数据库名
    const MYSQL_TABLE_ERROR = 10107; //配置mysql表名错误

    const DATA_TOPIC_ERROR = 10201; //topic 错误, 长度应为 1-64 个字符
    const DATA_KEY_ERROR = 10202; //key 错误, 长度应为 1-64 个字符
    const DATA_VALUE_ERROR = 10203; //value 错误, 不能为 null, 长度 小于 1024
    const DATA_EXTEND_ERROR = 10204; //extend 错误, 应为数组且 json_encode 后总长度不超过 65535
    const DATA_CREATE_TIME_ERROR = 10205; //创建时间错误
    const DATA_UPDATE_TIME_ERROR = 10206; //更新时间错误
    const DATA_WEIGHT_ERROR = 10207; //用户自定义权重错误
    const DATA_ALREADY_EXISTS = 10208; //数据已经存在

    const SELECT_ORDER_SCENE_ERROR = 10301;//排序方式必须在白名单之内
    const SELECT_PAGE_ERROR = 10302;//页码错误
    const SELECT_PAGE_SIZE_ERROR = 10303;//每页条数错误

}