<?php

namespace Lit\EasyKv\mappers;

use Lit\EasyKv\constants\ErrorMsg;
use Lit\EasyKv\utils\DataConvert;
use Lit\Parameter\V2\Parameter;
use Lit\Parameter\V2\Types\Types;


/**
 * @property Types $topic 主题
 * @property Types $key 键
 * @property Types $value 值
 * @property Types $extend 扩展
 * @property Types $create_time 创建时间
 * @property Types $update_time 更新时间
 * @property Types $weight 数据权重
 */
class DataMapper extends Parameter
{
    public function __construct($params = []) {

        //主题
        $this->topic->isString()->notEmpty()->setDefault("default")
            ->minLength(1)->maxLength(64)->callback(function ($field) {
                return DataConvert::checkField($field);
            })->setCode(ErrorMsg::DATA_TOPIC_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_TOPIC_ERROR));

        //键
        $this->key->isString()->notEmpty()->setDefault("default")
            ->minLength(1)->maxLength(64)->callback(function ($field) {
                return DataConvert::checkField($field);
            })->setCode(ErrorMsg::DATA_KEY_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_KEY_ERROR));

        //值
        $this->value->isString()->notEmpty()->maxLength(1024)->callback(function ($field) {
            return DataConvert::checkField($field);
        })->setCode(ErrorMsg::DATA_VALUE_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_VALUE_ERROR));

        //扩展 (选填)
        $this->extend->isArray()->callback(function ($v) {
            return strlen(json_encode($v)) < 65535;
        })->setCode(ErrorMsg::DATA_EXTEND_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_EXTEND_ERROR));

        //数据权重
        $this->weight->isInteger()->setDefault(1)->setCode(ErrorMsg::DATA_WEIGHT_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_WEIGHT_ERROR));

        //创建时间 自动维护(选填)
        $this->create_time->isString()->setCode(ErrorMsg::DATA_CREATE_TIME_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_CREATE_TIME_ERROR));

        //更新时间 自动维护(选填)
        $this->update_time->isString()->setCode(ErrorMsg::DATA_UPDATE_TIME_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_UPDATE_TIME_ERROR));

        parent::__construct($params);
    }

}