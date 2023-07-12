EasyKV PHP
==============
EasyKV PHP 帮助文件.

### 初始化数据库

````php
//如果不启用某项存储可以变量定义为null, 如果都定义则双写, 读取优先redis
//$redisConfig = null;
//$mysqlConfig = null;
$mysqlConfig = new \Lit\EasyKv\mappers\MySQLConfigMapper();
$mysqlConfig->host = 'utils-mysql';
$mysqlConfig->port = 3306;
$mysqlConfig->username = "root";
$mysqlConfig->password = "123456";
$mysqlConfig->database = "test";
$mysqlConfig->table = "easy_kv";

$redisConfig = new \Lit\EasyKv\mappers\RedisConfigMapper();
$redisConfig->host = "utils-redis";
$redisConfig->port = 6379;
$redisConfig->prefix = "easy:kv";
EasyKV::init($mysqlConfig, $redisConfig);
````

### 写入一条数据

````php
$data = new \Lit\EasyKv\mappers\DataMapper();
$data->topic = "test";
$data->key = "test1";
$data->value = uniqid();
$data->extend = ["name" => uniqid(), "age" => "a123546"];
$data->weight = rand(0, 1000);
var_dump(EasyKV::add($data));
````

### 读取单条数据

````php
if ($dataMapper = EasyKV::get("test", "test1", "6449e97bb85af")) {
    var_dump($dataMapper->toArray());
}
````

### 修改

````php
$extendAppend = false; //扩展数据是否追加
$data = new \Lit\EasyKv\mappers\DataMapper();
$data->topic = "test";
$data->key = "test1";
$data->value = '6449e97b0be5c';
$data->extend = ["name" => uniqid(), "age" => "a123546"];
$data->weight =  999;
EasyKV::modify($data, $extendAppend);
````

### 删除

````php
EasyKV::delete("test", "test1", "6449e97c6e47c");
````

### 列表查询

````php
$selectMapper = new \Lit\EasyKv\mappers\SelectMapper();
$selectMapper->topic = "test";
$selectMapper->key = "test1";
$selectMapper->order_scene = \Lit\EasyKv\constants\SelectConst::ORDER_SCENE_ASC;
$selectMapper->pageNum = 3;
$selectMapper->pageSize = 1;
$select = EasyKV::select($selectMapper);

foreach ($select["list"] as $v) {
echo $v->topic->value(), ' ', $v->key->value(), ' ', $v->value->value(), ' ', $v->weight->value(), "\n";
}

var_dump($select);
````