<?php

namespace ethercap\apiBase\columns;

use Yii;
use ethercap\apiBase\components\Column;

class WidgetColumn extends Column
{
    public $widgetConfig;

    public function evaluate()
    {
        $widget = Yii::createObject($this->widgetConfig);
        return $widget->run();
    }
}
