<?php

namespace Lit\EasyKv\mappers;

use Lit\EasyKv\constants\ErrorMsg;
use Lit\EasyKv\constants\SelectConst;
use Lit\Parameter\V2\Parameter;
use Lit\Parameter\V2\Types\Types;

/**
 * @property Types $topic 主题
 * @property Types $key 键
 * @property Types $order_scene 排序方式
 * @property Types $pageNum 页码
 * @property Types $pageSize 每页条数
 */
class SelectMapper extends Parameter
{

    public function __construct($params = []) {

        //主题
        $this->topic->isString()->notEmpty()->setDefault("default")->minLength(1)->maxLength(64)->setCode(ErrorMsg::DATA_TOPIC_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_TOPIC_ERROR));

        //键
        $this->key->isString()->notEmpty()->setDefault("default")->minLength(1)->maxLength(64)->setCode(ErrorMsg::DATA_KEY_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::DATA_KEY_ERROR));

        //排序方式
        $this->order_scene->isString()->in([
            SelectConst::ORDER_SCENE_ASC, SelectConst::ORDER_SCENE_DESC
        ])->setDefault(SelectConst::ORDER_SCENE_DESC)
            ->setCode(ErrorMsg::SELECT_ORDER_SCENE_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::SELECT_ORDER_SCENE_ERROR));

        //页码
        $this->pageNum->isInteger()->setDefault(1)->gt(0)->setCode(ErrorMsg::SELECT_PAGE_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::SELECT_PAGE_ERROR));

        //每页条数
        $this->pageSize->isInteger()->setDefault(10)->gt(0)->setCode(ErrorMsg::SELECT_PAGE_SIZE_ERROR)->setMsg(ErrorMsg::getComment(ErrorMsg::SELECT_PAGE_SIZE_ERROR));

        //启用快速赋值(非必须)
        parent::__construct($params);
    }
}