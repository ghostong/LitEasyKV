<?php

namespace Lit\EasyKv\mappers;

use Lit\EasyKv\constants\ErrorMsg;
use Lit\EasyKv\constants\SelectConst;
use Lit\Parameter\V2\Parameter;
use Lit\Parameter\V2\Types\Types;

/**
 * @property Types $topic 主题
 * @property Types $key 键
 * @property Types $status 状态
 * @property Types $order_by 排序字段
 * @property Types $order_scene 排序方式
 */
class SelectMapper extends Parameter
{

    public function __construct($params = []) {

        //主题
        $this->topic->isString()->notEmpty()->setDefault("default")->minLength(1)->maxLength(64)->setCode(ErrorMsg::DATA_TOPIC_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_TOPIC_ERROR));

        //键
        $this->key->isString()->notEmpty()->setDefault("default")->minLength(1)->maxLength(64)->setCode(ErrorMsg::DATA_KEY_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_KEY_ERROR));

        //状态 默认1(选填)
        $this->status->isNumeric()->setDefault(1)->ge(-32768)->le(32767)->setCode(ErrorMsg::DATA_STATUS_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_STATUS_ERROR));

        //排序字段
        $this->order_by->isString()->in([
            SelectConst::ORDER_BY_CREATE, SelectConst::ORDER_BY_UPDATE, SelectConst::ORDER_BY_USER
        ])->setDefault(SelectConst::ORDER_BY_CREATE)
            ->setCode(ErrorMsg::SELECT_ORDER_BY_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::SELECT_ORDER_BY_ERROR));

        //排序方式
        $this->order_scene->isString()->in([
            SelectConst::ORDER_SCENE_ASC, SelectConst::ORDER_SCENE_DESC
        ])->setDefault(SelectConst::ORDER_SCENE_DESC)
            ->setCode(ErrorMsg::SELECT_ORDER_SCENE_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::SELECT_ORDER_SCENE_ERROR));

        //启用快速赋值(非必须)
        parent::__construct($params);
    }
}