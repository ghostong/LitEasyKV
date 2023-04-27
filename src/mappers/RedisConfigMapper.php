<?php

namespace Lit\EasyKv\mappers;

use Lit\EasyKv\constants\ErrorMsg;
use Lit\Parameter\V2\Parameter;
use Lit\Parameter\V2\Types\Types;

/**
 * @property Types $host redis连接地址
 * @property Types $port redis端口
 * @property Types $db redis数据库编号
 * @property Types $auth redis密码
 */
class RedisConfigMapper extends Parameter
{
    public function __construct($params = []) {

        //redis连接地址
        $this->host->isString()->setCode(ErrorMsg::REDIS_HOST_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::REDIS_HOST_ERROR));

        //redis端口
        $this->port->isNumeric()->setCode(ErrorMsg::REDIS_PORT_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::REDIS_PORT_ERROR));

        //redis数据库编号
        $this->db->isNumeric()->setDefault(0)->setCode(ErrorMsg::REDIS_DB_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::REDIS_DB_ERROR));

        //redis密码
        $this->auth->isString()->setCode(ErrorMsg::REDIS_AUTH_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::REDIS_AUTH_ERROR));

        //启用快速赋值(非必须)
        parent::__construct($params);
    }

}