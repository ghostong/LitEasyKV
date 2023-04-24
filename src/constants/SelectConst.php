<?php

namespace Lit\EasyKv\constants;

use Lit\Utils\LiConst;

/**
 * @value("ORDER_BY_CREATE","创建时间")
 * @value("ORDER_BY_UPDATE","修改时间")
 * @value("ORDER_BY_USER","用户自定义排序")
 * @value("ORDER_SCENE_ASC","asc")
 * @value("ORDER_SCENE_DESC","desc")
 */
class SelectConst extends LiConst
{
    const ORDER_BY_CREATE = "create_time";

    const ORDER_BY_UPDATE = "update_time";

    const ORDER_BY_USER = "user_order";

    const ORDER_SCENE_ASC = "asc";

    const ORDER_SCENE_DESC = "desc";

}