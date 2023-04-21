<?php

namespace Lit\EasyKv\mappers;

use Lit\EasyKv\constants\ErrorMsg;
use Lit\Parameter\V2\Parameter;
use Lit\Parameter\V2\Types\Types;


/**
 * @property Types $topic 主题
 * @property Types $key 键
 * @property Types $value 值
 * @property Types $status 状态
 * @property Types $extend 扩展
 */
class DataMapper extends Parameter
{
    public function __construct($params = []) {
        //主题
        $this->topic->isString()->notEmpty()->setDefault("default")->minLength(1)->maxLength(64)->setCode(ErrorMsg::DATA_TOPIC_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_TOPIC_ERROR));
        //键
        $this->key->isString()->notEmpty()->minLength(1)->maxLength(64)->setCode(ErrorMsg::DATA_KEY_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_KEY_ERROR));
        //值
        $this->value->isString()->notEmpty()->maxLength(1024)->setCode(ErrorMsg::DATA_VALUE_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_VALUE_ERROR));
        //状态
        $this->status->isNumeric()->setDefault(1)->ge(-32768)->le(32767)->setCode(ErrorMsg::DATA_STATUS_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_STATUS_ERROR));
        //扩展
        $this->extend->isArray()->callback(function ($v) {
            return strlen(json_encode($v)) < 65535;
        })->setCode(ErrorMsg::DATA_EXTEND_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_EXTEND_ERROR));

        parent::__construct($params);
    }

}