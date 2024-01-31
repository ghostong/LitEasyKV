<?php

namespace Lit\EasyKv\mappers;

use Lit\EasyKv\constants\ErrorMsg;
use Lit\Parameter\V2\Parameter;
use Lit\Parameter\V2\Types\Types;

/**
 * @property Types $host mysql连接地址
 * @property Types $port mysql端口
 * @property Types $charset mysql字符集
 * @property Types $username mysql用户名
 * @property Types $password mysql密码
 * @property Types $database mysql数据库名
 * @property Types $table mysql数据表名
 */
class MySQLConfigMapper extends Parameter
{
    public function __construct($params = []) {

        //mysql连接地址
        $this->host->isString()->setCode(ErrorMsg::MYSQL_HOSTNAME_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::MYSQL_HOSTNAME_ERROR));

        //mysql端口
        $this->port->isNumeric()->setDefault(3306)->setCode(ErrorMsg::MYSQL_PORT_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::MYSQL_PORT_ERROR));

        //mysql字符集
        $this->charset->isString()->notEmpty()->setDefault("utf8mb4")->setCode(ErrorMsg::MYSQL_CHARSET_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::MYSQL_CHARSET_ERROR));

        //mysql用户名
        $this->username->isString()->setCode(ErrorMsg::MYSQL_USERNAME_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::MYSQL_USERNAME_ERROR));

        //mysql密码
        $this->password->isString()->setCode(ErrorMsg::MYSQL_PASSWORD_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::MYSQL_PASSWORD_ERROR));

        //mysql数据库名
        $this->database->isString()->notEmpty()->setCode(ErrorMsg::MYSQL_DATABASE_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::MYSQL_DATABASE_ERROR));

        //mysql数据表
        $this->table->isString()->notEmpty()->setDefault("easy_kv")->setCode(ErrorMsg::MYSQL_TABLE_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::MYSQL_TABLE_ERROR));

        //启用快速赋值(非必须)
        parent::__construct($params);
    }
}