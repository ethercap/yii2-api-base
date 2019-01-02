<?php

namespace ethercap\apiBase;

use ethercap\apiBase\components\Serializer as BaseSerializer;

class Serializer extends BaseSerializer
{
    public function init()
    {
        $this->collectionEnvelope = 'items';
        $this->metaEnvelope = 'meta';
        parent::init();
    }

    public function setAddConfig()
    {
        $this->addConfig = true;
        return $this;
    }

    public function useModelResponse()
    {
        $this->useModelResponse = true;
        return $this;
    }

    public function setColumn($columns)
    {
        $this->columns = $columns;
        return $this;
    }
}
