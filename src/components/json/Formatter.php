<?php

namespace ethercap\apiBase\components\json;

use yii\web\JsonResponseFormatter;

class Formatter extends JsonResponseFormatter
{
    protected function formatJson($response)
    {
        if ($response->data !== null) {
            $options = $this->encodeOptions;
            if ($this->prettyPrint) {
                $options |= JSON_PRETTY_PRINT;
            }
            $response->content = Json::encode($response->data, $options);
        }
    }
}
