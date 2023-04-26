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
 */
class MySQLConfigMapper extends Parameter
{
    public function __construct($params = []) {
        $this->host->isString()->setCode(ErrorMsg::MYSQL_HOSTNAME_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::MYSQL_HOSTNAME_ERROR));
        $this->port->isNumeric()->setDefault(3306)->setCode(ErrorMsg::MYSQL_PORT_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::MYSQL_PORT_ERROR));
        $this->charset->isNumeric()->notEmpty()->setDefault("utf8mb4")->setCode(ErrorMsg::MYSQL_CHARSET_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::MYSQL_CHARSET_ERROR));
        $this->username->isString()->setCode(ErrorMsg::MYSQL_USERNAME_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::MYSQL_USERNAME_ERROR));
        $this->password->isString()->setCode(ErrorMsg::MYSQL_PASSWORD_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::MYSQL_PASSWORD_ERROR));
        $this->database->isString()->notEmpty()->setCode(ErrorMsg::MYSQL_DATABASE_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::MYSQL_DATABASE_ERROR));

        //启用快速赋值(非必须)
        parent::__construct($params);
    }
}