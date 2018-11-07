<?php

namespace ethercap\apiBase\components;

use Yii;
use yii\base\Widget as YiiWidget;
use yii\base\InvalidCallException;

class Widget extends YiiWidget
{
    final public static function begin($config = [])
    {
        throw new InvalidCallException();
    }

    final public static function end()
    {
        throw new InvalidCallException();
    }

    public static function widget($config = [])
    {
        ob_start();
        ob_implicit_flush(false);
        try {
            /* @var $widget Widget */
            $config['class'] = get_called_class();
            $widget = Yii::createObject($config);
            $out = '';
            if ($widget->beforeRun()) {
                $result = $widget->run();
                $out = $widget->afterRun($result);
            }
        } catch (\Exception $e) {
            // close the output buffer opened above if it has not been closed already
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            throw $e;
        }

        return $out;
    }
}
