<?php

namespace ethercap\apiBase\demos\models;

use common\models\Project as PlatformP;

class Project extends PlatformP
{
    public $ip = '10.10.11.11';
    public $bool = false;
    public $captcha = 'asdas';
    public $compare = 20000;
    public $email;
    public $in;
    public $match;
    public $string;
    public $url;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['ip'], 'ip'],
            [['updateTime'], 'datetime'],
            [['bool'], 'boolean'],
            [['captcha'], 'captcha'],
            [['email'], 'email'],
            [['in'], 'in', 'range' => range(1, 100, 3)],
            [['url'], 'url'],
            [['string'], 'string', 'max' => 10, 'tooLong' => '太长了'],
            [['match'], 'match', 'pattern' => '/^1[3-9]{1}\d{9}$/'],
            [['compare'], 'compare', 'compareValue' => 1000000, 'operator' => '>='],
        ]);
    }
}
