<?php
namespace ethercap\apiBase\helpers;
class ConfigHelper
{
    public static function getConfig()
    {
        $config = [
            'api' => [
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => [
                    'default' => '@vendor/ethercap/apiBase/src/template/api',
                ],
            ],
        ];
        return $config;
    }
}
