<?php

namespace Lit\EasyKv\drivers;

use Lit\EasyKv\mappers\DataMapper;

interface DriverInterface
{
    public static function isEnable();

    public static function config();

    public static function add(DataMapper $dataMapper);
}