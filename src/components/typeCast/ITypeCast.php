<?php

namespace ethercap\apiBase\components\typeCast;

interface ITypeCast
{
    public static function cast($type, &$value);
}
