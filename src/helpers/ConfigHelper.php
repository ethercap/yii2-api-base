<?php
namespace ethercap\apiBase\helpers;
class ConfigHelper
{
    public static function getConfig()
    {
        $config = [
            'api' => [
                'class' => 'ethercap\apiBase\components\Generator',
                'templates' => [
                    'default' => '@vendor/ethercap/apiBase/src/template/api',
                ],
            ],
        ];
        return $config;
    }
}
