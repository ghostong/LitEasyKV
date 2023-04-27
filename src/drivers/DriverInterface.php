<?php

namespace Lit\EasyKv\drivers;

use Lit\EasyKv\mappers\DataMapper;
use Lit\EasyKv\mappers\SelectMapper;

interface DriverInterface
{

    public static function connect();

    public static function isEnable();

    public static function config();

    public static function add(DataMapper $dataMapper);

    public static function modify(DataMapper $dataMapper, $extendAppend);

    public static function get($topic, $key, $value);

    public static function delete($topic, $key, $value);

    public static function select(SelectMapper $selectMapper);

}